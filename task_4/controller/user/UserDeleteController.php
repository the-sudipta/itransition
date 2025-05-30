<?php
//require_once __DIR__ . '/../model/db_connect.php';
global $routes, $backend_routes;
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/userRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/logsRepo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';

// Frontend Page Links
$User_dashboard_page = $routes['user_dashboard'];
// Backend File Links
$user_block_controller     = $backend_routes['user_block_controller'];
$user_delete_controller     = $backend_routes['user_delete_controller'];
$user_unblock_controller     = $backend_routes['user_unblock_controller'];

$current_date_time = date("Y-m-d H:i:s");
$user_id = -1;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}


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
        deleteUser($id);
        // Add Log
        $log_inserted_id = createLog("delete user : ".$id, $current_date_time, $user_id);
        // Deleting Session
        if($id === $user_id){
            $_SESSION['user_id'] = -1;
        }
    }
}

// when done, redirect back
$errorMessage = urldecode("Deleted successfully");
navigate($User_dashboard_page, $errorMessage,"success_message");
echo generateSuccessText("User selection", "Successfully Deleted Users");
exit;

