<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome | Liga Malam Jumat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: #f4f6f8;
      font-family: 'Segoe UI', sans-serif;
    }
    .welcome-box {
      max-width: 600px;
      margin: 80px auto;
      padding: 40px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .btn-lg {
      padding: 10px 24px;
      font-size: 1.2rem;
    }
  </style>
</head>
<body>
  <button id="darkModeToggle" class="btn btn-secondary">Dark Mode</button>
  <div class="container">
    <div class="welcome-box text-center">
      <h1 class="mb-4">⚽ Liga Malam Jumat</h1>
      <p class="mb-4">
        Create and manage your own custom eFootball league.<br>
        Add teams, generate fixtures, input results, and track standings.
      </p>

      <div class="d-grid gap-2 col-8 mx-auto mb-3">
        <a href="register.php" class="btn btn-primary btn-lg">🆕 Create an Account</a>
        <a href="login.php" class="btn btn-outline-secondary btn-lg">🔐 Login</a>
      </div>

      <div class="mt-4 text-muted">
        Or view a public league:
        <br>
        <code>show-standing.php?code=xxxxx</code>
      </div>
    </div>
  </div>
  <script src="darkmode.js"></script>
</body>
</html>