<?php
session_start();
require '../include/config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Get raw POST data if content-type is json, otherwise use $_POST
    $input = json_decode(file_get_contents('php://input'), true);
    
    $email = isset($_POST['email']) ? trim($_POST['email']) : (isset($input['email']) ? trim($input['email']) : '');
    $password = isset($_POST['password']) ? $_POST['password'] : (isset($input['password']) ? $input['password'] : '');

    if(empty($email) || empty($password)){
        echo json_encode(['success' => false, 'message' => 'Please enter both email and password.']);
        exit;
    }

    // Fetch user
    $stmt = $con->prepare("SELECT user_id, password_hash, role, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hash, $role, $status);
    $stmt->fetch();
    $stmt->close();

    if(!$user_id){
        echo json_encode(['success' => false, 'message' => 'Invalid credentials or inactive account.']);
        exit;
    } elseif(password_verify($password, $hash)){
        // Successful login
        $session_code_hashed = str_pad(strval(random_int(0, 9999999999999999)), 16, '0', STR_PAD_LEFT);

        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        $_SESSION['session_code'] = $session_code_hashed;

        // Insert login record
        $stmtLog = $con->prepare("INSERT INTO loginregister (user_id, session_code, log_status) VALUES (?, ?, 'login')");
        $stmtLog->bind_param("is", $user_id, $session_code_hashed);
        $stmtLog->execute();
        $stmtLog->close();

        // Determine redirect URL
        $redirect = ($role == 1) ? '../user' : '../admin';

        echo json_encode(['success' => true, 'redirect' => $redirect]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
?>
