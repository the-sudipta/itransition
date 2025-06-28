<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    // public/index.php or config/bootstrap.php
    date_default_timezone_set('Asia/Dhaka');
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
