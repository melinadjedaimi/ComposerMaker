<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Explicitly set APP_SECRET for PHP built-in server
$_ENV['APP_SECRET'] = 'd5434123e471886639a37bc8edf52ffd';
$_SERVER['APP_SECRET'] = 'd5434123e471886639a37bc8edf52ffd';
putenv('APP_SECRET=d5434123e471886639a37bc8edf52ffd');

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
