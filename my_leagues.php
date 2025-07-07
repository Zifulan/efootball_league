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

// Redirect to public view if ?code is provided
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    header("Location: show-standing.php?code=" . urlencode($code));
    exit;
}

// Otherwise, load logged-in user's leagues
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, name, code, is_public FROM leagues WHERE user_id = ?");
$stmt->execute([$user_id]);
$leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);

$base_url = "https://lensajon.my.id/league";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Leagues</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <style>
    .league-card {
      border-left: 5px solid #0d6efd;
      background: #fff;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .league-card:hover {
      background: #f8f9fa;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <h2 class="text-center mb-4">⚽ My Leagues</h2>

  <div class="mb-4 text-end">
    <a href="create_league.php" class="btn btn-success">➕ Create New League</a>
    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
  </div>

  <?php if (count($leagues) === 0): ?>
    <div class="alert alert-info text-center">You don't have any leagues yet.</div>
  <?php else: ?>
    <?php foreach ($leagues as $lg): ?>
      <div class="league-card">
        <h5><?= htmlspecialchars($lg['name']) ?></h5>
        <div class="small text-muted">League Code: <code><?= $lg['code'] ?></code></div>
        <?php if ($lg['is_public']): ?>
          <div class="small">Public URL:
            <a href="<?= $base_url ?>/show-standing.php?code=<?= $lg['code'] ?>" target="_blank">
              <?= $base_url ?>/show-standing.php?code=<?= $lg['code'] ?>
            </a>
          </div>
        <?php endif; ?>
        <div class="mt-3">
          <a href="add_team.php?league_id=<?= $lg['id'] ?>" class="btn btn-outline-primary btn-sm">Add Team</a>
          <a href="add_result.php?league_id=<?= $lg['id'] ?>" class="btn btn-outline-success btn-sm">Add Result</a>
          <a href="show-standing.php?code=<?= $lg['code'] ?>" target="_blank" class="btn btn-outline-dark btn-sm">Standings</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>