<?php

use App\Kernel;

if (file_exists(__DIR__ . '/../.install') === false) {
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
    $domain = $_SERVER['SERVER_NAME'];        
    header("Location: $protocol$domain/install");
    die();
}

if (file_exists(__DIR__ . '/../lock')) {
    die('application locked');
}

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
