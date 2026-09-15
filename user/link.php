<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1){
    header("Location: ../login");
    exit;
}
// Example dynamic URL — in real use, you can fetch this from DB or session
$url = "https://qrcode.biostarhealth.in/user/duplicate.php?user_id=" . urlencode($_SESSION['session_code']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scan to Duplicate QR</title>
  <link rel="stylesheet" href="./user-style.css">
  <style>
    :root {
      --primary-color: #2C7BE5;
      --secondary-color: #6C757D;
      --background: #F8FAFC;
      --card-bg: #FFFFFF;
      --success-color: #28A745;
      --shadow: 0 10px 20px rgba(0,0,0,0.08);
      --border-radius: 16px;
    }

    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: linear-gradient(135deg, var(--qr-primary) 0%, var(--qr-primary-dark) 100%);
      color: #333;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .container {
      background:linear-gradient(135deg, rgba(7, 7, 7, 0.85), rgba(8, 8, 8, 0.65));
      padding: 40px;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      text-align: center;
      max-width: 400px;
      width: 90%;
      transition: transform 0.3s ease;
    }

    .container:hover {
      transform: translateY(-3px);
    }

    h1 {
      color: var(--primary-color);
      font-size: 24px;
      margin-bottom: 10px;
    }

    p {
      color: var(--secondary-color);
      margin-bottom: 30px;
      font-size: 15px;
    }

    .qr-box {
      padding: 20px;
      background: #F1F3F5;
      border-radius: var(--border-radius);
      display: inline-block;
    }

    img {
      width: 220px;
      height: 220px;
      border-radius: 10px;
      transition: transform 0.3s ease;
    }

    img:hover {
      transform: scale(1.05);
    }

    .note {
      margin-top: 20px;
      font-size: 14px;
      color: var(--secondary-color);
    }

    .btn {
      display: inline-block;
      background: linear-gradient(135deg, var(--qr-primary), var(--qr-primary-dark));
      color: white;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: var(--border-radius);
      margin-top: 25px;
      font-weight: 500;
      transition: background 0.3s;
    }

    .btn:hover {
      background:linear-gradient(135deg, var(--qr-primary), var(--qr-primary-dark));
      box-shadow: 0 18px 32px rgba(124, 58, 237, 0.35);

    }
  </style>
</head>
<body>
  <div class="container">
    <h1>📱 Scan to Duplicate</h1>
    <p>Scan this QR code to duplicate or verify patient record instantly.</p>

    <div class="qr-box">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?php echo $url; ?>" alt="Duplicate QR Code">
    </div>

    <div class="note">Each scan redirects to your secure patient link.</div>

    <a href="./" class="btn">⬅ Back to Home</a>
  </div>
</body>
</html>
