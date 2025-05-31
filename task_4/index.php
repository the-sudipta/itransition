<?php
require_once $_SERVER['DOCUMENT_ROOT'] . getenv('PROJECT_ROOT_URL'). '/routes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . getenv('PROJECT_ROOT_URL').'/utility_functions.php';
global $routes;
date_default_timezone_set('Asia/Dhaka');
session_start();
$login_page = $routes['login'];
navigate($login_page);

