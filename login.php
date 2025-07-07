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
    die("Database connection failed: " . $e->getMessage());
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($usernameOrEmail) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Liga Malam Jumat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
      min-height: 100vh;
      font-family: 'Inter', 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      position: relative;
      overflow-x: hidden;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grain" width="100" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="8" cy="8" r="0.3" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="20" fill="url(%23grain)"/></svg>');
      opacity: 0.4;
    }
    
    .floating-shapes {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 1;
    }
    
    .shape {
      position: absolute;
      opacity: 0.1;
      animation: float 6s ease-in-out infinite;
    }
    
    .shape:nth-child(1) {
      top: 10%;
      left: 10%;
      width: 60px;
      height: 60px;
      background: white;
      border-radius: 50%;
      animation-delay: 0s;
    }
    
    .shape:nth-child(2) {
      top: 70%;
      left: 80%;
      width: 40px;
      height: 40px;
      background: white;
      border-radius: 0;
      animation-delay: 2s;
    }
    
    .shape:nth-child(3) {
      top: 20%;
      right: 10%;
      width: 80px;
      height: 80px;
      background: white;
      clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
      animation-delay: 4s;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
    }
    
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 3rem;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      max-width: 450px;
      margin: 0 auto;
      animation: slideUp 0.8s ease-out;
    }
    
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .app-logo {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .logo-icon {
      width: 80px;
      height: 80px;
      background: var(--primary-gradient);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      color: white;
      font-size: 2rem;
      animation: pulse 2s infinite;
    }
    
    .app-title {
      font-size: 1.8rem;
      font-weight: 800;
      color: #2d3748;
      margin-bottom: 0.5rem;
    }
    
    .app-subtitle {
      color: #718096;
      font-size: 0.95rem;
    }
    
    .form-floating {
      margin-bottom: 1.5rem;
    }
    
    .form-control {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 1rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255,255,255,0.8);
    }
    
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      background: white;
    }
    
    .form-label {
      color: #4a5568;
      font-weight: 500;
    }
    
    .btn-login {
      background: var(--primary-gradient);
      border: none;
      border-radius: 12px;
      padding: 1rem;
      font-size: 1rem;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
      color: white;
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn-login:hover::before {
      left: 100%;
    }
    
    .divider {
      text-align: center;
      margin: 2rem 0;
      position: relative;
    }
    
    .divider::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: #e2e8f0;
    }
    
    .divider span {
      background: white;
      padding: 0 1rem;
      color: #718096;
      font-size: 0.9rem;
    }
    
    .register-link {
      text-align: center;
      margin-top: 1.5rem;
    }
    
    .register-link a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .register-link a:hover {
      color: #5a67d8;
      text-decoration: underline;
    }
    
    .alert {
      border-radius: 12px;
      border: none;
      margin-bottom: 1.5rem;
    }
    
    .alert-danger {
      background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
      color: #742a2a;
    }
    
    .input-icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      z-index: 5;
    }
    
    .password-toggle {
      cursor: pointer;
      transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
      color: #667eea;
    }
    
    @media (max-width: 768px) {
      .login-card {
        margin: 1rem;
        padding: 2rem;
      }
      
      .app-title {
        font-size: 1.5rem;
      }
      
      .logo-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <button id="darkModeToggle" class="btn btn-secondary">Dark Mode</button>
  <div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
  </div>

  <div class="container login-container">
    <div class="login-card">
      <div class="app-logo">
        <div class="logo-icon">
          <i class="fas fa-futbol"></i>
        </div>
        <h1 class="app-title">Liga Malam Jumat</h1>
        <p class="app-subtitle">Sign in to manage your football leagues</p>
      </div>

      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle me-2"></i>
          <ul class="mb-0 list-unstyled">
            <?php foreach ($errors as $err): ?>
              <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" id="loginForm">
        <div class="form-floating position-relative">
          <input type="text" name="username" class="form-control" id="username" placeholder="Username or Email" required>
          <label for="username">Username or Email</label>
          <i class="fas fa-user input-icon"></i>
        </div>

        <div class="form-floating position-relative">
          <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
          <label for="password">Password</label>
          <i class="fas fa-eye password-toggle input-icon" onclick="togglePassword()"></i>
        </div>

        <button type="submit" class="btn btn-login" id="loginBtn">
          <i class="fas fa-sign-in-alt me-2"></i>
          <span class="btn-text">Sign In</span>
        </button>
      </form>

      <div class="divider">
        <span>New to Liga Malam Jumat?</span>
      </div>

      <div class="register-link">
        <a href="register.php">
          <i class="fas fa-user-plus me-2"></i>
          Create an Account
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.querySelector('.password-toggle');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    // Add loading state to login button
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.getElementById('loginBtn');
      const btnText = btn.querySelector('.btn-text');
      
      btn.disabled = true;
      btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';
    });

    // Add focus animations
  document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
      });
    });
  </script>
  <script src="darkmode.js"></script>
</body>
</html>