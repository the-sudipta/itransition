<?php

declare(strict_types=1); // Forcing to use variable types
$PROJECT_ROOT = getenv('PROJECT_ROOT_URL');
$path = $PROJECT_ROOT.'/view/BookGenerator.php';
header("Location: {$path}");

