<?php

$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/db_connect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php'; // Responsible for show_error_page() Function
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php'; // Responsible for show_error_page() Function

//require __DIR__ . '/../routes.php';
global $routes;

$database_error_page = $routes["database_error"];


function findAllUser_Details()
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `user_details`';

    try {
        $result = $conn->query($selectQuery);

        // Check if the query was successful
        if (!$result) {
            $error_location = "Database -> User_DetailsRepo -> findAllUser_Details()";
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
        $error_location = "Database -> User_DetailsRepo -> findAllUser_Details()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findUser_DetailsByID($id)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `user_details` WHERE `id` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> User_DetailsRepo -> findUser_DetailsByID()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }
        // Bind the parameter
        $stmt->bind_param("i", $id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the User_Detailss as an associative array
        $User_Detailss = $result->fetch_assoc();

        // Check for an empty result set
        if (!$User_Detailss) {
            return null;
        }
        // Close the statement
        $stmt->close();
        return $User_Detailss;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> User_DetailsRepo -> findUser_DetailsByID()";
        $error_message = "Error : " . $e->getMessage();;
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findUser_DetailsByUserID($user_id)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `user_details` WHERE `user_id` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> User_DetailsRepo -> findUser_DetailsByUserID()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }
        // Bind the parameter
        $stmt->bind_param("i", $user_id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the User_Details as an associative array
        $User_Details = $result->fetch_assoc();

        // Check for an empty result set
        if (!$User_Details) {
            return null;
        }
        // Close the statement
        $stmt->close();
        return $User_Details;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> User_DetailsRepo -> findUser_DetailsByUserID()";
        $error_message = "Error : " . $e->getMessage();;
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findAllUser_DetailsByName($name)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `user_details` WHERE name = ?';

    try {
        // Prepare the statement
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
            $error_location = "Database -> User_DetailsRepo -> findAllUser_DetailsByName()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind the parameter
        $stmt->bind_param('s', $name);

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
        $error_location = "Database -> User_DetailsRepo -> findAllUser_DetailsByName()";
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

function updateUser_Details($name, $user_id, $id)
{
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "UPDATE `user_details` SET 
                    name = ?,
                    user_id  = ?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> User_DetailsRepo -> updateUser_Details()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind $created_at
        $stmt->bind_param('sii', $name, $user_id, $id);

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
        // Handle the exception, you might want to User_Details it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> User_DetailsRepo -> updateUser_Details()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function updateUser_DetailsName($name, $id)
{
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "UPDATE `user_details` SET 
                    name =?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> User_DetailsRepo -> updateUser_DetailsName()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameters
        $stmt->bind_param('si', $name, $id);

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
        // Handle the exception, you might want to User_Details it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> User_DetailsRepo -> updateUser_DetailsName()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function deleteUser_Details($id) {
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "DELETE FROM `user_details`
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> User_DetailsRepo -> deleteUser_Details()";
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
        // Handle the exception, you might want to User_Details it or return false
//        echo "Error: " . $e->getMessage();
//        $error_location = "Database -> User_DetailsRepo -> deleteUser_Details()";
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

function createUser_Details($name, $user_id) {
    $conn = db_conn();

    // Construct the SQL query
    $insertQuery = "INSERT INTO `user_details` (name, user_id) VALUES (?, ?)";

    try {
        $newUser_DetailsId = -1;
        // Prepare the statement
        $stmt = $conn->prepare($insertQuery);

        // Bind parameters
        $stmt->bind_param('si', $name, $user_id);

        // Execute the query
        $stmt->execute();

        // Return the ID of the newly inserted User_Details
        $newUser_DetailsId = $stmt->insert_id;

        if($newUser_DetailsId < 0){
            return -1;
        }

        // Close the statement
        $stmt->close();

        return $newUser_DetailsId;
    } catch (Throwable $e) {
        // Handle the exception, you might want to User_Details it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> User_DetailsRepo -> createUser_Details()";
        $error_message = $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

// ####################### Important Functions Related to User_Details ####################### //


