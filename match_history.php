<?php
session_start();
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

$league_id = $_GET['league_id'] ?? $_GET['code'] ?? null;
if (!$league_id) {
  die("League ID is required.");
}

$matches = $pdo->prepare("SELECT * FROM league_match_log WHERE league_id = ? ORDER BY id DESC");
$matches->execute([$league_id]);
$matches = $matches->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Match History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }
    .header-title {
      font-weight: 700;
      color: #0d6efd;
    }
    table th, table td {
      vertical-align: middle;
    }
  </style>
</head>
<body class="bg-light">
  <button id="darkModeToggle" class="btn btn-secondary">Dark Mode</button>
  <div class="container py-5">
    <h2 class="text-center mb-4">Match Result History</h2>
    <div class="card p-4">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Round</th>
            <th>Home Team</th>
            <th>Score</th>
            <th>Away Team</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($matches) > 0): ?>
            <?php foreach ($matches as $i => $m): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($m['match_date']) ?></td>
                <td><?= htmlspecialchars($m['round']) ?></td>
                <td><?= htmlspecialchars($m['home_team']) ?></td>
                <td><strong><?= $m['home_score'] ?> - <?= $m['away_score'] ?></strong></td>
                <td><?= htmlspecialchars($m['away_team']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">No match history available yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
  <a href="show-standing.php?code=<?= $league_id ?>" class="btn btn-secondary mt-3">Back to League</a>
    </div>
  </div>
  <script src="darkmode.js"></script>
</body>
</html>