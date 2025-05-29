<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/routes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php';
global $routes;
session_start();
$login_page = $routes['login'];
navigate($login_page);

