<?php
require '../include/config.php';

header('Content-Type: application/json');

// Get session_code from URL
if(!isset($_GET['session_code'])){
    echo json_encode(['success' => false, 'error' => 'No session_code provided']);
    exit;
}

$session_code = $_GET['session_code'];

// Fetch the most recent QR code for this session
$stmt = $con->prepare("SELECT patient_name, qr_image_name, created_at FROM patient_qrcodes WHERE session_code = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $session_code);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    
    $qr_image_path = './qr/' . $row['qr_image_name'];
    
    // Check if file exists
    if(file_exists($qr_image_path)){
        echo json_encode([
            'success' => true,
            'patient_name' => $row['patient_name'],
            'qr_image_path' => $qr_image_path,
            'created_at' => $row['created_at']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'QR image file not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'No QR code found for this session'
    ]);
}

$stmt->close();
$con->close();
?>