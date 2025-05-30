<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';
global $routes;
date_default_timezone_set('Asia/Dhaka');
session_start();
$login_page = $routes['login'];
navigate($login_page);

