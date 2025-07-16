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
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/api_tokenRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php';
    global $routes;
    $Login_page = $routes["login"];
    $profile_page = $routes["user_my_profile"];



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
        $api_token = trim($_POST['api_token'] ?? ''); // Trim whitespace first




        echo generateNoticeText("Request Receiving Notification", "Got Request!");

        //* Api Token Validation
        if ($api_token === '' || mb_strlen($api_token) > 256) {
            $everythingOK = false;
            $everythingOKCounter++;
            $errorMessage = urldecode("Api Token cannot be empty and must be at most 256 characters");
            echo generateErrorText(
                "Api Token Error",
                "Api Token cannot be empty and must be at most 256 characters."
            );
        } else {
            $everythingOK = true;
        }



        if ($everythingOK && $everythingOKCounter === 0) {

            $old_api_token = findApi_tokenByUser_ID($user_id);

            echo generateSuccessText("Validation Passed", "Everything is ok");

            $decision = false;
            if($old_api_token === null){
                // Create New One
                $inserted_id = createApi_token($api_token, $current_date_time, $user_id);
                if($inserted_id > 0){
                    $decision = true;
                }
            }else{
                // Update the old one
                $update_decision = updateApi_token($api_token, $current_date_time, $old_api_token['id']);
                echo generateSuccessText("Api Updated", "Updated API = ".$api_token);
                if($update_decision){
                    $decision = true;
                }
            }

            if($decision){
                navigate($profile_page, "Update Successful","success_message");
                exit;
            }else{
                $errorMessage = urldecode("Failed to update API token");
                navigate($profile_page, $errorMessage);
                echo generateErrorText("Api Update Error", "Can not update API token");
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
    $error_location = "ApiController";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();