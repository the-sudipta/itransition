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
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/model/api_tokenRepo.php';

    // Backend Routes
    $logoutController_file     = $backend_routes['logout_controller'];
    $my_profileController_file     = $backend_routes['my_profile_controller'];
    $apiController_file     = $backend_routes['api_controller'];

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

    $user_details = findUserByUserID($user_id);
    $old_api_data = findApi_tokenByUser_ID($user_id);




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

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />


    <style>
        /* 1) page background + overall centering */
        main {
            /*background: #f5f5f5;*/
            min-height: calc(100vh - 70px);
            padding: 2rem;
            display: flex;
            justify-content: center;
        }

        /* 2) content wrapper (removes any white BG) */
        .profile-content {
            width: 100%;
            max-width: 900px;
        }

        /* 3) page header */
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .profile-header h1 {
            font-size: 2rem;
            color: #111823;
            position: relative;
            display: inline-block;
            padding-bottom: .5rem;
        }
        .profile-header h1::after {
            content: "";
            position: absolute;
            height: 4px;
            width: 60px;
            background: #C49F60;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* 4) cards container */
        .profile-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2rem;
            flex: 1 1 300px;
            max-width: 400px;
        }

        /* 5) card headings + centered underline */
        .card h2 {
            margin: 0 0 1rem;
            font-size: 1.5rem;
            color: #111823;
            position: relative;
            padding-bottom: .5rem;
            text-align: center;
        }
        .card h2::after {
            content: "";
            position: absolute;
            height: 3px;
            width: 40px;
            background: #C49F60;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* 6) form fields */
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: .25rem;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: .75rem 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        /* 7) buttons */
        .btn {
            display: inline-block;
            padding: .75rem 1.5rem;
            background: #C49F60;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background .2s ease;
            text-align: center;
        }
        .btn:hover {
            background: #ad8b4e;
        }
        .btn-outline {
            background: transparent;
            color: #C49F60;
            border: 2px solid #C49F60;
        }
        .btn-outline:hover {
            background: #C49F60;
            color: #fff;
        }

        /* 8) token field group */
        .token-group {
            display: flex;
            gap: .5rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .token-group input {
            flex: 1;
            font-family: monospace;
            letter-spacing: .05em;
            padding: .75rem 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        /* small‑screen tweaks */
        @media (max-width: 600px) {
            .profile-cards {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>

    <style>
        /* Password show/hide toggle */
        .password-container {
            position: relative;
        }
        .password-container input {
            width: 100%;
            padding-right: 2.5rem; /* space for icon */
        }
        .password-container .toggle-pass {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 1.2rem;
        }
        .password-container .toggle-pass:hover {
            color: #333;
        }

    </style>

    <style>
        /* API Access card tweaks */
        .card.api-access h2 {
            margin-bottom: 1rem;
        }
        .api-access .api-group {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
        .api-access .api-group input {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        .api-access .api-group .btn,
        .api-access .api-group .btn-outline {
            white-space: nowrap;
        }

        /* modal backdrop (hidden by default) */
        #confirm-modal {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;               /* start hidden */
            align-items: center;
            justify-content: center;
            z-index: 500;
        }
        .confirm-box {
            background: #fff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            width: 90%;
            max-width: 320px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .confirm-box p {
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            color: #333;
        }
        .confirm-box button {
            margin: 0 0.5rem;
        }
    </style>

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
            <li><a href="<?php echo $user_dashboard_page; ?>" >Dashboard</a></li>
            <li><a href="<?php echo $profile_page; ?>" class="active">My Profile</a></li>
            <li><a href="<?php echo $logoutController_file; ?>">Logout</a></li>
        </ul>
    </nav>
</header>





<main>


    <div class="profile-content">
        <!-- page title -->
        <div class="profile-header">
            <h1>My Profile</h1>
        </div>

        <div class="profile-cards">
            <!-- Account Details card -->
            <div class="card">
                <h2>Account Details</h2>
                <form action="<?php echo $my_profileController_file; ?>" method="post" id="signup_form" onsubmit="return validateForm();">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email"
                               value="<?= htmlspecialchars($user_details['email'] ?? '') ?>" readonly>
                    </div>
                    <div class="form-group"  style="position: relative; width:100%;">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password"
                               placeholder="••••••••" style="width:100%; padding-right:2.5rem;"
                        >
                        <span
                            id="togglePassword"
                            style="
                                position: absolute;
                                top: 66%;
                                right: 0.75rem;
                                transform: translateY(-50%);
                                cursor: pointer;
                                font-size: 1.1rem;
                                color: #666;
                            "
                        >
                            <i class="fa fa-eye"></i>
                          </span>
                    </div>
                    <button type="submit" name="update_profile" class="btn">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- API Access card -->
            <div class="card api-access">
                <h2>API Access</h2>
                <form action="<?php echo $apiController_file; ?>" method="post" id="api-form" onsubmit="return validateForm();">
                    <div class="api-group">
                        <input
                            id="api-input"
                            name="api_token"
                            type="text"
                            placeholder="Paste your Formulate API token…"
                            value="<?= empty($old_api_data) ? '' : htmlspecialchars($old_api_data["token"]) ?>"
                            required
                        />
                        <?php if (empty($old_api_data)): ?>
                            <button id="save-token" type="submit" class="btn">Save Token</button>
                        <?php else: ?>
                            <button
                                id="update-token"
                                type="button"
                                style="
                                    background-color: #C49A5F;
                                    color: #FFFFFF;
                                    padding: 0.75rem 1.5rem;
                                    border: none;
                                    border-radius: 0.5rem;
                                    font-size: 1rem;
                                    cursor: pointer;
                                    margin-left: 0.75rem;
                                "
                            >
                                Update Token
                            </button>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- confirmation modal -->
                <div id="confirm-modal">
                    <div class="confirm-box">
                        <p>Updating your token will revoke the previous one. Continue?</p>
                        <button id="confirm-ok" class="btn">Yes, update</button>
                        <button
                                id="confirm-cancel"
                                style="
                                    padding: 0.5rem 1rem;
                                    margin: 0 0.5rem;
                                    font-size: 0.95rem;
                                    border: 1px solid #dc3545;
                                    border-radius: 0.5rem;
                                    background-color: transparent;
                                    color: #dc3545;
                                    cursor: pointer;
                                    transition: background-color 0.2s, color 0.2s;
                                "
                                onmouseover="this.style.backgroundColor='#dc3545'; this.style.color='#fff';"
                                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#dc3545';"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // POST back to generate or regenerate token
        document.querySelector('#generate')?.addEventListener('click', e => {
            e.preventDefault();
            const f = document.createElement('form');
            f.method = 'post'; f.action = 'My_Profile.php';
            f.innerHTML = '<input type="hidden" name="generate_token" value="1">';
            document.body.appendChild(f);
            f.submit();
        });
        document.querySelector('#regenerate')?.addEventListener('click', e => {
            e.preventDefault();
            const f = document.createElement('form');
            f.method = 'post'; f.action = 'My_Profile.php';
            f.innerHTML = '<input type="hidden" name="regenerate_token" value="1">';
            document.body.appendChild(f);
            f.submit();
        });
    </script>
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

<script>
    // Password Show Hide Toggle
    (function() {
        const pwd = document.querySelector('#password');
        const btn = document.querySelector('#togglePassword');
        if (!pwd || !btn) return;
        btn.addEventListener('click', () => {
            const isPwd = pwd.type === 'password';
            pwd.type = isPwd ? 'text' : 'password';
            btn.firstElementChild.classList.toggle('fa-eye');
            btn.firstElementChild.classList.toggle('fa-eye-slash');
        });
    })();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateBtn = document.getElementById('update-token');
        const modal     = document.getElementById('confirm-modal');
        const okBtn     = document.getElementById('confirm-ok');
        const cancelBtn = document.getElementById('confirm-cancel');
        const form      = document.getElementById('api-form');

        // Show modal
        updateBtn?.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        // Confirm → submit to $apiController_file
        okBtn?.addEventListener('click', () => {
            modal.style.display = 'none';
            form.submit();
        });

        // Cancel → hide
        cancelBtn?.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    });
</script>



</body>
</html>




