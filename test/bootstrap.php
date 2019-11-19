<?php

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function($class) {
    if (strpos($class, 'Util\\') === 0) {
        $dir = strcasecmp(substr($class, -4), 'Test') ? 'src/' : 'test/';
        $name = substr($class, strlen('Util'));
        require __DIR__ . '/../' . $dir . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
});
