<?php
require '../include/config.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$old_password = trim($_POST['old_password']);
$new_password = trim($_POST['new_password']);

try {
    // Password strength validation
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (!preg_match($pattern, $new_password)) {
        throw new Exception("Password must be at least 8 characters long and include one uppercase letter, one lowercase letter, one number, and one special character.");
    }

    // Fetch the current password hash
    $stmt = $con->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        throw new Exception("User not found.");
    }

    $stmt->bind_result($stored_hash);
    $stmt->fetch();
    $stmt->close();

    // Verify old password
    if (!password_verify($old_password, $stored_hash)) {
        throw new Exception("Old password is incorrect.");
    }

    // Hash new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password
    $updateStmt = $con->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
    $updateStmt->bind_param("si", $new_password_hash, $user_id);

    if (!$updateStmt->execute()) {
        throw new Exception("Failed to update password.");
    }

    $updateStmt->close();
    echo json_encode(["status" => "success", "message" => "Password updated successfully!"]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
