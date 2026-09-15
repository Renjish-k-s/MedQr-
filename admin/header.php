<?php
// if(!isset($_SESSION['user_id']) ){
//     header("Location: ../login");
//     exit;
// }

// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | MedQR+</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<div class="admin-wrapper">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-brand">
        <i class="fas fa-qrcode" style="color: var(--primary-color);"></i> MedQR+ Admin
      </div>
    </div>
    
    <nav class="sidebar-nav">
      <a href="./index.php" class="nav-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i> Dashboard
      </a>
      <a href="./user_control.php" class="nav-item <?php echo $current_page == 'user_control.php' ? 'active' : ''; ?>">
        <i class="fas fa-users"></i> Manage Companies
      </a>
      <a href="./register.php" class="nav-item <?php echo $current_page == 'register.php' ? 'active' : ''; ?>">
        <i class="fas fa-plus-circle"></i> Add Company
      </a>
      <!-- <a href="#" class="nav-item">
        <i class="fas fa-chart-bar"></i> Reports
      </a>
      <a href="#" class="nav-item">
        <i class="fas fa-cog"></i> Settings
      </a> -->
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Topbar -->
    <header class="topbar">
      <div class="topbar-title">
        <h2><?php 
          if($current_page == 'index.php') echo 'Dashboard Overview';
          elseif($current_page == 'user_control.php') echo 'Company Management';
          elseif($current_page == 'register.php') echo 'Register New Company';
          elseif($current_page == 'edit_company.php') echo 'Edit Company Details';
          else echo 'Admin Panel';
        ?></h2>
        <p><?php echo date('l, F j, Y'); ?></p>
      </div>
      
      <div class="user-menu">
        <form action="logout.php" method="post" style="margin:0;">
          <button type="submit" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </div>
    </header>

  

