<?php
ob_start();
try{

    // No need for Session as there is no Login system
    global $routes, $backend_routes, $image_routes, $css_routes, $js_routes;
    $PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/routes.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT. '/utility_functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/view/Data_Provider.php';


    $seed = $_GET['seed'] ?? '42';
    $language = $_GET['lang'] ?? 'en';
    $likes = $_GET['likes'] ?? '3.7';
    $reviews = $_GET['reviews'] ?? '4.7';

    function generateBooks($seed, $language, $likes, $reviews, $count = 20) {
        mt_srand(crc32($seed));
        $titles = [
            'en' => ['The Lost City', 'Shadows and Light', 'Infinite Dreams'],
            'de' => ['Die verlorene Stadt', 'Schatten und Licht', 'Unendliche Träume'],
            'jp' => ['失われた街', '影と光', '無限の夢']
        ];
        $authors = [
            'en' => ['John Smith', 'Jane Doe', 'Alice Johnson'],
            'de' => ['Hans Müller', 'Anna Schmidt', 'Peter Fischer'],
            'jp' => ['山田太郎', '佐藤花子', '鈴木一郎']
        ];
        $publishers = [
            'en' => ['HarperCollins', 'Penguin Random House', 'Simon & Schuster'],
            'de' => ['Verlag Müller', 'Buchverlag Schmidt', 'Fischer Bücher'],
            'jp' => ['講談社', '集英社', '角川書店']
        ];
        $books = [];
        for ($i = 1; $i <= $count; $i++) {
            $isbn = mt_rand(100000000000, 999999999999);
            $title = $titles[$language][array_rand($titles[$language])];
            $author = $authors[$language][array_rand($authors[$language])];
            $publisher = $publishers[$language][array_rand($publishers[$language])];
            $likesCount = getRandomInt($likes);
            $reviewsCount = getRandomInt($reviews);
            $books[] = [
                'index' => $i,
                'isbn' => $isbn,
                'title' => $title,
                'author' => $author,
                'publisher' => $publisher,
                'likes' => $likesCount,
                'reviews' => $reviewsCount
            ];
        }
        return $books;
    }

    function getRandomInt($value) {
        $intPart = floor($value);
        $fractional = $value - $intPart;
        return $intPart + ((mt_rand() / mt_getrandmax()) < $fractional ? 1 : 0);
    }

    function buildTable($books) {
        $output = '<table><thead><tr>
            <th>#</th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Publisher</th>
            <th>Likes</th>
            <th>Reviews</th>
        </tr></thead><tbody>';
        foreach ($books as $book) {
            $output .= "<tr class='book-row' data-index='{$book['index']}' data-isbn='{$book['isbn']}' data-title='{$book['title']}' data-author='{$book['author']}' data-publisher='{$book['publisher']}' data-likes='{$book['likes']}' data-reviews='{$book['reviews']}'>
                <td>{$book['index']}</td>
                <td>{$book['isbn']}</td>
                <td>{$book['title']}</td>
                <td>{$book['author']}</td>
                <td>{$book['publisher']}</td>
                <td>{$book['likes']}</td>
                <td>{$book['reviews']}</td>
            </tr>";
        }
        $output .= '</tbody></table>';
        return $output;
    }

    $books = generateBooks($seed, $language, $likes, $reviews);
    echo buildTable($books);

} catch (Throwable $e){
//    Redirect to 500 Internal Server Error Page
    $error_location = " View -> update_likes_reviews file";
    $error_message = $e->getMessage();
    show_error_page($error_location, $error_message, "internal_server_error");
}
ob_end_flush();
