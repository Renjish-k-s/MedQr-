<?php
// update_user_data.php - Update user and company data
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    include '../include/config.php'; // includes $con (MySQLi)
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Configuration error']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($input['user_id']) || empty($input['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

if (!isset($input['email']) || empty($input['email'])) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

// Validate email format
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

$user_id = intval($input['user_id']);
$email = trim($input['email']);
$company_name = trim($input['company_name'] ?? '');
$short_form = trim($input['short_form'] ?? '');
$website_url = trim($input['website_url'] ?? '');
$full_address = trim($input['full_address'] ?? '');
$phone_number = trim($input['phone_number'] ?? '');
$company_logo = trim($input['company_logo'] ?? '');

try {
    // Begin MySQLi transaction
    $con->begin_transaction();

    // ✅ Check if user exists
    $stmt = $con->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->fetch_assoc()) {
        $con->rollback();
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    $stmt->close();

    // ✅ Check if email already exists for another user
    $stmt = $con->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        $con->rollback();
        echo json_encode(['success' => false, 'message' => 'Email already exists for another user']);
        exit;
    }
    $stmt->close();

    // ✅ Update users table
    $stmt = $con->prepare("UPDATE users SET email = ? WHERE user_id = ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $stmt->close();

    // ✅ Check if company_info record exists
    $stmt = $con->prepare("SELECT id FROM company_info WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $company = $result->fetch_assoc();
    $stmt->close();

    if ($company) {
        // ✅ Update existing company_info
        $stmt = $con->prepare("
            UPDATE company_info 
            SET company_name = ?,
                short_form = ?,
                website_url = ?,
                full_address = ?,
                phone_number = ?,
                email_address = ?,
                company_logo = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param(
            "sssssssi",
            $company_name,
            $short_form,
            $website_url,
            $full_address,
            $phone_number,
            $email,
            $company_logo,
            $user_id
        );
        $stmt->execute();
        $stmt->close();
    } else {
        // ✅ Insert new company_info
        $stmt = $con->prepare("
            INSERT INTO company_info 
            (user_id, company_name, short_form, website_url, full_address, phone_number, email_address, company_logo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isssssss",
            $user_id,
            $company_name,
            $short_form,
            $website_url,
            $full_address,
            $phone_number,
            $email,
            $company_logo
        );
        $stmt->execute();
        $stmt->close();
    }

    // ✅ Commit transaction
    $con->commit();

    echo json_encode(['success' => true, 'message' => 'Data updated successfully']);

} catch (mysqli_sql_exception $e) {
    if (isset($con)) {
        $con->rollback();
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
