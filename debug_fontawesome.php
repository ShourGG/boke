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
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
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

    <div class="debug-section">
        <h2>实时图标渲染测试</h2>
        <p>如果下面的图标显示为方块或问号，说明字体文件加载失败：</p>
        <div style="font-size: 48px; line-height: 1.5; text-align: center; background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <div style="margin: 10px 0;">
                <i class="fas fa-home" style="color: #007bff;"></i>
                <i class="fas fa-user" style="color: #28a745;"></i>
                <i class="fas fa-heart" style="color: #dc3545;"></i>
                <i class="fas fa-star" style="color: #ffc107;"></i>
                <i class="fas fa-cog" style="color: #6c757d;"></i>
            </div>
            <div style="margin: 10px 0;">
                <i class="far fa-heart" style="color: #e91e63;"></i>
                <i class="far fa-star" style="color: #ff9800;"></i>
                <i class="far fa-user" style="color: #9c27b0;"></i>
                <i class="far fa-envelope" style="color: #2196f3;"></i>
                <i class="far fa-clock" style="color: #00bcd4;"></i>
            </div>
            <div style="margin: 10px 0;">
                <i class="fab fa-github" style="color: #333;"></i>
                <i class="fab fa-twitter" style="color: #1da1f2;"></i>
                <i class="fab fa-facebook" style="color: #1877f2;"></i>
                <i class="fab fa-google" style="color: #4285f4;"></i>
                <i class="fab fa-apple" style="color: #000;"></i>
            </div>
        </div>
        <div style="margin-top: 15px; font-size: 14px; color: #666;">
            <strong>预期效果：</strong>应该看到彩色的图标，而不是方块或字母<br>
            <strong>如果看到方块：</strong>说明字体文件无法加载<br>
            <strong>如果看到字母：</strong>说明CSS类名解析失败
        </div>
    </div>

    <div class="debug-section">
        <h2>CSS优先级检测</h2>
        <div id="css-priority-test" class="status info">检测中...</div>
        <div style="margin-top: 10px;">
            <button onclick="testCSSPriority()" class="btn btn-primary">重新检测CSS优先级</button>
            <button onclick="forceFontAwesome()" class="btn btn-warning">强制应用Font Awesome样式</button>
        </div>
    </div>
    
    <script>
    // 炫酷的控制台输出工具
    class CoolConsole {
        constructor() {
            this.colors = {
                primary: '#00ff88',
                secondary: '#ff6b6b',
                accent: '#4ecdc4',
                warning: '#ffa726',
                error: '#f44336',
                success: '#4caf50',
                info: '#2196f3',
                purple: '#9c27b0',
                cyan: '#00bcd4'
            };
            this.init();
        }

        init() {
            this.clearConsole();
            this.showBanner();
            this.startDiagnostics();
        }

        clearConsole() {
            console.clear();
        }

        showBanner() {
            const banner = `
    ███████╗ ██████╗ ███╗   ██╗████████╗     █████╗ ██╗    ██╗███████╗███████╗ ██████╗ ███╗   ███╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║╚══██╔══╝    ██╔══██╗██║    ██║██╔════╝██╔════╝██╔═══██╗████╗ ████║██╔════╝
    █████╗  ██║   ██║██╔██╗ ██║   ██║       ███████║██║ █╗ ██║█████╗  ███████╗██║   ██║██╔████╔██║█████╗
    ██╔══╝  ██║   ██║██║╚██╗██║   ██║       ██╔══██║██║███╗██║██╔══╝  ╚════██║██║   ██║██║╚██╔╝██║██╔══╝
    ██║     ╚██████╔╝██║ ╚████║   ██║       ██║  ██║╚███╔███╔╝███████╗███████║╚██████╔╝██║ ╚═╝ ██║███████╗
    ╚═╝      ╚═════╝ ╚═╝  ╚═══╝   ╚═╝       ╚═╝  ╚═╝ ╚══╝╚══╝ ╚══════╝╚══════╝ ╚═════╝ ╚═╝     ╚═╝╚══════╝
                                        🔧 DIAGNOSTIC TOOL v2.0 🔧
            `;
            console.log(`%c${banner}`, `color: ${this.colors.primary}; font-family: monospace; font-size: 10px;`);
            console.log(`%c🚀 老王出品 - Font Awesome 炫酷诊断工具`, `color: ${this.colors.accent}; font-size: 16px; font-weight: bold;`);
        }

        log(message, type = 'info', icon = '📝') {
            const color = this.colors[type] || this.colors.info;
            console.log(`%c${icon} ${message}`, `color: ${color}; font-weight: bold;`);
        }

        logVersion(name, version, status = 'success') {
            const icons = {
                success: '✅',
                warning: '⚠️',
                error: '❌',
                loading: '⏳'
            };
            const colors = {
                success: this.colors.success,
                warning: this.colors.warning,
                error: this.colors.error,
                loading: this.colors.info
            };

            console.log(
                `%c${icons[status]} %c${name}%c v${version}`,
                `color: ${colors[status]}; font-size: 14px;`,
                `color: ${this.colors.primary}; font-weight: bold; font-size: 14px;`,
                `color: ${this.colors.secondary}; font-weight: bold; font-size: 14px;`
            );
        }

        logGroup(title, callback) {
            console.group(`%c🔍 ${title}`, `color: ${this.colors.purple}; font-size: 14px; font-weight: bold;`);
            callback();
            console.groupEnd();
        }

        logTable(data) {
            console.table(data);
        }

        async startDiagnostics() {
            this.log('开始系统诊断...', 'info', '🔍');

            // 延迟效果，增加炫酷感
            await this.delay(500);

            this.logGroup('📦 系统版本信息', () => {
                this.logVersion('Font Awesome', '6.5.1', 'success');
                this.logVersion('Bootstrap', '5.3.2', 'success');
                this.logVersion('jQuery', '3.7.1', 'success');
                this.logVersion('PHP', '<?= phpversion() ?>', 'success');
            });

            await this.delay(300);

            this.logGroup('🌐 网络资源检测', () => {
                this.checkResourceLoading();
            });

            await this.delay(300);

            this.logGroup('🎨 Font Awesome 详细检测', () => {
                this.checkFontAwesome();
            });

            await this.delay(300);

            this.logGroup('🔧 CSS 样式分析', () => {
                this.analyzeCSSStyles();
            });
        }

        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        checkResourceLoading() {
            const resources = [
                { name: 'Font Awesome CSS', url: '<?= SITE_URL ?>/public/fontawesome-free-6.5.1-web/css/all.min.css' },
                { name: 'Bootstrap CSS', url: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' },
                { name: 'Main CSS', url: '<?= SITE_URL ?>/public/css/style.css' }
            ];

            resources.forEach(resource => {
                this.checkResource(resource.name, resource.url);
            });
        }

        checkResource(name, url) {
            const link = document.querySelector(`link[href*="${url.split('/').pop()}"]`);
            if (link) {
                this.log(`${name}: 已加载`, 'success', '✅');
                this.log(`  └─ URL: ${url}`, 'info', '🔗');
            } else {
                this.log(`${name}: 未找到`, 'error', '❌');
            }
        }

        checkFontAwesome() {
            // 创建测试元素
            const testElements = [
                { class: 'fas fa-home', name: 'Solid Home' },
                { class: 'far fa-heart', name: 'Regular Heart' },
                { class: 'fab fa-github', name: 'Brand GitHub' }
            ];

            testElements.forEach(test => {
                const element = document.createElement('i');
                element.className = test.class;
                element.style.position = 'absolute';
                element.style.left = '-9999px';
                document.body.appendChild(element);

                const style = window.getComputedStyle(element);
                const fontFamily = style.getPropertyValue('font-family');
                const content = style.getPropertyValue('content');

                document.body.removeChild(element);

                const isLoaded = fontFamily && fontFamily.includes('Font Awesome');
                const hasContent = content && content !== 'normal' && content !== 'none';

                if (isLoaded && hasContent) {
                    this.log(`${test.name}: 完美加载`, 'success', '🎯');
                    this.log(`  ├─ 字体: ${fontFamily}`, 'info', '📝');
                    this.log(`  └─ 内容: ${content}`, 'info', '🎨');
                } else {
                    this.log(`${test.name}: 加载异常`, 'error', '💥');
                    this.log(`  ├─ 字体: ${fontFamily}`, 'warning', '⚠️');
                    this.log(`  └─ 内容: ${content}`, 'warning', '⚠️');
                }
            });
        }

        analyzeCSSStyles() {
            // 检查CSS规则
            const stylesheets = Array.from(document.styleSheets);
            let fontAwesomeRules = 0;

            stylesheets.forEach(sheet => {
                try {
                    const rules = Array.from(sheet.cssRules || sheet.rules || []);
                    rules.forEach(rule => {
                        if (rule.selectorText && rule.selectorText.includes('fa-')) {
                            fontAwesomeRules++;
                        }
                    });
                } catch (e) {
                    // 跨域CSS无法访问
                }
            });

            this.log(`发现 ${fontAwesomeRules} 个 Font Awesome CSS 规则`, 'info', '📊');

            // 检查字体加载状态
            if (document.fonts) {
                document.fonts.ready.then(() => {
                    const fontAwesomeFonts = Array.from(document.fonts).filter(font =>
                        font.family.includes('Font Awesome')
                    );

                    this.log(`已加载 ${fontAwesomeFonts.length} 个 Font Awesome 字体`, 'success', '🔤');

                    fontAwesomeFonts.forEach(font => {
                        this.log(`  └─ ${font.family} (${font.weight})`, 'info', '📝');
                    });
                });
            }
        }
    }

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
            setTimeout(() => {
                checkFontAwesome();
                // 启动炫酷控制台
                new CoolConsole();
            }, 100);
        });
    } else {
        setTimeout(() => {
            checkFontAwesome();
            // 启动炫酷控制台
            new CoolConsole();
        }, 100);
    }

    // CSS优先级检测
    function testCSSPriority() {
        const testDiv = document.getElementById('css-priority-test');
        testDiv.innerHTML = '检测中...';

        const testIcon = document.createElement('i');
        testIcon.className = 'fas fa-home';
        testIcon.style.position = 'absolute';
        testIcon.style.left = '-9999px';
        document.body.appendChild(testIcon);

        const styles = window.getComputedStyle(testIcon);
        const fontFamily = styles.fontFamily;
        const fontWeight = styles.fontWeight;
        const content = styles.content;
        const fontSize = styles.fontSize;

        document.body.removeChild(testIcon);

        let result = '<strong>CSS样式检测结果：</strong><br>';
        result += `字体族: ${fontFamily}<br>`;
        result += `字体粗细: ${fontWeight}<br>`;
        result += `内容: ${content}<br>`;
        result += `字体大小: ${fontSize}<br>`;

        // 检查是否有样式冲突
        const hasConflict = !fontFamily.includes('Font Awesome') || content === 'normal';

        if (hasConflict) {
            testDiv.className = 'status error';
            result += '<br><strong>❌ 检测到CSS冲突！</strong><br>';
            result += '可能的原因：<br>';
            result += '• 其他CSS覆盖了Font Awesome样式<br>';
            result += '• CSS加载顺序问题<br>';
            result += '• 字体文件路径错误<br>';
        } else {
            testDiv.className = 'status success';
            result += '<br><strong>✅ CSS样式正常！</strong>';
        }

        testDiv.innerHTML = result;
    }

    // 强制应用Font Awesome样式
    function forceFontAwesome() {
        console.log('%c🔧 强制修复Font Awesome样式...', 'color: #ff6b6b; font-size: 16px; font-weight: bold;');

        // 创建强制样式
        const forceStyle = document.createElement('style');
        forceStyle.id = 'force-fontawesome';
        forceStyle.innerHTML = `
            /* 强制Font Awesome样式 */
            .fas, .fa-solid {
                font-family: "Font Awesome 6 Free" !important;
                font-weight: 900 !important;
                font-style: normal !important;
                font-variant: normal !important;
                text-rendering: auto !important;
                line-height: 1 !important;
                -webkit-font-smoothing: antialiased !important;
                -moz-osx-font-smoothing: grayscale !important;
            }

            .far, .fa-regular {
                font-family: "Font Awesome 6 Free" !important;
                font-weight: 400 !important;
                font-style: normal !important;
                font-variant: normal !important;
                text-rendering: auto !important;
                line-height: 1 !important;
                -webkit-font-smoothing: antialiased !important;
                -moz-osx-font-smoothing: grayscale !important;
            }

            .fab, .fa-brands {
                font-family: "Font Awesome 6 Brands" !important;
                font-weight: 400 !important;
                font-style: normal !important;
                font-variant: normal !important;
                text-rendering: auto !important;
                line-height: 1 !important;
                -webkit-font-smoothing: antialiased !important;
                -moz-osx-font-smoothing: grayscale !important;
            }

            /* 确保图标不被其他样式覆盖 */
            i[class*="fa-"]:before {
                display: inline-block !important;
                text-rendering: auto !important;
                -webkit-font-smoothing: antialiased !important;
            }
        `;

        // 移除旧的强制样式
        const oldForceStyle = document.getElementById('force-fontawesome');
        if (oldForceStyle) {
            oldForceStyle.remove();
        }

        // 添加新的强制样式
        document.head.appendChild(forceStyle);

        console.log('%c✅ 强制样式已应用！', 'color: #4caf50; font-size: 14px; font-weight: bold;');

        // 重新检测
        setTimeout(() => {
            testCSSPriority();
            checkFontAwesome();
        }, 500);

        alert('强制样式已应用！请检查图标是否正常显示。');
    }
    </script>
</body>
</html>
