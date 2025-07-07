<?php
// show-standing.php
$host = 'localhost';
$db = 'czkugpvg_league';
$user = 'czkugpvg_league';
$pass = 'BangJal!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$league_code = $_GET['code'] ?? null;
if (!$league_code) {
    die("League code is missing.");
}

$stmt = $pdo->prepare("SELECT id, name FROM leagues WHERE code = ?");
$stmt->execute([$league_code]);
$league = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$league) {
    die("Invalid or unknown league.");
}

$league_id = $league['id'];

$standingsStmt = $pdo->prepare("SELECT * FROM league_standings WHERE league_id = ? ORDER BY points DESC, goal_difference DESC, goals_for DESC");
$standingsStmt->execute([$league_id]);
$standings = $standingsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch scores from match log
$logStmt = $pdo->prepare("SELECT * FROM league_match_log WHERE league_id = ?");
$logStmt->execute([$league_id]);
$match_logs = $logStmt->fetchAll(PDO::FETCH_ASSOC);

// Create easy lookup
$score_lookup = [];
foreach ($match_logs as $log) {
    $key = "{$log['round']}_{$log['home_team']}_{$log['away_team']}";
    $score_lookup[$key] = [
        'home_score' => $log['home_score'],
        'away_score' => $log['away_score'],
        'winner' => $log['home_score'] > $log['away_score'] ? 'home' : ($log['away_score'] > $log['home_score'] ? 'away' : 'draw')
    ];
}

$allFixturesStmt = $pdo->prepare("SELECT * FROM league_fixtures WHERE league_id = ? ORDER BY round ASC, id ASC");
$allFixturesStmt->execute([$league_id]);
$allFixtures = $allFixturesStmt->fetchAll(PDO::FETCH_ASSOC);

// Group by round
$fixtures_by_round = [];
foreach ($allFixtures as $fixture) {
    $fixtures_by_round[$fixture['round']][] = $fixture;
}

$totalRoundsStmt = $pdo->prepare("SELECT MAX(round) as max_round FROM league_fixtures WHERE league_id = ?");
$totalRoundsStmt->execute([$league_id]);
$max_round = (int) $totalRoundsStmt->fetchColumn();

// Calculate total matches played
$total_matches = count($match_logs);
$total_fixtures = count($allFixtures);
$completion_percentage = $total_fixtures > 0 ? round(($total_matches / $total_fixtures) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($league['name']) ?> - League Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --trophy-gold: #ffd700;
      --silver: #c0c0c0;
      --bronze: #cd7f32;
    }
    
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      font-family: 'Inter', 'Segoe UI', sans-serif;
    }
    
    .hero-header {
      background: var(--primary-gradient);
      color: white;
      padding: 3rem 0;
      margin-bottom: 2rem;
      position: relative;
      overflow: hidden;
      animation: heroSlideIn 1s ease-out;
    }
    
    @keyframes heroSlideIn {
      from {
        opacity: 0;
        transform: translateY(-50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .hero-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grain" width="100" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="8" cy="8" r="0.3" fill="rgba(255,255,255,0.05)"/><circle cx="15" cy="4" r="0.4" fill="rgba(255,255,255,0.08)"/></pattern></defs><rect width="100" height="20" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }
    
    .league-title {
      font-size: 3rem;
      font-weight: 800;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
      margin-bottom: 0.5rem;
      animation: titleBounce 1.2s ease-out 0.3s both;
    }
    
    @keyframes titleBounce {
      0% {
        opacity: 0;
        transform: scale(0.3) rotate(-10deg);
      }
      50% {
        transform: scale(1.05) rotate(2deg);
      }
      70% {
        transform: scale(0.95) rotate(-1deg);
      }
      100% {
        opacity: 1;
        transform: scale(1) rotate(0deg);
      }
    }
    
    .league-stats {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 1.5rem;
      border: 1px solid rgba(255,255,255,0.2);
    }
    
    .stats-item {
      text-align: center;
      animation: statsFloat 0.8s ease-out both;
    }
    
    .stats-item:nth-child(1) { animation-delay: 0.5s; }
    .stats-item:nth-child(2) { animation-delay: 0.7s; }
    .stats-item:nth-child(3) { animation-delay: 0.9s; }
    .stats-item:nth-child(4) { animation-delay: 1.1s; }
    
    @keyframes statsFloat {
      from {
        opacity: 0;
        transform: translateY(30px) scale(0.8);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    
    .stats-number {
      font-size: 2rem;
      font-weight: 700;
      display: block;
    }
    
    .stats-label {
      font-size: 0.9rem;
      opacity: 0.9;
    }
    
    .standings-card, .fixtures-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      border: none;
      overflow: hidden;
      margin-bottom: 2rem;
      animation: cardSlideUp 0.8s ease-out both;
    }
    
    .standings-card {
      animation-delay: 1.3s;
    }
    
    .fixtures-card {
      animation-delay: 1.5s;
    }
    
    @keyframes cardSlideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .card-header-custom {
      background: var(--success-gradient);
      color: white;
      padding: 1.5rem;
      border-bottom: none;
    }
    
    .card-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .standings-table {
      margin: 0;
      min-width: 600px; /* Ensures minimum width for all columns */
    }
    
    .standings-table thead th {
      background: #f8f9fa;
      border: none;
      font-weight: 600;
      padding: 1rem 0.75rem;
      color: #495057;
      font-size: 0.9rem;
    }
    
    .standings-table tbody tr {
      border-bottom: 1px solid #f1f3f4;
      transition: all 0.3s ease;
      animation: tableRowSlide 0.5s ease-out both;
    }
    
    .standings-table tbody tr:nth-child(1) { animation-delay: 1.6s; }
    .standings-table tbody tr:nth-child(2) { animation-delay: 1.7s; }
    .standings-table tbody tr:nth-child(3) { animation-delay: 1.8s; }
    .standings-table tbody tr:nth-child(4) { animation-delay: 1.9s; }
    .standings-table tbody tr:nth-child(5) { animation-delay: 2.0s; }
    .standings-table tbody tr:nth-child(6) { animation-delay: 2.1s; }
    .standings-table tbody tr:nth-child(7) { animation-delay: 2.2s; }
    .standings-table tbody tr:nth-child(8) { animation-delay: 2.3s; }
    
    @keyframes tableRowSlide {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    .standings-table tbody tr:hover {
      background: #f8f9fa;
      transform: scale(1.02);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .standings-table tbody tr:first-child {
      background: linear-gradient(90deg, #fff9c4, #fff);
      border-left: 4px solid var(--trophy-gold);
    }
    
    .standings-table tbody tr:nth-child(2) {
      background: linear-gradient(90deg, #f0f0f0, #fff);
      border-left: 4px solid var(--silver);
    }
    
    .standings-table tbody tr:nth-child(3) {
      background: linear-gradient(90deg, #f4e4bc, #fff);
      border-left: 4px solid var(--bronze);
    }
    
    .position-badge {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: white;
      font-size: 0.9rem;
      animation: badgePulse 2s ease-in-out infinite;
    }
    
    @keyframes badgePulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }
    
    .pos-1 { 
      background: var(--trophy-gold); 
      color: #333; 
      animation: goldShimmer 3s ease-in-out infinite;
    }
    
    @keyframes goldShimmer {
      0%, 100% { 
        background: var(--trophy-gold);
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
      }
      50% { 
        background: linear-gradient(45deg, #ffd700, #ffed4e, #ffd700);
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
      }
    }
    
    .pos-1 { background: var(--trophy-gold); color: #333; }
    .pos-2 { background: var(--silver); color: #333; }
    .pos-3 { background: var(--bronze); color: white; }
    .pos-other { background: #6c757d; }
    
    .team-name {
      font-weight: 600;
      font-size: 1.1rem;
    }
    
    .stat-highlight {
      font-weight: 600;
      color: #495057;
    }
    
    .fixture-round {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }
    
    .round-header {
      font-size: 1.2rem;
      font-weight: 600;
      color: #495057;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .match-fixture {
      background: white;
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 0.75rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      border: 1px solid #e9ecef;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      animation: fixtureSlideIn 0.6s ease-out both;
    }
    
    .fixture-round:nth-child(1) .match-fixture:nth-child(1) { animation-delay: 2.4s; }
    .fixture-round:nth-child(1) .match-fixture:nth-child(2) { animation-delay: 2.5s; }
    .fixture-round:nth-child(1) .match-fixture:nth-child(3) { animation-delay: 2.6s; }
    .fixture-round:nth-child(2) .match-fixture:nth-child(1) { animation-delay: 2.7s; }
    .fixture-round:nth-child(2) .match-fixture:nth-child(2) { animation-delay: 2.8s; }
    .fixture-round:nth-child(2) .match-fixture:nth-child(3) { animation-delay: 2.9s; }
    
    @keyframes fixtureSlideIn {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    
    .match-fixture:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .match-teams {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .team-side {
      flex: 1;
      min-width: 120px;
    }
    
    .team-name-fixture {
      font-weight: 600;
      font-size: 1rem;
    }
    
    .match-score {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-size: 1.2rem;
      font-weight: 700;
      min-width: 80px;
      text-align: center;
    }
    
    .score-played {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      animation: scoreGlow 2s ease-in-out infinite;
    }
    
    @keyframes scoreGlow {
      0%, 100% { 
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
      }
      50% { 
        box-shadow: 0 0 20px rgba(40, 167, 69, 0.8);
      }
    }
    
    .score-pending {
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
      animation: pendingPulse 3s ease-in-out infinite;
    }
    
    @keyframes pendingPulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.7; }
    }
    
    .winner-home { border-left: 4px solid #28a745; }
    .winner-away { border-right: 4px solid #28a745; }
    .match-draw { border-top: 4px solid #ffc107; }
    
    .progress-container-hero {
      width: 100%;
      max-width: 120px;
      margin: 0 auto;
    }
    
    .progress-bar-hero {
      width: 100%;
      height: 25px;
      background: rgba(255,255,255,0.2);
      border-radius: 15px;
      position: relative;
      overflow: hidden;
      border: 2px solid rgba(255,255,255,0.3);
    }
    
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #28a745, #20c997);
      border-radius: 13px;
      transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
    }
    
    .progress-fill::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      animation: shine 2s ease-in-out infinite;
    }
    
    @keyframes shine {
      0% { transform: translateX(-100%); }
      50% { transform: translateX(100%); }
      100% { transform: translateX(100%); }
    }
    
    .progress-percentage {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-weight: 700;
      font-size: 0.85rem;
      color: white;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
      z-index: 2;
    }
    
    .floating-share {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
      animation: floatBounce 3s ease-in-out infinite;
    }
    
    @keyframes floatBounce {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    
    .share-btn {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: var(--primary-gradient);
      color: white;
      border: none;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      animation: shareGlow 4s ease-in-out infinite;
    }
    
    @keyframes shareGlow {
      0%, 100% { 
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      }
      50% { 
        box-shadow: 0 4px 25px rgba(102, 126, 234, 0.6);
      }
    }
    
    .share-btn:hover {
      transform: scale(1.15) rotate(5deg);
      box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
    }
    
    @media (max-width: 768px) {
      .league-title { font-size: 2rem; }
      .match-teams { flex-direction: column; text-align: center; }
      .team-side { min-width: auto; }
      .standings-table { font-size: 0.85rem; }
      .standings-table th, .standings-table td { 
        padding: 0.5rem 0.3rem; 
        white-space: nowrap; /* Prevents text wrapping */
      }
      .team-name { font-size: 0.9rem; }
      .position-badge { width: 25px; height: 25px; font-size: 0.8rem; }
      
      /* Add scroll hint for mobile */
      .table-responsive::after {
        content: '← Swipe to see more →';
        display: block;
        text-align: center;
        font-size: 0.8rem;
        color: #6c757d;
        padding: 0.5rem;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
      }
      
      /* Fix progress bar alignment on mobile */
      .progress-container-hero {
        margin: 0 auto;
      }
      
      .stats-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }
      
      .stats-number, .progress-container-hero {
        align-self: center;
      }
      
      .stats-label {
        text-align: center;
      }
    }
    
    @media (min-width: 769px) {
      .mobile-toggle {
        display: none;
      }
    }
  </style>
</head>
<body>
  <button id="darkModeToggle" class="btn btn-secondary">Dark Mode</button>
  <!-- Hero Header -->
  <div class="hero-header">
    <div class="container position-relative">
      <div class="text-center mb-4">
        <h1 class="league-title">
          <i class="fas fa-trophy me-3"></i>
          <?= htmlspecialchars($league['name']) ?>
        </h1>
        <p class="lead mb-0">Season Statistics & Live Standings</p>
      </div>
      
      <div class="league-stats">
        <div class="row g-4">
          <div class="col-6 col-md-3">
            <div class="stats-item">
              <span class="stats-number"><?= count($standings) ?></span>
              <span class="stats-label">Teams</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stats-item">
              <span class="stats-number"><?= $total_matches ?></span>
              <span class="stats-label">Matches Played</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stats-item">
              <span class="stats-number"><?= $max_round ?></span>
              <span class="stats-label">Total Rounds</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stats-item">
              <div class="progress-container-hero">
                <div class="progress-bar-hero">
                  <div class="progress-fill" style="width: <?= $completion_percentage ?>%"></div>
                  <span class="progress-percentage"><?= $completion_percentage ?>%</span>
                </div>
              </div>
              <span class="stats-label mt-2 d-block">Complete</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container pb-5">
    <!-- League Standings -->
    <div class="standings-card">
      <div class="card-header-custom">
        <h2 class="card-title">
          <i class="fas fa-list-ol"></i>
          League Table
        </h2>
      </div>
      <div class="card-body p-0">
        <?php if (!empty($standings)): ?>
          <div class="table-responsive">
            <table class="table standings-table">
              <thead>
                <tr>
                  <th>Pos</th><th>Team</th><th>MP</th><th>W</th><th>D</th><th>L</th><th>GF</th><th>GA</th><th>GD</th><th>Pts</th>
                </tr>
              </thead>
            <tbody>
              <?php $rank = 1; foreach ($standings as $team): ?>
                <tr>
                  <td>
                    <div class="position-badge pos-<?= $rank <= 3 ? $rank : 'other' ?>">
                      <?php if ($rank == 1): ?>
                        <i class="fas fa-crown"></i>
                      <?php elseif ($rank <= 3): ?>
                        <?= $rank ?>
                      <?php else: ?>
                        <?= $rank ?>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <div class="team-name"><?= htmlspecialchars($team['team_name']) ?></div>
                  </td>
                  <td><span class="stat-highlight"><?= $team['matches_played'] ?></span></td>
                  <td><span class="text-success fw-bold"><?= $team['wins'] ?></span></td>
                  <td><span class="text-warning fw-bold"><?= $team['draws'] ?></span></td>
                  <td><span class="text-danger fw-bold"><?= $team['losses'] ?></span></td>
                  <td><?= $team['goals_for'] ?></td>
                  <td><?= $team['goals_against'] ?></td>
                  <td class="<?= $team['goal_difference'] > 0 ? 'text-success' : ($team['goal_difference'] < 0 ? 'text-danger' : 'text-muted') ?> fw-bold">
                    <?= $team['goal_difference'] > 0 ? '+' : '' ?><?= $team['goal_difference'] ?>
                  </td>
                  <td><span class="stat-highlight fs-5"><?= $team['points'] ?></span></td>
                </tr>
                <?php $rank++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
          <div class="text-center p-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No teams registered yet</h5>
            <p class="text-muted">The league table will appear once teams are added.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Fixtures -->
    <div class="fixtures-card">
      <div class="card-header-custom">
        <h2 class="card-title">
          <i class="fas fa-calendar-alt"></i>
          Fixtures & Results
        </h2>
      </div>
      <div class="card-body">
        <?php if (!empty($fixtures_by_round)): ?>
          <?php foreach ($fixtures_by_round as $round => $matches): ?>
            <div class="fixture-round">
              <div class="round-header">
                <i class="fas fa-flag"></i>
                Round <?= $round ?>
                <span class="badge bg-primary ms-auto"><?= count($matches) ?> matches</span>
              </div>
              
              <div class="row g-3">
                <?php foreach ($matches as $match): ?>
                  <?php
                    $home = $match['home_team'];
                    $away = $match['away_team'];
                    $key = "{$match['round']}_{$home}_{$away}";
                    $score_data = $score_lookup[$key] ?? null;
                    $is_played = $score_data !== null;
                  ?>
                  
                  <div class="col-md-6">
                    <div class="match-fixture <?= $is_played ? ($score_data['winner'] === 'home' ? 'winner-home' : ($score_data['winner'] === 'away' ? 'winner-away' : 'match-draw')) : '' ?>">
                      <div class="match-teams">
                        <div class="team-side text-end">
                          <div class="team-name-fixture"><?= htmlspecialchars($home) ?></div>
                        </div>
                        
                        <div class="match-score <?= $is_played ? 'score-played' : 'score-pending' ?>">
                          <?php if ($is_played): ?>
                            <i class="fas fa-trophy me-1"></i>
                            <?= $score_data['home_score'] ?> - <?= $score_data['away_score'] ?>
                          <?php else: ?>
                            <i class="far fa-clock me-1"></i>
                            vs
                          <?php endif; ?>
                        </div>
                        
                        <div class="team-side">
                          <div class="team-name-fixture"><?= htmlspecialchars($away) ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="text-center p-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No fixtures scheduled</h5>
            <p class="text-muted">Fixtures will appear once teams are added and matches are generated.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Floating Share Button -->
  <div class="floating-share">
    <button class="share-btn" onclick="shareLeague()" title="Share League">
      <i class="fas fa-share-alt"></i>
    </button>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function shareLeague() {
      const url = window.location.href;
      const title = "<?= htmlspecialchars($league['name']) ?> - League Standings";
      
      if (navigator.share) {
        navigator.share({
          title: title,
          url: url
        });
      } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
          // Show temporary notification
          const btn = document.querySelector('.share-btn');
          const originalHTML = btn.innerHTML;
          btn.innerHTML = '<i class="fas fa-check"></i>';
          btn.style.background = '#28a745';
          
          setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = '';
          }, 2000);
        });
      }
    }

    // Auto-refresh every 2 minutes if there are pending matches
    <?php if ($completion_percentage < 100): ?>
    setTimeout(() => {
      window.location.reload();
    }, 120000);
    <?php endif; ?>

    // Animate progress bar on load
    document.addEventListener('DOMContentLoaded', function() {
      const progressFill = document.querySelector('.progress-fill');
      if (progressFill) {
        const targetWidth = progressFill.style.width;
        progressFill.style.width = '0%';
        setTimeout(() => {
          progressFill.style.width = targetWidth;
        }, 1200);
      }

      // Add counter animation to stats numbers
      const statsNumbers = document.querySelectorAll('.stats-number');
      statsNumbers.forEach((stat, index) => {
        const finalNumber = parseInt(stat.textContent);
        if (!isNaN(finalNumber)) {
          let currentNumber = 0;
          const increment = finalNumber / 30;
          const timer = setInterval(() => {
            currentNumber += increment;
            if (currentNumber >= finalNumber) {
              stat.textContent = finalNumber;
              clearInterval(timer);
            } else {
              stat.textContent = Math.floor(currentNumber);
            }
          }, 50);
        }
      });

      // Add stagger animation to table rows
      const tableRows = document.querySelectorAll('.standings-table tbody tr');
      tableRows.forEach((row, index) => {
        row.style.animationDelay = `${1.6 + (index * 0.1)}s`;
      });

      // Add celebration animation for first place
      const firstPlace = document.querySelector('.standings-table tbody tr:first-child');
      if (firstPlace) {
        setInterval(() => {
          firstPlace.style.animation = 'none';
          setTimeout(() => {
            firstPlace.style.animation = 'celebrate 1s ease-in-out';
          }, 10);
        }, 10000);
      }
    });

    // Add celebrate animation
  const style = document.createElement('style');
  style.textContent = `
      @keyframes celebrate {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.02) translateX(2px); }
        50% { transform: scale(1.03); }
        75% { transform: scale(1.02) translateX(-2px); }
      }
    `;
  document.head.appendChild(style);
  </script>
  <script src="darkmode.js"></script>
</body>
</html>