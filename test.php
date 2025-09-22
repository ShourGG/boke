<?php
echo "PHP is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";

// Test file existence
$files = ['index.php', 'install.php', 'app', 'config'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}
?>
