<?php
/**
 * The displayed execution time in the toolbar will be highly inaccurate
 * if we don't define REQUEST_MICROTIME in PHP < 5.4.0.
 */
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    define('REQUEST_MICROTIME', microtime(true));
}

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
