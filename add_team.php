<?php
// add_team.php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_GET['league_id'])) {
    header('Location: login.php');
    exit;
}

$league_id = (int)$_GET['league_id'];

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_name = trim($_POST['team_name']);
    if ($team_name) {
        // Check if team already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM league_standings WHERE league_id = ? AND team_name = ?");
        $checkStmt->execute([$league_id, $team_name]);
        if ($checkStmt->fetchColumn() > 0) {
            $error_message = "Team '$team_name' already exists in this league!";
        } else {
            // Add team to standings
            $stmt = $pdo->prepare("INSERT INTO league_standings (league_id, team_name, matches_played, wins, draws, losses, goals_for, goals_against, goal_difference, points) VALUES (?, ?, 0, 0, 0, 0, 0, 0, 0, 0)");
            $stmt->execute([$league_id, $team_name]);

            // Get all existing teams (including the new one)
            $stmt = $pdo->prepare("SELECT team_name FROM league_standings WHERE league_id = ?");
            $stmt->execute([$league_id]);
            $all_teams = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Only add fixtures for the NEW team vs existing teams (not regenerate everything)
            if (count($all_teams) > 1) {
                addFixturesForNewTeam($pdo, $league_id, $team_name, $all_teams);
            }

            header("Location: add_team.php?league_id=" . $league_id . "&success=1");
            exit;
        }
    }
}

// Function to add fixtures only for the new team
function addFixturesForNewTeam($pdo, $league_id, $new_team, $all_teams) {
    // Get the highest existing round number
    $maxRoundStmt = $pdo->prepare("SELECT MAX(round) FROM league_fixtures WHERE league_id = ?");
    $maxRoundStmt->execute([$league_id]);
    $current_round = (int)$maxRoundStmt->fetchColumn() ?: 0;

    // Add fixtures for new team vs all other teams
    foreach ($all_teams as $existing_team) {
        if ($existing_team === $new_team) continue; // Skip self

        // Check if fixtures already exist between these teams
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM league_fixtures WHERE league_id = ? AND ((home_team = ? AND away_team = ?) OR (home_team = ? AND away_team = ?))");
        $checkStmt->execute([$league_id, $new_team, $existing_team, $existing_team, $new_team]);
        
        if ($checkStmt->fetchColumn() == 0) {
            // Add both home and away fixtures
            // New team at home
            $current_round++;
            $stmt = $pdo->prepare("INSERT INTO league_fixtures (league_id, round, home_team, away_team, home_score, away_score, is_played) VALUES (?, ?, ?, ?, NULL, NULL, 0)");
            $stmt->execute([$league_id, $current_round, $new_team, $existing_team]);

            // New team away
            $current_round++;
            $stmt = $pdo->prepare("INSERT INTO league_fixtures (league_id, round, home_team, away_team, home_score, away_score, is_played) VALUES (?, ?, ?, ?, NULL, NULL, 0)");
            $stmt->execute([$league_id, $current_round, $existing_team, $new_team]);
        }
    }
}

// Get current teams for display
$stmt = $pdo->prepare("SELECT team_name FROM league_standings WHERE league_id = ? ORDER BY team_name");
$stmt->execute([$league_id]);
$current_teams = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Team</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <style>
    .team-list {
      max-height: 300px;
      overflow-y: auto;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 15px;
      background: #f8f9fa;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="text-center mb-4">👥 Add Team to League</h2>
    
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        ✅ Team added successfully! Fixtures have been updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
      <div class="alert alert-danger">
        ❌ <?= htmlspecialchars($error_message) ?>
      </div>
    <?php endif; ?>

    <div class="row">
      <!-- Add Team Form -->
      <div class="col-md-6">
        <div class="card p-4">
          <h5>➕ Add New Team</h5>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Team Name</label>
              <input type="text" name="team_name" class="form-control" required placeholder="Enter team name">
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Team</button>
          </form>
        </div>
      </div>

      <!-- Current Teams List -->
      <div class="col-md-6">
        <div class="card p-4">
          <h5>📋 Current Teams (<?= count($current_teams) ?>)</h5>
          <?php if (!empty($current_teams)): ?>
            <div class="team-list">
              <?php foreach ($current_teams as $i => $team): ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span><?= $i + 1 ?>. <?= htmlspecialchars($team) ?></span>
                  <span class="badge bg-primary">Active</span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="alert alert-info mb-0">No teams added yet.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="add_result.php?league_id=<?= $league_id ?>" class="btn btn-success">📝 Add Results</a>
      <a href="show-standing.php?code=<?= $league_id ?>" class="btn btn-outline-primary" target="_blank">👀 View Standings</a>
      <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>