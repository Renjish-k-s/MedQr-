<?php
require '../include/config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1){
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch company details
$stmt = $con->prepare("SELECT * FROM company_info WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("No company info found for this user.");
}

$company = $result->fetch_assoc();

// Fetch latest QR for current session (Initial Load)
$qr_image_name = "";
$qr_image_path = "";

if(isset($_SESSION['session_code'])){
    $session_code = $_SESSION['session_code'];
    $stmt = $con->prepare("SELECT qr_image_name FROM patient_qrcodes WHERE session_code = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $session_code);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $qr_image_name = $row['qr_image_name'];
        $qr_image_path = './qr/' . $qr_image_name;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MedQR+ | Patient QR Console</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="stylesheet" href="user-style.css">
<style>
  /* Toggle Switch Styles */
  .toggle-container {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-bottom: 1rem;
  }
  .toggle-label {
    margin-right: 10px;
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
  }
  .switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 24px;
  }
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
  }
  .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }
  input:checked + .slider {
    background-color: #2196F3;
  }
  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }
  input:checked + .slider:before {
    transform: translateX(16px);
  }
  
  /* Hidden state for QR Card */
  .qr-card.hidden {
    display: none;
  }
  
  /* Loading Overlay */
  .loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
    border-radius: inherit;
    display: none;
  }
  .loading-overlay.active {
    display: flex;
  }
  .spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
</head>
<body>

<div class="dashboard-shell">
  <header class="console-hero">
    <div>
      <p class="hero-kicker">MedQR+ Console</p>
      <h1>Generate branded patient QR identities</h1>
      <p class="hero-copy">Mint secure vcards for every visit, keep your latest QR handy, and share contacts in seconds. All data stays inside your organizations shield.</p>
    </div>
    <div class="hero-links">
      <a href="./my_patients.php"><i class="fas fa-users"></i> My Patients</a>
      <a href="./change_password.php"><i class="fas fa-lock"></i> Change password</a>
      <a href="./link.php"><i class="fas fa-link"></i> Link device</a>
      <a href="./logout.php" class="danger"><i class="fas fa-arrow-right-from-bracket"></i> Logout</a>
    </div>
  </header>

  <main class="dashboard-grid">
    <section class="card form-card">
      <div class="card-head">
        <div>
          <span>Generate QR</span>
          <h2>Patient details</h2>
        </div>
        <p>Attach every visit to a unique QR + contact file.</p>
      </div>
      
      <!-- Toggle Switch -->
      <div class="toggle-container">
        <span class="toggle-label">Show QR Card</span>
        <label class="switch">
          <input type="checkbox" id="qrToggle" checked>
          <span class="slider"></span>
        </label>
      </div>

      <form id="qrForm" class="qr-form">
        <label class="field-group">
          <span>Patient name</span>
          <input type="text" name="fullname" placeholder="Enter patient name" required>
        </label>
        <label class="field-group">
          <span>OP number</span>
          <input type="text" name="homecontact" placeholder="Enter OP number" required>
        </label>
        <button type="submit" class="primary-btn">
          <i class="fas fa-qrcode"></i>
          Generate QR
        </button>
      </form>
    </section>

    <section class="card qr-card" id="qrCard">
      <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
      </div>
      <div class="card-head">
        <div>
          <span>Latest QR</span>
          <h2>Share-ready preview</h2>
        </div>
      </div>

      <div id="qrContent">
        <?php if(!empty($qr_image_name)): ?>
          <div class="qr-preview">
            <img id="qrImage" src="<?php echo htmlspecialchars($qr_image_path); ?>" alt="QR Code" width="280" height="280">
            <p>Scan this code to get the contact card instantly.</p>
          </div>
          <div class="qr-actions">
            <a id="downloadVcf" class="ghost-btn" href="contact.vcf" download>
              <i class="fas fa-address-card"></i>
              Download contact (.vcf)
            </a>
            <a id="downloadQr" class="ghost-btn" href="<?php echo htmlspecialchars($qr_image_path); ?>" download>
              <i class="fas fa-image"></i>
              Download QR image
            </a>
          </div>
        <?php else: ?>
          <div class="empty-state" id="emptyState">
            <i class="fas fa-qrcode"></i>
            <h3>No QR generated yet</h3>
            <p>Submit patient details to mint your first MedQR+ code.</p>
          </div>
          
          <!-- Hidden template for populating later -->
          <div class="qr-preview" style="display:none;" id="qrPreviewTemplate">
            <img id="qrImageTemplate" src="" alt="QR Code" width="280" height="280">
            <p>Scan this code to get the contact card instantly.</p>
          </div>
          <div class="qr-actions" style="display:none;" id="qrActionsTemplate">
            <a id="downloadVcfTemplate" class="ghost-btn" href="" download>
              <i class="fas fa-address-card"></i>
              Download contact (.vcf)
            </a>
            <a id="downloadQrTemplate" class="ghost-btn" href="" download>
              <i class="fas fa-image"></i>
              Download QR image
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const qrToggle = document.getElementById('qrToggle');
  const qrCard = document.getElementById('qrCard');
  const qrForm = document.getElementById('qrForm');
  const loadingOverlay = document.getElementById('loadingOverlay');
  const qrContent = document.getElementById('qrContent');

  // 1. Toggle Visibility Logic
  // Check local storage for preference
  const savedState = localStorage.getItem('qrCardVisible');
  if (savedState === 'false') {
    qrToggle.checked = false;
    qrCard.classList.add('hidden');
  }

  qrToggle.addEventListener('change', function() {
    if (this.checked) {
      qrCard.classList.remove('hidden');
      localStorage.setItem('qrCardVisible', 'true');
    } else {
      qrCard.classList.add('hidden');
      localStorage.setItem('qrCardVisible', 'false');
    }
  });

  // 2. AJAX Form Submission
  qrForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading
    if (!qrCard.classList.contains('hidden')) {
        loadingOverlay.classList.add('active');
    }

    const formData = new FormData(this);

    fetch('generate_qr.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      loadingOverlay.classList.remove('active');
      
      if (data.success) {
        // If hidden, maybe we should show it? Or respect the toggle?
        // Let's respect the toggle, but if the user just generated one, they probably want to see it.
        // Requirement: "default it should be enabed if he disable it the qr showing card shounld not be visible"
        // So we respect the current state.
        
        updateQRCard(data);
      } else {
        alert('Error: ' + (data.error || 'Unknown error occurred'));
      }
    })
    .catch(error => {
      loadingOverlay.classList.remove('active');
      console.error('Error:', error);
      alert('An error occurred while generating the QR code.');
    });
  });

  function updateQRCard(data) {
    // Clear empty state if it exists
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.remove();
    }

    // Check if we have the preview elements already visible
    let qrPreview = document.querySelector('.qr-preview:not(#qrPreviewTemplate)');
    let qrActions = document.querySelector('.qr-actions:not(#qrActionsTemplate)');

    // If not, clone from templates (if they exist) or create structure
    if (!qrPreview) {
        // We might be in the "empty state" initial load scenario where templates are hidden
        const previewTemplate = document.getElementById('qrPreviewTemplate');
        const actionsTemplate = document.getElementById('qrActionsTemplate');
        
        if (previewTemplate && actionsTemplate) {
            qrPreview = previewTemplate.cloneNode(true);
            qrPreview.id = "";
            qrPreview.style.display = "block";
            
            qrActions = actionsTemplate.cloneNode(true);
            qrActions.id = "";
            qrActions.style.display = "flex"; // Actions are usually flex
            
            qrContent.appendChild(qrPreview);
            qrContent.appendChild(qrActions);
            
            // Remove templates to avoid confusion or keep them? 
            // Better to just use the new elements.
        }
    }

    // Update Image
    const qrImage = qrPreview.querySelector('img');
    qrImage.src = data.qr_image_path;

    // Update Download Links
    const downloadVcf = qrActions.querySelector('a[href*=".vcf"]');
    const downloadQr = qrActions.querySelector('a[href*=".png"]'); // or just the second link

    if (downloadVcf) downloadVcf.href = data.vcard_path;
    if (downloadQr) downloadQr.href = data.qr_image_path;
  }
});
</script>

</body>
</html>
