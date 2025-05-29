<?php


global $routes;




require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/userRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/transactionRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/returnRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/productRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/product_categoryRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/employeeRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/billRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/bill_itemRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/historyRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';

@session_start();

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
