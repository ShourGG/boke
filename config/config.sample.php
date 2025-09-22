<?php
/**
 * Configuration File Template
 * Copy this file to config.php and modify the values
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'koi_blog');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_NAME', 'Koi Blog');
define('SITE_DESCRIPTION', 'A personal blog with website directory');
define('SITE_KEYWORDS', 'blog, personal, website directory');
define('SITE_URL', 'http://38.12.4.139');
define('ADMIN_EMAIL', 'admin@example.com');

// Security Configuration
define('SECRET_KEY', 'your-secret-key-here-change-this');
define('PASSWORD_SALT', 'your-password-salt-here');

// Upload Configuration
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf,doc,docx');

// Pagination
define('POSTS_PER_PAGE', 10);
define('WEBSITES_PER_PAGE', 20);

// Debug Mode (set to false in production)
define('DEBUG', true);

// Timezone
date_default_timezone_set('Asia/Shanghai');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session
session_start();
