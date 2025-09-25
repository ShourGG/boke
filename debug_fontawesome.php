<?php
// 加载配置
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Font Awesome 调试页面</title>
    
    <!-- 本地Font Awesome -->
    <link href="<?= SITE_URL ?>/public/fontawesome-free-6.5.1-web/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .debug-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .icon-test {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            text-align: center;
            min-width: 80px;
        }
        .icon-test i {
            font-size: 24px;
            margin-bottom: 5px;
            display: block;
        }
        .status {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        pre {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1><i class="fas fa-bug"></i> Font Awesome 调试页面</h1>
    
    <div class="debug-section">
        <h2>配置信息</h2>
        <div class="info status">
            <strong>SITE_URL:</strong> <?= SITE_URL ?><br>
            <strong>Font Awesome CSS路径:</strong> <?= SITE_URL ?>/public/fontawesome-free-6.5.1-web/css/all.min.css<br>
            <strong>当前页面URL:</strong> <?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>
        </div>
    </div>
    
    <div class="debug-section">
        <h2>文件存在性检查</h2>
        <?php
        $fontAwesomePath = __DIR__ . '/public/fontawesome-free-6.5.1-web/css/all.min.css';
        $webfontsPath = __DIR__ . '/public/fontawesome-free-6.5.1-web/webfonts/';
        ?>
        <div class="<?= file_exists($fontAwesomePath) ? 'success' : 'error' ?> status">
            <strong>CSS文件:</strong> <?= file_exists($fontAwesomePath) ? '✅ 存在' : '❌ 不存在' ?><br>
            <strong>路径:</strong> <?= $fontAwesomePath ?>
        </div>
        <div class="<?= is_dir($webfontsPath) ? 'success' : 'error' ?> status">
            <strong>字体目录:</strong> <?= is_dir($webfontsPath) ? '✅ 存在' : '❌ 不存在' ?><br>
            <strong>路径:</strong> <?= $webfontsPath ?>
        </div>
        
        <?php if (is_dir($webfontsPath)): ?>
        <div class="info status">
            <strong>字体文件列表:</strong><br>
            <?php
            $fontFiles = scandir($webfontsPath);
            foreach ($fontFiles as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo "• " . $file . "<br>";
                }
            }
            ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="debug-section">
        <h2>CSS内容检查</h2>
        <?php if (file_exists($fontAwesomePath)): ?>
        <div class="info status">
            <strong>CSS文件大小:</strong> <?= number_format(filesize($fontAwesomePath)) ?> 字节<br>
            <strong>CSS文件前100个字符:</strong><br>
            <pre><?= htmlspecialchars(substr(file_get_contents($fontAwesomePath), 0, 200)) ?>...</pre>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="debug-section">
        <h2>图标测试</h2>
        <div class="icon-test">
            <i class="fas fa-home"></i>
            <span>fa-home</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-user"></i>
            <span>fa-user</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-heart"></i>
            <span>fa-heart</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-star"></i>
            <span>fa-star</span>
        </div>
    </div>
    
    <div class="debug-section">
        <h2>JavaScript检测</h2>
        <div id="js-status" class="status">检测中...</div>
    </div>
    
    <script>
    // 检测Font Awesome是否正确加载
    function checkFontAwesome() {
        const testElement = document.createElement('i');
        testElement.className = 'fas fa-home';
        testElement.style.position = 'absolute';
        testElement.style.left = '-9999px';
        testElement.style.visibility = 'hidden';
        document.body.appendChild(testElement);
        
        const computedStyle = window.getComputedStyle(testElement);
        const fontFamily = computedStyle.getPropertyValue('font-family');
        const fontSize = computedStyle.getPropertyValue('font-size');
        const content = computedStyle.getPropertyValue('content');
        
        document.body.removeChild(testElement);
        
        const statusDiv = document.getElementById('js-status');
        
        let result = '<strong>检测结果:</strong><br>';
        result += 'Font Family: ' + fontFamily + '<br>';
        result += 'Font Size: ' + fontSize + '<br>';
        result += 'Content: ' + content + '<br>';
        
        if (fontFamily && (fontFamily.includes('Font Awesome') || fontFamily.includes('FontAwesome'))) {
            statusDiv.className = 'status success';
            result += '<strong>✅ Font Awesome 加载成功！</strong>';
        } else {
            statusDiv.className = 'status error';
            result += '<strong>❌ Font Awesome 加载失败！</strong>';
        }
        
        statusDiv.innerHTML = result;
    }
    
    // 页面加载完成后检测
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkFontAwesome, 100);
        });
    } else {
        setTimeout(checkFontAwesome, 100);
    }
    
    // 输出调试信息到控制台
    console.log('SITE_URL:', '<?= SITE_URL ?>');
    console.log('Font Awesome CSS URL:', '<?= SITE_URL ?>/public/fontawesome-free-6.5.1-web/css/all.min.css');
    console.log('Current URL:', window.location.href);
    </script>
</body>
</html>
