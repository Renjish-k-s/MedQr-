<?php
// fetch_user_data.php - Fetch user and company data
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    include '../include/config.php'; // includes $con (MySQLi)
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Configuration error']);
    exit;
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check for user_id
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$user_id = intval($_GET['user_id']);

try {
    // Fetch user data
    $stmt = $con->prepare("SELECT user_id, email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    // Fetch company data
    $stmt = $con->prepare("
        SELECT 
            id,
            user_id,
            company_name,
            short_form,
            website_url,
            full_address,
            phone_number,
            email_address,
            company_logo
        FROM company_info 
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $company = $result->fetch_assoc();
    $stmt->close();

    // Combine the data
    $data = [
        'user_id' => $user['user_id'],
        'email' => $user['email'],
        'company_id' => $company ? $company['id'] : null,
        'company_name' => $company ? $company['company_name'] : '',
        'short_form' => $company ? $company['short_form'] : '',
        'website_url' => $company ? $company['website_url'] : '',
        'full_address' => $company ? $company['full_address'] : '',
        'phone_number' => $company ? $company['phone_number'] : '',
        'company_logo' => $company ? $company['company_logo'] : ''
    ];

    echo json_encode(['success' => true, 'data' => $data]);

} catch (mysqli_sql_exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
