<?php
// Frontend routes

$PROJECT_ROUTE = getenv('PROJECT_ROOT_URL');

$routes = [
    'INDEX' => $PROJECT_ROUTE . '/index.php',

    'autoload_faker' => $PROJECT_ROUTE . '/vendor/autoload.php',

    'book_generator_css' => $PROJECT_ROUTE . '/view/css/BookGenerator_css.css',
    'book_generator_js' => $PROJECT_ROUTE . '/view/js/BookGenerator_js.js',
];
