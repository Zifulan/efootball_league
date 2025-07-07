<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Liga Malam Jumat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --info-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      font-family: 'Inter', 'Segoe UI', sans-serif;
    }
    
    .hero-section {
      background: var(--primary-gradient);
      color: white;
      padding: 4rem 0 3rem;
      position: relative;
      overflow: hidden;
    }
    
    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grain" width="100" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="8" cy="8" r="0.3" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="20" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }
    
    .welcome-card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 2rem;
      border: 1px solid rgba(255,255,255,0.2);
      text-align: center;
      position: relative;
    }
    
    .welcome-title {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 0.5rem;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .welcome-subtitle {
      font-size: 1.2rem;
      opacity: 0.9;
      margin-bottom: 0;
    }
    
    .user-avatar {
      width: 80px;
      height: 80px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin: 0 auto 1rem;
      border: 3px solid rgba(255,255,255,0.3);
    }
    
    .action-cards {
      margin-top: -50px;
      position: relative;
      z-index: 10;
    }
    
    .action-card {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      border: none;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      height: 100%;
      text-decoration: none;
      color: inherit;
      position: relative;
      overflow: hidden;
    }
    
    .action-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
      transition: left 0.5s;
    }
    
    .action-card:hover::before {
      left: 100%;
    }
    
    .action-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
      text-decoration: none;
      color: inherit;
    }
    
    .card-icon {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin: 0 auto 1rem;
      color: white;
      position: relative;
      z-index: 2;
    }
    
    .create-league .card-icon {
      background: var(--success-gradient);
    }
    
    .view-leagues .card-icon {
      background: var(--info-gradient);
    }
    
    .logout-card .card-icon {
      background: var(--warning-gradient);
    }
    
    .card-title {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 2;
    }
    
    .card-description {
      color: #6c757d;
      font-size: 0.95rem;
      line-height: 1.5;
      position: relative;
      z-index: 2;
    }
    
    .quick-stats {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      margin-top: 2rem;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 1.5rem;
      text-align: center;
    }
    
    .stat-item {
      padding: 1rem;
      border-radius: 15px;
      background: #f8f9fa;
      transition: all 0.3s ease;
    }
    
    .stat-item:hover {
      transform: translateY(-5px);
      background: #e9ecef;
    }
    
    .stat-number {
      font-size: 2rem;
      font-weight: 800;
      color: #495057;
      display: block;
    }
    
    .stat-label {
      font-size: 0.9rem;
      color: #6c757d;
      margin-top: 0.25rem;
    }
    
    .floating-help {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
    }
    
    .help-btn {
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
      transition: all 0.3s ease;
    }
    
    .help-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
    
    .pulse-animation {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    
    @media (max-width: 768px) {
      .welcome-title { font-size: 2rem; }
      .welcome-subtitle { font-size: 1rem; }
      .action-card { padding: 1.5rem; }
      .card-icon { width: 60px; height: 60px; font-size: 1.5rem; }
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    .floating-particles {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      top: 0;
      left: 0;
    }
    
    .particle {
      position: absolute;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }
    
    .particle:nth-child(1) { width: 10px; height: 10px; top: 20%; left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { width: 15px; height: 15px; top: 40%; left: 80%; animation-delay: 2s; }
    .particle:nth-child(3) { width: 8px; height: 8px; top: 60%; left: 20%; animation-delay: 4s; }
    .particle:nth-child(4) { width: 12px; height: 12px; top: 80%; left: 70%; animation-delay: 1s; }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 1; }
      50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
    }
  </style>
</head>
<body>
  <button id="darkModeToggle" class="btn btn-secondary">Dark Mode</button>
  <!-- Hero Section -->
  <div class="hero-section">
    <div class="floating-particles">
      <div class="particle"></div>
      <div class="particle"></div>
      <div class="particle"></div>
      <div class="particle"></div>
    </div>
    
    <div class="container position-relative">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="welcome-card pulse-animation">
            <div class="user-avatar">
              <i class="fas fa-user"></i>
            </div>
            <h1 class="welcome-title">
              Welcome back, <?= htmlspecialchars($username) ?>! 
              <i class="fas fa-hand-wave" style="color: #ffd700;"></i>
            </h1>
            <p class="welcome-subtitle">Ready to manage your football leagues?</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <!-- Action Cards -->
              <br> </br>
                        <br> </br>
    <div class="action-cards">
      <div class="row g-4">
        <div class="col-md-4 col-sm-6 d-flex">
          <a href="create_league.php" class="action-card create-league">
            <div class="card-icon">
              <i class="fas fa-plus-circle"></i>
            </div>
            <h3 class="card-title">Create New League</h3>
            <p class="card-description">Start a fresh league season with your teams and customize everything from scratch.</p>
          </a>
        </div>
        
        <div class="col-md-4 col-sm-6 d-flex">
          <a href="my_leagues.php" class="action-card view-leagues">
            <div class="card-icon">
              <i class="fas fa-trophy"></i>
            </div>
            <h3 class="card-title">My Leagues</h3>
            <p class="card-description">View and manage all your existing leagues, add results, and track standings.</p>
          </a>
        </div>
        
        <div class="col-md-4 col-sm-6 d-flex">
          <a href="logout.php" class="action-card logout-card">
            <div class="card-icon">
              <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="card-title">Sign Out</h3>
            <p class="card-description">Safely logout from your account when you're done managing your leagues.</p>
          </a>
        </div>
      </div>
    </div>
  </div>
    
    


  <!-- Floating Help Button -->
  <div class="floating-help">
    <button class="help-btn" onclick="showHelp()" title="Need Help?">
      <i class="fas fa-question"></i>
    </button>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showHelp() {
      alert('Welcome to Liga Malam Jumat!\n\n' +
            '1. Create League: Start a new football league\n' +
            '2. My Leagues: Manage existing leagues\n' +
            '3. Add teams, fixtures, and results\n' +
            '4. Share league standings with friends!\n\n' +
            'Need more help? Contact JonMEMEK!');
    }

    // Add entrance animations
  document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.action-card');
      cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
          card.style.transition = 'all 0.6s ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, index * 200);
      });
    });
  </script>
  <script src="darkmode.js"></script>
</body>
</html>