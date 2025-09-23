<?php
/**
 * Koi Blog 安装程序
 * 自动化安装向导，配置数据库和管理员账户
 */

// 防止重复安装
if (file_exists('config/config.php')) {
    die('系统已经安装完成！如需重新安装，请删除 config/config.php 文件。');
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// 处理安装步骤
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 2:
            // 数据库连接测试
            $host = $_POST['db_host'] ?? '';
            $name = $_POST['db_name'] ?? '';
            $user = $_POST['db_user'] ?? '';
            $pass = $_POST['db_pass'] ?? '';
            
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // 保存数据库信息到会话
                session_start();
                $_SESSION['db_config'] = compact('host', 'name', 'user', 'pass');
                
                header('Location: install.php?step=3');
                exit;
            } catch (PDOException $e) {
                $error = '数据库连接失败：' . $e->getMessage();
            }
            break;
            
        case 3:
            // 创建数据库表
            session_start();
            if (!isset($_SESSION['db_config'])) {
                header('Location: install.php?step=2');
                exit;
            }
            
            $db_config = $_SESSION['db_config'];
            
            try {
                $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']};charset=utf8mb4", 
                              $db_config['user'], $db_config['pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // 执行数据库脚本
                $sql = file_get_contents('database.sql');
                $pdo->exec($sql);
                
                header('Location: install.php?step=4');
                exit;
            } catch (Exception $e) {
                $error = '数据库表创建失败：' . $e->getMessage();
            }
            break;
            
        case 4:
            // 创建管理员账户
            session_start();
            if (!isset($_SESSION['db_config'])) {
                header('Location: install.php?step=2');
                exit;
            }
            
            $username = $_POST['admin_username'] ?? '';
            $email = $_POST['admin_email'] ?? '';
            $password = $_POST['admin_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($username) || empty($email) || empty($password)) {
                $error = '请填写所有必填字段';
            } elseif ($password !== $confirm_password) {
                $error = '两次输入的密码不一致';
            } elseif (strlen($password) < 6) {
                $error = '密码长度至少6位';
            } else {
                $db_config = $_SESSION['db_config'];
                
                try {
                    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']};charset=utf8mb4", 
                                  $db_config['user'], $db_config['pass']);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // 创建管理员账户
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO admins (username, email, password, display_name) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$username, $email, $password_hash, $username]);
                    
                    header('Location: install.php?step=5');
                    exit;
                } catch (Exception $e) {
                    $error = '管理员账户创建失败：' . $e->getMessage();
                }
            }
            break;
            
        case 5:
            // 生成配置文件
            session_start();
            if (!isset($_SESSION['db_config'])) {
                header('Location: install.php?step=2');
                exit;
            }
            
            $site_name = $_POST['site_name'] ?? 'Koi Blog';
            $site_url = $_POST['site_url'] ?? 'http://localhost';
            $admin_email = $_POST['admin_email'] ?? '';
            
            $db_config = $_SESSION['db_config'];
            
            // 生成安全密钥
            $secret_key = bin2hex(random_bytes(32));
            $password_salt = bin2hex(random_bytes(16));
            
            $config_content = "<?php\n";
            $config_content .= "/**\n * Koi Blog 配置文件\n * 由安装程序自动生成\n */\n\n";
            $config_content .= "// 数据库配置\n";
            $config_content .= "define('DB_HOST', '{$db_config['host']}');\n";
            $config_content .= "define('DB_NAME', '{$db_config['name']}');\n";
            $config_content .= "define('DB_USER', '{$db_config['user']}');\n";
            $config_content .= "define('DB_PASS', '{$db_config['pass']}');\n";
            $config_content .= "define('DB_CHARSET', 'utf8mb4');\n\n";
            $config_content .= "// 站点配置\n";
            $config_content .= "define('SITE_NAME', '" . addslashes($site_name) . "');\n";
            $config_content .= "define('SITE_DESCRIPTION', '个人博客与网站目录');\n";
            $config_content .= "define('SITE_KEYWORDS', 'blog, 博客, 网站目录');\n";
            $config_content .= "define('SITE_URL', '" . rtrim($site_url, '/') . "');\n";
            $config_content .= "define('ADMIN_EMAIL', '" . addslashes($admin_email) . "');\n\n";
            $config_content .= "// 安全配置\n";
            $config_content .= "define('SECRET_KEY', '$secret_key');\n";
            $config_content .= "define('PASSWORD_SALT', '$password_salt');\n\n";
            $config_content .= "// 上传配置\n";
            $config_content .= "define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB\n";
            $config_content .= "define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf,doc,docx');\n\n";
            $config_content .= "// 分页配置\n";
            $config_content .= "define('POSTS_PER_PAGE', 10);\n";
            $config_content .= "define('WEBSITES_PER_PAGE', 20);\n\n";
            $config_content .= "// 调试模式（生产环境请设为false）\n";
            $config_content .= "define('DEBUG', false);\n\n";
            $config_content .= "// 时区设置\n";
            $config_content .= "date_default_timezone_set('Asia/Shanghai');\n\n";
            $config_content .= "// 会话配置\n";
            $config_content .= "ini_set('session.cookie_httponly', 1);\n";
            $config_content .= "ini_set('session.use_only_cookies', 1);\n";
            $config_content .= "ini_set('session.cookie_secure', 0); // HTTPS环境请设为1\n\n";
            $config_content .= "// 启动会话\n";
            $config_content .= "session_start();\n";
            
            if (file_put_contents('config/config.php', $config_content)) {
                // 清理会话
                session_destroy();
                header('Location: install.php?step=6');
                exit;
            } else {
                $error = '配置文件写入失败，请检查 config 目录权限';
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koi Blog 安装向导</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .install-container { max-width: 600px; margin: 50px auto; }
        .install-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .install-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px 15px 0 0; text-align: center; }
        .install-body { padding: 40px; }
        .step-indicator { display: flex; justify-content: center; margin-bottom: 30px; }
        .step { width: 40px; height: 40px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; margin: 0 10px; font-weight: bold; }
        .step.active { background: #667eea; color: white; }
        .step.completed { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="install-card">
                <div class="install-header">
                    <h1><i class="fas fa-fish"></i> Koi Blog</h1>
                    <p class="mb-0">安装向导</p>
                </div>
                <div class="install-body">
                    <!-- 步骤指示器 -->
                    <div class="step-indicator">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <div class="step <?= $i < $step ? 'completed' : ($i == $step ? 'active' : '') ?>">
                                <?= $i ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <?php
                    switch ($step) {
                        case 1:
                            include 'install_steps/step1_welcome.php';
                            break;
                        case 2:
                            include 'install_steps/step2_database.php';
                            break;
                        case 3:
                            include 'install_steps/step3_tables.php';
                            break;
                        case 4:
                            include 'install_steps/step4_admin.php';
                            break;
                        case 5:
                            include 'install_steps/step5_config.php';
                            break;
                        case 6:
                            include 'install_steps/step6_complete.php';
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
