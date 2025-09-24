<?php
/**
 * Cleanup Banner Data - Remove duplicate records
 * Keep only the latest record and clean up the database
 */

echo "<!DOCTYPE html><html><head><title>Cleanup Banner Data</title></head><body>";
echo "<h2>🧹 Banner Data Cleanup</h2>";

// Load configuration
require_once 'config/config.php';

try {
    // Direct PDO connection
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Database connected successfully</p>";
    
    // Show current records before cleanup
    echo "<h3>Before Cleanup:</h3>";
    $stmt = $pdo->query("SELECT id, overlay_opacity, created_at, updated_at FROM banner_settings ORDER BY id ASC");
    $allRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Opacity</th><th>Created</th><th>Updated</th></tr>";
    foreach ($allRecords as $record) {
        echo "<tr>";
        echo "<td>" . $record['id'] . "</td>";
        echo "<td>" . $record['overlay_opacity'] . "</td>";
        echo "<td>" . $record['created_at'] . "</td>";
        echo "<td>" . $record['updated_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p>Total records: " . count($allRecords) . "</p>";
    
    if (isset($_GET['confirm_cleanup'])) {
        // Get the latest record
        $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
        $latestRecord = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($latestRecord) {
            echo "<h3>Cleanup Process:</h3>";
            echo "<p>Latest record ID: " . $latestRecord['id'] . "</p>";
            
            // Delete all records except the latest one
            $stmt = $pdo->prepare("DELETE FROM banner_settings WHERE id != ?");
            $result = $stmt->execute([$latestRecord['id']]);
            
            if ($result) {
                $deletedCount = $stmt->rowCount();
                echo "<p>✅ Deleted $deletedCount old records</p>";
                
                // Reset auto increment to start from 1
                $pdo->exec("ALTER TABLE banner_settings AUTO_INCREMENT = 1");
                
                // Update the remaining record to ID = 1
                $stmt = $pdo->prepare("UPDATE banner_settings SET id = 1 WHERE id = ?");
                $stmt->execute([$latestRecord['id']]);
                
                echo "<p>✅ Reset record ID to 1</p>";
                echo "<p>✅ Reset auto increment counter</p>";
                
                // Show final state
                echo "<h3>After Cleanup:</h3>";
                $stmt = $pdo->query("SELECT id, overlay_opacity, created_at, updated_at FROM banner_settings ORDER BY id ASC");
                $finalRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>ID</th><th>Opacity</th><th>Created</th><th>Updated</th></tr>";
                foreach ($finalRecords as $record) {
                    echo "<tr>";
                    echo "<td>" . $record['id'] . "</td>";
                    echo "<td><strong>" . $record['overlay_opacity'] . "</strong></td>";
                    echo "<td>" . $record['created_at'] . "</td>";
                    echo "<td>" . $record['updated_at'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<p>Total records: " . count($finalRecords) . "</p>";
                
                echo "<p style='color: green;'><strong>✅ Cleanup completed successfully!</strong></p>";
                echo "<p>Now both admin panel and test page will work with the same single record.</p>";
                
            } else {
                echo "<p style='color: red;'>❌ Cleanup failed!</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ No records found to clean up!</p>";
        }
        
    } else {
        echo "<h3>Cleanup Action:</h3>";
        echo "<p>This will:</p>";
        echo "<ul>";
        echo "<li>Keep only the latest record (ID: " . (count($allRecords) > 0 ? max(array_column($allRecords, 'id')) : 'N/A') . ")</li>";
        echo "<li>Delete all older records</li>";
        echo "<li>Reset the remaining record ID to 1</li>";
        echo "<li>Reset auto increment counter</li>";
        echo "</ul>";
        echo "<p><strong>⚠️ This action cannot be undone!</strong></p>";
        echo "<p><a href='cleanup_banner_data.php?confirm_cleanup=1' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🧹 Confirm Cleanup</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='debug_banner_data.php'>🔍 Debug Banner Data</a></p>";
echo "<p><a href='simple_banner_test.php'>🧪 Simple Banner Test</a></p>";
echo "</body></html>";
?>
