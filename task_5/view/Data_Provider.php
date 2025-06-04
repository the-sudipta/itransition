<?php
/**
 * Data_Provider.php
 *
 * Provides:
 *   - getRandomInt(), to convert fractional averages into integers
 *   - generateBooks(), which echoes <tr>…</tr> rows for a given seed/lang/page
 */

$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/vendor/autoload.php';

use Faker\Factory as FakerFactory;

$localeMap = require __DIR__ . '/languages.php';


//----------------------------------
//  CONSTANTS
//----------------------------------
const ISBN_MIN = 100000000000;    // smallest 12‐digit number
const ISBN_MAX = 999999999999;    // largest 12‐digit number
// How many characters we want faker to pick for a “title”
const TITLE_MIN_CHARS = 20;
const TITLE_MAX_CHARS = 40;
// How many characters we want faker to pick for a “review”
const REVIEW_MAX_CHARS = 40;
// Default Language
const DEFAULT_LANGUAGE_LOCALE = 'en_US';

/**
 * getRandomInt( float $value, int $subSeed ): int
 *
 * Takes a fractional number (e.g. 3.7) and a deterministic sub‐seed,
 * then returns floor($value) or ceil($value) with probability “$fractional”.
 *
 * We use PHP’s mt_rand() for speed. We call mt_srand($subSeed) each time,
 * so that every “book index” has its own reproducible random number generator.
 *
 * @param float $value    Average (0..10, fractional)
 * @param int   $subSeed  Deterministic integer seed for this specific row
 * @return int            Integer number (floor or floor+1)
 */
function getRandomInt(float $value, int $subSeed): int {
    $intPart    = floor($value);
    $fractional = $value - $intPart;

    // Re‐seed PHP RNG for this row:
    mt_srand($subSeed);

    $randFloat = mt_rand(0, mt_getrandmax()) / mt_getrandmax();
    $result    = (int)$intPart;
    if ($randFloat < $fractional) {
        $result++;
    }
    return $result;
}

/**
 * generateBooks(
 *   string $seed,
 *   string $lang,
 *   float  $likesPer,
 *   float  $reviewsPer,
 *   int    $page,
 *   int    $count = 20
 * ) : void
 *
 * Echoes exactly $count pairs of <tr>…</tr> rows:
 *   1) a “.book-row” <tr> (visible)
 *   2) a “.book-details” <tr> (hidden by default)
 * Each pair is uniquely keyed by “globalIndex = ($page-1)*$count + $i”.
 *
 * @param string $seed       Arbitrary seed string (e.g. “42” or “foobar”)
 * @param string $lang       “en”, “ru”, or “jp”
 * @param float  $likesPer   e.g. 3.7
 * @param float  $reviewsPer e.g. 4.7
 * @param int    $page       1,2,3… for infinite scroll
 * @param int    $count      how many rows to generate (20 for page=1, else 10)
 */
function generateBooks(string $seed, string $lang, float $likesPer, float $reviewsPer, int $page, int $count = 20): void {
    // 1) Combine seed & page into a 32‐bit integer for reproducibility:
    $baseSeed = crc32($seed . '_' . $page);

    // 2) Map “en|ru|jp” → Faker locale codes
    global $localeMap;
    $fakerLocale = $localeMap[$lang] ?? DEFAULT_LANGUAGE_LOCALE;

    // 3) Create a single Faker instance for this locale, seeded by $baseSeed:
    $faker = FakerFactory::create($fakerLocale);
    $faker->seed($baseSeed);

    // 4) For each row i=1..$count, generate data:
    for ($i = 1; $i <= $count; $i++) {
        // a) Global index across all pages (1‐based):
        $globalIndex = ($page - 1) * $count + $i;

        // b) ISBN: use PHP’s mt_rand() in [ISBN_MIN .. ISBN_MAX] seeded by ($baseSeed + $i + 100)
        $subSeedISBN = (int)$baseSeed + $i + 100;
        mt_srand($subSeedISBN);
        $isbn = (string)mt_rand(ISBN_MIN, ISBN_MAX);

        // c) Title: use Faker’s realTextBetween() to get a plausible “book‐title” snippet
        $rawTitle = $faker->realTextBetween(TITLE_MIN_CHARS, TITLE_MAX_CHARS); // e.g. “The old manor creaked open late at night”
        $title    = rtrim($rawTitle, ' .') . '';      // strip trailing period, if any


        // d) Author: Faker’s full name
        $author = $faker->name();

        // e) Publisher: Faker’s company
        $publisher = $faker->company();

        // f) Likes: use getRandomInt() seeded by ($baseSeed + $i + 200)
        $subSeedLikes = (int)$baseSeed + $i + 200;
        $likes        = getRandomInt($likesPer, $subSeedLikes);

        // g) Reviews count: getRandomInt() seeded by ($baseSeed + $i + 300)
        $subSeedReviews = (int)$baseSeed + $i + 300;
        $reviewsCount   = getRandomInt($reviewsPer, $subSeedReviews);

        // h) Generate sample review‐texts (Faker’s realText for Japanese, sentence() otherwise)
        $reviewCardsHtml = '';
        for ($r = 0; $r < $reviewsCount; $r++) {
            if ($lang === 'jp') {
                $sampleText = $faker->realText(REVIEW_MAX_CHARS);
            } else {
                // If Russian locale ("ru"), Faker→sentence() will yield Russian text.
                $sampleText = $faker->sentence();
            }
            $escapedText = htmlspecialchars($sampleText, ENT_QUOTES | ENT_SUBSTITUTE);
            $reviewCardsHtml .= "<div class=\"review-card\">{$escapedText}</div>";
        }

        // Escape everything for HTML:
        $escTitle     = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE);
        $escAuthor    = htmlspecialchars($author, ENT_QUOTES | ENT_SUBSTITUTE);
        $escPublisher = htmlspecialchars($publisher, ENT_QUOTES | ENT_SUBSTITUTE);

        // 5) Echo two <tr> rows: one visible “.book-row” and one hidden “.book-details”
        echo "
        <tr class=\"book-row\" data-isbn=\"{$isbn}\" data-title=\"{$escTitle}\"
            data-author=\"{$escAuthor}\" data-publisher=\"{$escPublisher}\"
            data-likes=\"{$likes}\" data-reviews=\"{$reviewsCount}\">
          <td>#{$globalIndex}</td>
          <td>{$isbn}</td>
          <td>{$escTitle}</td>
          <td>{$escAuthor}</td>
          <td>{$escPublisher}</td>
          <td>{$likes}</td>
          <td>{$reviewsCount}</td>
        </tr>
        <tr class=\"book-details\" style=\"display: none;\">
          <td colspan=\"7\">
            <div class=\"details-content\">
              <img src=\"https://picsum.photos/seed/{$isbn}/200/260\" alt=\"Book Cover\" />
              <div class=\"info\">
                <h2>{$escTitle}</h2>
                <p><strong>ISBN:</strong> {$isbn}</p>
                <p><strong>Author:</strong> {$escAuthor}</p>
                <p><strong>Publisher:</strong> {$escPublisher}</p>
                <p><strong>Likes:</strong> {$likes}</p>
                <p><strong>Reviews:</strong> {$reviewsCount}</p>
                <div class=\"book-reviews\">
                  <strong>Sample Reviews:</strong>
                  {$reviewCardsHtml}
                </div>
              </div>
            </div>
          </td>
        </tr>
        ";
    }
}


/**
 * Returns the human‐friendly name for a Faker‐locale code (e.g. "en_US" → "English (United States)").
 * Falls back to the input code if not found.
 */
function getLanguageLabel(string $locale): string
{
    static $labelsMap = null;

    if ($labelsMap === null) {
        $jsonPath = __DIR__ . '/data/countries.json';
        if (! file_exists($jsonPath)) {
            throw new RuntimeException("Could not find countries.json at $jsonPath");
        }
        $contents = file_get_contents($jsonPath);
        $decoded  = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($decoded)) {
            throw new RuntimeException("countries.json did not decode into an array!");
        }
        $labelsMap = $decoded;
    }

    // If the full‐locale code is missing, just return the code itself.
    return $labelsMap[$locale] ?? $locale;
}
