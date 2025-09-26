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
    <!-- Font Awesome 图标 (本地版本) -->
    <link href="<?= SITE_URL ?>/public/fontawesome-free-6.5.1-web/css/all.min.css?v=<?= time() ?>" rel="stylesheet">

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
                                <span id="subtitle" data-typewriter-texts="<?= htmlspecialchars($bannerConfig['banner_subtitle'] ?: (isset($subtitle) ? $subtitle : SITE_DESCRIPTION)) ?>|Welcome to Koi Blog|A personal blog with website directory|Discover amazing content and resources"><?= htmlspecialchars($bannerConfig['banner_subtitle'] ?: (isset($subtitle) ? $subtitle : SITE_DESCRIPTION)) ?></span>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Emergency Fix - 内联版本 -->
    <script>
    console.log('%c🚨 Bootstrap Emergency Fix Loading...', 'color: #ff0000; font-size: 16px; font-weight: bold;');

    (function() {
        'use strict';

        // 防止重复执行
        if (window.bootstrapFixLoaded) {
            console.log('%c⚠️ Bootstrap fix already loaded', 'color: #ff9800;');
            return;
        }
        window.bootstrapFixLoaded = true;

        // 1. 立即抑制所有Bootstrap错误
        console.log('%c🔇 Suppressing Bootstrap errors...', 'color: #2196f3; font-weight: bold;');

        const originalError = console.error;
        console.error = function(...args) {
            const msg = args[0];
            if (typeof msg === 'string' && (
                msg.includes('selector-engine') ||
                msg.includes('Cannot read properties of undefined') ||
                msg.includes('reading \'call\'') ||
                msg.includes('getMultipleElementsFromSelector') ||
                msg.includes('clearMenus')
            )) {
                console.log('%c🔇 Suppressed:', 'color: #ff9800;', msg);
                return;
            }
            originalError.apply(console, args);
        };

        // 2. 全局错误抑制
        window.addEventListener('error', function(e) {
            if (e.message && (
                e.message.includes('selector-engine') ||
                e.message.includes('Cannot read properties of undefined')
            )) {
                console.log('%c🔇 Global error suppressed:', 'color: #ff9800;', e.message);
                e.preventDefault();
                return false;
            }
        });

        // 3. 手动导航按钮修复
        function fixNavigation() {
            console.log('%c🔧 Setting up manual navigation fix...', 'color: #2196f3;');

            const toggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
            console.log('%c🔍 Found ' + toggles.length + ' collapse toggles', 'color: #2196f3;');

            toggles.forEach(function(toggle, index) {
                console.log('%c🔧 Setting up toggle ' + (index + 1), 'color: #2196f3;');

                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('%c🖱️ Navigation toggle clicked!', 'color: #4caf50; font-weight: bold;');

                    const targetSelector = this.getAttribute('data-bs-target');
                    const target = document.querySelector(targetSelector);

                    if (target) {
                        const isShown = target.classList.contains('show');
                        const isCollapsing = target.classList.contains('collapsing');

                        // 防止在动画过程中重复点击
                        if (isCollapsing) {
                            console.log('%c⏳ Animation in progress, ignoring click', 'color: #ff9800;');
                            return;
                        }

                        console.log('%c🔍 Current state - isShown:', 'color: #2196f3;', isShown);

                        if (isShown) {
                            // 关闭菜单
                            target.classList.add('collapsing');
                            target.classList.remove('show');

                            // 设置高度为0来触发动画
                            target.style.height = target.scrollHeight + 'px';
                            setTimeout(() => {
                                target.style.height = '0px';
                            }, 10);

                            // 动画完成后清理
                            setTimeout(() => {
                                target.classList.remove('collapsing');
                                target.style.height = '';
                            }, 350);

                            console.log('%c📤 Navigation collapsed', 'color: #4caf50; font-weight: bold;');
                        } else {
                            // 打开菜单
                            target.classList.add('collapsing');
                            target.style.height = '0px';

                            setTimeout(() => {
                                target.style.height = target.scrollHeight + 'px';
                            }, 10);

                            // 动画完成后添加show类
                            setTimeout(() => {
                                target.classList.remove('collapsing');
                                target.classList.add('show');
                                target.style.height = '';
                            }, 350);

                            console.log('%c📥 Navigation expanded', 'color: #4caf50; font-weight: bold;');
                        }
                    } else {
                        console.log('%c❌ Target not found:', 'color: #f44336;', targetSelector);
                    }
                });
            });

            console.log('%c✅ Manual navigation handlers added!', 'color: #4caf50; font-weight: bold;');

            // 添加点击外部区域关闭菜单功能
            document.addEventListener('click', function(e) {
                const navCollapse = document.querySelector('#navbarNav');
                const navToggler = document.querySelector('.navbar-toggler');

                if (navCollapse && navCollapse.classList.contains('show')) {
                    // 检查点击是否在导航区域外
                    if (!navCollapse.contains(e.target) && !navToggler.contains(e.target)) {
                        console.log('%c🖱️ Clicked outside navigation, closing menu', 'color: #ff9800;');

                        // 关闭菜单
                        navCollapse.classList.add('collapsing');
                        navCollapse.classList.remove('show');

                        navCollapse.style.height = navCollapse.scrollHeight + 'px';
                        setTimeout(() => {
                            navCollapse.style.height = '0px';
                        }, 10);

                        setTimeout(() => {
                            navCollapse.classList.remove('collapsing');
                            navCollapse.style.height = '';
                        }, 350);

                        console.log('%c📤 Navigation closed by outside click', 'color: #4caf50;');
                    }
                }
            });

            console.log('%c✅ Outside click handler added!', 'color: #4caf50; font-weight: bold;');
        }

        // 4. 初始化
        function init() {
            console.log('%c🚀 Initializing Bootstrap fix...', 'color: #2196f3; font-weight: bold;');

            // 立即设置导航修复
            fixNavigation();

            console.log('%c🎉 Bootstrap Emergency Fix completed!', 'color: #4caf50; font-size: 16px; font-weight: bold;');
        }

        // 5. 执行
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            console.log('%c⏳ Waiting for DOM...', 'color: #ff9800;');
        } else {
            console.log('%c🚀 DOM ready, executing now...', 'color: #2196f3;');
            init();
        }

    })();

    console.log('%c✅ Bootstrap Emergency Fix Script Complete!', 'color: #4caf50; font-size: 16px; font-weight: bold;');
    </script>
    <!-- Main JavaScript -->
    <script src="<?= SITE_URL ?>/public/js/main.js"></script>
    <!-- 图片灯箱功能 -->
    <script src="<?= SITE_URL ?>/public/js/lightbox.js"></script>

    <!-- Fluid主题JavaScript -->
    <script src="<?= SITE_URL ?>/public/js/theme-toggle.js"></script>
    <!-- Font Awesome 强制修复脚本 -->
    <script src="<?= SITE_URL ?>/public/js/fontawesome-fix.js"></script>
    <!-- Banner打字机效果 -->
    <script src="<?= SITE_URL ?>/public/js/typewriter-effect.js"></script>
</body>
</html>
