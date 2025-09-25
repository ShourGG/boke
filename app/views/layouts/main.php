<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' : '' ?><?= SITE_NAME ?></title>
    <meta name="description" content="<?= isset($description) ? htmlspecialchars($description) : SITE_DESCRIPTION ?>">
    <meta name="keywords" content="<?= isset($keywords) ? htmlspecialchars($keywords) : SITE_KEYWORDS ?>">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🐟</text></svg>">
    <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🐟</text></svg>">

    <!-- Bootstrap CSS (Fluid主题依赖) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 图标 (多CDN备用 + 本地备用) -->
    <link id="fontawesome-primary" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link id="fontawesome-backup" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css" rel="stylesheet" media="none">

    <!-- Font Awesome 加载检测和备用方案 -->
    <script>
    (function() {
        'use strict';

        let fontAwesomeLoaded = false;
        let checkAttempts = 0;
        const maxAttempts = 10;

        function checkFontAwesome() {
            checkAttempts++;

            // 创建测试元素
            const testElement = document.createElement('i');
            testElement.className = 'fas fa-home';
            testElement.style.position = 'absolute';
            testElement.style.left = '-9999px';
            testElement.style.visibility = 'hidden';
            testElement.style.fontSize = '16px';
            document.body.appendChild(testElement);

            const computedStyle = window.getComputedStyle(testElement);
            const fontFamily = computedStyle.getPropertyValue('font-family');

            // 检查是否加载成功
            const isLoaded = fontFamily && (
                fontFamily.includes('Font Awesome') ||
                fontFamily.includes('FontAwesome') ||
                fontFamily.includes('"Font Awesome')
            );

            document.body.removeChild(testElement);

            if (isLoaded) {
                fontAwesomeLoaded = true;
                console.log('Font Awesome loaded successfully');
                return true;
            }

            // 如果主CDN失败，尝试备用CDN
            if (checkAttempts === 3) {
                console.warn('Primary Font Awesome CDN failed, trying backup');
                const backup = document.getElementById('fontawesome-backup');
                if (backup) {
                    backup.media = 'all';
                }
            }

            // 如果所有CDN都失败，尝试本地备用CSS
            if (checkAttempts === 7) {
                console.warn('All Font Awesome CDNs failed, trying local fallback CSS');
                const localFallback = document.createElement('link');
                localFallback.rel = 'stylesheet';
                localFallback.href = '<?= SITE_URL ?>/public/fontawesome/fontawesome-local.css';
                localFallback.id = 'fontawesome-local';
                document.head.appendChild(localFallback);
            }

            // 如果本地CSS也失败，启用icon-fallback
            if (checkAttempts >= maxAttempts) {
                console.error('All Font Awesome options failed, enabling Unicode fallback');
                if (window.iconFallback) {
                    window.iconFallback.config.debug = true;
                    window.iconFallback.reset();
                    window.iconFallback.fixIcons();
                }
                return false;
            }

            // 继续检查
            setTimeout(checkFontAwesome, 500);
            return false;
        }

        // 开始检查
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(checkFontAwesome, 100);
            });
        } else {
            setTimeout(checkFontAwesome, 100);
        }

        // 导出状态供其他脚本使用
        window.fontAwesomeStatus = {
            isLoaded: function() { return fontAwesomeLoaded; },
            getAttempts: function() { return checkAttempts; }
        };
    })();
    </script>

    <!-- 主要样式文件 -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/style.css">

    <!-- Fluid主题样式 -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/material-styles.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/dark-theme.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/animation.css">

    <!-- UI增强样式 -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/ui-enhancements.css">

    <!-- 字体和卡片设计修复 (最后加载以确保优先级) -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/typography-fix.css">

    <!-- 图片灯箱样式 -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/lightbox.css">

    <!-- SEO Meta Tags -->
    <meta property="og:title" content="<?= isset($title) ? htmlspecialchars($title) : SITE_NAME ?>">
    <meta property="og:description" content="<?= isset($description) ? htmlspecialchars($description) : SITE_DESCRIPTION ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= SITE_URL . $_SERVER['REQUEST_URI'] ?>">

    <?php if (isset($canonical)): ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
    <?php endif; ?>
</head>
<body data-theme="light">
    <!-- Fluid主题头部区域 -->
    <header>
        <div class="header-inner" style="height: 100vh;">
            <!-- Fluid主题导航栏 -->
            <nav class="navbar navbar-expand-lg scrolling-navbar">
                <div class="container">
                    <a class="navbar-brand" href="<?= SITE_URL ?>">
                        <i class="fas fa-fish"></i> <?= SITE_NAME ?>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <div class="animated-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE_URL ?>">
                                    <i class="fas fa-home"></i> 首页
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE_URL ?>/websites">
                                    <i class="fas fa-globe"></i> 网站收录
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE_URL ?>/search">
                                    <i class="fas fa-search"></i> 搜索
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE_URL ?>/admin">
                                    <i class="fas fa-cog"></i> 管理
                                </a>
                            </li>
                        </ul>

                        <!-- 主题切换按钮 -->
                        <button id="theme-toggle" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-moon"></i> 深色模式
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Fluid主题Banner -->
            <?php
            // 获取Banner设置
            require_once 'app/models/BannerSettings.php';
            $bannerSettings = new BannerSettings();
            $bannerConfig = $bannerSettings->getSettings();

            if ($bannerConfig['banner_enabled']):
            ?>
            <div id="banner" class="banner"
                 <?= $bannerConfig['parallax_enabled'] ? 'parallax="true"' : '' ?>
                 style="background: url('<?= htmlspecialchars($bannerConfig['banner_image']) ?>') no-repeat center center; background-size: cover;">
                <div class="full-bg-img">
                    <div class="mask d-flex align-items-center justify-content-center"
                         style="background-color: rgba(0, 0, 0, <?= $bannerConfig['overlay_opacity'] ?>);">
                        <div class="banner-text text-center fade-in-up">
                            <?php if ($bannerConfig['banner_title']): ?>
                                <div class="h1 mb-3">
                                    <?= htmlspecialchars($bannerConfig['banner_title']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="h2">
                                <span id="subtitle"><?= htmlspecialchars($bannerConfig['banner_subtitle'] ?: (isset($subtitle) ? $subtitle : SITE_DESCRIPTION)) ?></span>
                            </div>
                        </div>

                        <!-- 滚动箭头 -->
                        <div class="scroll-down-bar">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php
    $flash = isset($flash) ? $flash : [];
    foreach ($flash as $type => $message):
    ?>
    <div class="card" style="margin: 16px; padding: 12px; background-color: <?= $type === 'error' ? '#ffebee' : '#e8f5e8' ?>; border-left: 4px solid <?= $type === 'error' ? '#f44336' : '#4caf50' ?>;">
        <span style="color: <?= $type === 'error' ? '#c62828' : '#2e7d32' ?>;"><?= htmlspecialchars($message) ?></span>
    </div>
    <?php endforeach; ?>

    <!-- Fluid主题主内容区域 -->
    <main>
        <div class="container nopadding-x-md">
            <div id="board">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-10 m-auto">
                            <?= $content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Fluid主题页脚 -->
    <footer class="text-center py-4" style="background-color: var(--navbar-bg-color); color: var(--navbar-text-color); margin-top: 2rem;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= SITE_NAME ?></h5>
                    <p><?= SITE_DESCRIPTION ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
                    <p>
                        <a href="<?= SITE_URL ?>/admin" style="color: var(--navbar-text-color);">
                            <i class="fas fa-cog"></i> 管理后台
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap冲突修复 (必须在Bootstrap之前加载) -->
    <script src="<?= SITE_URL ?>/public/js/bootstrap-fix.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Main JavaScript -->
    <script src="<?= SITE_URL ?>/public/js/main.js"></script>
    <!-- 图片灯箱功能 -->
    <script src="<?= SITE_URL ?>/public/js/lightbox.js"></script>
    <!-- 图标备用系统 -->
    <script src="<?= SITE_URL ?>/public/js/icon-fallback.js"></script>
    <!-- Fluid主题JavaScript -->
    <script src="<?= SITE_URL ?>/public/js/theme-toggle.js"></script>
</body>
</html>
