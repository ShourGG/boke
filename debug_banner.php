<?php
/**
 * Banner Debug Page
 * Test Banner settings save functionality
 */

echo "<!DOCTYPE html><html><head><title>Banner Debug</title></head><body>";
echo "<h2>Banner Settings Debug</h2>";

require_once 'config/config.php';
require_once 'app/models/BannerSettings.php';

try {
    $bannerSettings = new BannerSettings();
    
    // Test form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h3>POST Data Received:</h3>";
        echo "<pre>" . print_r($_POST, true) . "</pre>";
        
        $result = $bannerSettings->updateSettings($_POST);
        
        echo "<h3>Update Result:</h3>";
        echo "<p>Result: " . ($result ? 'SUCCESS' : 'FAILED') . "</p>";
        
        // Get updated settings
        $settings = $bannerSettings->getSettings();
        echo "<h3>Current Settings After Update:</h3>";
        echo "<pre>" . print_r($settings, true) . "</pre>";
    }
    
    // Get current settings
    $settings = $bannerSettings->getSettings();
    echo "<h3>Current Banner Settings:</h3>";
    echo "<pre>" . print_r($settings, true) . "</pre>";
    
    // Test form
    echo "<h3>Test Form:</h3>";
    echo "<form method='POST'>";
    echo "<p>Banner Image URL: <input type='url' name='banner_image' value='" . htmlspecialchars($settings['banner_image']) . "' style='width: 400px;'></p>";
    echo "<p>Banner Title: <input type='text' name='banner_title' value='" . htmlspecialchars($settings['banner_title']) . "' style='width: 300px;'></p>";
    echo "<p>Banner Subtitle: <input type='text' name='banner_subtitle' value='" . htmlspecialchars($settings['banner_subtitle']) . "' style='width: 400px;'></p>";
    echo "<p>Overlay Opacity: <input type='range' name='overlay_opacity' min='0' max='1' step='0.05' value='" . $settings['overlay_opacity'] . "'> <span id='opacity_display'>" . round($settings['overlay_opacity'] * 100) . "%</span></p>";
    echo "<p><label><input type='checkbox' name='banner_enabled' " . ($settings['banner_enabled'] ? 'checked' : '') . "> Enable Banner</label></p>";
    echo "<p><label><input type='checkbox' name='parallax_enabled' " . ($settings['parallax_enabled'] ? 'checked' : '') . "> Enable Parallax</label></p>";
    echo "<p><button type='submit'>Save Settings</button></p>";
    echo "</form>";
    
    // Test database connection
    echo "<h3>Database Test:</h3>";
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check table structure
    $stmt = $pdo->query("DESCRIBE banner_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>✅ Database connected</p>";
    echo "<p>✅ banner_settings table structure:</p>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check current data
    $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
    $currentData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($currentData) {
        echo "<p>✅ Current data in database:</p>";
        echo "<pre>" . print_r($currentData, true) . "</pre>";
    } else {
        echo "<p>❌ No data found in banner_settings table</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<script>";
echo "document.querySelector('input[name=\"overlay_opacity\"]').addEventListener('input', function() {";
echo "  document.getElementById('opacity_display').textContent = Math.round(this.value * 100) + '%';";
echo "});";
echo "</script>";

echo "<hr>";
echo "<p><a href='" . SITE_URL . "/admin/banner'>← Back to Banner Settings</a></p>";
echo "<p><a href='" . SITE_URL . "'>← Back to Website</a></p>";

echo "</body></html>";
?>
