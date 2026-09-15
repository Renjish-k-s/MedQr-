<?php
// Start the session if not already started
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Detect environment (localhost or live server)
    $server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';
    $is_local = in_array($server_name, ['localhost', '127.0.0.1']);

    if ($is_local) {
        //  Local environment
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'qr_base';
    } else {
        // Live server environment i cannot give you the live server credentials for security reasons, please fill them in yourself
        $db_host = '';
        $db_user = '';
        $db_pass = '';
        $db_name = '';
    }

    // Create DB connection
    $con = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Set charset
    $con->set_charset("utf8");

} catch (mysqli_sql_exception $e) {
    // Log the error for debugging (optional)
    error_log("Database connection error: " . $e->getMessage());

    // Redirect to a friendly error page
    header("Location: /error.php");
    exit();
}
?>
