<?php


global $routes;




require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/userRepo.php';
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


/**
 * Return the user’s display name (from user_details) or empty string.
 */
function getUserName(int $userId): string
{
    $details = findUser_DetailsByUserID($userId);
    return $details['name'] ?? '';
}

/**
 * Scan all logs for a user and return an array with:
 *  - lastLogin
 *  - lastActivity
 *  - registrationTime
 */
function getUserLogStats(int $userId): array
{
    $logs = findAllLogsByUser_ID($userId);
    $stats = [
        'lastLogin'        => null,
        'lastActivity'     => null,
        'registrationTime' => null,
    ];

    foreach ($logs as $log) {
        $ts = strtotime($log['created_at']);

        // overall latest
        if ($stats['lastActivity'] === null || $ts > strtotime($stats['lastActivity'])) {
            $stats['lastActivity'] = $log['created_at'];
        }
        // latest login only
        if ($log['log_type'] === 'login') {
            if ($stats['lastLogin'] === null || $ts > strtotime($stats['lastLogin'])) {
                $stats['lastLogin'] = $log['created_at'];
            }
        }
        // signup time
        if ($log['log_type'] === 'signup') {
            $stats['registrationTime'] = $log['created_at'];
        }
    }

    return $stats;
}


