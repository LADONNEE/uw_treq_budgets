<?php

function autoload_doubles($name) {
    $file = __DIR__ . '/doubles/' . $name . '.php';
    if (file_exists($file)) {
        include $file;
        return true;
    }
    return false;
}

spl_autoload_register('autoload_doubles', true, true);

require __DIR__.'/../vendor/autoload.php';
