<?php
session_start();
require '../include/config.php';
// PHP logic moved to login_process.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MedQR+ | Secure Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="./login-style.css">
  <style>
      .alert-error {
          display: none; /* Hidden by default */
      }
  </style>
</head>
<body>
  <div class="login-shell">
    <section class="login-hero">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <div class="brand-pill">
          <i class="fas fa-qrcode"></i>
          MedQR+ Access
        </div>
        <h1>Welcome back to your smart QR workspace</h1>
        <p>Manage patient identities, branding presets, and analytics through a single, encrypted console crafted with the new MedQR+ look.</p>
        <div class="hero-stats">
          <div>
            <span>38</span>
            <small>Hospitals onboarded</small>
          </div>
          <div>
            <span>120K+</span>
            <small>Active QR cards</small>
          </div>
          <div>
            <span>99.9%</span>
            <small>Platform uptime</small>
          </div>
        </div>
      </div>
      <div class="hero-qr">
        <div class="qr-light"></div>
        <div class="qr-card">
          <div class="qr-card-header">
            <strong>MedQR+ Preview</strong>
            <span>Live</span>
          </div>
          <div class="qr-grid">
            <div class="qr-avatar">
              <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" alt="Avatar" loading="lazy">
              <div>
                <p>Dravya Mathew</p>
                <small>OP ID: BX-92014</small>
              </div>
            </div>
            <div class="qr-code"></div>
          </div>
          <p class="qr-caption">Scan-ready, role-aware identity cards</p>
        </div>
      </div>
    </section>

    <main class="login-panel" aria-label="MedQR+ Login form">
      <div class="panel-card">
        <a href="../" class="back-link">
          <i class="fas fa-arrow-left"></i>
          Back to MedQR+ site
        </a>
        <h2>Secure Console Login</h2>
        <p class="panel-subtitle">Use your registered credentials to continue</p>

        <div class="alert-error" id="errorAlert">
            <i class="fas fa-circle-exclamation"></i>
            <span id="errorMessage"></span>
        </div>

        <form id="loginForm" class="login-form">
          <label class="form-field">
            <span>Email address</span>
            <input type="email" name="email" placeholder="you@hospital.com" required>
          </label>
          <label class="form-field">
            <span>Password</span>
            <input type="password" name="password" placeholder="••••••••••" required>
          </label>
          <button type="submit" class="submit-btn" id="loginBtn">
            <i class="fas fa-unlock"></i>
            <span id="btnText">Sign in to console</span>
          </button>
        </form>

        <div class="panel-footer">
          <p>Need access or forgot your credentials? <a href="mailto:support@biostarhealth.in">Contact support</a></p>
        </div>
      </div>
    </main>
  </div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('btnText');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');
    
    // Reset error
    errorAlert.style.display = 'none';
    
    // Loading state
    const originalText = btnText.innerText;
    btnText.innerText = 'Signing in...';
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.style.cursor = 'not-allowed';
    
    const formData = new FormData(form);
    
    fetch('login_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            // Show error
            errorMessage.innerText = data.message || 'An error occurred.';
            errorAlert.style.display = 'flex'; // Assuming flex is used in CSS for alert
            
            // Reset button
            btnText.innerText = originalText;
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.innerText = 'Network error. Please try again.';
        errorAlert.style.display = 'flex';
        
        // Reset button
        btnText.innerText = originalText;
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    });
});
</script>

</body>
</html>
