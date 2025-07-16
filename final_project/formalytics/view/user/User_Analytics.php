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


    $api_details = findApi_tokenByUser_ID($user_id);
    $token = $api_details["token"] ?? null;
    if (!$token) {
        die('No API token – please generate one first.');
    }

// call your Symfony API endpoint
    $apiUrl = "http://localhost:8000/api/formalytics?token=" . urlencode($token);
    $response = @file_get_contents($apiUrl);
    if ($response === false) {
        die('Failed to fetch analytics data.');
    }

// decode JSON
    $data = json_decode($response, true);
    if (!isset($data['templates']) || !is_array($data['templates'])) {
        die('Invalid API response.');
    }

// now you have a $templates array to loop over in the view
    $templates = $data['templates'];




    // Message from Backend
    $info    = isset($_GET['message'])          ? htmlspecialchars($_GET['message'])          : '';
    $success = isset($_GET['success_message'])  ? htmlspecialchars($_GET['success_message'])  : '';
    $warning = isset($_GET['warning_message'])  ? htmlspecialchars($_GET['warning_message'])  : '';
    $error   = isset($_GET['error_message'])    ? htmlspecialchars($_GET['error_message'])    : '';






} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = " View -> User Analytics Page";
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

    <style>
        /* 3) page header */
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: sans-serif; background: #f5f5f5; margin:0; }
        header { background: #222; color:#fff; padding:1rem; }
        header h1 { margin:0; font-size:1.5rem; }
        main { max-width:1000px; margin:2rem auto; }
        .cards { display:flex; gap:1rem; margin-bottom:2rem; }
        .card { background:#fff; flex:1; padding:1rem; border-radius:8px; text-align:center; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
        .card h2 { margin:0.5rem 0; font-size:1.25rem; }
        .charts { display:flex; flex-wrap:wrap; gap:2rem; margin-bottom:2rem; }
        .charts > div { flex:1 1 45%; background:#fff; padding:1rem; border-radius:8px; }
        table { width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; }
        th, td { padding:0.75rem; text-align:left; border-bottom:1px solid #eee; }
        th { background:#fafafa; }
        a { color:#0070D2; text-decoration:none; }
        a:hover { text-decoration:underline; }
    </style>

    <style>
        /* ensure canvases get some size */
        .chart-container {
            flex: 1;
            min-width: 300px;
            height: 300px;
        }
        .charts {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 2rem auto;
        }
    </style>





</head>
<body>

<!-- Alerts placeholder -->
<div id="alerts-container"></div>


<header style="display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 40px;
    background: #323232;              /* changed to white for elegance */
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    position: sticky;
    top: 0;
    z-index: 10;
">
    <div class="logo">
        <a href="<?php echo $user_dashboard_page; ?>"><img src="<?php echo $logo; ?>" alt="Formalytics logo"></a>
    </div>
    <nav>
        <ul>
<!--            <li><a href="--><?php //echo $user_dashboard_page; ?><!--" class="active">Dashboard</a></li>-->
            <li><a href="<?php echo $user_dashboard_page; ?>">Dashboard</a></li>
            <li><a href="<?php echo $profile_page; ?>">My Profile</a></li>
            <li><a href="<?php echo $logoutController_file; ?>">Logout</a></li>
        </ul>
    </nav>
</header>

<main>

    <!-- Breadcrumbs -->
    <nav aria-label="Breadcrumb" style="margin:1rem 0;font-size:0.9rem;color:#555;">
        <a href="<?php echo $user_dashboard_page; ?>" style="text-decoration:none; color:#555;">Dashboard</a>
        &nbsp;&gt;&nbsp;
        <span style="text-decoration:none;color:#0070d2;">Analytics</span>
    </nav>

    <!-- page title -->
    <div class="profile-header">
        <h1>Analytics</h1>
    </div>

    <!-- summary cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;max-width:900px;margin:0 auto 2rem;">
        <div style="background:#fff;padding:1rem;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);text-align:center;">
            <div id="formsCount" style="font-size:2rem;font-weight:700;">–</div>
            <div style="margin-top:.5rem;color:#555;">Total Forms</div>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);text-align:center;">
            <div id="questionsCount" style="font-size:2rem;font-weight:700;">–</div>
            <div style="margin-top:.5rem;color:#555;">Total Questions</div>
        </div>
        <div style="background:#fff;padding:1rem;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);text-align:center;">
            <div id="commentsCount" style="font-size:2rem;font-weight:700;">–</div>
            <div style="margin-top:.5rem;color:#555;">Total Comments</div>
        </div>
    </div>

    <div class="charts">
        <div class="chart-container">
            <canvas id="questionsBarChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="topicPieChart"></canvas>
        </div>
    </div>


    <h2>All Forms</h2>
    <!-- Search bar -->
    <div style="margin-bottom:1rem;">
        <input
            type="text"
            id="searchInput"
            placeholder="Search forms..."
            style="width:100%;max-width:300px;padding:0.5rem;border:1px solid #ccc;border-radius:4px;font-size:1rem;"
        />
    </div>
    <!-- Data table -->
    <!-- Summary Chart -->
    <div style="max-width:800px;margin:0 auto 2rem;">
        <canvas id="summaryChart" style="width:100%;height:300px;"></canvas>
    </div>

    <!-- Data Table -->
    <table id="analyticsTable" style="width:100%;border-collapse:collapse;">
        <thead>
        <tr style="background:#f5f5f5;">
            <th style="padding:.75rem;border:1px solid #ddd;text-align:left;">ID</th>
            <th style="padding:.75rem;border:1px solid #ddd;text-align:left;">Title</th>
            <th style="padding:.75rem;border:1px solid #ddd;text-align:left;">Topic</th>
            <th style="padding:.75rem;border:1px solid #ddd;text-align:right;">Questions</th>
            <th style="padding:.75rem;border:1px solid #ddd;text-align:right;">Likes</th>
            <th style="padding:.75rem;border:1px solid #ddd;text-align:right;">Comments</th>
        </tr>
        </thead>
        <tbody id="tableBody">
        <?php foreach($templates as $tpl): ?>
            <tr style="cursor: pointer">
                <td style="padding:.75rem;border:1px solid #ddd;"><?= $tpl['id'] ?></td>
                <td style="padding:.75rem;border:1px solid #ddd;"><?= htmlspecialchars($tpl['title']) ?></td>
                <td style="padding:.75rem;border:1px solid #ddd;"><?= htmlspecialchars($tpl['topic']) ?></td>
                <td style="padding:.75rem;border:1px solid #ddd;text-align:right;"><?= count($tpl['questions']) ?></td>
                <td style="padding:.75rem;border:1px solid #ddd;text-align:right;"><?= count($tpl['likes']) ?></td>
                <td style="padding:.75rem;border:1px solid #ddd;text-align:right;"><?= count($tpl['comments']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <div id="pagination"></div>


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
    (async function(){
        const token = '<?= addslashes($api_details["token"]) ?>';
        const resp  = await fetch(`/api/formalytics?token=${token}`);
        const data  = await resp.json();
        if (data.error) {
            alert(data.error);
            return;
        }

        const tpl = data.templates;
        // summary cards
        document.getElementById('total-forms').textContent     = tpl.length;
        document.getElementById('total-questions').textContent = tpl.reduce((sum,t)=>sum + t.questions.length,0);
        document.getElementById('total-comments').textContent  = tpl.reduce((sum,t)=>sum + t.comments.length,0);

        // Bar chart: Questions per form
        new Chart(document.getElementById('questionsBarChart'), {
            type: 'bar',
            data: {
                labels: tpl.map(t=>t.title),
                datasets: [{
                    label: '# Questions',
                    data: tpl.map(t=>t.questions.length),
                }]
            },
            options: {
                responsive: true,
                scales: { x:{ ticks:{autoSkip:true,maxRotation:45} }, y:{beginAtZero:true} }
            }
        });

        // Pie chart: breakdown by topic
        const countsByTopic = tpl.reduce((acc,t)=>{
            acc[t.topic] = (acc[t.topic]||0) + 1;
            return acc;
        },{});
        new Chart(document.getElementById('topicPieChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(countsByTopic),
                datasets: [{ data: Object.values(countsByTopic) }]
            },
            options: { responsive:true }
        });

        // Table rows
        const tbody = document.getElementById('forms-table-body');
        tpl.forEach(t => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
          <td><a href="/analytics/view?form=${t.id}">${t.title}</a></td>
          <td>${t.topic}</td>
          <td>${t.questions.length}</td>
          <td>${t.likes.length}</td>
          <td>${t.comments.length}</td>
        `;
            tbody.appendChild(tr);
        });
    })();
</script>

<script>
    (() => {
        // embed your PHP data into JS
        const templates = <?php echo json_encode($templates, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP); ?>;

        // 1) Build summary chart
        const labels = templates.map(t => t.title);
        const qData   = templates.map(t => t.questions.length);
        const lData   = templates.map(t => t.likes.length);
        const cData   = templates.map(t => t.comments.length);

        const ctx = document.getElementById('summaryChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Questions', data: qData },
                    { label: 'Likes',     data: lData },
                    { label: 'Comments',  data: cData }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // 2) Live search + pagination
        const rowsPerPage = 5;
        let currentPage = 1;

        const tableBody = document.getElementById('tableBody');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('searchInput');

        function renderTable(page = 1) {
            const filter = searchInput.value.toLowerCase();
            const filtered = templates.filter(t => {
                return [
                    t.id,
                    t.title,
                    t.topic,
                    t.questions.length,
                    t.likes.length,
                    t.comments.length
                ].some(val =>
                    val.toString().toLowerCase().includes(filter)
                );
            });

            const start = (page - 1) * rowsPerPage;
            const pageItems = filtered.slice(start, start + rowsPerPage);

            // clear
            tableBody.innerHTML = '';
            pageItems.forEach(t => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td style="padding:.75rem;border:1px solid #ddd;">${t.id}</td>
                <td style="padding:.75rem;border:1px solid #ddd;">${t.title}</td>
                <td style="padding:.75rem;border:1px solid #ddd;">${t.topic}</td>
                <td style="padding:.75rem;border:1px solid #ddd;text-align:right;">${t.questions.length}</td>
                <td style="padding:.75rem;border:1px solid #ddd;text-align:right;">${t.likes.length}</td>
                <td style="padding:.75rem;border:1px solid #ddd;text-align:right;">${t.comments.length}</td>
            `;
                row.style.cursor = 'pointer';
                tableBody.appendChild(row);
            });

            // pagination buttons
            const pageCount = Math.ceil(filtered.length / rowsPerPage) || 1;
            pagination.innerHTML = '';
            for (let i = 1; i <= pageCount; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.style.margin = '0 .25rem';
                btn.style.padding = '.5rem .75rem';
                btn.style.border = 'none';
                btn.style.borderRadius = '4px';
                btn.style.background = (i === page ? '#d4af37' : '#f0f0f0');
                btn.style.color = (i === page ? '#fff' : '#333');
                btn.style.cursor = 'pointer';
                btn.addEventListener('click', () => {
                    currentPage = i;
                    renderTable(i);
                });
                pagination.appendChild(btn);
            }
        }

        // wire up search and initial render
        searchInput.addEventListener('input', () => {
            currentPage = 1;
            renderTable(1);
        });
        renderTable(1);
    })();
</script>

<script>
    (() => {
        // 1) pull in your PHP array
        const templates = <?php echo json_encode($templates, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP); ?>;

        // 2) summary counts
        const totalForms     = templates.length;
        const totalQuestions = templates.reduce((sum, tpl) => {
            return sum + (tpl.questions?.length||0);
        }, 0);
        const totalComments  = templates.reduce((sum, tpl) => {
            return sum + (tpl.comments?.length||0);
        }, 0);

        // 3) inject into the cards
        document.getElementById('formsCount').textContent     = totalForms;
        document.getElementById('questionsCount').textContent = totalQuestions;
        document.getElementById('commentsCount').textContent  = totalComments;

        // 4) then your existing Chart.js code…
        const labels = templates.map(t=>t.title);
        const qData  = templates.map(t=>t.questions?.length||0);
        const lData  = templates.map(t=>t.likes?.length||0);
        const cData  = templates.map(t=>t.comments?.length||0);
        const ctx    = document.getElementById('summaryChart').getContext('2d');
        new Chart(ctx, {
            type:'bar',
            data:{ labels, datasets:[
                    { label:'Questions', data:qData },
                    { label:'Likes',     data:lData },
                    { label:'Comments',  data:cData }
                ]},
            options:{
                responsive:true,
                maintainAspectRatio:false,
                scales:{ y:{ beginAtZero:true }}
            }
        });

        // …and your live‐search / pagination JS (unchanged)
        // …
    })();
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1) embed PHP data into JS
        const templates = <?php echo json_encode($templates, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP); ?>;

        // build common arrays
        const labels   = templates.map(t => t.title);
        const qData    = templates.map(t => t.questions.length);

        // 2) QUESTIONS BAR CHART
        const qCtx = document.getElementById('questionsBarChart').getContext('2d');
        new Chart(qCtx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Questions per Form',
                    data: qData,
                    backgroundColor: 'rgba(54,162,235,0.6)',
                    borderColor:     'rgba(54,162,235,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 3) TOPIC DISTRIBUTION PIE CHART
        const topicCounts = templates.reduce((acc, tpl) => {
            acc[tpl.topic] = (acc[tpl.topic]||0) + 1;
            return acc;
        }, {});
        const topicLabels = Object.keys(topicCounts);
        const topicData   = Object.values(topicCounts);
        // generate a distinct color per slice
        const colors = topicLabels.map((_, i) =>
            `hsl(${ i * 360 / topicLabels.length }, 65%, 60%)`
        );

        const pCtx = document.getElementById('topicPieChart').getContext('2d');
        new Chart(pCtx, {
            type: 'pie',
            data: {
                labels: topicLabels,
                datasets: [{
                    data: topicData,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // 4) …then your existing live‑search + pagination code…
    });
</script>


</body>
</html>




