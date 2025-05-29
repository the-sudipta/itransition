<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
global $routes, $backend_routes, $image_routes, $css_routes, $js_routes;

$loginController_file = $backend_routes['login_controller'];


// CSS Path
$alert_box_css = $css_routes['alert_box_css'];
$login_css = $css_routes['login_css'];

// JS Path
$alert_box_script = $js_routes['alert_box_script'];
$utility_functions_script = $js_routes['utility_functions_script'];
$login_js = $js_routes['login_js'];

// Message from Backend
$info    = isset($_GET['message'])          ? htmlspecialchars($_GET['message'])          : '';
$success = isset($_GET['success_message'])  ? htmlspecialchars($_GET['success_message'])  : '';
$warning = isset($_GET['warning_message'])  ? htmlspecialchars($_GET['warning_message'])  : '';
$error   = isset($_GET['error_message'])    ? htmlspecialchars($_GET['error_message'])    : '';

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login / Registration</title>
    <link rel="stylesheet" href="<?php echo $login_css; ?>">
    <!--  Alert Box  -->
    <link rel="stylesheet" href="<?php echo $alert_box_css; ?>">
    <script src="<?php echo $alert_box_script; ?>"></script>

</head>
<body>

<!-- Alerts placeholder -->
<div id="alerts-container"></div>

<div class="container" id="container">

    <!-- SIGN UP FORM -->
    <div class="form-container sign-up-container">
        <form id="signUpForm">
            <h1>Create Account</h1>
            <input type="text" placeholder="Name" required />
            <input type="email" placeholder="Email" required />
            <input type="password" placeholder="Password" required minlength="1" />
            <button type="submit">Sign Up</button>
            <span>or</span>
            <button type="button" class="ghost" id="signIn">Sign In</button>
        </form>
    </div>

    <!-- SIGN IN FORM -->
    <div class="form-container sign-in-container">
        <form id="signInForm">
            <h1>Sign In</h1>
            <input type="email" placeholder="Email" required />
            <input type="password" placeholder="Password" required minlength="1" />
<!--            <a href="#">Forgot your password?</a>-->
            <button type="submit">Sign In</button>
            <span>or</span>
            <button type="button" class="ghost" id="signUp">Sign Up</button>
        </form>
    </div>

    <!-- OVERLAY PANELS -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signInAlt">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start your journey with us</p>
                <button class="ghost" id="signUpAlt">Sign Up</button>
            </div>
        </div>
    </div>

</div>

<script src="<?php echo $login_js; ?>"></script>
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
