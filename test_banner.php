<?php
/**
 * Banner Test Page
 * Test if Banner settings are working correctly
 */

echo "<!DOCTYPE html><html><head><title>Banner Test</title></head><body>";
echo "<h2>Banner Settings Test</h2>";

require_once 'config/config.php';

try {
    // Test database connection
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Database connection successful</p>";
    
    // Check if banner_settings table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'banner_settings'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ banner_settings table exists</p>";
        
        // Get banner settings
        $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($settings) {
            echo "<p>✅ Banner settings found:</p>";
            echo "<ul>";
            echo "<li>Image: " . htmlspecialchars($settings['banner_image']) . "</li>";
            echo "<li>Title: " . htmlspecialchars($settings['banner_title']) . "</li>";
            echo "<li>Subtitle: " . htmlspecialchars($settings['banner_subtitle']) . "</li>";
            echo "<li>Enabled: " . ($settings['banner_enabled'] ? 'Yes' : 'No') . "</li>";
            echo "<li>Parallax: " . ($settings['parallax_enabled'] ? 'Yes' : 'No') . "</li>";
            echo "<li>Opacity: " . $settings['overlay_opacity'] . "</li>";
            echo "</ul>";
        } else {
            echo "<p>❌ No banner settings found in database</p>";
        }
    } else {
        echo "<p>❌ banner_settings table does not exist</p>";
        echo "<p>Please run the migration: <a href='migrate_banner.php'>migrate_banner.php</a></p>";
    }
    
    // Test BannerSettings model
    if (file_exists('app/models/BannerSettings.php')) {
        echo "<p>✅ BannerSettings model file exists</p>";
        
        require_once 'app/models/BannerSettings.php';
        $bannerSettings = new BannerSettings();
        $modelSettings = $bannerSettings->getSettings();
        
        echo "<p>✅ BannerSettings model working:</p>";
        echo "<ul>";
        echo "<li>Image: " . htmlspecialchars($modelSettings['banner_image']) . "</li>";
        echo "<li>Enabled: " . ($modelSettings['banner_enabled'] ? 'Yes' : 'No') . "</li>";
        echo "</ul>";
    } else {
        echo "<p>❌ BannerSettings model file not found</p>";
    }
    
    // Test BannerController
    if (file_exists('app/controllers/BannerController.php')) {
        echo "<p>✅ BannerController file exists</p>";
    } else {
        echo "<p>❌ BannerController file not found</p>";
    }
    
    // Test admin view
    if (file_exists('app/views/admin/banner/index.php')) {
        echo "<p>✅ Banner admin view exists</p>";
    } else {
        echo "<p>❌ Banner admin view not found</p>";
    }
    
    echo "<hr>";
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>If banner_settings table doesn't exist: <a href='migrate_banner.php'>Run Migration</a></li>";
    echo "<li>Access Banner management: <a href='" . SITE_URL . "/admin/banner'>Banner Settings</a></li>";
    echo "<li>View website: <a href='" . SITE_URL . "'>Homepage</a></li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>
