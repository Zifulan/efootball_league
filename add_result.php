<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$db = 'czkugpvg_league';
$user = 'czkugpvg_league';
$pass = 'BangJal!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$league_id = $_GET['league_id'] ?? null;
if (!$league_id) {
    die("League ID missing.");
}

// Handle AJAX requests for saving individual rounds
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    header('Content-Type: application/json');
    
    try {
        $round = (int)$_POST['round'];
        $results = $_POST['results'];
        
        $updated_count = 0;
        
        foreach ($results as $match_id => $data) {
            if (empty($data['home_score']) && $data['home_score'] !== '0') continue;
            if (empty($data['away_score']) && $data['away_score'] !== '0') continue;
            
            $home_score = (int)$data['home_score'];
            $away_score = (int)$data['away_score'];

            // Get fixture details
            $fixture = $pdo->prepare("SELECT * FROM league_fixtures WHERE id = ? AND league_id = ?");
            $fixture->execute([$match_id, $league_id]);
            $match = $fixture->fetch(PDO::FETCH_ASSOC);
            if (!$match) continue;

            $home = $match['home_team'];
            $away = $match['away_team'];
            $match_round = $match['round'];

            // Check if result already exists
            $logCheck = $pdo->prepare("SELECT id FROM league_match_log WHERE league_id = ? AND round = ? AND home_team = ? AND away_team = ?");
            $logCheck->execute([$league_id, $match_round, $home, $away]);
            $existing_log = $logCheck->fetch(PDO::FETCH_ASSOC);

            if ($existing_log) {
                // UPDATE existing result
                $updateLog = $pdo->prepare("UPDATE league_match_log SET home_score = ?, away_score = ? WHERE id = ?");
                $updateLog->execute([$home_score, $away_score, $existing_log['id']]);
            } else {
                // INSERT new result
                $logStmt = $pdo->prepare("INSERT INTO league_match_log (league_id, round, home_team, away_team, home_score, away_score) VALUES (?, ?, ?, ?, ?, ?)");
                $logStmt->execute([$league_id, $match_round, $home, $away, $home_score, $away_score]);
            }

            // Update fixture table
            $updateFixture = $pdo->prepare("UPDATE league_fixtures SET home_score = ?, away_score = ?, is_played = 1 WHERE id = ?");
            $updateFixture->execute([$home_score, $away_score, $match_id]);
            
            $updated_count++;
        }

        // Recalculate standings
        recalculateStandings($pdo, $league_id);

        echo json_encode(['success' => true, 'updated' => $updated_count, 'round' => $round]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Fetch all fixtures grouped by round
$fixturesStmt = $pdo->prepare("SELECT * FROM league_fixtures WHERE league_id = ? ORDER BY round, id");
$fixturesStmt->execute([$league_id]);
$fixtures = $fixturesStmt->fetchAll(PDO::FETCH_ASSOC);

// Group fixtures by round
$grouped = [];
foreach ($fixtures as $match) {
    $grouped[$match['round']][] = $match;
}

// Fetch existing match results for prefilling
$logStmt = $pdo->prepare("SELECT * FROM league_match_log WHERE league_id = ?");
$logStmt->execute([$league_id]);
$match_logs = $logStmt->fetchAll(PDO::FETCH_ASSOC);

// Create lookup for existing results
$result_lookup = [];
foreach ($match_logs as $log) {
    $key = "{$log['round']}_{$log['home_team']}_{$log['away_team']}";
    $result_lookup[$key] = [
        'home_score' => $log['home_score'],
        'away_score' => $log['away_score'],
        'id' => $log['id']
    ];
}

// Function to recalculate standings from scratch
function recalculateStandings($pdo, $league_id) {
    // Reset all standings to zero
    $resetStmt = $pdo->prepare("UPDATE league_standings SET matches_played=0, wins=0, draws=0, losses=0, goals_for=0, goals_against=0, goal_difference=0, points=0 WHERE league_id=?");
    $resetStmt->execute([$league_id]);

    // Get all match results
    $resultsStmt = $pdo->prepare("SELECT * FROM league_match_log WHERE league_id = ?");
    $resultsStmt->execute([$league_id]);
    $results = $resultsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Process each result
    foreach ($results as $result) {
        $home = $result['home_team'];
        $away = $result['away_team'];
        $home_score = $result['home_score'];
        $away_score = $result['away_score'];

        // Update both teams
        foreach ([$home, $away] as $team) {
            $stmt = $pdo->prepare("SELECT * FROM league_standings WHERE league_id = ? AND team_name = ?");
            $stmt->execute([$league_id, $team]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($record) {
                $mp = $record['matches_played'] + 1;
                $gf = $record['goals_for'] + ($team === $home ? $home_score : $away_score);
                $ga = $record['goals_against'] + ($team === $home ? $away_score : $home_score);
                $gd = $gf - $ga;
                $w = $record['wins'];
                $d = $record['draws'];
                $l = $record['losses'];
                $pts = $record['points'];

                if ($home_score === $away_score) {
                    $d++; $pts += 1;
                } elseif (
                    ($team === $home && $home_score > $away_score) ||
                    ($team === $away && $away_score > $home_score)
                ) {
                    $w++; $pts += 3;
                } else {
                    $l++;
                }

                $update = $pdo->prepare("UPDATE league_standings SET matches_played=?, wins=?, draws=?, losses=?, goals_for=?, goals_against=?, goal_difference=?, points=? WHERE id=?");
                $update->execute([$mp, $w, $d, $l, $gf, $ga, $gd, $pts, $record['id']]);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Match Results</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .match-card {
      border-left: 4px solid #0d6efd;
      background: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 10px;
    }
    .match-saved {
      border-left-color: #198754;
      background: #d1e7dd;
    }
    .score-input {
      width: 60px;
      text-align: center;
    }
    .vs-text {
      font-weight: bold;
      color: #6c757d;
      margin: 0 10px;
    }
    .loading {
      opacity: 0.6;
      pointer-events: none;
    }
    .save-btn {
      transition: all 0.3s ease;
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="text-center mb-4">📝 Match Results Management</h2>

  <div id="alerts"></div>

  <?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $round => $matches): ?>
      <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <span>📅 Round <?= $round ?></span>
          <span class="badge bg-light text-dark" id="counter-<?= $round ?>">
            <?php 
            $saved_count = 0;
            foreach ($matches as $match) {
              $key = "{$match['round']}_{$match['home_team']}_{$match['away_team']}";
              if (isset($result_lookup[$key])) $saved_count++;
            }
            echo "$saved_count/" . count($matches) . " saved";
            ?>
          </span>
        </div>
        <div class="card-body">
          <form class="round-form" data-round="<?= $round ?>">
            
            <?php foreach ($matches as $match): ?>
              <?php
                $key = "{$match['round']}_{$match['home_team']}_{$match['away_team']}";
                $existing = $result_lookup[$key] ?? null;
                $is_saved = $existing !== null;
              ?>
              
              <div class="match-card <?= $is_saved ? 'match-saved' : '' ?>">
                <div class="row align-items-center">
                  <div class="col-md-3">
                    <strong><?= htmlspecialchars($match['home_team']) ?></strong>
                  </div>
                  <div class="col-md-2">
                    <input type="number" 
                           name="results[<?= $match['id'] ?>][home_score]" 
                           class="form-control score-input" 
                           min="0" 
                           value="<?= $existing ? $existing['home_score'] : '' ?>"
                           placeholder="0">
                  </div>
                  <div class="col-md-1 text-center">
                    <span class="vs-text">vs</span>
                  </div>
                  <div class="col-md-2">
                    <input type="number" 
                           name="results[<?= $match['id'] ?>][away_score]" 
                           class="form-control score-input" 
                           min="0" 
                           value="<?= $existing ? $existing['away_score'] : '' ?>"
                           placeholder="0">
                  </div>
                  <div class="col-md-3">
                    <strong><?= htmlspecialchars($match['away_team']) ?></strong>
                  </div>
                  <div class="col-md-1">
                    <?php if ($is_saved): ?>
                      <span class="badge bg-success">✅ Saved</span>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark">⏳ Pending</span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-success save-btn">
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                💾 Save Round <?= $round ?> Results
              </button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">No fixtures found for this league.</div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    <a href="show-standing.php?code=<?= $league_id ?>" class="btn btn-primary" target="_blank">👀 View Standings</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions via AJAX
    document.querySelectorAll('.round-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const round = this.dataset.round;
            const formData = new FormData(this);
            formData.append('ajax', '1');
            formData.append('round', round);
            
            const saveBtn = this.querySelector('.save-btn');
            const spinner = saveBtn.querySelector('.spinner-border');
            const cardBody = this.closest('.card-body');
            
            // Show loading state
            saveBtn.disabled = true;
            spinner.classList.remove('d-none');
            cardBody.classList.add('loading');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', `✅ Round ${data.round} results saved successfully! ${data.updated} matches updated.`);
                    
                    // Update counter
                    updateRoundCounter(round);
                    
                    // Mark saved matches
                    markMatchesAsSaved(cardBody);
                    
                } else {
                    showAlert('danger', '❌ Error saving results: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                showAlert('danger', '❌ Network error: ' + error.message);
            })
            .finally(() => {
                // Hide loading state
                saveBtn.disabled = false;
                spinner.classList.add('d-none');
                cardBody.classList.remove('loading');
            });
        });
    });
    
    function showAlert(type, message) {
        const alertsContainer = document.getElementById('alerts');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertsContainer.appendChild(alert);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
    
    function updateRoundCounter(round) {
        const counter = document.getElementById(`counter-${round}`);
        const form = document.querySelector(`[data-round="${round}"]`);
        const inputs = form.querySelectorAll('input[type="number"]');
        
        let savedCount = 0;
        const totalMatches = inputs.length / 2; // 2 inputs per match
        
        // Count filled pairs
        for (let i = 0; i < inputs.length; i += 2) {
            const homeScore = inputs[i].value;
            const awayScore = inputs[i + 1].value;
            if (homeScore !== '' && awayScore !== '') {
                savedCount++;
            }
        }
        
        counter.textContent = `${savedCount}/${totalMatches} saved`;
    }
    
    function markMatchesAsSaved(cardBody) {
        const matchCards = cardBody.querySelectorAll('.match-card');
        matchCards.forEach(card => {
            const inputs = card.querySelectorAll('input[type="number"]');
            const homeScore = inputs[0].value;
            const awayScore = inputs[1].value;
            
            if (homeScore !== '' && awayScore !== '') {
                card.classList.add('match-saved');
                const badge = card.querySelector('.badge');
                badge.className = 'badge bg-success';
                badge.textContent = '✅ Saved';
            }
        });
    }
});
</script>
</body>
</html>