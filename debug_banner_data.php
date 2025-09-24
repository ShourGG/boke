<?php
/**
 * Debug Banner Data - Check actual database content
 * This script will show exactly what's in the database
 */

echo "<!DOCTYPE html><html><head><title>Debug Banner Data</title></head><body>";
echo "<h2>🔍 Banner Data Debug</h2>";

// Load configuration
require_once 'config/config.php';

try {
    // Direct PDO connection
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Database connected successfully</p>";
    
    echo "<h3>Database Configuration:</h3>";
    echo "<ul>";
    echo "<li>Host: " . DB_HOST . "</li>";
    echo "<li>Database: " . DB_NAME . "</li>";
    echo "<li>User: " . DB_USER . "</li>";
    echo "</ul>";
    
    // Check table structure
    echo "<h3>Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE banner_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Get ALL records (not just latest)
    echo "<h3>ALL Records in banner_settings:</h3>";
    $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id ASC");
    $allRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($allRecords)) {
        echo "<p>❌ No records found in banner_settings table!</p>";
    } else {
        echo "<p>Found " . count($allRecords) . " record(s):</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr>";
        echo "<th>ID</th><th>Image</th><th>Title</th><th>Subtitle</th>";
        echo "<th>Enabled</th><th>Parallax</th><th>Opacity</th>";
        echo "<th>Created</th><th>Updated</th>";
        echo "</tr>";
        
        foreach ($allRecords as $record) {
            echo "<tr>";
            echo "<td>" . $record['id'] . "</td>";
            echo "<td>" . htmlspecialchars(substr($record['banner_image'], 0, 50)) . "...</td>";
            echo "<td>" . htmlspecialchars($record['banner_title']) . "</td>";
            echo "<td>" . htmlspecialchars($record['banner_subtitle']) . "</td>";
            echo "<td>" . ($record['banner_enabled'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . ($record['parallax_enabled'] ? 'Yes' : 'No') . "</td>";
            echo "<td><strong style='color: red;'>" . $record['overlay_opacity'] . "</strong></td>";
            echo "<td>" . $record['created_at'] . "</td>";
            echo "<td>" . $record['updated_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Get latest record specifically
    echo "<h3>Latest Record (ORDER BY id DESC LIMIT 1):</h3>";
    $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
    $latestRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($latestRecord) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        foreach ($latestRecord as $key => $value) {
            $style = ($key === 'overlay_opacity') ? "style='background: yellow; font-weight: bold;'" : "";
            echo "<tr><td><strong>" . htmlspecialchars($key) . "</strong></td><td $style>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No latest record found!</p>";
    }
    
    // Test a manual update
    echo "<h3>Manual Update Test:</h3>";
    if (isset($_GET['test_update'])) {
        $testOpacity = 0.99;
        $stmt = $pdo->prepare("UPDATE banner_settings SET overlay_opacity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = 1");
        $result = $stmt->execute([$testOpacity]);
        
        if ($result) {
            echo "<p>✅ Manual update successful! Set opacity to $testOpacity</p>";
            echo "<p><a href='debug_banner_data.php'>Refresh to see changes</a></p>";
        } else {
            echo "<p>❌ Manual update failed!</p>";
        }
    } else {
        echo "<p><a href='debug_banner_data.php?test_update=1'>🧪 Test Manual Update (set opacity to 0.99)</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='simple_banner_test.php'>← Back to Simple Banner Test</a></p>";
echo "</body></html>";
?>
