<?php
/**
 * Server Test - Check if files are properly deployed
 */

echo "<!DOCTYPE html><html><head><title>Server Test</title></head><body>";
echo "<h2>Server Deployment Test</h2>";

echo "<h3>File Existence Check:</h3>";
$files_to_check = [
    'simple_banner_test.php',
    'debug_banner.php', 
    'migrate_banner.php',
    'test_banner.php',
    'config/config.php',
    'app/core/Database.php',
    'app/models/BannerSettings.php',
    'app/controllers/BannerController.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "<p>✅ " . htmlspecialchars($file) . " - EXISTS</p>";
    } else {
        echo "<p>❌ " . htmlspecialchars($file) . " - NOT FOUND</p>";
    }
}

echo "<h3>Current Directory:</h3>";
echo "<p>" . htmlspecialchars(getcwd()) . "</p>";

echo "<h3>Directory Listing:</h3>";
echo "<pre>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo htmlspecialchars($file) . "\n";
    }
}
echo "</pre>";

echo "<h3>Git Status (if available):</h3>";
if (file_exists('.git')) {
    echo "<p>✅ Git repository detected</p>";
    
    // Try to get git log
    $git_log = shell_exec('git log --oneline -5 2>&1');
    if ($git_log) {
        echo "<p>Recent commits:</p>";
        echo "<pre>" . htmlspecialchars($git_log) . "</pre>";
    }
    
    // Try to get git status
    $git_status = shell_exec('git status --porcelain 2>&1');
    if ($git_status !== null) {
        echo "<p>Git status:</p>";
        echo "<pre>" . htmlspecialchars($git_status) . "</pre>";
    }
} else {
    echo "<p>❌ No Git repository found</p>";
}

echo "<h3>PHP Info:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p>Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";

echo "<h3>Quick Links:</h3>";
echo "<ul>";
echo "<li><a href='simple_banner_test.php'>Simple Banner Test</a></li>";
echo "<li><a href='debug_banner.php'>Debug Banner</a></li>";
echo "<li><a href='migrate_banner.php'>Migrate Banner</a></li>";
echo "<li><a href='test_banner.php'>Test Banner</a></li>";
echo "</ul>";

echo "</body></html>";
?>
