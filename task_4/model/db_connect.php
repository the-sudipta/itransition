<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
//require __DIR__ . '/../routes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php'; // Responsible for show_error_page() Function
//require_once dirname(__DIR__) . '/utility_functions.php'; // Responsible for show_error_page() Function

global $routes;

$database_error_page = $routes["database_error"];

function db_conn()
{
    try{
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "itansition_task_4";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
//            die("Connection failed: " . $conn->connect_error);
            $error_location = "Database Connection";
            $error_message = "Connection failed: " . $conn->connect_error;
            show_error_page($error_location, $error_message, "database_error");
        }
        return $conn;
    }catch (Exception $e){
        $error_location = "Database Connection";
        $error_message = "Connection failed: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    }
}
    