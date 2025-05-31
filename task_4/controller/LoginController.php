<?php
ob_start();
try{

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php'; // Responsible for show_error_page() Function
    setCustomErrorHandler();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/userRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/logsRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
    global $routes;




//    Page Links
    $Login_page = $routes['login'];
    $forbidden_error_page = $routes['forbidden_error'];
    $Admin_Dashboard_page = $routes['admin_dashboard'];
    $User_dashboard_page = $routes['user_dashboard'];

    $errorMessage = "";
    $everythingOKCounter = 0;
    $current_date_time = date("Y-m-d H:i:s");

//    echo generateNoticeText("Request Method of the Received Request", "Request Method = ".$_SERVER['REQUEST_METHOD']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /// Check the session first


        echo generateNoticeText("Request Receiving Notification", "Got Request!");

        $email = trim($_POST['email'] ?? ''); // Trim whitespace first
        $password = $_POST['password'];
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
            $data = findUserByEmailAndPassword($email, $password);

            echo generateSuccessText("Validation Passed", "Everything is ok");
            echo generateNoticeText("ID found", "ID = ".isset($data["id"]));

            if ($data && isset($data["id"])) {

                $log_inserted_id = createLog("login", $current_date_time, $data["id"]);
                if($log_inserted_id > 0){
                    $_SESSION["data"] = $data;
                    $_SESSION["user_id"] = $data["id"];
                    $_SESSION["user_role"] = $data["role"];
                    $_SESSION["user_status"] = $data["status"];

                    if (strtolower($data['role']) === 'user' && strtolower($data['status']) === 'active') {
                        navigate($User_dashboard_page);
                        exit;
                    } else {

                        if(strtolower($data['status']) !== 'active'){
                            $errorMessage = urldecode("Your account is blocked");
                            navigate($Login_page, $errorMessage);
                            echo generateErrorText("Account Activation Error", "This account is not active. It is blocked");
                            exit;
                        }

                        $errorMessage = urldecode("Role did not match to any valid roles");
                        navigate($Login_page, $errorMessage);
                        echo generateErrorText("Role Error", "Role did not match to any valid roles");
                        exit;
                    }
                }else{
                    $errorMessage = urldecode("Log can not be inserted");
                    navigate($Login_page, $errorMessage);
                    echo generateErrorText("Log Error", "Log can not be inserted");
                    exit;
                }
            } else {
                echo generateErrorText("Data Not Found Error", "Returning to Login page because Email Password did not match");
                $errorMessage = urldecode("Email and Password did not match");
                navigate($Login_page, $errorMessage);
                exit;
            }
        } else {
            echo generateErrorText("User Input Validation Error", "Returning to Login page because The data user provided is not properly validated like 
                in password: 1-upper_case, 1-lower_case, 1-number, 1-special_character and at least 8 character long it must be provided");
            navigate($Login_page, $errorMessage);
            exit;
        }

    }else{
        $_SESSION['backend_direct_access'] = true;
        navigate($forbidden_error_page);
    }
} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = "LoginController";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();