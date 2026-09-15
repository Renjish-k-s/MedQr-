<?php
session_start();
require '../include/config.php';

if(isset($_SESSION['user_id']) && isset($_SESSION['session_code'])){
    $user_id = $_SESSION['user_id'];
    $session_code = $_SESSION['session_code'];

    // Update logout time
    $stmt = $con->prepare("UPDATE loginregister SET logout_time = NOW(), log_status='logout' WHERE user_id=? AND session_code=? AND log_status='login'");
    $stmt->bind_param("is", $user_id, $session_code);
    $stmt->execute();
    $stmt->close();

    // Destroy session
    session_unset();
    session_destroy();
}

header("Location: ../login");
exit;
