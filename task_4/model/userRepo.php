<?php

$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/db_connect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php'; // Responsible for show_error_page() Function
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php'; // Responsible for show_error_page() Function

global $routes;

$database_error_page = $routes["database_error"];


function findAllUsers()
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `users`';

    try {
        $result = $conn->query($selectQuery);

        // Check if the query was successful
        if (!$result) {
            $error_location = "Database -> userRepo -> findAllUsers()";
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
        $error_location = "Database -> userRepo -> findAllUsers()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findUserByEmailAndPassword($email, $password) {
    $conn = db_conn();

    // Use prepared statement to prevent SQL injection
    $selectQuery = 'SELECT * FROM `users` WHERE `email` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Bind parameters
        $stmt->bind_param("s", $email);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user as an associative array
        $user = $result->fetch_assoc();

        // Close the result set
        $result->close();

        // Close the statement
        $stmt->close();

        // Check if the user exists and if the password matches
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct
            return $user;
        } else {
            // Password is incorrect or user doesn't exist
            return null;
        }
    } catch (Throwable $e) {
//        echo $e->getMessage();
        $error_location = "Database -> userRepo -> findUserByEmailAndPassword()";
        $error_message = $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
        return null;
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findUserByUserID($id)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `users` WHERE `id` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> findUserByUserID()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }
        // Bind the parameter
        $stmt->bind_param("i", $id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user as an associative array
        $user = $result->fetch_assoc();

        // Check for an empty result set
        if (!$user) {
            return null;
        }
        // Close the statement
        $stmt->close();
        return $user;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> userRepo -> findUserByID()";
        $error_message = "Error : " . $e->getMessage();;
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function findUserByEmail($email)
{
    $conn = db_conn();
    $selectQuery = 'SELECT * FROM `users` WHERE `email` = ?';

    try {
        $stmt = $conn->prepare($selectQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> findUserByEmail()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind the parameter
        $stmt->bind_param("s", $email);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user as an associative array
        $user = $result->fetch_assoc();

        // Check for an empty result set
        if (!$user) {
            return null;
        }
        // Close the statement
        $stmt->close();
        return $user;
    } catch (Throwable $e) {
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> userRepo -> findUserByEmail()";
        $error_message = "Error : " . $e->getMessage();;
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

function updateUser($email, $password, $role, $status, $id)
{
    $conn = db_conn();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Construct the SQL query
    $updateQuery = "UPDATE `users` SET 
                    email = ?,
                    password = ?,
                    role = ?,
                    status =?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> updateUser()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameters
        $stmt->bind_param('ssssi', $email, $hashedPassword, $role, $status, $id);

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
        $error_location = "Database -> userRepo -> updateUser()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function updateUserStatus($status, $id)
{
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "UPDATE `users` SET 
                    status =?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> updateUserStatus()";
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
        $error_location = "Database -> userRepo -> updateUserStatus()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function deleteUser($id) {
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "DELETE FROM `users`
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> deleteUser()";
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
//        $error_location = "Database -> userRepo -> deleteUser()";
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

function createUser($email, $password, $role, $status) {
    $conn = db_conn();

    // Hash the password using a secure hashing algorithm (e.g., password_hash)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Construct the SQL query
    $insertQuery = "INSERT INTO `users` (email, password, role, status) VALUES (?, ?, ?, ?)";

    try {
        $newUserId = -1;
        // Prepare the statement
        $stmt = $conn->prepare($insertQuery);

        // Bind parameters
        $stmt->bind_param('ssss', $email, $hashedPassword, $role, $status);

        // Execute the query
        $stmt->execute();

        // Return the ID of the newly inserted user
        $newUserId = $stmt->insert_id;

        if($newUserId < 0){
            return -1;
        }

        // Close the statement
        $stmt->close();

        return $newUserId;
    } catch (Throwable $e) {
        // Handle the exception, you might want to log it or return false
//        echo "Error: " . $e->getMessage();
        $error_location = "Database -> userRepo -> createUser()";
        $error_message = $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the database connection
        $conn->close();
    }
}

// ####################### Important Functions Related to User ####################### //

function updateUserWithoutPassword($email, $role, $status, $id)
{
    $conn = db_conn();


    // Construct the SQL query
    $updateQuery = "UPDATE `users` SET 
                    email = ?,
                    role = ?,
                    status =?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> updateUserWithoutPassword()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameters
        $stmt->bind_param('sssi', $email, $role, $status, $id);

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
        $error_location = "Database -> userRepo -> updateUserWithoutPassword()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function updateUserEmail($email, $id)
{
    $conn = db_conn();

    // Construct the SQL query
    $updateQuery = "UPDATE `users` SET 
                    email = ?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> updateUserUsername()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameters
        $stmt->bind_param('si', $email, $id);

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
        $error_location = "Database -> userRepo -> updateUserUsername()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

function updateUserPassword($password, $id)
{
    $conn = db_conn();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Construct the SQL query
    $updateQuery = "UPDATE `users` SET 
                    password = ?
                    WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if (!$stmt) {
//            throw new Exception("Prepare statement failed: " . $conn->error);
            $error_location = "Database -> userRepo -> updateUserPassword()";
            $error_message = "Prepare statement failed: " . $conn->error;
            show_error_page($error_location, $error_message, "database_error");
        }

        // Bind parameters
        $stmt->bind_param('si', $hashedPassword, $id);

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
        $error_location = "Database -> userRepo -> updateUserPassword()";
        $error_message = "Error: " . $e->getMessage();
        show_error_page($error_location, $error_message, "database_error");
    } finally {
        // Close the statement
        $stmt->close();
        // Close the database connection
        $conn->close();
    }
}

