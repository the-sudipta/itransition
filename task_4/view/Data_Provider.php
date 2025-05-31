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



/**
 * Turn a DATETIME string (or null) into [ formattedDate, formattedTime ].
 * If input is null/empty, returns ['', ''].
 */
function formatDT($dtStr): array {
    if (empty($dtStr)) {
        return ['', ''];
    }

    $dt  = new DateTime($dtStr);
    $day = (int)$dt->format('j');

    // build ordinal: 1st, 2nd, 3rd, … 11th, 12th, 13th, …
    $suffix = 'th';
    if (!in_array($day % 100, [11,12,13])) {
        $mod10 = $day % 10;
        if      ($mod10 === 1) $suffix = 'st';
        elseif  ($mod10 === 2) $suffix = 'nd';
        elseif  ($mod10 === 3) $suffix = 'rd';
    }

    $date = $day . $suffix . ' ' . $dt->format('F, Y');
    $time = $dt->format('g:i A');

    return [$date, $time];
}


/**
 * Sort an array of users by their “lastLogin” timestamp (descending).
 *
 * @param array $users  Array of user records, each must have an ['id'] key.
 * @return array        A new array, sorted so that the most‐recent lastLogin comes first.
 */
function sortUsersByLastLogin(array $users): array
{
    usort($users, function($a, $b) {
        // Fetch each user’s lastLogin via your stats function:
        $aStats = getUserLogStats($a['id']);
        $bStats = getUserLogStats($b['id']);

        // If no login exists, treat it as '0000-00-00 00:00:00'
        $aLast = $aStats['lastLogin'] ?? '0000-00-00 00:00:00';
        $bLast = $bStats['lastLogin'] ?? '0000-00-00 00:00:00';

        // Convert to UNIX timestamps
        $tA = strtotime($aLast);
        $tB = strtotime($bLast);

        // Descending order: return negative if $b’s time is newer than $a’s
        return $tB <=> $tA;
    });

    return $users;
}

