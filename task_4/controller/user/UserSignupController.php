<?php
ob_start();
try{

    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php'; // Responsible for show_error_page() Function
    setCustomErrorHandler();
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/userRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/logsRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/user_detailsRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php';
    global $routes;




//    Page Links
    $User_Signup_page = $routes['login'];
    $login_page = $routes['login'];
    $forbidden_error_page = $routes['forbidden_error'];
    $User_Signup_page = $routes['user_signup'];

    $errorMessage = "";
    $everythingOKCounter = 0;
    $current_date_time = date("Y-m-d H:i:s");

//    echo generateNoticeText("Request Method of the Received Request", "Request Method = ".$_SERVER['REQUEST_METHOD']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /// Check the session first
        $email = trim($_POST['email'] ?? ''); // Trim whitespace first
        $password = $_POST['password'];
        $name = $_POST['name'];


        echo generateNoticeText("Request Receiving Notification", "Got Request!");

        //* Email Validation
        if ($email === '' || mb_strlen($email) > 120) {
            $everythingOK = false;
            $everythingOKCounter++;
            $errorMessage = urldecode("Email cannot be empty and must be at most 120 characters");
            echo generateErrorText(
                "Email Error",
                "Email cannot be empty and must be at most 120 characters."
            );
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $everythingOK = false;
            $everythingOKCounter++;
            $errorMessage = urldecode("Please enter a valid email address with a valid format");
            echo generateErrorText(
                "Email Error",
                "Please enter a valid email address with a valid format."
            );
        } else {
            $everythingOK = true;
        }

        //* Password Validation
        if (empty($password) || strlen($password) < 8) {
            // check if password size in 8 or more and  check if it is empty
            $everythingOK = FALSE;
            $everythingOKCounter += 1;
            $errorMessage = urldecode("Password has less than 8 Characters or It is empty");
            echo generateErrorText("Password Error", "Password has less than 8 Characters or It is empty");
        } else {
            $everythingOK = TRUE;
        }

        //* Name Validation
        if ($name === '' || mb_strlen($name) > 255) {
            $everythingOK = false;
            $everythingOKCounter++;
            $errorMessage = urldecode("Name cannot be empty and must be at most 255 characters");
            echo generateErrorText(
                "Name Error",
                "Name cannot be empty and must be at most 255 characters."
            );
        }

        if ($everythingOK && $everythingOKCounter === 0) {
            $data = findUserByEmail($email);

            echo generateSuccessText("Validation Passed", "Everything is ok");
            echo generateNoticeText("ID found", "ID = ".isset($data["id"]));
            if($data === null){
                // Email is Unique (Tested through PHP Code)
                $inserted_id = createUser($email, $password, strtolower('user'), strtolower('active'));
                if($inserted_id > 0){
                    // insert Name
                    $user_details_inserted_id = createUser_Details($name, $inserted_id);
                    if($user_details_inserted_id > 0){
                        // insert log
                        $log_inserted_id = createLog("signup", $current_date_time, $inserted_id);
                        if($log_inserted_id > 0){
                            navigate($login_page, "Registration successful","success_message");
                            exit;
                        }else{
                            $errorMessage = urldecode("Log can not be inserted");
                            navigate($User_Signup_page, $errorMessage);
                            echo generateErrorText("Log Error", "Log can not be inserted");
                            exit;
                        }
                    }else{
                        $errorMessage = urldecode("User details can not be inserted");
                        navigate($User_Signup_page, $errorMessage);
                        echo generateErrorText("User_details Error", "Name can not be inserted");
                        exit;
                    }
                }
            }else{
                $errorMessage = urldecode("Email is already in use");
                navigate($User_Signup_page, $errorMessage);
                echo generateErrorText("Unique Email Error", "This email is already in the database");
                exit;
            }
        } else {
            echo generateErrorText("User Input Validation Error", "Returning to Signup page because The data user provided is not properly validated like 
                in password: 1-upper_case, 1-lower_case, 1-number, 1-special_character and at least 8 character long it must be provided");
            navigate($User_Signup_page, $errorMessage);
            exit;
        }
    }else{
        $_SESSION['backend_direct_access'] = true;
        navigate($forbidden_error_page);
    }

} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = "SignupController";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();