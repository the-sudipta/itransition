<?php
ob_start();
try{

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php'; // Responsible for show_error_page() Function
    setCustomErrorHandler();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/userRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
    global $routes;




//    Page Links
    $Login_page = $routes['login'];
    $forbidden_error_page = $routes['forbidden_error'];
    $Admin_Dashboard_page = $routes['admin_dashboard'];
    $Salesman_dashboard_page = $routes['salesman_dashboard'];

    $errorMessage = "";
    $everythingOKCounter = 0;

//    echo generateNoticeText("Request Method of the Received Request", "Request Method = ".$_SERVER['REQUEST_METHOD']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /// Check the session first


        echo generateNoticeText("Request Receiving Notification", "Got Request!");

        //* Username Validation
        $username = $_POST['username'];
        if (empty($username)) {

            $everythingOK = FALSE;
            $everythingOKCounter += 1;

            $errorMessage = urldecode("Username has more than 120 Characters or It is empty");
            echo generateErrorText("Username Error", "Username is Empty");
        } else {
            $everythingOK = TRUE;
        }

        //* Password Validation
        $password = $_POST['password'];
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
            $data = findUserByEmailAndPassword($username, $password);

            echo generateSuccessText("Validation Passed", "Everything is ok");
            echo generateNoticeText("ID found", "ID = ".isset($data["id"]));

            if ($data && isset($data["id"])) {
                $_SESSION["data"] = $data;
                $_SESSION["user_id"] = $data["id"];
                $_SESSION["user_role"] = $data["role"];
                $_SESSION["user_status"] = $data["status"];

                if (strtolower($data['role']) === 'admin' && strtolower($data['status']) === 'activated') {
                    navigate($Admin_Dashboard_page);
                    exit;
                } elseif (strtolower($data['role']) === 'salesman'  && strtolower($data['status']) === 'activated'){
                    echo generateSuccessText("Login Decision", "Login Successful");
                    navigate($Salesman_dashboard_page);
                    exit;
                } else {

                    if(strtolower($data['status']) !== 'activated'){
                        $errorMessage = urldecode("Your account is deleted. Please contact your Admin");
                        navigate($Login_page, $errorMessage);
                        echo generateErrorText("Account Activation Error", "This account is not activated");
                        exit;
                    }

                    $errorMessage = urldecode("Role did not match to any valid roles");
                    navigate($Login_page, $errorMessage);
                    echo generateErrorText("Role Error", "Role did not match to any valid roles");
                    exit;
                }
            } else {
                echo generateErrorText("Data Not Found Error", "Returning to Login page because Username Password did not match");
                $errorMessage = urldecode("Username and Password did not match");
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