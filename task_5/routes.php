<?php
// Frontend routes

$PROJECT_ROUTE = getenv('PROJECT_ROOT_URL');

$routes = [
    'INDEX' => $PROJECT_ROUTE . '/index.php',
    'database_error' => $PROJECT_ROUTE . '/view/error/_database_error.php',
    'not_found_error' => $PROJECT_ROUTE . '/view/error/_404_not_found_error.php',
    'forbidden_error' => $PROJECT_ROUTE . '/view/error/_403_forbidden_error.php',
    'internal_server_error' => $PROJECT_ROUTE . '/view/error/_500_internal_server_error.php',
    'data_provider' => $PROJECT_ROUTE . '/view/Data_Provider.php',


    'book_generator' => $PROJECT_ROUTE . '/view/BookGenerator.php',


];
// Backend routes
$backend_routes = [

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

    'book_generator_css' => $PROJECT_ROUTE . '/view/css/BookGenerator_css.css',

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


    'book_generator_js' => $PROJECT_ROUTE . '/view/js/BookGenerator_js.js',

];

$fonts_routes = [

    'roxborough_cf' => $PROJECT_ROUTE . '/view/fonts/Roxborough CF.woff2',

];







