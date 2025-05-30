<?php
ob_start();
try{

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    global $routes, $backend_routes, $image_routes, $css_routes, $js_routes;
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/view/Data_Provider.php';

    // Backend Routes
    $logoutController_file     = $backend_routes['logout_controller'];

    // Frontends Path
    $login_page                     = $routes['login'];
    $forbidden_error                = $routes['forbidden_error'];
    $admin_dashboard_page           = $routes['admin_dashboard'];
    $admin_inventory_page           = $routes['admin_inventory'];
    $admin_billing_page             = $routes['admin_billing'];
    $admin_returns_page             = $routes['admin_returns'];
    $admin_bank_page                = $routes['admin_bank'];
    $admin_reports_page             = $routes['admin_reports'];
    $admin_employees_page           = $routes['admin_employees'];
    $admin_settings_page            = $routes['admin_settings'];

    $salesman_dashboard_page        = $routes['salesman_dashboard'];
    $salesman_inventory_page        = $routes['salesman_inventory'];
    $salesman_billing_page          = $routes['salesman_billing'];
    $salesman_returns_page          = $routes['salesman_returns'];
    $salesman_settings_page         = $routes['salesman_settings'];



    // Images Path
    $logo                           = $image_routes['logo'];
    $logo_svg                       = $image_routes['logo_svg'];
    $akar_image                     = $image_routes['akar_image'];
    $arct_icons_bank_image          = $image_routes['arct_icons_bank_image'];
    $avatar_image                   = $image_routes['avatar_image'];
    $calendar_image                 = $image_routes['calendar_image'];
    $emojione_image                 = $image_routes['emojione_image'];
    $fluent_box_image               = $image_routes['fluent_box_image'];
    $home_image                     = $image_routes['home_image'];
    $huge_icons_delivery_image      = $image_routes['huge_icons_delivery_image'];
    $inventory_image                = $image_routes['inventory_image'];
    $logOut_image                   = $image_routes['logOut_image'];
    $manage_store_image             = $image_routes['manage__store_image'];
    $notification_image             = $image_routes['notification_image'];
    $order_image                    = $image_routes['order_image'];
    $report_image                   = $image_routes['report_image'];
    $search_image                   = $image_routes['search_image'];
    $settings_image                 = $image_routes['settings_image'];
    $stash_billing_image            = $image_routes['stash_billing_image'];
    $suppliers_image                = $image_routes['suppliers_image'];

    $product_image_upload_folder    = $image_routes['product_image_Upload_folder_path'];

    // Icon Path
    $sales_icon                = $image_routes['sales_icon'];
    $revenue_icon                = $image_routes['revenue_icon'];
    $profit_icon                = $image_routes['profit_icon'];
    $cost_icon                = $image_routes['cost_icon'];
    $quantity_icon                = $image_routes['quantity_icon'];
    $on_the_way_icon                = $image_routes['on_the_way_icon'];
    $purchase_icon                = $image_routes['purchase_icon'];
    $cancel_icon                = $image_routes['cancel_icon'];
    $suppliers_icon                = $image_routes['suppliers_icon'];
    $categories_icon                = $image_routes['categories_icon'];

    $default_image_folder_location = $image_routes['website_image_folder_path'];
    $uploaded_employee_image_folder_location = $image_routes['employee_image_Upload_folder_path'];



    // CSS Path
    $style_css = $css_routes['global_style'];
    $all_min_style = $css_routes['all_min_style'];
    $alert_box_css = $css_routes['alert_box_css'];

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
    if(strtolower($_SESSION["user_role"]) !== 'admin'){
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="icon" href="<?php echo $logo_svg; ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo $style_css; ?>" />
    <script src="<?php echo $chart_script; ?>"></script>
    <link rel="stylesheet" href="<?php echo $all_min_style; ?>" />
    <link rel="stylesheet" href="<?php echo $alert_box_css; ?>">
    <script src="<?php echo $alert_box_script; ?>"></script>
    <script src="<?php echo $utility_functions_script; ?>"></script>
    <script>
        turn_off_right_click_menu();
    </script>


    <style>
        /*    Dashboard Layout CSS    */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 60% 40%;
            grid-template-rows: auto auto auto auto;
            gap: 20px;
        }

        .dashboard-grid > div {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }

            .dashboard-grid > div {
                grid-column: auto !important;
                grid-row: auto !important;
            }
        }

    </style>

    <style>
        /* Overview Metrics Final Styling Fixes */
        .card.sales-overview .metrics {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        /* One metric block */
        .metric-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            gap: 8px;
            border-right: 1px solid #eee;
            padding: 0 8px;
        }

        .metric-block:last-child {
            border-right: none;
        }

        /* Icon Box */
        .metric-block img {
            width: 36px;
            height: 36px;
            padding: 6px;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        /* Value Text */
        .metric-block strong {
            font-size: 18px;
            font-weight: 700;
            color: #2c2c2c;
            margin-bottom: 2px;
        }

        /* Label Text */
        .metric-block span {
            font-size: 14px;
            font-weight: 500;
            color: #666;
        }

        /* Individual Background Tints */
        .metric-icon-blue {
            background-color: #e3f2fd;
        }
        .metric-icon-purple {
            background-color: #ede7f6;
        }
        .metric-icon-orange {
            background-color: #fff3e0;
        }
        .metric-icon-green {
            background-color: #e8f5e9;
        }
    </style>

    <style>
        /* === Inventory Summary Styling === */
        .inventory-summary .overview-metrics {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #eee;
            gap: 16px;
        }

        .overview-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            border-right: 1px solid #eee;
            padding: 0 8px;
        }

        .overview-block:last-child {
            border-right: none;
        }

        /* Icon */
        .overview-block img {
            width: 36px;
            height: 36px;
            padding: 6px;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        /* Value */
        .overview-info strong {
            font-size: 18px;
            font-weight: 700;
            color: #2c2c2c;
            margin: 4px 0 2px;
        }

        /* Label */
        .overview-info span {
            font-size: 14px;
            font-weight: 500;
            color: #666;
        }

        /* Tint */
        .icon-orange {
            background-color: #fff3e0;
        }
        .icon-purple {
            background-color: #ede7f6;
        }
    </style>

    <style>
        /* === Purchase Overview Styling === */
        .purchase-overview .overview-metrics {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #eee;
            gap: 16px;
        }

        .purchase-overview .overview-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            border-right: 1px solid #eee;
            padding: 0 8px;
        }

        .purchase-overview .overview-block:last-child {
            border-right: none;
        }

        /* Icon */
        .purchase-overview .overview-block img {
            width: 36px;
            height: 36px;
            padding: 6px;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        /* Number */
        .purchase-overview .overview-info strong {
            font-size: 18px;
            font-weight: 700;
            color: #2c2c2c;
            margin: 4px 0 2px;
        }

        /* Label */
        .purchase-overview .overview-info span {
            font-size: 14px;
            font-weight: 500;
            color: #666;
        }

        /* Tint Helpers */
        .icon-blue {
            background-color: #e3f2fd;
        }
        .icon-green {
            background-color: #e8f5e9;
        }
        .icon-purple {
            background-color: #ede7f6;
        }
        .icon-orange {
            background-color: #fff3e0;
        }
    </style>

    <style>
        /* === Product Summary Styling === */
        .product-summary .overview-metrics {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #eee;
            gap: 16px;
        }

        .product-summary .overview-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            border-right: 1px solid #eee;
            padding: 0 8px;
        }

        .product-summary .overview-block:last-child {
            border-right: none;
        }

        /* Icon */
        .product-summary .overview-block img {
            width: 36px;
            height: 36px;
            padding: 6px;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        /* Value */
        .product-summary .overview-info strong {
            font-size: 18px;
            font-weight: 700;
            color: #2c2c2c;
            margin: 4px 0 2px;
        }

        /* Label */
        .product-summary .overview-info span {
            font-size: 14px;
            font-weight: 500;
            color: #666;
        }

        /* Tint Helpers */
        .icon-blue {
            background-color: #e3f2fd;
        }
        .icon-purple {
            background-color: #ede7f6;
        }
    </style>

    <style>
        /* === Top Selling Stock Styling === */
        .top-selling-stock h4 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2c2c2c;
        }

        .top-selling-stock .see-all-link {
            font-size: 13px;
            color: #1a73e8;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .top-selling-stock .see-all-link:hover {
            color: #0d47a1;
        }

        /* Table Setup */
        .top-selling-stock table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .top-selling-stock table thead {
            background-color: #f9f9f9;
        }

        .top-selling-stock th,
        .top-selling-stock td {
            text-align: left;
            padding: 12px;
        }

        .top-selling-stock th {
            color: #555;
            font-weight: 600;
        }

        .top-selling-stock td {
            color: #333;
            font-weight: 500;
            border-bottom: 1px solid #eee;
        }

        .top-selling-stock tr:last-child td {
            border-bottom: none;
        }
    </style>

    <style>
        /* === Low Quantity Stock Styling === */
        .low-quantity-stock h4 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .low-quantity-stock .see-all-link {
            font-size: 13px;
            font-weight: 500;
            color: #1a73e8;
            text-decoration: none;
        }

        .low-stock-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .low-stock-list li {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .low-stock-list li:last-child {
            border-bottom: none;
        }

        .low-stock-list img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .low-stock-info {
            flex-grow: 1;
        }

        .low-stock-info strong {
            display: block;
            font-size: 15px;
            color: #222;
            font-weight: 600;
        }

        .low-stock-info span {
            font-size: 13px;
            color: #666;
        }

        .low {
            background-color: #ffebee;
            color: #d32f2f;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>

    <style>
        /* Fix for Bar chart canvas size */
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: 300px;
            min-height: 280px;
        }

        /* Ensure the line chart fills properly inside the chart box */
        .chart-container {
            position: relative;
            width: 100%;
            height: 280px;
            min-height: 260px;
        }
        .chart-box {
            display: flex;
            flex-direction: column;
            padding: 16px 20px 12px;
            box-sizing: border-box;
            overflow: hidden;
            height: auto;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 240px;  /* ✅ Reduced height */
            min-height: 220px;
        }



    </style>

    <style>

        /*    Main Container Scrolling CSS    */

        /* Make sidebar fixed */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            z-index: 10;
            overflow-y: auto;
        }

        /* Push main-content to the right of fixed sidebar */
        .main-content {
            margin-left: 240px; /* match sidebar width */
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Make header stick to top */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 9;
            background-color: #f9f9f9;
            padding: 16px 24px;

            /* Remove this line to eliminate the bottom border */
            /* border-bottom: 1px solid #e0e0e0; */
        }

        /* Sidebar: Fixed Left */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 240px;
            height: 100vh;
            background-color: #ffffff;
            z-index: 10;
            overflow-y: auto;
            border-right: 1px solid #eee;
        }

        /* Main Content pushes right of sidebar */
        .main-content {
            margin-left: 240px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Prevent main scroll */
        }

        /* Sticky Header */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 9;
            background-color: #f9f9f9;
            padding: 16px 24px;
            /* No border! */
        }

        /* Scrollable Area Only */
        .dashboard-grid {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 24px;
        }

        /* Aesthetic Transparent Scrollbar */
        .dashboard-grid::-webkit-scrollbar {
            width: 8px;
        }

        .dashboard-grid::-webkit-scrollbar-track {
            background: transparent;
        }

        .dashboard-grid::-webkit-scrollbar-thumb {
            background: rgba(120, 120, 120, 0.1); /* very faint thumb */
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .dashboard-grid::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 100, 100, 0.2); /* subtle on hover */
        }

        /* Firefox */
        .dashboard-grid {
            scrollbar-width: thin;
            scrollbar-color: rgba(120, 120, 120, 0.15) transparent;
        }



    </style>

    <style>
        /*    Hide Search Bar Visual    */
        .main-header input[type="text"] {
            visibility: hidden;     /* hides it but leaves the space */
            pointer-events: none;   /* so you can’t accidentally focus it */
        }
        .hide-visually {
            visibility: hidden;
            pointer-events: none;
        }
    </style>


</head>

<body>


<!-- Alerts placeholder -->
<div id="alerts-container"></div>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="logo">
            <img src="<?php echo $logo; ?>" alt="Logo" />
        </div>
        <nav class="nav-links">
            <a href="<?php echo $admin_dashboard_page; ?>" class="active">
                <span class="menu-icon"><img src="<?php echo $home_image; ?>" alt="Dashboard Icon"></span>
                <span class="menu-text">Dashboard</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?php echo $admin_settings_page; ?>">
                <span class="menu-icon"><img src="<?php echo $settings_image; ?>" alt="Settings Icon"></span>
                <span class="menu-text">Settings</span>
            </a>
            <a href="<?php echo $logoutController_file; ?>" id="logout-btn">
                <span class="menu-icon"><img src="<?php echo $logOut_image; ?>" alt="Logout Icon"></span>
                <span class="menu-text">Log Out</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <input type="text" placeholder="Search ..." class="keep-space hide-visually" />
            <div class="user-section">
                <a href="<?php echo $admin_settings_page; ?>">
                    <img src="<?php echo $default_image_folder_location.'/default_admin.png'; ?>" class="user-avatar" alt="User Avatar" />
                </a>
            </div>
        </header>

        <div class="dashboard-grid">
            <!-- Row 1 -->
            <div class="card row-1-col-1 sales-overview">


            </div>

            <div class="card row-1-col-2 inventory-summary">

            </div>

            <!-- Row 2 -->
            <div class="card row-2-col-1 purchase-overview">

            </div>


            <div class="card row-2-col-2 product-summary">

            </div>


            <!-- Row 3 -->
            <div class="chart-box row-3-col-1">

            </div>


            <div class="chart-box row-3-col-2">

            </div>


            <!-- Row 4 -->
            <div class="stock-box row-4-col-1 top-selling-stock">
<!--                <h4>Top Selling Stock <a href="#" class="see-all-link">See All</a></h4>-->
                <div class="table-container">
                    <table id="top-selling-stock">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Sold Quantity</th>
                            <th>Remaining Quantity</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <?php $top_selling_product_array = getTopSellingProducts(); ?>
                        <tbody>
                        <?php foreach ($top_selling_product_array as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo $product['sold_quantity']; ?></td>
                                <td><?php echo $product['remaining_quantity']; ?></td>
                                <td><?php echo $product['price']; ?> ৳</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="stock-box row-4-col-2 low-quantity-stock">

            </div>


        </div>
    </main>
</div>


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
    };
</script>


</body>

</html>