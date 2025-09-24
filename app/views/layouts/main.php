<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' : '' ?><?= SITE_NAME ?></title>
    <meta name="description" content="<?= isset($description) ? htmlspecialchars($description) : SITE_DESCRIPTION ?>">
    <meta name="keywords" content="<?= isset($keywords) ? htmlspecialchars($keywords) : SITE_KEYWORDS ?>">

    <!-- Material Design 图标 -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Roboto 字体 -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- 自定义样式 -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/material-styles.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/dark-theme.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/animation.css">

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
    <!-- 顶部导航栏 -->
    <header class="app-bar">
        <div class="app-bar-title">
            <a href="<?= SITE_URL ?>" style="color: white; text-decoration: none;">
                <span class="material-icons" style="vertical-align: middle; margin-right: 8px;">pets</span>
                <?= SITE_NAME ?>
            </a>
        </div>

        <nav class="app-bar-nav">
            <a href="<?= SITE_URL ?>">首页</a>
            <a href="<?= SITE_URL ?>/websites">网站收录</a>
            <a href="<?= SITE_URL ?>/search">搜索</a>
            <a href="<?= SITE_URL ?>/admin">管理</a>

            <!-- 主题切换按钮 -->
            <a href="#" id="theme-toggle" class="btn btn-outlined" style="color: white; border-color: white; text-transform: none;">深色模式</a>
        </nav>
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

    <!-- 主内容区域 -->
    <main class="main-content">
        <?= $content ?>
    </main>

    <!-- 页脚 -->
    <footer style="padding: 24px 16px; text-align: center; background: var(--md-surface); color: var(--md-text-secondary); margin-top: 48px; border-top: 1px solid var(--md-divider);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <p style="margin: 0 0 8px 0; font-weight: 500;"><?= SITE_NAME ?></p>
            <p style="margin: 0 0 16px 0; font-size: 0.875rem;"><?= SITE_DESCRIPTION ?></p>
            <p style="margin: 0; font-size: 0.875rem;">
                &copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.
                <a href="<?= SITE_URL ?>/admin" style="color: var(--md-primary); text-decoration: none; margin-left: 16px;">
                    <span class="material-icons" style="font-size: 1rem; vertical-align: middle;">settings</span>
                    管理后台
                </a>
            </p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?= SITE_URL ?>/public/js/theme-toggle.js"></script>
</body>
</html>
