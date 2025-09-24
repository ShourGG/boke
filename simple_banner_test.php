<?php
/**
 * Simple Banner Test - Direct Database Access
 * No complex class dependencies
 */

echo "<!DOCTYPE html><html><head><title>Simple Banner Test</title></head><body>";
echo "<h2>Simple Banner Settings Test</h2>";

// Load configuration
require_once 'config/config.php';

try {
    // Direct PDO connection
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Database connected successfully</p>";
    
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'banner_settings'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ banner_settings table exists</p>";
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h3>Processing Form Submission...</h3>";
            echo "<p>POST Data: <pre>" . print_r($_POST, true) . "</pre></p>";
            
            // Prepare data
            $bannerImage = filter_var($_POST['banner_image'] ?? '', FILTER_SANITIZE_URL);
            $bannerTitle = htmlspecialchars($_POST['banner_title'] ?? '', ENT_QUOTES, 'UTF-8');
            $bannerSubtitle = htmlspecialchars($_POST['banner_subtitle'] ?? '', ENT_QUOTES, 'UTF-8');
            $bannerEnabled = isset($_POST['banner_enabled']) ? 1 : 0;
            $parallaxEnabled = isset($_POST['parallax_enabled']) ? 1 : 0;
            $overlayOpacity = floatval($_POST['overlay_opacity'] ?? 0.30);
            
            echo "<p>Processed Data:</p>";
            echo "<ul>";
            echo "<li>Image: " . htmlspecialchars($bannerImage) . "</li>";
            echo "<li>Title: " . htmlspecialchars($bannerTitle) . "</li>";
            echo "<li>Subtitle: " . htmlspecialchars($bannerSubtitle) . "</li>";
            echo "<li>Enabled: " . ($bannerEnabled ? 'Yes' : 'No') . "</li>";
            echo "<li>Parallax: " . ($parallaxEnabled ? 'Yes' : 'No') . "</li>";
            echo "<li>Opacity: " . $overlayOpacity . "</li>";
            echo "</ul>";
            
            // Check if record exists (use same logic as display - get latest record)
            $stmt = $pdo->prepare("SELECT id FROM banner_settings ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $exists = $stmt->fetch();
            
            if ($exists) {
                // Update existing
                echo "<p>Updating existing record (ID: " . $exists['id'] . ")...</p>";
                $stmt = $pdo->prepare("
                    UPDATE banner_settings 
                    SET banner_image = ?, banner_title = ?, banner_subtitle = ?, 
                        banner_enabled = ?, parallax_enabled = ?, overlay_opacity = ?,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                $result = $stmt->execute([
                    $bannerImage, $bannerTitle, $bannerSubtitle, 
                    $bannerEnabled, $parallaxEnabled, $overlayOpacity,
                    $exists['id']
                ]);
            } else {
                // Insert new
                echo "<p>Inserting new record...</p>";
                $stmt = $pdo->prepare("
                    INSERT INTO banner_settings 
                    (banner_image, banner_title, banner_subtitle, banner_enabled, parallax_enabled, overlay_opacity)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $result = $stmt->execute([
                    $bannerImage, $bannerTitle, $bannerSubtitle, 
                    $bannerEnabled, $parallaxEnabled, $overlayOpacity
                ]);
            }
            
            echo "<p><strong>Save Result: " . ($result ? 'SUCCESS ✅' : 'FAILED ❌') . "</strong></p>";
            
            if ($result) {
                echo "<p style='color: green;'>Settings saved successfully!</p>";
            } else {
                echo "<p style='color: red;'>Failed to save settings!</p>";
            }
        }
        
        // Get current settings
        $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($settings) {
            echo "<h3>Current Settings:</h3>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            foreach ($settings as $key => $value) {
                echo "<tr><td><strong>" . htmlspecialchars($key) . "</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
            }
            echo "</table>";
            
            // Test form
            echo "<h3>Test Form:</h3>";
            echo "<form method='POST' style='max-width: 600px;'>";
            echo "<p><label>Banner Image URL:<br><input type='url' name='banner_image' value='" . htmlspecialchars($settings['banner_image']) . "' style='width: 100%; padding: 5px;'></label></p>";
            echo "<p><label>Banner Title:<br><input type='text' name='banner_title' value='" . htmlspecialchars($settings['banner_title']) . "' style='width: 100%; padding: 5px;'></label></p>";
            echo "<p><label>Banner Subtitle:<br><input type='text' name='banner_subtitle' value='" . htmlspecialchars($settings['banner_subtitle']) . "' style='width: 100%; padding: 5px;'></label></p>";
            echo "<p><label>Overlay Opacity:<br><input type='range' name='overlay_opacity' min='0' max='1' step='0.05' value='" . $settings['overlay_opacity'] . "' style='width: 100%;'> <span id='opacity_display'>" . round($settings['overlay_opacity'] * 100) . "%</span></label></p>";
            echo "<p><label><input type='checkbox' name='banner_enabled' " . ($settings['banner_enabled'] ? 'checked' : '') . "> Enable Banner</label></p>";
            echo "<p><label><input type='checkbox' name='parallax_enabled' " . ($settings['parallax_enabled'] ? 'checked' : '') . "> Enable Parallax</label></p>";
            echo "<p><button type='submit' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Save Settings</button></p>";
            echo "</form>";
            
        } else {
            echo "<p>❌ No settings found in database</p>";
            echo "<p>Please run migration first: <a href='migrate_banner.php'>migrate_banner.php</a></p>";
        }
        
    } else {
        echo "<p>❌ banner_settings table does not exist</p>";
        echo "<p>Please run migration: <a href='migrate_banner.php'>migrate_banner.php</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<script>";
echo "const opacitySlider = document.querySelector('input[name=\"overlay_opacity\"]');";
echo "const opacityDisplay = document.getElementById('opacity_display');";
echo "if (opacitySlider && opacityDisplay) {";
echo "  opacitySlider.addEventListener('input', function() {";
echo "    opacityDisplay.textContent = Math.round(this.value * 100) + '%';";
echo "  });";
echo "}";
echo "</script>";

echo "<hr>";
echo "<p><a href='" . SITE_URL . "/admin/banner'>← Back to Banner Settings</a></p>";
echo "<p><a href='" . SITE_URL . "'>← Back to Website</a></p>";

echo "</body></html>";
?>
