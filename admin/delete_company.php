<?php
require '../include/config.php';
header('Content-Type: application/json');

$id = intval($_POST['id']);

if (!$id) {
    echo json_encode(["status" => "error", "message" => "Invalid company ID."]);
    exit;
}

// Get user_id linked to this company
$query = $con->prepare("SELECT user_id FROM company_info WHERE user_id = ?");
$query->bind_param("i", $id);
$query->execute();
$query->bind_result($user_id);
$query->fetch();
$query->close();

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "Company not found."]);
    exit;
}

// Delete company first
$delCompany = $con->prepare("DELETE FROM company_info WHERE user_id = ?");
$delCompany->bind_param("i", $id);
$delCompany->execute();
$delCompany->close();

// Delete associated user
$delUser = $con->prepare("DELETE FROM users WHERE user_id  = ?");
$delUser->bind_param("i", $user_id);
$delUser->execute();
$delUser->close();

echo json_encode(["status" => "success", "message" => "Company and user deleted successfully."]);
?>
