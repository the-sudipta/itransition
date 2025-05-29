<?php
ob_start();
try {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';
    setCustomErrorHandler();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/userRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/productRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/historyRepo.php';
//    require '../routes.php';
    global $routes;

    $Login_page = $routes['login'];
    $forbidden_error_page = $routes['forbidden_error'];

    // Authentication Checking, Authorized for all user-roles
    if(isset($_SESSION["user_id"])){
        // Is Session Active?
        if($_SESSION["user_id"] <= 0){
            echo generateErrorText("Session Issue", "No user_id found in session variable");
            $user_id = -1;
            navigate($Login_page, "Session Expired");
        }else{
            $user_id = $_SESSION["user_id"];
        }
    }else{
        /// same as $_SESSION["user_id"] <= 0
        echo generateErrorText("Session Issue", "No user_id found in session variable");
        $user_id = -1;
        navigate($Login_page, "Session Expired");
    }




} catch (Throwable $e){

//    Redirect to 500 Internal Server Error Page
    $error_location = "Backend Utility Functions";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();

