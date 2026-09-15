<?php
require '../include/config.php';
header('Content-Type: application/json');

$company_name  = trim($_POST['company_name']);
$short_form    = trim($_POST['short_form']);
$website_url   = trim($_POST['website_url']);
$full_address  = trim($_POST['full_address']);
$phone_number  = trim($_POST['phone_number']);
$email_address = trim($_POST['email_address']);
$password      = "default123";
$role          = '1';
$status        = '0';

try {
    // Start transaction
    $con->begin_transaction();

    // Check if email exists
    $checkStmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email_address);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        throw new Exception("Email already registered.");
    }
    $checkStmt->close();

    // Create user
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmtUser = $con->prepare("INSERT INTO users (email, password_hash, role, status) VALUES (?, ?, ?, ?)");
    $stmtUser->bind_param("ssss", $email_address, $password_hash, $role, $status);
    if (!$stmtUser->execute()) throw new Exception("User creation failed.");
    $user_id = $stmtUser->insert_id;
    $stmtUser->close();

    // Handle logo upload (JPEG only)
    $logo_path = null;
    if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['company_logo']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($file_tmp);

        // ✅ Allow only JPEG
        if (!in_array($file_ext, ['jpg', 'jpeg']) || !in_array($file_mime, ['image/jpeg'])) {
            throw new Exception("Only JPEG images are allowed for company logo.");
        }

        $upload_dir = '../uploads/company_logos/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_name = 'logo_' . $user_id . '_' . time() . '.jpg';
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $logo_path = 'uploads/company_logos/' . $file_name;
        } else {
            throw new Exception("Failed to upload company logo.");
        }
    }

    // Insert company info
    $stmtCompany = $con->prepare("
        INSERT INTO company_info (user_id, company_name, short_form, website_url, full_address, phone_number, email_address, company_logo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmtCompany->bind_param("isssssss", $user_id, $company_name, $short_form, $website_url, $full_address, $phone_number, $email_address, $logo_path);

    if (!$stmtCompany->execute()) {
        throw new Exception("Failed to insert company info.");
    }

    // ✅ Commit transaction if all success
    $con->commit();

    echo json_encode(["status" => "success", "message" => "Company registered successfully!"]);

} catch (Exception $e) {
    // ❌ Rollback all changes if any error occurs
    $con->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
