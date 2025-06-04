<?php

global $routes;
$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/routes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/view/Data_Provider.php';
$supported = require __DIR__ . '/languages.php';

// Paths
$book_generator_css = $routes['book_generator_css'];
$book_generator_js = $routes['book_generator_js'];


//----------------------------------
//  CONSTANTS
//----------------------------------
const DEFAULT_LANG     = 'en';
const DEFAULT_SEED     = '0';
const DEFAULT_LIKES    = 0;
const DEFAULT_REVIEWS  = 0;
const DEFAULT_PAGE     = 1;

$lang       = $_GET['lang']    ?? DEFAULT_LANG;
$seed       = $_GET['seed']    ?? DEFAULT_SEED;
$likesPer   = floatval($_GET['likes']   ?? DEFAULT_LIKES);
$reviewsPer = floatval($_GET['reviews'] ?? DEFAULT_REVIEWS);
$page       = intval($_GET['page']    ?? DEFAULT_PAGE);
$isAjax     = (isset($_GET['fetch']) && $_GET['fetch'] === 'rows');

if ($isAjax) {
    // AJAX: just output rows, then exit
    $count = ($page === 1 ? 20 : 10);
    generateBooks($seed, $lang, $likesPer, $reviewsPer, $page, $count);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📚 Book Generator App</title>
    <link rel="stylesheet" href="<?php echo $book_generator_css; ?>">
    <!-- Seedrandom & PapaParse for JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/seedrandom/3.0.5/seedrandom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
</head>
<body>
<header>
    <h1>📚 Book Generator App</h1>
</header>
<main>
    <section class="controls">
        <label for="language">Language & Region:</label>
        <select id="language">
            <?php
            // Only "en", "ru", "jp" now:
            global $supported;
            foreach ($supported as $code => $fakerLocale) {
                // “$code” is the short code (‘en’, ‘ru’, ‘jp’, …)
                $selected = ($code === $lang) ? ' selected' : '';

                // Instead of $labelsMap[$code], call getLanguageLabel($code)
                $friendly = getLanguageLabel($fakerLocale);

                echo "<option value=\""
                    . htmlspecialchars($code, ENT_QUOTES)
                    . "\"{$selected}>"
                    . htmlspecialchars($friendly, ENT_QUOTES)
                    . "</option>\n";
            }
            ?>
        </select>

        <label for="seed">Seed:</label>
        <input type="text" id="seed"
               value="<?php echo htmlspecialchars($seed, ENT_QUOTES | ENT_SUBSTITUTE); ?>"
               placeholder="Enter seed">
        <button id="random-seed">🔀 Random Seed</button>

        <label for="likes">Likes per Book:</label>
        <input type="range" id="likes" min="0" max="10" step="0.1"
               value="<?php echo htmlspecialchars($likesPer, ENT_QUOTES | ENT_SUBSTITUTE); ?>">
        <span id="likes-value">
        <?php echo htmlspecialchars($likesPer, ENT_QUOTES | ENT_SUBSTITUTE); ?>
      </span>

        <label for="reviews">Reviews per Book:</label>
        <input type="number" id="reviews" min="0" max="10" step="0.1"
               value="<?php echo htmlspecialchars($reviewsPer, ENT_QUOTES | ENT_SUBSTITUTE); ?>">

        <button id="export-csv">Export CSV</button>
    </section>

    <section>
        <table id="books-table">
            <thead>
            <tr>
                <th>#</th>
                <th>ISBN</th>
                <th>Title</th>
                <th>Author(s)</th>
                <th>Publisher</th>
                <th>Likes</th>
                <th>Reviews</th>
            </tr>
            </thead>
            <tbody id="books-body">
            <!-- Rows will be injected by JS/AJAX -->
            </tbody>
        </table>
    </section>
</main>

<script>
    // Initializing the configs
    // Pass PHP side defaults into JS
    window.initialConfig = {
        lang:    "<?php echo htmlspecialchars($lang, ENT_QUOTES | ENT_SUBSTITUTE); ?>",
        seed:    "<?php echo htmlspecialchars($seed, ENT_QUOTES | ENT_SUBSTITUTE); ?>",
        likes:   "<?php echo htmlspecialchars($likesPer, ENT_QUOTES | ENT_SUBSTITUTE); ?>",
        reviews: "<?php echo htmlspecialchars($reviewsPer, ENT_QUOTES | ENT_SUBSTITUTE); ?>",
        page:    1
    };
</script>
<script src="<?php echo $book_generator_js; ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const seedInput    = document.getElementById('seed');
        const reviewsInput = document.getElementById('reviews');
        const likesInput   = document.getElementById('likes');
        const likesValue   = document.getElementById('likes-value');

        const SEED_MIN = 100_000_000_000;
        const SEED_MAX = 999_999_999_999;
        const REV_MIN  = 1;    // you had 1 instead of 0
        const REV_MAX  = 10;
        const LIKE_MIN = 1;    // enforce minimum of 1
        const LIKE_MAX = 10;

        // -----------------------------------------------------
        // Seed: must be an integer between SEED_MIN and SEED_MAX
        // -----------------------------------------------------
        seedInput.addEventListener('blur', () => {
            let raw = seedInput.value.trim();
            if (!/^\d+$/.test(raw)) {
                seedInput.value = SEED_MIN;
                return;
            }
            let num = Number(raw);
            if (num < SEED_MIN) num = SEED_MIN;
            if (num > SEED_MAX) num = SEED_MAX;
            seedInput.value = String(num);
        });

        seedInput.addEventListener('input', () => {
            seedInput.value = seedInput.value.replace(/\D+/g, '');
            if (seedInput.value.length > 12) {
                seedInput.value = seedInput.value.slice(0, 12);
            }
        });

        // -----------------------------------------------------
        // Likes: must be between LIKE_MIN and LIKE_MAX
        // -----------------------------------------------------
        likesInput.addEventListener('input', () => {
            let val = parseFloat(likesInput.value);
            if (isNaN(val) || val < LIKE_MIN) {
                val = LIKE_MIN;
            }
            if (val > LIKE_MAX) {
                val = LIKE_MAX;
            }
            // round to one decimal place if desired
            val = Math.round(val * 10) / 10;
            likesInput.value = val;
            likesValue.innerText = val;
        });

        // -----------------------------------------------------
        // Reviews: must be between REV_MIN and REV_MAX
        // -----------------------------------------------------
        reviewsInput.addEventListener('input', () => {
            let val = parseFloat(reviewsInput.value);
            if (isNaN(val) || val < REV_MIN) {
                reviewsInput.value = REV_MIN;
                return;
            }
            if (val > REV_MAX) {
                reviewsInput.value = REV_MAX;
            } else {
                reviewsInput.value = Math.round(val * 10) / 10;
            }
        });
    });
</script>


</body>
</html>
