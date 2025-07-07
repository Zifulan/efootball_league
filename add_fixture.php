<?php
// add_fixture.php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO league_fixtures (round, home_team, away_team) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['round'], $_POST['home_team'], $_POST['away_team']]);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Fixture</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
  <button id="darkModeToggle" class="btn btn-secondary">Dark Mode</button>
  <div class="container py-5">
    <h2 class="text-center mb-4">Add New Fixture</h2>
    <div class="card p-4 mx-auto" style="max-width: 500px;">
      <form method="POST">
        <div class="mb-3">
          <label for="round" class="form-label">Round</label>
          <input type="number" name="round" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="home_team" class="form-label">Home Team</label>
          <input type="text" name="home_team" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="away_team" class="form-label">Away Team</label>
          <input type="text" name="away_team" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Add Fixture</button>
        <a href="index.php" class="btn btn-secondary w-100 mt-2">Back to League</a>
      </form>
    </div>
  </div>
  <script src="darkmode.js"></script>
</body>
</html>