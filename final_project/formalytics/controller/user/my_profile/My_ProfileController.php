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
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php';
    global $routes;
    $Login_page = $routes["login"];



//    Page Links
    $User_Signup_page = $routes['login'];
    $login_page = $routes['login'];
    $forbidden_error_page = $routes['forbidden_error'];
    $User_Signup_page = $routes['user_signup'];

    $errorMessage = "";
    $everythingOKCounter = 0;
    $current_date_time = date("Y-m-d H:i:s");

    // Authentication and Authorization Checking
    if(isset($_SESSION["user_id"]) && isset($_SESSION["user_role"])){
        // Is Session Active?
        if($_SESSION["user_id"] <= 0){
            echo generateErrorText("Session Issue", "No user_id found in session variable");
            $user_id = -1;
            navigate($Login_page, "Session Expired");
        }else{
            $user_id = $_SESSION["user_id"];
        }

        // Is Correct Role?
        if(strtolower($_SESSION["user_role"]) !== 'user'){
            $_SESSION['role_error'] = true;
            navigate($forbidden_error_page);
            echo generateErrorText("User Role Issue", "This page is only accessible by admin");
        }
    }else{
        /// same as $_SESSION["user_id"] <= 0
        echo generateErrorText("Session Issue", "No user_id found in session variable");
        $user_id = -1;
        navigate($Login_page, "Session Expired");
    }


//    echo generateNoticeText("Request Method of the Received Request", "Request Method = ".$_SERVER['REQUEST_METHOD']);
//    exit();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /// Check the session first
        $email = trim($_POST['email'] ?? ''); // Trim whitespace first
        $password = $_POST['password'];



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



        if ($everythingOK && $everythingOKCounter === 0) {

            $user_old_data = findUserByUserID($user_id);

            echo generateSuccessText("Validation Passed", "Everything is ok");
            echo generateNoticeText("Email and Password found", "Email = ".isset($user_old_data["email"]) . "Password = " . isset($user_old_data["password"])  );

            $email_update_decision = updateUserEmail($email, $user_id);
            $email_update_decision = updateUserPassword($password, $user_id);

            if($email_update_decision && $email_update_decision){
                navigate($login_page, "Update Successful","success_message");
                exit;
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