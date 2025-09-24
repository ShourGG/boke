<?php
/**
 * Banner Settings Migration Script
 */

require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = file_get_contents('database/migrations/create_banner_settings.sql');
    $pdo->exec($sql);
    
    echo 'Banner settings table created successfully!' . PHP_EOL;
    echo 'You can now access Banner management at: ' . SITE_URL . '/admin/banner' . PHP_EOL;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>
