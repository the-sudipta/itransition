<?php

$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/db_connect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php'; // Responsible for show_error_page() Function
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php'; // Responsible for show_error_page() Function

//require __DIR__ . '/../routes.php';
global $routes;

$database_error_page = $routes["database_error"];


function findAllLogs()
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `logs`';

    try {
        $result = $conn->query($selectQuery);

        // Check if the query was successful
        if (!$result) {
            $error_location = "Database -> LogsRepo -> findAllLogs()";
            $error_message = "Query failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        $rows = array();

        // Fetch rows one by one
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Check for an empty result set
        if (empty($rows)) {
            return null;
        }

        return $rows;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> LogsRepo -> findAllLogs()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findLogByID($id)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `logs` WHERE `id` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> LogsRepo -> findLogByID()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }
        // Bind the parameter
        $stmt->bind_param("i", $id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the Logs as an associative array
        $Logs = $result->fetch_assoc();

        // Check for an empty result set
        if (!$Logs) {
            return null;
        }
        // Close the statement
        $stmt->close();
        return $Logs;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> LogsRepo -> findLogByID()";
        $error_message = "Error : " . $e->getMessage();;
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findAllLogsByUser_ID($user_id)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `logs` WHERE user_id = ?';

    try {
        // Prepare the statement
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
            $error_location = "Database -> LogsRepo -> findAllLogsByUser_ID()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind the parameter
        $stmt->bind_param('i', $user_id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        $rows = array();

        // Fetch rows one by one
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Check for an empty result set
        if (empty($rows)) {
            return null;
        }

        return $rows;
    } catch (Throwable $e) {
        $error_location = "Database -> LogsRepo -> findAllLogsByUser_ID()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement and connection
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}

function findAllLogsByLog_Type($log_type)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `logs` WHERE log_type = ?';

    try {
        // Prepare the statement
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
            $error_location = "Database -> LogsRepo -> findAllLogsByLog_Type()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind the parameter
        $stmt->bind_param('s', $log_type);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        $rows = array();

        // Fetch rows one by one
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Check for an empty result set
        if (empty($rows)) {
            return null;
        }

        return $rows;
    } catch (Throwable $e) {
        $error_location = "Database -> LogsRepo -> findAllLogsByLog_Type()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement and connection
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}

function findAllLogsByCreated_At($created_at)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `logs` WHERE created_at = ?';

    try {
        // Prepare the statement
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
            $error_location = "Database -> LogsRepo -> findAllLogsByCreated_At()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind the parameter
        $stmt->bind_param('s', $created_at);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        $rows = array();

        // Fetch rows one by one
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Check for an empty result set
        if (empty($rows)) {
            return null;
        }

        return $rows;
    } catch (Throwable $e) {
        $error_location = "Database -> LogsRepo -> findAllLogsByCreated_At()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement and connection
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}

function updateLog($log_type, $created_at, $user_id, $id)
{
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "UPDATE `logs` SET 
                    log_type = ?,
                    created_at = ?,
                    user_id  = ?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> LogsRepo -> updateLog()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind $created_at
        $stmt->bind_param('ssii', $log_type, $created_at, $user_id, $id);

        // Execute the query
        if ($stmt->execute()) {
            if ($stmt->affected_rows < 0) {
                return false;
            }
        } else {
            return false;
        }
        // Return true if the update is successful
        return true;
    } catch (Throwable $e) {
        // Handle the exception, you might want to log it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> LogsRepo -> updateLog()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function updateLogStatus($status, $id)
{
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "UPDATE `logs` SET 
                    status =?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> LogsRepo -> updateLogStatus()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameters
        $stmt->bind_param('si', $status, $id);

        // Execute the query
        if ($stmt->execute()) {
            if ($stmt->affected_rows < 0) {
                return false;
            }
        } else {
            return false;
        }

        // Return true if the update is successful
        return true;
    } catch (Throwable $e) {
        // Handle the exception, you might want to log it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> LogsRepo -> updateLogStatus()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function deleteLog($id) {
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "DELETE FROM `logs`
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> LogsRepo -> deleteLog()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameter
        $stmt->bind_param('i', $id);

        // Execute the query
        $stmt->execute();

        // Return true if the update is successful
        return true;
    } catch (Throwable $e) {
        // Handle the exception, you might want to log it or return false
//        echo "Error: " . $e->getMessage();
//        $error_location = "Database -> LogsRepo -> deleteLog()";
//        $error_message = $e->getMessage();
//        show_error_page($error_location, $error_message, "database_error");
        return false;
    } finally {
        // Close the statement
        $stmt->close();

        // Close the database connection
        $conn->close();
    }
}

function createLog($log_type, $created_at, $user_id) {
    $conn = db_conn();

    // Construct the SQL query
    $insertQuery = "INSERT INTO `logs` (log_type, created_at, user_id) VALUES (?, ?, ?)";

    try {
        $newLogsId = -1;
        // Prepare the statement
        $stmt = $conn->prepare($insertQuery);

        // Bind parameters
        $stmt->bind_param('ssi', $log_type, $created_at, $user_id);

        // Execute the query
        $stmt->execute();

        // Return the ID of the newly inserted Logs
        $newLogsId = $stmt->insert_id;

        if($newLogsId < 0){
            return -1;
        }

        // Close the statement
        $stmt->close();

        return $newLogsId;
    } catch (Throwable $e) {
        // Handle the exception, you might want to log it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> LogsRepo -> createLog()";
        $error_message = $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

// ####################### Important Functions Related to Logs ####################### //


