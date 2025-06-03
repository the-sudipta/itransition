<?php

global $routes;
require 'routes.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function show_error_page($error_location, $error_message, $error_type) {

    global $routes;
    if ($error_type === 'database_error') {
        $_SESSION['error_location'] = $error_location;
        $_SESSION['error_message'] = $error_message;
        header("Location: {$routes['database_error']}");
        exit;
    } elseif ($error_type === 'internal_server_error') {
        $_SESSION['internal_server_error_location'] = $error_location;
        $_SESSION['internal_server_error_message'] = $error_message;
        header("Location: {$routes['internal_server_error']}");
        exit;
    } else {
        // Fallback: if route not found, show plain error
        echo "<h2>Error:</h2><p>{$error_message}</p><p>Location: {$error_location}</p>";
        exit;
    }
}


function customErrorHandler($errno, $errstr, $errfile, $errline) {
    // Custom error handler function to catch PHP errors and convert them to exceptions
    // You can customize the error levels to convert specific types of errors to exceptions
    if ($errno == E_WARNING || $errno == E_NOTICE || $errno == E_ERROR) {
        // You can throw an exception with custom error message or log the error.
        throw new Exception("Error [$errno]: $errstr in $errfile on line $errline");
    }
    // For other errors, you can handle them or log them.
    return true; // Return true to prevent PHP's internal error handler from running
}


function setCustomErrorHandler() {
    // Set the custom error handler globally
    set_error_handler("customErrorHandler");
}


function restoreCustomErrorHandler() {
    // Restore the original PHP error handler if needed
    restore_error_handler();
}


/**
 * @param string      $path
 * @param string|null $message  (omit the `?` in code for PHP <7.1)
 * @param string      $type
 */
function navigate(string $path, $message = null, $type = 'error_message') {
    if ($message !== null) {
        $encoded = urlencode($message);
        $sep     = (strpos($path, '?') === false) ? '?' : '&';
        header("Location: {$path}{$sep}{$type}={$encoded}");
    } else {
        header("Location: {$path}");
    }
    exit();
}


function generateErrorText($heading, $bodyText) {
    // CSS styles for beautification
    $styles = "
    <style>
        .error-text {
            font-family: 'Arial', sans-serif;
            text-align: left;
            max-width: 600px;
            margin: 30px auto;
            padding: 15px 20px;
            background-color: #FFFBF2; /* Light cream background */
            border-left: 8px solid #F87171; /* Bold left border */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: relative;
            line-height: 1.5;
        }
        .error-text h1 {
            font-size: 1.4em;
            margin-bottom: 10px;
            margin-top: 40px;
            color: #F87171; /* Deep red for the heading */
            font-weight: bold;
        }
        .error-text p {
            font-size: 1.1em;
            color: #9B1D20; /* Dark red for body text */
            font-weight: 600;
        }
        .error-icon {
            position: absolute;
            top: 10px;
            left: 15px;
            font-size: 1.6em;
            color: #F87171; /* Red for the icon */
        }
    </style>
    ";

    // HTML structure for the output
    $html = "
    <div class='error-text'>
        <div class='error-icon'>❌</div>
        <h1>{$heading}</h1>
        <p>{$bodyText}</p>
    </div>
    ";

    // Return combined CSS and HTML
    return $styles . $html;
}


function generateSuccessText($heading, $bodyText) {
    $styles = "
    <style>
        .success-text {
            font-family: 'Arial', sans-serif;
            text-align: left;
            max-width: 600px;
            margin: 30px auto;
            padding: 15px 20px;
            background-color: #F0FFF4; /* Light green background */
            border-left: 8px solid #22C55E; /* Green border */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            line-height: 1.5;
        }
        .success-text h1 {
            font-size: 1.4em;
            margin-bottom: 10px;
            margin-top: 40px;
            color: #15803D; /* Deep green */
            font-weight: bold;
        }
        .success-text p {
            font-size: 1.1em;
            color: #166534; /* Rich green text */
            font-weight: 600;
        }
        .success-icon {
            position: absolute;
            top: 10px;
            left: 15px;
            font-size: 1.6em;
            color: #22C55E; /* Green check */
        }
    </style>
    ";

    $html = "
    <div class='success-text'>
        <div class='success-icon'>✅</div>
        <h1>{$heading}</h1>
        <p>{$bodyText}</p>
    </div>
    ";

    return $styles . $html;
}


function generateNoticeText($heading, $bodyText) {
    $styles = "
    <style>
        .notice-text {
            font-family: 'Arial', sans-serif;
            text-align: left;
            max-width: 600px;
            margin: 30px auto;
            padding: 15px 20px;
            background-color: #F0F9FF; /* Soft blue background */
            border-left: 8px solid #38BDF8; /* Blue border */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            line-height: 1.5;
        }
        .notice-text h1 {
            font-size: 1.4em;
            margin-bottom: 10px;
            margin-top: 40px;
            color: #0369A1; /* Deep blue */
            font-weight: bold;
        }
        .notice-text p {
            font-size: 1.1em;
            color: #075985; /* Rich blue */
            font-weight: 600;
        }
        .notice-icon {
            position: absolute;
            top: 10px;
            left: 15px;
            font-size: 1.6em;
            color: #38BDF8; /* Blue info icon */
        }
    </style>
    ";

    $html = "
    <div class='notice-text'>
        <div class='notice-icon'>📢</div>
        <h1>{$heading}</h1>
        <p>{$bodyText}</p>
    </div>
    ";

    return $styles . $html;
}
