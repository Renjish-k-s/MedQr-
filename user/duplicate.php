<?php
require '../include/config.php';

// Get session_code from URL
if(!isset($_GET['user_id'])){
    die("Invalid access. No user_id provided.");
}

$session_code = $_GET['user_id'];

// Fetch the most recent QR code for this session
$stmt = $con->prepare("SELECT patient_name, qr_image_name, created_at FROM patient_qrcodes WHERE session_code = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $session_code);
$stmt->execute();
$result = $stmt->get_result();

$patient_name = "";
$qr_image_path = "";
$last_updated = "";

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $patient_name = htmlspecialchars($row['patient_name']);
    $qr_image_path = './qr/' . htmlspecialchars($row['qr_image_name']);
    $last_updated = $row['created_at'];
} else {
    $patient_name = "Guest";
    $qr_image_path = "";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient QR Code - Live View</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
  }
  
  .container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    padding: 30px 20px;
    max-width: 500px;
    width: 100%;
    text-align: center;
    animation: slideIn 0.5s ease-out;
  }
  
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .greeting {
    font-size: clamp(20px, 5vw, 28px);
    color: #333;
    margin-bottom: 8px;
    font-weight: 600;
    line-height: 1.3;
  }
  
  .patient-name {
    color: #667eea;
    font-weight: 700;
    display: inline-block;
    word-break: break-word;
  }
  
  .instruction {
    font-size: clamp(14px, 4vw, 18px);
    color: #666;
    margin-bottom: 20px;
  }
  
  .qr-container {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 20px;
    margin: 20px 0;
    position: relative;
    overflow: hidden;
  }
  
  .qr-container::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #667eea, #764ba2, #667eea);
    border-radius: 15px;
    z-index: -1;
    animation: rotate 3s linear infinite;
  }
  
  @keyframes rotate {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  
  .qr-image {
    width: 100%;
    max-width: 350px;
    height: auto;
    border-radius: 10px;
    background: white;
    padding: 15px;
    transition: transform 0.3s ease;
    display: block;
    margin: 0 auto;
  }
  
  .qr-image:hover {
    transform: scale(1.02);
  }
  
  .no-qr {
    padding: 40px 20px;
    color: #999;
    font-size: clamp(14px, 4vw, 18px);
  }
  
  .spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 20px auto;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: clamp(11px, 3vw, 14px);
    font-weight: 600;
    margin-top: 15px;
  }
  
  .status.live {
    background: #d4edda;
    color: #155724;
  }
  
  .status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    margin-right: 6px;
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0.5;
    }
  }
  
  .footer {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #eee;
    color: #999;
    font-size: clamp(11px, 3vw, 14px);
  }
  
  .footer p {
    margin: 5px 0;
  }
  
  .footer strong {
    word-break: break-all;
    display: inline-block;
    max-width: 100%;
  }
  
  @media (max-width: 600px) {
    body {
      padding: 5px;
    }
    
    .container {
      padding: 20px 15px;
      border-radius: 15px;
    }
    
    .greeting {
      margin-bottom: 5px;
    }
    
    .instruction {
      margin-bottom: 15px;
    }
    
    .qr-container {
      padding: 15px;
      margin: 15px 0;
      border-radius: 12px;
    }
    
    .qr-image {
      padding: 10px;
      max-width: 280px;
    }
    
    .status {
      padding: 5px 10px;
      margin-top: 10px;
    }
    
    .status-dot {
      width: 6px;
      height: 6px;
    }
    
    .footer {
      margin-top: 15px;
      padding-top: 12px;
    }
  }
  
  @media (max-width: 400px) {
    .container {
      padding: 15px 10px;
    }
    
    .qr-container {
      padding: 10px;
    }
    
    .qr-image {
      max-width: 240px;
      padding: 8px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <h1 class="greeting">
    Hi <span class="patient-name"><?php echo $patient_name; ?></span>! 👋
  </h1>
  <p class="instruction">Please scan the QR code below</p>
  
  <div class="qr-container" id="qrContainer">
    <?php if(!empty($qr_image_path) && file_exists($qr_image_path)): ?>
      <img src="<?php echo $qr_image_path; ?>?v=<?php echo time(); ?>" 
           class="qr-image" 
           id="qrImage" 
           alt="Patient QR Code">
    <?php else: ?>
      <div class="no-qr">
        <div class="spinner"></div>
        <p>Waiting for QR code...</p>
      </div>
    <?php endif; ?>
  </div>
  
  <div class="status live">
    <span class="status-dot"></span>
    Auto-updating every 3 seconds
  </div>
  
  <div class="footer">
    <p>Session Code: <strong><?php echo htmlspecialchars($session_code); ?></strong></p>
    <p style="margin-top: 8px; font-size: 12px;">This page updates automatically when new QR codes are generated</p>
  </div>
</div>

<script>
// Auto-refresh QR code every 3 seconds
let lastImageSrc = '';
const sessionCode = '<?php echo addslashes($session_code); ?>';

function checkForUpdates() {
  fetch('get_latest_qr.php?session_code=' + encodeURIComponent(sessionCode))
    .then(response => response.json())
    .then(data => {
      if (data.success && data.qr_image_path) {
        const newImageSrc = data.qr_image_path + '?v=' + new Date().getTime();
        
        // Update image if changed
        if (newImageSrc !== lastImageSrc) {
          const qrContainer = document.getElementById('qrContainer');
          const currentImage = document.getElementById('qrImage');
          
          if (currentImage) {
            // Smooth transition
            currentImage.style.opacity = '0';
            setTimeout(() => {
              currentImage.src = newImageSrc;
              currentImage.style.opacity = '1';
            }, 300);
          } else {
            // First time loading - replace the "waiting" message
            qrContainer.innerHTML = `
              <img src="${newImageSrc}" 
                   class="qr-image" 
                   id="qrImage" 
                   alt="Patient QR Code"
                   style="opacity: 0; transition: opacity 0.3s;">
            `;
            setTimeout(() => {
              document.getElementById('qrImage').style.opacity = '1';
            }, 100);
          }
          
          lastImageSrc = newImageSrc;
          
          // Update patient name if changed
          if (data.patient_name) {
            const nameElement = document.querySelector('.patient-name');
            if (nameElement.textContent !== data.patient_name) {
              nameElement.textContent = data.patient_name;
            }
          }
        }
      }
    })
    .catch(error => {
      console.error('Error checking for updates:', error);
    });
}

// Initial check
checkForUpdates();

// Check every 3 seconds
setInterval(checkForUpdates, 3000);

// Add smooth opacity transition to image
const style = document.createElement('style');
style.textContent = '#qrImage { transition: opacity 0.3s ease; }';
document.head.appendChild(style);
</script>

</body>
</html>