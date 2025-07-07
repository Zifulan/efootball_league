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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $league_name = $_POST['league_name'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    $owner_id = $_SESSION['user_id'];
    $code = bin2hex(random_bytes(5)); // 10-char league code

    // Insert league
    $stmt = $pdo->prepare("INSERT INTO leagues (user_id, name, is_public, code) VALUES (?, ?, ?, ?)");
    $stmt->execute([$owner_id, $league_name, $is_public, $code]);

    $league_id = $pdo->lastInsertId();
    header("Location: add_team.php?league_id=$league_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create League</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <style>
    .form-box {
      max-width: 600px;
      margin: 80px auto;
      background: #fff;
      border-radius: 10px;
      padding: 40px;
      box-shadow: 0 0 15px rgba(0,0,0,0.08);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h2 class="text-center mb-4">🆕 Create New League</h2>
      <form method="POST">
        <div class="mb-3">
          <label for="league_name" class="form-label">League Name</label>
          <input type="text" name="league_name" class="form-control" required>
        </div>
        <div class="form-check mb-4">
          <input type="checkbox" name="is_public" class="form-check-input" id="publicCheck">
          <label class="form-check-label" for="publicCheck">Make this league public</label>
        </div>
        <button type="submit" class="btn btn-success w-100">Create League</button>
        <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
      </form>
    </div>
  </div>
</body>
</html>