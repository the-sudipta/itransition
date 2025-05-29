<?php

global $routes, $backend_routes, $image_routes, $js_routes;
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';


@session_start();


// Frontend Paths
$login_page = $routes['login'];
$admin_dashboard_page = $routes['admin_dashboard'];
$salesman_dashboard_page = $routes['salesman_dashboard'];
$forbidden_error_page = $routes['forbidden_error'];


// Backend Paths
$logout_controller = $backend_routes["logout_controller"];

// Image Path
$logo_svg          = $image_routes['logo_svg'];
$return_icon = $image_routes['return_btn'];
$return_icon_green = $image_routes['return_btn_green'];

// JS Path
$jquery_min_script = $js_routes['jquery_min_script'];
$jquery_min_script = $js_routes['jquery_min_script'];


//$database_error    = "";

$route = '';
$error_message = '';
$error_type = 'message';


if($_SESSION["user_id"] > 0){

    if(strtolower($_SESSION["user_role"]) === "admin"){
        $route = $admin_dashboard_page;
        $error_message = "Sorry the page does not exist";
    }else{
        $route = $salesman_dashboard_page;
        $error_message = "Sorry the page does not exist";
    }

}else{
    navigate($forbidden_error_page);
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>404 - Page Not Found</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, #f0f4f8, #d9e2ec);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
            text-align: center;
        }

        h1 {
            font-size: 6rem;
            margin: 0;
            color: #2b2d42;
        }

        p {
            font-size: 1.5rem;
            margin: 0.5rem 0 1rem;
            color: #4a5568;
        }

        .robot-scene {
            position: relative;
            width: 300px;
            height: 420px;
            margin: 20px auto;
        }

        /* Shared start/end points */
        .robot, .parcel {
            position: absolute;
            left: -150px;
        }

        .robot {
            bottom: 0;
            width: 100px;
            height: 100px;
            background: #8d99ae;
            border-radius: 20% 20% 50% 50%;
            animation: slideToCenter 4s ease-out forwards;
            z-index: 2;
        }

        .parcel {
            width: 60px;
            height: 40px;
            background: #e0a96d;
            border: 2px solid #c07f45;
            top: 449px;
            left: -122px; /* ✅ starts slightly right of robot */
            border-radius: 6px;
            animation: slideParcelRight 4s ease-out forwards;
            z-index: 1;
        }

        @keyframes slideParcelRight {
            0% { left: -130px; }
            100% { left: 118px; }
        }


        .parcel::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 8px;
            width: 44px;
            height: 2px;
            background: #c07f45;
        }

        @keyframes slideToCenter {
            0% { left: -150px; }
            100% { left: 100px; }
        }

        /* Robot face */
        .robot::before, .robot::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            background: #edf2f4;
            border-radius: 50%;
            top: 30px;
        }

        .robot::before {
            left: 18px;
        }

        .robot::after {
            right: 18px;
        }

        .eyebrow {
            position: absolute;
            width: 20px;
            height: 4px;
            background: #2b2d42;
            top: 20px;
            border-radius: 2px;
        }

        .eyebrow.left {
            left: 15px;
            transform: rotate(-15deg);
        }

        .eyebrow.right {
            right: 15px;
            transform: rotate(15deg);
        }

        .robot-arm {
            position: absolute;
            width: 20px;
            height: 40px;
            background: #2b2d42;
            border-radius: 10px;
            top: 105px;
            transform-origin: top center;
            z-index: 3;
        }

        .robot-arm.left {
            left: -15px;
            transform: rotate(-30deg);
        }

        .robot-arm.right {
            right: -15px;
            transform: rotate(30deg);
        }

        .question-mark {
            position: absolute;
            font-size: 7rem; /* Increased size */
            font-weight: 900; /* Bold */
            color: #2b2d42; /* Same as button */
            top: -110px; /* Adjusted position slightly */
            left: 110px;
            opacity: 0;
            transform: rotate(30deg);
            animation: popUpQ 1s ease-out 4s forwards;
        }


        @keyframes popUpQ {
            0%   { transform: scale(0) rotate(0deg); opacity: 0; }
            60%  { transform: scale(1.3) rotate(30deg); opacity: 1; }
            100% { transform: scale(1) rotate(30deg); opacity: 1; }
        }


        .btn {
            background-color: #2b2d42;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1rem;
            margin-top: 5px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #1a1c30;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            font-size: 0.8rem;
            color: #aaa;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 4rem;
            }
            p {
                font-size: 1.2rem;
            }

            .robot-scene {
                transform: scale(0.9);
            }
        }
    </style>
</head>
<body>
<div class="robot-scene">
    <div class="robot">
        <div class="eyebrow left"></div>
        <div class="eyebrow right"></div>
        <div class="robot-arm left"></div>
        <div class="robot-arm right"></div>
        <div class="question-mark">?</div>
    </div>
    <div class="parcel"></div>
</div>
<br>
<h1>404</h1>
<p><strong>The robot checked inventory, billing, even the bank — but this page? Nowhere to be found! Confused and still searching...</strong></p>
<p>Oops! This page doesn’t exist in the system</p>
<a href="<?php echo $route.'?'.$error_type.'='.$error_message;?>" class="btn">Return</a>
<div class="footer">Inventory Management System by Black Box Tech</div>
</body>
</html>
