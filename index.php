<?php
/**
 * Koi Blog System - Main Entry Point
 * Lightweight MVC Architecture for Personal Blog
 * 
 * @author Old Wang (The Grumpy Developer)
 * @version 1.0
 */

// Error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');

// Auto-load classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/core/',
        APP_PATH . '/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load configuration
if (file_exists(CONFIG_PATH . '/config.php')) {
    require_once CONFIG_PATH . '/config.php';
} else {
    // Redirect to installation if config doesn't exist
    if (!strpos($_SERVER['REQUEST_URI'], 'install.php')) {
        header('Location: install.php');
        exit;
    }
}

// Initialize router
try {
    $router = new Router();
    $router->dispatch();
} catch (Exception $e) {
    // Simple error handling
    echo '<h1>Error</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    if (defined('DEBUG') && DEBUG) {
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    }
}
