<?php

$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/db_connect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php'; // Responsible for show_error_page() Function
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php'; // Responsible for show_error_page() Function

global $routes;

$database_error_page = $routes["database_error"];


function findAllApi_Tokens()
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `api_token`';

    try {
        $result = $conn->query($selectQuery);

        // Check if the query was successful
        if (!$result) {
            $error_location = "Database -> api_tokenRepo -> findAllApi_tokens()";
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
        $error_location = "Database -> api_tokenRepo -> findAllApi_tokens()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findApi_tokenByToken($token) {
    $conn = db_conn();

    // Use prepared statement to prevent SQL injection
    $selectQuery = 'SELECT * FROM `api_token` WHERE `token` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Bind parameters
        $stmt->bind_param("s", $email);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the api_token as an associative array
        $api_token = $result->fetch_assoc();

        // Close the result set
        $result->close();

        // Close the statement
        $stmt->close();

        // Check if the api_token exists and if the password matches
        if ($api_token) {
            // Aoi token found is correct
            return $api_token;
        } else {
            // api_token doesn't exist
            return null;
        }
    } catch (Throwable $e) {
//        echo $e->getMessage();
        $error_location = "Database -> api_tokenRepo -> findApi_tokenByToken()";
        $error_message = $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
        return null;
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findApi_tokenByID($id)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `api_token` WHERE `id` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> api_tokenRepo -> findApi_tokenByID()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }
        // Bind the parameter
        $stmt->bind_param("i", $id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the api_token as an associative array
        $api_token = $result->fetch_assoc();

        // Check for an empty result set
        if (!$api_token) {
            return null;
        }
        // Close the statement
        $stmt->close();
        return $api_token;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> api_tokenRepo -> findApi_tokenByID()";
        $error_message = "Error : " . $e->getMessage();;
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findApi_tokenByUser_ID($user_id)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `api_token` WHERE `user_id` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> api_tokenRepo -> findApi_tokenByUser_ID()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }
        // Bind the parameter
        $stmt->bind_param("i", $user_id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the api_token as an associative array
        $api_token = $result->fetch_assoc();

        // Check for an empty result set
        if (!$api_token) {
            return null;
        }
        // Close the statement
        $stmt->close();
        return $api_token;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> api_tokenRepo -> findApi_tokenByUser_ID()";
        $error_message = "Error : " . $e->getMessage();;
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}


function updateApi_token($token , $created_at, $id)
{
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "UPDATE `api_token` SET 
                    token = ?,
                    created_at = ?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> api_tokenRepo -> updateApi_token()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameters
        $stmt->bind_param('ssi', $token , $created_at, $id);

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
        $error_location = "Database -> api_tokenRepo -> updateApi_token()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}


function deleteApi_token($id) {
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "DELETE FROM `api_token`
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> api_tokenRepo -> deleteApi_token()";
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
//        $error_location = "Database -> api_tokenRepo -> deleteApi_token()";
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

function createApi_token($token , $created_at, $user_id) {
    $conn = db_conn();

    // Construct the SQL query
    $insertQuery = "INSERT INTO `api_token` (token, created_at, user_id ) VALUES (?, ?, ?)";

    try {
        $newApi_tokenId = -1;
        // Prepare the statement
        $stmt = $conn->prepare($insertQuery);

        // Bind parameters
        $stmt->bind_param('ssi', $token , $created_at, $user_id);

        // Execute the query
        $stmt->execute();

        // Return the ID of the newly inserted api_token
        $newApi_tokenId = $stmt->insert_id;

        if($newApi_tokenId < 0){
            return -1;
        }

        // Close the statement
        $stmt->close();

        return $newApi_tokenId;
    } catch (Throwable $e) {
        // Handle the exception, you might want to log it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> api_tokenRepo -> createApi_token()";
        $error_message = $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

// ####################### Important Functions Related to api_token ####################### //


