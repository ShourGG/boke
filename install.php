<?php
/**
 * Koi Blog Installation Script
 * One-click installation for Baota Panel deployment
 */

// Start session for multi-step installation
session_start();

// Prevent access if already installed
if (file_exists('config/config.php')) {
    header('Location: index.php');
    exit;
}

// Handle installation steps
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1:
            // Environment check passed, go to step 2
            header('Location: install.php?step=2');
            exit;
            
        case 2:
            // Database configuration
            $result = handleDatabaseConfig();
            if ($result['success']) {
                header('Location: install.php?step=3');
                exit;
            } else {
                $error = $result['error'];
            }
            break;
            
        case 3:
            // Admin account creation
            $result = handleAdminCreation();
            if ($result['success']) {
                header('Location: install.php?step=4');
                exit;
            } else {
                $error = $result['error'];
            }
            break;
    }
}

/**
 * Handle database configuration
 */
function handleDatabaseConfig()
{
    $host = trim($_POST['db_host'] ?? '');
    $name = trim($_POST['db_name'] ?? '');
    $user = trim($_POST['db_user'] ?? '');
    $pass = $_POST['db_pass'] ?? '';
    
    // Validate input
    if (empty($host) || empty($name) || empty($user)) {
        return ['success' => false, 'error' => '请填写所有必需的数据库信息'];
    }
    
    // Test database connection
    try {
        $dsn = "mysql:host={$host};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$name}`");
        
        // Import database schema
        $sql = file_get_contents('database.sql');
        $pdo->exec($sql);

        // Store database config in session for step 3
        $_SESSION['db_config'] = [
            'host' => $host,
            'name' => $name,
            'user' => $user,
            'pass' => $pass
        ];

        return ['success' => true];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => '数据库连接失败: ' . $e->getMessage()];
    }
}

/**
 * Handle admin account creation
 */
function handleAdminCreation()
{
    $username = trim($_POST['admin_username'] ?? '');
    $email = trim($_POST['admin_email'] ?? '');
    $password = $_POST['admin_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $displayName = trim($_POST['display_name'] ?? '');
    
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return ['success' => false, 'error' => '请填写所有必需信息'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => '请输入有效的邮箱地址'];
    }
    
    if (strlen($password) < 6) {
        return ['success' => false, 'error' => '密码至少需要6个字符'];
    }
    
    if ($password !== $confirmPassword) {
        return ['success' => false, 'error' => '两次输入的密码不一致'];
    }
    
    try {
        // Get database config from session
        if (!isset($_SESSION['db_config'])) {
            return ['success' => false, 'error' => '数据库配置丢失，请重新开始安装'];
        }

        $dbConfig = $_SESSION['db_config'];
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Update admin account
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ?, password = ?, display_name = ? WHERE id = 1");
        $stmt->execute([$username, $email, $hashedPassword, $displayName ?: $username]);

        // Create config file after successful admin creation
        $configContent = generateConfigFile($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass']);
        file_put_contents('config/config.php', $configContent);

        // Clear session data
        unset($_SESSION['db_config']);

        return ['success' => true];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => '创建管理员账户失败: ' . $e->getMessage()];
    }
}

/**
 * Generate config file content
 */
function generateConfigFile($host, $name, $user, $pass)
{
    $siteUrl = 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $secretKey = bin2hex(random_bytes(32));
    $passwordSalt = bin2hex(random_bytes(16));
    
    return "<?php
/**
 * Koi Blog Configuration File
 * Generated by installation script
 */

// Database Configuration
define('DB_HOST', '{$host}');
define('DB_NAME', '{$name}');
define('DB_USER', '{$user}');
define('DB_PASS', '{$pass}');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_NAME', 'Koi Blog');
define('SITE_DESCRIPTION', 'A personal blog with website directory');
define('SITE_KEYWORDS', 'blog, personal, website directory');
define('SITE_URL', '{$siteUrl}');
define('ADMIN_EMAIL', 'admin@example.com');

// Security Configuration
define('SECRET_KEY', '{$secretKey}');
define('PASSWORD_SALT', '{$passwordSalt}');

// Upload Configuration
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf,doc,docx');

// Pagination
define('POSTS_PER_PAGE', 10);
define('WEBSITES_PER_PAGE', 20);

// Debug Mode (set to false in production)
define('DEBUG', false);

// Timezone
date_default_timezone_set('Asia/Shanghai');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session
session_start();
";
}

/**
 * Check system requirements
 */
function checkRequirements()
{
    $requirements = [
        'PHP Version >= 7.4' => version_compare(PHP_VERSION, '7.4.0', '>='),
        'PDO Extension' => extension_loaded('pdo'),
        'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
        'Config Directory Writable' => is_writable('config') || mkdir('config', 0755, true),
        'Uploads Directory Writable' => is_writable('uploads') || mkdir('uploads', 0755, true),
    ];
    
    return $requirements;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koi Blog 安装向导</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .install-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .install-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .install-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .install-body {
            padding: 2rem;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.5rem;
            background: #e9ecef;
            color: #6c757d;
            font-weight: bold;
        }
        .step.active {
            background: #3498db;
            color: white;
        }
        .step.completed {
            background: #27ae60;
            color: white;
        }
        .requirement-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .requirement-status {
            margin-left: auto;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <i class="fas fa-fish fa-3x mb-3"></i>
                <h1>Koi Blog</h1>
                <p class="mb-0">安装向导</p>
            </div>
            
            <div class="install-body">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step <?= $step >= 1 ? ($step == 1 ? 'active' : 'completed') : '' ?>">1</div>
                    <div class="step <?= $step >= 2 ? ($step == 2 ? 'active' : 'completed') : '' ?>">2</div>
                    <div class="step <?= $step >= 3 ? ($step == 3 ? 'active' : 'completed') : '' ?>">3</div>
                    <div class="step <?= $step >= 4 ? ($step == 4 ? 'active' : 'completed') : '' ?>">4</div>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($step == 1): ?>
                <!-- Step 1: Environment Check -->
                <h3><i class="fas fa-check-circle"></i> 环境检测</h3>
                <p class="text-muted mb-4">检查服务器环境是否满足运行要求</p>
                
                <?php
                $requirements = checkRequirements();
                $allPassed = true;
                ?>
                
                <?php foreach ($requirements as $name => $passed): ?>
                <div class="requirement-item">
                    <span><?= $name ?></span>
                    <span class="requirement-status">
                        <?php if ($passed): ?>
                            <i class="fas fa-check text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-times text-danger"></i>
                            <?php $allPassed = false; ?>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endforeach; ?>
                
                <div class="text-center mt-4">
                    <?php if ($allPassed): ?>
                    <form method="POST">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-right"></i> 下一步
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        请解决上述问题后刷新页面重试
                    </div>
                    <button onclick="location.reload()" class="btn btn-warning">
                        <i class="fas fa-refresh"></i> 重新检测
                    </button>
                    <?php endif; ?>
                </div>
                
                <?php elseif ($step == 2): ?>
                <!-- Step 2: Database Configuration -->
                <h3><i class="fas fa-database"></i> 数据库配置</h3>
                <p class="text-muted mb-4">请输入您的数据库连接信息</p>
                
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="db_host" class="form-label">数据库主机</label>
                        <input type="text" class="form-control" id="db_host" name="db_host" 
                               value="localhost" required>
                        <div class="invalid-feedback">请输入数据库主机地址</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_name" class="form-label">数据库名称</label>
                        <input type="text" class="form-control" id="db_name" name="db_name" 
                               placeholder="koi_blog" required>
                        <div class="invalid-feedback">请输入数据库名称</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_user" class="form-label">数据库用户名</label>
                        <input type="text" class="form-control" id="db_user" name="db_user" 
                               placeholder="root" required>
                        <div class="invalid-feedback">请输入数据库用户名</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_pass" class="form-label">数据库密码</label>
                        <input type="password" class="form-control" id="db_pass" name="db_pass">
                        <div class="form-text">如果没有密码请留空</div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-database"></i> 创建数据库
                        </button>
                    </div>
                </form>
                
                <?php elseif ($step == 3): ?>
                <!-- Step 3: Admin Account -->
                <h3><i class="fas fa-user-shield"></i> 管理员账户</h3>
                <p class="text-muted mb-4">创建您的管理员账户</p>
                
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="admin_username" class="form-label">用户名</label>
                        <input type="text" class="form-control" id="admin_username" name="admin_username" 
                               placeholder="admin" required>
                        <div class="invalid-feedback">请输入用户名</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">邮箱地址</label>
                        <input type="email" class="form-control" id="admin_email" name="admin_email" 
                               placeholder="admin@example.com" required>
                        <div class="invalid-feedback">请输入有效的邮箱地址</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="display_name" class="form-label">显示名称</label>
                        <input type="text" class="form-control" id="display_name" name="display_name" 
                               placeholder="管理员">
                        <div class="form-text">可选，用于显示的名称</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">密码</label>
                        <input type="password" class="form-control" id="admin_password" name="admin_password" 
                               minlength="6" required>
                        <div class="invalid-feedback">密码至少需要6个字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">确认密码</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                               minlength="6" required>
                        <div class="invalid-feedback">请再次输入密码</div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus"></i> 创建账户
                        </button>
                    </div>
                </form>
                
                <?php elseif ($step == 4): ?>
                <!-- Step 4: Installation Complete -->
                <div class="text-center">
                    <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                    <h3 class="text-success">安装完成！</h3>
                    <p class="text-muted mb-4">恭喜！Koi Blog 已成功安装到您的服务器。</p>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> 重要提示</h6>
                        <p class="mb-0">为了安全起见，请删除 <code>install.php</code> 文件。</p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="index.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-home"></i> 访问网站首页
                        </a>
                        <a href="admin" class="btn btn-success btn-lg">
                            <i class="fas fa-cog"></i> 进入管理后台
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        
        // Password confirmation validation
        const password = document.getElementById('admin_password');
        const confirmPassword = document.getElementById('confirm_password');
        
        if (password && confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('两次输入的密码不一致');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            });
        }
    </script>
</body>
</html>
