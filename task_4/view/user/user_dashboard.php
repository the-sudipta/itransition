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
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/userRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/logsRepo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/user_detailsRepo.php';

    // Backend Routes
    $logoutController_file     = $backend_routes['logout_controller'];

    // Frontends Path
    $login_page                     = $routes['login'];
    $forbidden_error                = $routes['forbidden_error'];

    $user_dashboard_page        = $routes['user_dashboard'];


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


    $users = findAllUsers();

    // --------------- HELPERS ----------------
    /**
     * Return “1st”, “2nd”, “3rd”, “4th”, etc.
     */
    function ordinal(int $n): string {
        if (in_array($n % 100, [11,12,13])) {
            return $n . 'th';
        }
        switch ($n % 10) {
            case 1:  return $n . 'st';
            case 2:  return $n . 'nd';
            case 3:  return $n . 'rd';
            default: return $n . 'th';
        }
    }




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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task 4: User Management Dashboard</title>
    <!-- Bootstrap CSS -->
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
    <!-- Bootstrap Icons -->
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    >
    <link rel="stylesheet" href="<?php echo $user_dashboard_css; ?>">

</head>
<body>
<div class="container py-4">
    <div class="card shadow-sm">
        <!-- TOOLBAR -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">User Management</h4>
            <div class="btn-group">
                <button id="blockBtn"   class="btn btn-outline-gold">Block</button>
                <button id="unblockBtn" class="btn btn-outline-gold">
                    <i class="bi bi-unlock-fill"></i>
                </button>
                <button id="deleteBtn"  class="btn btn-outline-gold">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0 text-light">
                    <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="selectAll"></th>
                        <th scope="col">Name</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Last Login</th>
                        <th scope="col">Last Activity</th>
                        <th scope="col">Registered On</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No users found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($users as $u):
                            $name  = getUserName($u['id']);
                            $stats = getUserLogStats($u['id']);

                            // Helper to format date+time
                            function formatDT($dtStr) {
                                if (! $dtStr) return ['',''];
                                $dt   = new DateTime($dtStr);
                                $day  = (int)$dt->format('j');
                                $date = ordinal($day) . ' ' . $dt->format('F, Y');
                                $time = $dt->format('g:i A');
                                return [$date, $time];
                            }

                            list($loginDate, $loginTime)       = formatDT($stats['lastLogin']);
                            list($activityDate, $activityTime) = formatDT($stats['lastActivity']);
                            list($regDate, $regTime)           = formatDT($stats['registrationTime']);
                            ?>
                            <tr>
                                <td>
                                    <input
                                            type="checkbox"
                                            class="form-check-input row-checkbox"
                                            value="<?= $u['id'] ?>"
                                    >
                                </td>
                                <td><?= htmlspecialchars($name) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>

                                <!-- Last Login -->
                                <td>
                                    <?php if ($loginDate): ?>
                                        <div><?= $loginDate ?></div>
                                        <small class="text-muted"><?= $loginTime ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">n/a</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Last Activity -->
                                <td>
                                    <?php if ($activityDate): ?>
                                        <div><?= $activityDate ?></div>
                                        <small class="text-muted"><?= $activityTime ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">n/a</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Registered On -->
                                <td>
                                    <?php if ($regDate): ?>
                                        <div><?= $regDate ?></div>
                                        <small class="text-muted"><?= $regTime ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">n/a</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Status -->
                                <td>
                                      <span class="<?= strtolower($u['status'])==='active'
                                          ? 'status-active'
                                          : 'status-blocked' ?>">
                                        <?= htmlspecialchars(ucfirst($u['status'])) ?>
                                      </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for dropdowns, etc.) -->
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"
></script>
<script>
    // SELECT / DESELECT ALL CHECKBOXES
    const selectAll   = document.getElementById('selectAll');
    const checkboxes  = document.querySelectorAll('.row-checkbox');
    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
    });

    // TODO: hook up blockBtn, unblockBtn, deleteBtn via AJAX or form-post
</script>



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




