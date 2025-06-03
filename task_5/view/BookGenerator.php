<?php
ob_start();
try{

    // No need for Session as there is no Login system
    global $routes, $backend_routes, $image_routes, $css_routes, $js_routes;
    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/routes.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/view/Data_Provider.php';


    // CSS Path
    $book_generator_css = $css_routes['book_generator_css'];

    // JS Path
    $book_generator_js = $js_routes['book_generator_js'];

    // 1) Read incoming GET parameters, with defaults:
    $lang       = $_GET['lang']    ?? 'en';
    $seed       = $_GET['seed']    ?? '42';
    $likesPer   = $_GET['likes']   ?? '3.7';
    $reviewsPer = $_GET['reviews'] ?? '4.7';
    $page       = $_GET['page']    ?? '1';    // for infinite scroll
    $isAjax     = isset($_GET['fetch']) && $_GET['fetch'] === 'rows';

    // If this is an AJAX request for more rows, send just the <tr>…</tr> block and exit.
    if ($isAjax) {
        $booksBatch = generateBooks($seed, $lang, $likesPer, $reviewsPer, $page);
        renderRows($booksBatch);
        exit;
    }

} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = " View -> BookGenerator Page";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Generator App</title>
    <link rel="stylesheet" href="<?php echo $book_generator_css; ?>">
</head>
<body>
<header>
    <h1>📚 Book Generator App</h1>
</header>
<main>

    <section class="controls">
        <label for="language">Language & Region:</label>
        <select id="language">
            <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>English</option>
            <option value="de" <?= $lang === 'de' ? 'selected' : '' ?>>German</option>
            <option value="jp" <?= $lang === 'jp' ? 'selected' : '' ?>>Japanese</option>
        </select>

        <label for="seed">Seed:</label>
        <input type="text" id="seed" value="<?= htmlspecialchars($seed) ?>">

        <button id="random-seed">🔀 Random Seed</button>

        <label for="likes">Likes per Book:</label>
        <input type="range" id="likes" min="0" max="10" step="0.1" value="<?= htmlspecialchars($likesPer) ?>">
        <span id="likes-value"><?= htmlspecialchars($likesPer) ?></span>

        <label for="reviews">Reviews per Book:</label>
        <input type="number" id="reviews" min="0" max="10" step="0.1" value="<?= htmlspecialchars($reviewsPer) ?>">

        <button id="export-csv">Export CSV</button>
    </section>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Publisher</th>
            <th>Likes</th>
            <th>Reviews</th>
        </tr>
        </thead>
        <tbody id="books-body">
        <!-- Initial 20 rows -->
        <?php
        $initialBatch = generateBooks($seed, $lang, $likesPer, $reviewsPer, 1);
        renderRows($initialBatch);
        ?>
        </tbody>
    </table>
    <script src="<?php echo $book_generator_js; ?>"></script>
</main>
</body>
</html>
