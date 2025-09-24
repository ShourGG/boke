<?php
/**
 * Banner Settings Migration Script
 * Run this script to create the banner_settings table
 */

echo "<!DOCTYPE html><html><head><title>Banner Migration</title></head><body>";
echo "<h2>Banner Settings Migration</h2>";

require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p>Connected to database successfully...</p>";

    // Create table
    $createTable = "
    CREATE TABLE IF NOT EXISTS banner_settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        banner_image VARCHAR(500) DEFAULT 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        banner_title VARCHAR(200) DEFAULT '',
        banner_subtitle VARCHAR(500) DEFAULT '',
        banner_enabled TINYINT(1) DEFAULT 1,
        parallax_enabled TINYINT(1) DEFAULT 0,
        overlay_opacity DECIMAL(3,2) DEFAULT 0.30,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    $pdo->exec($createTable);
    echo "<p>✅ Banner settings table created successfully!</p>";

    // Insert default settings
    $insertDefault = "
    INSERT INTO banner_settings (banner_image, banner_title, banner_subtitle, banner_enabled)
    VALUES (
        'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        '',
        'A personal blog with website directory123',
        1
    ) ON DUPLICATE KEY UPDATE id=id";

    $pdo->exec($insertDefault);
    echo "<p>✅ Default banner settings inserted!</p>";

    echo "<p><strong>Migration completed successfully!</strong></p>";
    echo "<p>You can now access Banner management at: <a href='" . SITE_URL . "/admin/banner'>" . SITE_URL . "/admin/banner</a></p>";
    echo "<p><a href='" . SITE_URL . "'>← Back to website</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
}

echo "</body></html>";
?>
