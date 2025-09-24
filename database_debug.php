<?php
/**
 * Database Debug - Deep dive into banner_settings table
 */

echo "<!DOCTYPE html><html><head><title>Database Debug</title></head><body>";
echo "<h2>Database Deep Debug</h2>";

require_once 'config/config.php';

try {
    // Direct PDO connection
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Database connected successfully</p>";
    
    // Show database info
    echo "<h3>Database Information:</h3>";
    echo "<p>Host: " . DB_HOST . "</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
    echo "<p>User: " . DB_USER . "</p>";
    
    // Check table structure
    echo "<h3>Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE banner_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . ($column['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . ($column['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show all records
    echo "<h3>All Records in banner_settings:</h3>";
    $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($records)) {
        echo "<p>❌ No records found in banner_settings table</p>";
        
        // Try to insert a test record
        echo "<h4>Inserting test record...</h4>";
        $stmt = $pdo->prepare("
            INSERT INTO banner_settings 
            (banner_image, banner_title, banner_subtitle, banner_enabled, parallax_enabled, overlay_opacity)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $result = $stmt->execute([
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4',
            'Test Title',
            'Test Subtitle',
            1,
            0,
            0.30
        ]);
        
        if ($result) {
            echo "<p>✅ Test record inserted successfully</p>";
            $lastId = $pdo->lastInsertId();
            echo "<p>New record ID: " . $lastId . "</p>";
            
            // Re-fetch records
            $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id");
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "<p>❌ Failed to insert test record</p>";
        }
    }
    
    if (!empty($records)) {
        echo "<p>Found " . count($records) . " record(s):</p>";
        foreach ($records as $index => $record) {
            echo "<h4>Record #" . ($index + 1) . " (ID: " . $record['id'] . "):</h4>";
            echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
            foreach ($record as $key => $value) {
                echo "<tr>";
                echo "<td style='padding: 5px; font-weight: bold;'>" . htmlspecialchars($key) . "</td>";
                echo "<td style='padding: 5px;'>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
    
    // Test UPDATE operation
    if (!empty($records)) {
        $firstRecord = $records[0];
        echo "<h3>Testing UPDATE Operation:</h3>";
        
        $newOpacity = 0.75; // Different value to test
        $stmt = $pdo->prepare("
            UPDATE banner_settings 
            SET overlay_opacity = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $updateResult = $stmt->execute([$newOpacity, $firstRecord['id']]);
        
        echo "<p>Update query executed: " . ($updateResult ? 'SUCCESS' : 'FAILED') . "</p>";
        echo "<p>Rows affected: " . $stmt->rowCount() . "</p>";
        
        // Check if update worked
        $stmt = $pdo->prepare("SELECT overlay_opacity, updated_at FROM banner_settings WHERE id = ?");
        $stmt->execute([$firstRecord['id']]);
        $updatedRecord = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($updatedRecord) {
            echo "<p>New opacity value: " . $updatedRecord['overlay_opacity'] . "</p>";
            echo "<p>Updated at: " . $updatedRecord['updated_at'] . "</p>";
            
            if (abs($updatedRecord['overlay_opacity'] - $newOpacity) < 0.01) {
                echo "<p>✅ UPDATE operation successful!</p>";
            } else {
                echo "<p>❌ UPDATE operation failed - value not changed</p>";
            }
        }
    }
    
    // Test transaction
    echo "<h3>Testing Transaction:</h3>";
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("UPDATE banner_settings SET banner_title = ? WHERE id = ?");
        $result = $stmt->execute(['Transaction Test Title', $records[0]['id']]);
        
        if ($result) {
            $pdo->commit();
            echo "<p>✅ Transaction committed successfully</p>";
        } else {
            $pdo->rollback();
            echo "<p>❌ Transaction rolled back</p>";
        }
    } catch (Exception $e) {
        $pdo->rollback();
        echo "<p>❌ Transaction error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    // Show final state
    echo "<h3>Final Table State:</h3>";
    $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id");
    $finalRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($finalRecords as $record) {
        echo "<p>ID " . $record['id'] . ": Title='" . htmlspecialchars($record['banner_title']) . "', Opacity=" . $record['overlay_opacity'] . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='simple_banner_test.php'>← Simple Banner Test</a></p>";
echo "<p><a href='test_server.php'>← Server Test</a></p>";

echo "</body></html>";
?>
