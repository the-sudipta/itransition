<?php
ob_start();
try{

    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    global $routes, $backend_routes, $image_routes, $css_routes, $js_routes;
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/view/Data_Provider.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/userRepo.php';

    // Backend Routes
    $logoutController_file     = $backend_routes['logout_controller'];

    // Frontends Path
    $login_page                     = $routes['login'];
    $forbidden_error                = $routes['forbidden_error'];

    $user_dashboard_page        = $routes['user_dashboard'];
    $profile_page        = $routes['user_my_profile'];
    $analytics_page        = $routes['analytics'];

    // Images Path
    $logo_with_background = $image_routes["logo_with_background"];
    $logo = $image_routes["logo"];
    $logo_icon = $image_routes["logo_icon"];


    // CSS Path
    $style_css = $css_routes['global_style'];
    $all_min_style = $css_routes['all_min_style'];
    $alert_box_css = $css_routes['alert_box_css'];
    $user_dashboard_css = $css_routes['user_dashboard_css'];

    // JS Path
    $chart_script = $js_routes['chart_script'];
    $jspdf_umd_min_script = $js_routes['jspdf_umd_min_script'];
    $jspdf_umd_min_script = $js_routes['jspdf_umd_min_script'];
    $script_js = $js_routes['global_script'];
    $utility_functions_script = $js_routes['utility_functions_script'];
    $alert_box_script = $js_routes['alert_box_script'];

    // Is Session Active?
    if($_SESSION["user_id"] <= 0){
        echo generateErrorText("Session Issue", "No user_id found in session variable");
        $user_id = -1;
        navigate($login_page, "Session Expired");
    }else{
        $user_id = $_SESSION["user_id"];
    }

    // Is Correct Role?
    if(strtolower($_SESSION["user_role"]) !== 'user'){
        $_SESSION['role_error'] = true;
        navigate($forbidden_error);
        echo generateErrorText("User Role Issue", "This page is only accessible by admin");
    }


    // Message from Backend
    $info    = isset($_GET['message'])          ? htmlspecialchars($_GET['message'])          : '';
    $success = isset($_GET['success_message'])  ? htmlspecialchars($_GET['success_message'])  : '';
    $warning = isset($_GET['warning_message'])  ? htmlspecialchars($_GET['warning_message'])  : '';
    $error   = isset($_GET['error_message'])    ? htmlspecialchars($_GET['error_message'])    : '';






} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = " View -> User Dashboard Page";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Formalytics</title>
    <link rel="icon" href="<?php echo $logo_icon; ?>">
    <!--  Alert Box  -->
    <link rel="stylesheet" href="<?php echo $alert_box_css; ?>">
    <link rel="stylesheet" href="<?php echo $style_css; ?>">
    <script src="<?php echo $alert_box_script; ?>"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<!--    <link rel="stylesheet" href="--><?php //echo $user_dashboard_css; ?><!--">-->
    <link
            href="https://fonts.googleapis.com/icon?family=Material+Icons"
            rel="stylesheet"
    />





</head>
<body>

<!-- Alerts placeholder -->
<div id="alerts-container"></div>


<header>
    <div class="logo">
        <a href="<?php echo $user_dashboard_page; ?>"><img src="<?php echo $logo; ?>" alt="Formalytics logo"></a>
    </div>
    <nav>
        <ul>
            <li><a href="<?php echo $user_dashboard_page; ?>" class="active">Dashboard</a></li>
            <li><a href="<?php echo $profile_page; ?>">My Profile</a></li>
            <li><a href="<?php echo $logoutController_file; ?>">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Services</h1>
    <div class="services-grid">
        <a href="<?= $analytics_page ?>" class="service-item">
            <img
                    src="https://img.icons8.com/fluency/96/combo-chart.png"
                    alt="Analytics"
                    class="service-icon"
            />
            <span>Analytics</span>
        </a>

        <a href="<?= $user_dashboard_page ?>" class="service-item"><span>Service 2</span></a>
        <a href="<?= $user_dashboard_page ?>" class="service-item"><span>Service 3</span></a>
        <a href="<?= $user_dashboard_page ?>" class="service-item"><span>Service 4</span></a>
        <a href="<?= $user_dashboard_page ?>" class="service-item"><span>Service 5</span></a>
        <a href="<?= $user_dashboard_page ?>" class="service-item"><span>Service 6</span></a>
        <a href="<?= $user_dashboard_page ?>" class="service-item"><span>Service 7</span></a>
        <a href="<?= $user_dashboard_page ?>" class="service-item"><span>Service 8</span></a>
    </div>
</main>








<!-- Bootstrap JS (for dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo $script_js; ?>"></script>

<script>
    // ----------   Backend Message Handling   -----------------
    window.onload = function() {
        initAlerts({
            info:    "<?php echo addslashes($info); ?>",
            success: "<?php echo addslashes($success); ?>",
            warning: "<?php echo addslashes($warning); ?>",
            error:   "<?php echo addslashes($error); ?>"
        });
        // remove all GET parameters from the URL
        if (window.history.replaceState) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState(null, '', cleanUrl);
        }
    };
</script>




</body>
</html>




