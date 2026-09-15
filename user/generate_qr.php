<?php
require '../include/config.php';
session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1){
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch company details
$stmt = $con->prepare("SELECT * FROM company_info WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo json_encode(['success' => false, 'error' => 'No company info found']);
    exit;
}

$company = $result->fetch_assoc();

require 'vendor/autoload.php';
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $patient_name = htmlspecialchars($_POST['fullname']);
    $op_number    = htmlspecialchars($_POST['homecontact']);

    if(empty($patient_name) || empty($op_number)){
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    // Generate unique full name
    $fullname = $company["short_form"] . "@" . $op_number . "_" . $patient_name;

    // vCard data
    $vcard = "BEGIN:VCARD\n"
    . "VERSION:3.0\n"
    . "N:;$fullname;;;\n"
    . "FN:$fullname\n"
    . "ORG:{$company['company_name']}\n"
    . "TITLE:Patient Record\n"
    . "TEL;TYPE=WORK:{$company['phone_number']}\n"
    . "EMAIL;TYPE=INTERNET:{$company['email_address']}\n"
    . "URL:{$company['website_url']}\n"
    . "ADR;TYPE=WORK:;;{$company['full_address']};;;;\n"
    . "NOTE:OP Number - $op_number\n"
    . "END:VCARD";

    // QR options - Output as raw string, not data URI
    $options = new QROptions([
        'version' => QRCode::VERSION_AUTO,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel' => QRCode::ECC_Q,
        'scale' => 6,
        'addQuietzone' => true,
        'quietzoneSize' => 2,
        'returnResource' => false, // Return binary string, not resource
    ]);

    try {
        $qrcode = new QRCode($options);
        
        // Get the QR code as a data URI string
        $qr_data_uri = $qrcode->render($vcard);
        
        // Extract the base64 image data from data URI
        if (preg_match('/^data:image\/png;base64,(.+)$/', $qr_data_uri, $matches)) {
            $qr_image_binary = base64_decode($matches[1]);
        } else {
            $qr_image_binary = $qr_data_uri;
        }

        // Always sanitize or regenerate session_code to be filesystem-safe
        if(!isset($_SESSION['session_code']) || preg_match('/[^a-zA-Z0-9_-]/', $_SESSION['session_code'])){
            $_SESSION['session_code'] = bin2hex(random_bytes(8));
        }
        $session_code = $_SESSION['session_code'];

        // Create 'qr' folder if not exists
        $qr_dir = __DIR__ . '/qr';
        if (!is_dir($qr_dir)) {
            if (!mkdir($qr_dir, 0777, true)) {
                throw new Exception("Failed to create QR directory");
            }
        }

        // Generate unique filename with safe characters only
        $unique_name = 'qr_' . $session_code . '_' . time() . '.png';
        $qr_filename = $qr_dir . '/' . $unique_name;

        // Save QR image as binary data
        if (file_put_contents($qr_filename, $qr_image_binary) === false) {
            throw new Exception("Failed to save QR code image");
        }

        // Save vCard file
        file_put_contents(__DIR__ . '/contact.vcf', $vcard);

        $user_id=$_SESSION['user_id'];

        // Insert record into DB
        $stmt = $con->prepare("INSERT INTO patient_qrcodes (patient_name, session_code, qr_image_name, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $patient_name, $session_code, $unique_name, $user_id);
        
        if(!$stmt->execute()){
            throw new Exception("Database error: " . $stmt->error);
        }
        $stmt->close();

        echo json_encode([
            'success' => true,
            'qr_image_name' => $unique_name,
            'qr_image_path' => './qr/' . $unique_name,
            'vcard_path' => 'contact.vcf'
        ]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
