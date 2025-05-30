<?php

global $routes, $backend_routes, $image_routes, $js_routes;
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';


@session_start();


// Frontend Paths
$login_page = $routes['login'];
$admin_dashboard_page = $routes['admin_dashboard'];
$user_dashboard_page = $routes['user_dashboard'];


// Backend Paths
$logout_controller = $backend_routes["logout_controller"];

// Image Path
$logo_svg          = '';
$return_icon = '';
$return_icon_green = '';

// JS Path
$database_error_script = $js_routes['database_error_script'];
$jquery_min_script = $js_routes['jquery_min_script'];
$jquery_min_script = $js_routes['jquery_min_script'];





$database_error    = "";
$logout_controller = $backend_routes["logout_controller"];
$logo_svg          = '';



$route = '';
$error_message = '';
$error_type = 'warning_message';
$user_id = -1;
if(isset($_SESSION["user_id"])){
    $user_id = $_SESSION["user_id"];
}
if($user_id < 0){
    $route = $login_page;
    $error_message = "Direct URL access is not allowed. Use the proper interface";
    navigate($route, $error_message, $error_type);
}else{
    // The user is logged in
    if(isset($_SESSION['error_message']) && isset($_SESSION['error_location'])){
        $database_error = $_SESSION['error_message'];

        if(strtolower($_SESSION["user_role"]) === "admin"){
            $route = $admin_dashboard_page;
            $error_message = "Call your Software Engineer Immediately";
        }elseif(strtolower($_SESSION["user_role"]) === "user"){
            $route = $user_dashboard_page;
            $error_message = "Call your Software Engineer Immediately";
        }
    }else{
        if(strtolower($_SESSION["user_role"]) === "admin"){
            $route = $admin_dashboard_page;
            $error_message = "Direct URL access is not allowed. Use the proper interface";
        }elseif(strtolower($_SESSION["user_role"]) === "user"){
            $route = $user_dashboard_page;
            $error_message = "Direct URL access is not allowed. Use the proper interface";
        }else{
            // Signup Issue
            $route = $login_page;
            $error_message = "Database issue. Call your Software Engineer immediately";
        }
        navigate($route, $error_message, $error_type);
    }
}





?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Database Error</title>
    <link rel="stylesheet" href="../css/database_error/style.css">
    <link rel="icon" href="<?php echo $logo_svg; ?>" type="image/svg+xml">
    <style>
        .btn {
            background-color: white;
            color: green;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1rem;
            margin-top: 5px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #3F3F3F;
            color: white;
        }
    </style>


</head>
<body>
<!-- partial:index.partial.html -->
<div class="container">
    <div class="error">
        <h1>500</h1>
        <h2>error</h2>
        <p><b>Ohh No! <span style="color: #ff2e00; "><?php echo $_SESSION['error_location']; ?></span> issue. Call your Software Engineer immediately.</b> <br><br> Issue : <p style="color: #ff2e00;"><?php echo $database_error; ?></p></p>
        <a href="<?php echo $route.'?'.$error_type.'='.$error_message;?>" class="btn">Return</a>
    </div>
    <div class="stack-container">
        <div class="card-container">
            <div class="perspec" style="--spreaddist: 125px; --scaledist: .75; --vertdist: -25px;">
                <div class="card">
                    <div class="writing">
                        <div class="topbar">
                            <div class="red"></div>
                            <div class="yellow"></div>
                            <div class="green"></div>
                        </div>
                        <div class="code">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-container">
            <div class="perspec" style="--spreaddist: 100px; --scaledist: .8; --vertdist: -20px;">
                <div class="card">
                    <div class="writing">
                        <div class="topbar">
                            <div class="red"></div>
                            <div class="yellow"></div>
                            <div class="green"></div>
                        </div>
                        <div class="code">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-container">
            <div class="perspec" style="--spreaddist:75px; --scaledist: .85; --vertdist: -15px;">
                <div class="card">
                    <div class="writing">
                        <div class="topbar">
                            <div class="red"></div>
                            <div class="yellow"></div>
                            <div class="green"></div>
                        </div>
                        <div class="code">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-container">
            <div class="perspec" style="--spreaddist: 50px; --scaledist: .9; --vertdist: -10px;">
                <div class="card">
                    <div class="writing">
                        <div class="topbar">
                            <div class="red"></div>
                            <div class="yellow"></div>
                            <div class="green"></div>
                        </div>
                        <div class="code">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-container">
            <div class="perspec" style="--spreaddist: 25px; --scaledist: .95; --vertdist: -5px;">
                <div class="card">
                    <div class="writing">
                        <div class="topbar">
                            <div class="red"></div>
                            <div class="yellow"></div>
                            <div class="green"></div>
                        </div>
                        <div class="code">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-container">
            <div class="perspec" style="--spreaddist: 0px; --scaledist: 1; --vertdist: 0px;">
                <div class="card">
                    <div class="writing">
                        <div class="topbar">
                            <div class="red"></div>
                            <div class="yellow"></div>
                            <div class="green"></div>
                        </div>
                        <div class="code">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- partial -->
<script src="<?php echo $database_error_script; ?>"></script>

</body>
</html>
    