<?php
ob_start();
try{

    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/routes.php';
    global $routes, $backend_routes, $image_routes, $css_routes, $js_routes;
    
    $loginController_file = $backend_routes['login_controller'];
    $signupController_file = $backend_routes['signup_controller'];
    
    
    // CSS Path
    $alert_box_css = $css_routes['alert_box_css'];
    $login_css = $css_routes['login_css'];
    
    // JS Path
    $alert_box_script = $js_routes['alert_box_script'];
    $utility_functions_script = $js_routes['utility_functions_script'];
    $login_js = $js_routes['login_js'];
    
    // Alert Message from Backend
    $info    = isset($_GET['message'])          ? htmlspecialchars($_GET['message'])          : '';
    $success = isset($_GET['success_message'])  ? htmlspecialchars($_GET['success_message'])  : '';
    $warning = isset($_GET['warning_message'])  ? htmlspecialchars($_GET['warning_message'])  : '';
    $error   = isset($_GET['error_message'])    ? htmlspecialchars($_GET['error_message'])    : '';

} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = " View -> Admin Dashboard Page";
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
    <title>User Management Portal</title>
    <script src="<?php echo $utility_functions_script; ?>"></script>
    <link rel="stylesheet" href="<?php echo $login_css; ?>">
    <!--  Alert Box  -->
    <link rel="stylesheet" href="<?php echo $alert_box_css; ?>">
    <script src="<?php echo $alert_box_script; ?>"></script>
    <!--  Password Icon  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>



</head>
<body>

<!-- Alerts placeholder -->
<div id="alerts-container"></div>

<div class="container" id="container">

    <!-- SIGN UP FORM -->
    <div class="form-container sign-up-container">
        <form
                action="<?php echo $signupController_file; ?>"
                method="post"
                id="signup_form"
                onsubmit="return validateForm(this, {
                    rules: {
                        name: [
                          'required',
                          { rule: 'minLength', value: 3 },
                          { rule: 'maxLength', value: 16 },
                          v => /^[A-Za-z. ]+$/.test(v) || 'Only letters, spaces, and dots allowed.'
                        ],
                        email:    ['required','email'],
                        password: ['required','passwordWeak']
                    }
                });"
        >
            <h1>Create Account</h1>
            <input type="text" id="signup_name" name="name" placeholder="Name" />
            <input type="email" id="signup_email" name="email" placeholder="Email" />
            <input type="password" id="signup_password" name="password" placeholder="Password"/>
            <i class="bi bi-eye-fill text-muted toggle-password" data-target="signup_password" style="position:absolute; top:57%; right:4rem; transform:translateY(-50%); cursor:pointer;"></i>
            <button type="submit">Sign Up</button>
            <span>or</span>
            <button type="button" class="ghost" id="signIn">Sign In</button>
        </form>
    </div>

    <!-- SIGN IN FORM -->
    <div class="form-container sign-in-container">
        <form
                action="<?php echo $loginController_file; ?>"
                method="post"
                id="login_form"
                onsubmit="return validateForm(this, {
                    rules: {
                        email:    ['required','email'],
                        password: ['required','passwordWeak']
                    }
                });"
        >
            <h1>Sign In</h1>
            <input type="email" id="login_email" name="email" placeholder="Email"  />
            <input type="password" id="login_password" name="password" placeholder="Password"/>
            <i class="bi bi-eye-fill text-muted toggle-password" data-target="login_password" style="position:absolute; top:51%; right:4rem; transform:translateY(-50%); cursor:pointer;"></i>
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

<script>
    // Password view or hide control
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = document.getElementById(icon.dataset.target);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        });
    });
</script>



</body>
</html>
