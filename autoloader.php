<?php


spl_autoload_register(function ($className) {
    $className = str_replace('_', '/', $className);
    
    if (file_exists($className . '.php')) {
        require_once $className . '.php';
        return true;
    }
    return false;
});
