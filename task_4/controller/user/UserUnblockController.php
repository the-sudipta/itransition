<?php
ob_start();

try {

    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    global $routes, $backend_routes;
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/userRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/logsRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php';

    // Frontend Page Links
    $User_dashboard_page = $routes['user_dashboard'];
    $Login_page = $routes['login'];

    // Error Page
    $forbidden_error_page = $routes['forbidden_error'];


    // Backend File Links
    $user_block_controller     = $backend_routes['user_block_controller'];
    $user_delete_controller     = $backend_routes['user_delete_controller'];
    $user_unblock_controller     = $backend_routes['user_unblock_controller'];

    $current_date_time = date("Y-m-d H:i:s");


    // Authentication and Authorization Checking
    if(isset($_SESSION["user_id"]) && isset($_SESSION["user_role"])){
        // Is Session Active?
        if($_SESSION["user_id"] <= 0){
            echo generateErrorText("Session Issue", "No user_id found in session variable");
            $user_id = -1;
            navigate($Login_page, "Session Expired");
        }else{
            $user_id = $_SESSION["user_id"];
        }

        // Is Correct Role?
        if(strtolower($_SESSION["user_role"]) !== 'user'){
            $_SESSION['role_error'] = true;
            navigate($forbidden_error_page);
            echo generateErrorText("User Role Issue", "This page is only accessible by admin");
        }
    }else{
        /// same as $_SESSION["user_id"] <= 0
        echo generateErrorText("Session Issue", "No user_id found in session variable");
        $user_id = -1;
        navigate($Login_page, "Session Expired");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids) || empty($ids)) {
            $errorMessage = urldecode("No user selected");
            navigate($User_dashboard_page, $errorMessage);
            echo generateErrorText("User selection Error", "No user selected");
            exit;

        }

        foreach ($ids as $id) {
            // make sure it’s an integer
            $id = (int)$id;
            if ($id <= 0) {
                continue;
            }else{
                updateUserStatus('active', $id);
                // Add Log
                $log_inserted_id = createLog("unblock : ".$id, $current_date_time, $user_id);
            }
        }

        // when done, redirect back
        $errorMessage = urldecode("Unlocked successfully");
        navigate($User_dashboard_page, $errorMessage,"success_message");
        echo generateSuccessText("User selection", "Successfully Blocked Users");
        exit;
    }else{
        $_SESSION['backend_direct_access'] = true;
        navigate($forbidden_error_page);
    }

} catch (Throwable $e) {
    // Fallback to error page

    $error_location = "UserUnblockController";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}

ob_end_flush();

