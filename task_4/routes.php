<?php
// Frontend routes

$PROJECT_ROUTE = getenv('PROJECT_ROOT_URL');

$routes = [
    'INDEX' => $PROJECT_ROUTE . '/index.php',
    'login' => $PROJECT_ROUTE . '/view/Login.php',
    'database_error' => $PROJECT_ROUTE . '/view/error/_database_error.php',
    'not_found_error' => $PROJECT_ROUTE . '/view/error/_404_not_found_error.php',
    'forbidden_error' => $PROJECT_ROUTE . '/view/error/_403_forbidden_error.php',
    'internal_server_error' => $PROJECT_ROUTE . '/view/error/_500_internal_server_error.php',
    'invoice' => $PROJECT_ROUTE . '/view/invoice.php',
    'data_provider' => $PROJECT_ROUTE . '/view/Data_Provider.php',

    'user_signup' => $PROJECT_ROUTE . '/view/Login.php',
    'admin_dashboard' => $PROJECT_ROUTE . '/view/admin/admin_dashboard.php',
    'user_dashboard' => $PROJECT_ROUTE . '/view/user/user_dashboard.php',


];
// Backend routes
$backend_routes = [
//    General Controllers
    'login_controller' => $PROJECT_ROUTE . '/controller/LoginController.php',
    'logout_controller' => $PROJECT_ROUTE . '/controller/LogoutController.php',
    'backend_utility_functions' => $PROJECT_ROUTE . '/controller/backend_utility_functions.php',


    'signup_controller' => $PROJECT_ROUTE . '/controller/user/UserSignupController.php',
    'user_block_controller' => $PROJECT_ROUTE . '/controller/user/UserBlockController.php',
    'user_unblock_controller' => $PROJECT_ROUTE . '/controller/user/UserUnblockController.php',
    'user_delete_controller' => $PROJECT_ROUTE . '/controller/user/UserDeleteController.php',



];

// Backend routes
$image_routes = [

];

$css_routes = [

//    Database Error
    'database_error_style' => $PROJECT_ROUTE . '/view/css/database_error/style.css',

    'global_style' => $PROJECT_ROUTE . '/view/css/style.css',
    'alert_box_css' => $PROJECT_ROUTE . '/view/css/alert_box_css.css',
    'all_min_style' => $PROJECT_ROUTE . '/view/css/all.min.css',
    'flatpickr_min_style' => $PROJECT_ROUTE . '/view/css/flatpickr_min_style.css',

    'login_css' => $PROJECT_ROUTE . '/view/css/login.css',
    'user_dashboard_css' => $PROJECT_ROUTE . '/view/css/user_dashboard.css',



];

$js_routes = [

//    Database Error
    'database_error_script' => $PROJECT_ROUTE . '/view/js/database_error/script.js',

    'global_script' => $PROJECT_ROUTE . '/view/js/script.js',
    'alert_box_script' => $PROJECT_ROUTE . '/view/js/alerts.js',
    'bank_script' => $PROJECT_ROUTE . '/view/js/bank.js',
    'billings_script' => $PROJECT_ROUTE . '/view/js/billings.js',
    'canvas_min_script' => $PROJECT_ROUTE . '/view/js/canvas.min.js',
    'chart_script' => $PROJECT_ROUTE . '/view/js/chart.js',
    'employees_script' => $PROJECT_ROUTE . '/view/js/employees.js',
    'flatpickr_script' => $PROJECT_ROUTE . '/view/js/flatpickr_script.js',
    'html2canvas_min_script' => $PROJECT_ROUTE . '/view/js/html2canvas_min_script.js',
    'html2pdf_bundle_min_script' => $PROJECT_ROUTE . '/view/js/html2pdf_bundle_min_script.js',
    'inventory_script' => $PROJECT_ROUTE . '/view/js/inventory.js',
    'jquery_min_script' => $PROJECT_ROUTE . '/view/js/jquery_min_script.js',
    'jspdf_script' => $PROJECT_ROUTE . '/view/js/jspdf.min.js',
    'jspdf_plugin_autotable_min_script' => $PROJECT_ROUTE . '/view/js/jspdf_plugin_autotable_min_script.js',
    'jspdf_umd_min_script' => $PROJECT_ROUTE . '/view/js/jspdf_umd_min_script.js',
    'loginPage_script' => $PROJECT_ROUTE . '/view/js/loginpage_script.js',
    'reports_script' => $PROJECT_ROUTE . '/view/js/reports.js',
    'returns_script' => $PROJECT_ROUTE . '/view/js/returns.js',
    'settings_script' => $PROJECT_ROUTE . '/view/js/settings.js',
    'sheet_script' => $PROJECT_ROUTE . '/view/js/sheet.js',
    'xlsx_full_min_script' => $PROJECT_ROUTE . '/view/js/xlsx_full_min_script.js',
    'utility_functions_script' => $PROJECT_ROUTE . '/view/js/Utility_Functions.js',

    'login_js' => $PROJECT_ROUTE . '/view/js/login.js',



];

$fonts_routes = [

    'roxborough_cf' => $PROJECT_ROUTE . '/view/fonts/Roxborough CF.woff2',

];







