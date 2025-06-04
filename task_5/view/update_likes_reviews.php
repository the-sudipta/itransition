<?php
/**
 * AJAX endpoint to update “likes” and “reviews” for page=1 only.
 * POST parameters: seed, lang, likes, reviews, page (optional, default=1).
 * Responds with JSON array of { index, likes, reviews } for first 20 rows.
 */

$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/view/Data_Provider.php';
//----------------------------------
//  CONSTANTS
//----------------------------------
const DEFAULT_LANG     = 'en';
const DEFAULT_SEED     = '0';
const DEFAULT_LIKES    = 0;
const DEFAULT_REVIEWS  = 0;
const DEFAULT_PAGE     = 1;

$seed       = $_POST['seed']    ?? DEFAULT_SEED;
$lang       = $_POST['lang']    ?? DEFAULT_LANG;
$likesPer   = floatval($_POST['likes']   ?? DEFAULT_LIKES);
$reviewsPer = floatval($_POST['reviews'] ?? DEFAULT_REVIEWS);
$page       = intval($_POST['page']    ?? DEFAULT_PAGE);

// We only re‐generate the first page (20 rows), since user changed likes/reviews:
$count = 20;

// Capture the HTML that generateBooks() prints:
ob_start();
generateBooks($seed, $lang, $likesPer, $reviewsPer, $page, $count);
$html = ob_get_clean();

// Extract the “.book-row” lines to find updated likes/reviews
preg_match_all(
    '~<tr class="book-row".*?>\s*<td>#(\d+)</td>.*?<td>(\d+)</td>\s*<td>(\d+)</td>~s',
    $html,
    $matches,
    PREG_SET_ORDER
);

$response = [];
foreach ($matches as $m) {
    $response[] = [
        'index'   => intval($m[1]),
        'likes'   => intval($m[2]),
        'reviews' => intval($m[3])
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
