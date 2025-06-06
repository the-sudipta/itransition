<?php
ob_start();

try {
    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php';
    global $routes;
    require '../routes.php';
    $root_page = $routes['INDEX'];

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Clear session data
    $_SESSION["data"] = null;
    $_SESSION["user_id"] = -1;
    $_SESSION["user_role"] = null;
    $_SESSION["data"]["status"] = -1;
    $_SESSION["user_status"] = null;


    session_destroy();

    // Add Log
    // Redirect to homepage
    header("Location: {$root_page}");
    exit;

} catch (Throwable $e) {
    // Fallback to error page

    $error_location = "LogoutController";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}

ob_end_flush();
