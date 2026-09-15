<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MedQR+ — Smart Medical QR System | Biostarhealth</title>
  
  <!-- SEO Meta Tags -->
  <meta name="description" content="Transform hospital visits with MedQR+ Smart QR Codes. Never lose your OP card again with secure digital QR codes for patient identification and medical records.">
  <meta name="keywords" content="medical QR code, patient ID, digital OP card, hospital QR system, MedQR+">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Custom Styles -->
  <link rel="stylesheet" href="css/medqr-style.css">
</head>
<body>

  <!-- Navbar -->
  <header class="medqr-nav">
    <div class="nav-container">
      <a class="nav-brand" href="#home">
        <i class="fas fa-qrcode"></i>
        MedQR<span>+</span><span style="font-size: 8px;color: #0c0c0cff;">A biostarhealth product</span>
      </a>
      <nav>
        <a href="#home">Overview</a>
        <a href="#features">Highlights</a>
        <a href="#journey">Journey</a>
        <a href="#trust">Proof</a>
      </nav>
      <div class="nav-cta">
        <a href="./login/" class="ghost-link">
          <i class="fas fa-arrow-right-to-bracket"></i>
          Dashboard
        </a>
        <a href="./login/" class="solid-link">
          <i class="fas fa-plus-circle"></i>
          Create Patient QR
        </a>
      </div>
      <button class="nav-toggle" aria-label="Open navigation">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- Mobile Drawer -->
  <div class="nav-drawer">
    <a href="#home">Overview</a>
    <a href="#features">Highlights</a>
    <a href="#journey">Journey</a>
    <a href="#trust">Proof</a>
    <a href="./login/" class="solid-link">
      <i class="fas fa-plus-circle"></i>
      Create Patient QR
    </a>
  </div>

  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="scan-line"></div>
    <div class="hero-grid container">
      <div class="hero-copy">
        <span class="pill">MedQR+ <span style="font-size: 8px;">A biostarhealth product</span></span>
        <h1>Bring hospital visits to life with a single scan</h1>
        <p>Inspired by the Bioscale precision-first experience, MedQR+ gives every patient a living QR identity. Secure onboarding, instant contact sync, and clinician-ready data in one visually rich card.</p>
        <div class="hero-actions">
          <a href="./login/" class="cta-btn">
            <i class="fas fa-bolt"></i>
            Launch Creator
          </a>
          <button class="ghost-link hero-video" data-demo="true">
            <i class="fas fa-circle-play"></i>
            Watch the 60s demo
          </button>
        </div>
        <div class="hero-stats">
          <div>
            <h3>120K+</h3>
            <p>Active patient passes</p>
          </div>
          <div>
            <h3>38</h3>
            <p>Partner hospitals</p>
          </div>
          <div>
            <h3>99.9%</h3>
            <p>Scan uptime</p>
          </div>
        </div>
      </div>

      <div class="hero-preview">
        <div class="qr-orb"></div>
        <div class="preview-card">
          <div class="preview-header">
            <div class="badge">MedQR+</div>
            <span>Live</span>
          </div>
          <div class="preview-body">
            <div class="preview-avatar">
              <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" alt="Patient Avatar" loading="lazy">
              <div>
                <h4>Dravya Mathew</h4>
                <p>OP ID: BX-92014</p>
              </div>
            </div>
            <div class="preview-qr">
              <div class="animated-qr"></div>
              <p>Scan to sync contacts + case sheet</p>
            </div>
            <ul class="preview-meta">
              <li>
                <i class="fas fa-hospital"></i>
                Sunrise Multi Care
              </li>
              <li>
                <i class="fas fa-calendar-day"></i>
                Visits tracked automatically
              </li>
              <li>
                <i class="fas fa-lock"></i>
                AES-256 record encryption
              </li>
            </ul>
          </div>
          <div class="preview-footer">
            <span><i class="fas fa-wave-square"></i> Dynamic QR status</span>
            <button>
              <i class="fas fa-share-nodes"></i>
              Share card
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="features">
    <div class="container">
      <div class="section-header">
        <span>Highlights</span>
        <h2>Crafted for clinical teams & patient journeys</h2>
        <p>Borrowing Bioscale’s clean geometry, this layout keeps every interaction obvious yet delightful.</p>
      </div>
      <div class="feature-grid">
        <article class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-layer-group"></i>
          </div>
          <h3>Multi-profile cards</h3>
          <p>Issue OP, inpatient, and specialist-ready QR layers with one click. The card adapts contextually every time it is scanned.</p>
          <ul>
            <li>Profile aware metadata</li>
            <li>Custom branding slot</li>
            <li>Offline safe mode</li>
          </ul>
        </article>
        <article class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-rocket"></i>
          </div>
          <h3>Rapid onboarding</h3>
          <p>No wasted clicks. Import OP registers or sync EMR sheets and let MedQR+ mint identities in seconds.</p>
          <ul>
            <li>CSV + API bridge</li>
            <li>Bulk printing layouts</li>
            <li>One-tap share links</li>
          </ul>
        </article>
        <article class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-shield-heart"></i>
          </div>
          <h3>Clinical-grade privacy</h3>
          <p>Scoped access tokens, masked numbers, and audit-ready logs. Security defaults that fit NABH workflows.</p>
          <ul>
            <li>Custom retention rules</li>
            <li>Role-aware QR views</li>
            <li>Redaction friendly</li>
          </ul>
        </article>
      </div>
    </div>
  </section>

  <!-- Journey Timeline -->
  <section id="journey" class="journey">
    <div class="container">
      <div class="journey-heading">
        <span>Activation flow</span>
        <h2>From registration desk to clinician in three elegant hops</h2>
      </div>
      <div class="journey-steps">
        <div class="journey-step">
          <div class="step-count">01</div>
          <h3>Capture & mint</h3>
          <p>Reception uploads minimal demographics or pulls them from ORMS. MedQR+ generates branded cards instantly.</p>
        </div>
        <div class="journey-step">
          <div class="step-count">02</div>
          <h3>Share & sync</h3>
          <p>Patients add the card to their wallet. Clinicians scan to view visits, attach notes, and drop prescriptions.</p>
        </div>
        <div class="journey-step">
          <div class="step-count">03</div>
          <h3>Track & learn</h3>
          <p>Analytics highlight peak OP timings, returning patients, and scan health in a Bioscale-like dashboard.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Trust Section -->
  <section id="trust" class="trust">
    <div class="container trust-grid">
      <div class="trust-copy">
        <span>Proof points</span>
        <h2>Hospitals rely on MedQR+ for resilient patient identity</h2>
        <p>Our UI leans on the same dimensional spacing you loved on Bioscale, making MedQR+ feel like part of the same ecosystem.</p>
        <a href="./login/" class="ghost-link">
          <i class="fas fa-arrow-right"></i>
          Explore admin console
        </a>
      </div>
      <div class="trust-stats">
        <div class="stat-card">
          <i class="fas fa-building"></i>
          <h3>4.8/5</h3>
          <p>Average IT team rating</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-chart-line"></i>
          <h3>68%</h3>
          <p>Faster patient lookup</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-file-shield"></i>
          <h3>0 incidents</h3>
          <p>Security escalations</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-recycle"></i>
          <h3>250K</h3>
          <p>Sheets saved annually</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <!-- <section class="cta">
    <div class="container cta-card">
      <div>
        <span>Ready to switch?</span>
        <h2>Spin up a pilot clinic in under a day</h2>
        <p>Keep your current brand colors, integrate with EMR, and hand every patient a futuristic MedQR+ pass.</p>
      </div>
      <div class="cta-actions">
        <a href="./login/" class="solid-link">
          <i class="fas fa-star"></i>
          Start now
        </a>
        <a href="mailto:hello@biostarhealth.in" class="ghost-link">
          <i class="fas fa-envelope"></i>
          Talk to our team
        </a>
      </div>
    </div>
  </section> -->

  <!-- Footer -->
  <footer>
    <div class="container">
      <p>
        © <?php echo date('Y'); ?> <strong>MedQR+</strong> by <strong>Biostarhealth</strong>. All rights reserved.<br> 
        <a href="#">Privacy Policy</a> | 
        <a href="#">Terms of Service</a>
      </p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JavaScript -->
  <script src="js/medqr-script.js"></script>
  
</body>
</html>
