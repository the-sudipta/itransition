<?php
// Frontend routes
$routes = [
    'INDEX' => '/itransition/index.php',
    'login' => '/itransition/view/Login.php',
    'database_error' => '/itransition/view/error/_database_error.php',
    'not_found_error' => '/itransition/view/error/_404_not_found_error.php',
    'forbidden_error' => '/itransition/view/error/_403_forbidden_error.php',
    'internal_server_error' => '/itransition/view/error/_500_internal_server_error.php',
    'invoice' => '/itransition/view/invoice.php',
    'data_provider' => '/itransition/view/Data_Provider.php',

    'user_signup' => '/itransition/view/Login.php',
    'admin_dashboard' => '/itransition/view/admin/admin_dashboard.php',
    'user_dashboard' => '/itransition/view/user/user_dashboard.php',


];
// Backend routes
$backend_routes = [
//    General Controllers
    'login_controller' => '/itransition/controller/LoginController.php',
    'logout_controller' => '/itransition/controller/LogoutController.php',
    'backend_utility_functions' => '/itransition/controller/backend_utility_functions.php',


    'signup_controller' => '/itransition/controller/user/UserSignupController.php',



];

// Backend routes
$image_routes = [

];

$css_routes = [

//    Database Error
    'database_error_style' => '/itransition/view/css/database_error/style.css',

    'global_style' => '/itransition/view/css/style.css',
    'alert_box_css' => '/itransition/view/css/alert_box_css.css',
    'all_min_style' => '/itransition/view/css/all.min.css',
    'flatpickr_min_style' => '/itransition/view/css/flatpickr_min_style.css',

    'login_css' => '/itransition/view/css/login.css',
    'user_dashboard_css' => '/itransition/view/css/user_dashboard.css',



];

$js_routes = [

//    Database Error
    'database_error_script' => '/itransition/view/js/database_error/script.js',

    'global_script' => '/itransition/view/js/script.js',
    'alert_box_script' => '/itransition/view/js/alerts.js',
    'bank_script' => '/itransition/view/js/bank.js',
    'billings_script' => '/itransition/view/js/billings.js',
    'canvas_min_script' => '/itransition/view/js/canvas.min.js',
    'chart_script' => '/itransition/view/js/chart.js',
    'employees_script' => '/itransition/view/js/employees.js',
    'flatpickr_script' => '/itransition/view/js/flatpickr_script.js',
    'html2canvas_min_script' => '/itransition/view/js/html2canvas_min_script.js',
    'html2pdf_bundle_min_script' => '/itransition/view/js/html2pdf_bundle_min_script.js',
    'inventory_script' => '/itransition/view/js/inventory.js',
    'jquery_min_script' => '/itransition/view/js/jquery_min_script.js',
    'jspdf_script' => '/itransition/view/js/jspdf.min.js',
    'jspdf_plugin_autotable_min_script' => '/itransition/view/js/jspdf_plugin_autotable_min_script.js',
    'jspdf_umd_min_script' => '/itransition/view/js/jspdf_umd_min_script.js',
    'loginPage_script' => '/itransition/view/js/loginpage_script.js',
    'reports_script' => '/itransition/view/js/reports.js',
    'returns_script' => '/itransition/view/js/returns.js',
    'settings_script' => '/itransition/view/js/settings.js',
    'sheet_script' => '/itransition/view/js/sheet.js',
    'xlsx_full_min_script' => '/itransition/view/js/xlsx_full_min_script.js',
    'utility_functions_script' => '/itransition/view/js/Utility_Functions.js',

    'login_js' => '/itransition/view/js/login.js',



];

$tools_routes = [

    'add_check_to_json' => '/itransition/tools/code_checker/add_check_to_json.php',
    'custom_consistency_checker' => '/itransition/tools/code_checker/custom_consistency_checker.php',
    'custom_consistency_checker_items' => '/itransition/tools/code_checker/custom_consistency_checker_items.json',

];

$fonts_routes = [

    'roxborough_cf' => '/itransition/view/fonts/Roxborough CF.woff2',

];







