<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' : '' ?>管理后台 - <?= SITE_NAME ?></title>

    <!-- CSRF Protection -->
    <?php
    require_once __DIR__ . '/../../core/CSRFProtection.php';
    echo CSRFProtection::getTokenMeta();
    ?>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (本地版本) -->
    <link href="<?= SITE_URL ?>/public/fontawesome-free-6.5.1-web/css/all.min.css?v=<?= time() ?>" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="<?= SITE_URL ?>/public/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">
    <?php if (isset($_SESSION['admin_id'])): ?>
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= SITE_URL ?>/admin">
                <i class="fas fa-cog"></i> <?= SITE_NAME ?> 管理后台
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= isset($currentPage) && $currentPage == 'dashboard' ? 'active' : '' ?>" 
                           href="<?= SITE_URL ?>/admin">
                            <i class="fas fa-tachometer-alt"></i> 仪表盘
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-file-alt"></i> 文章管理
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/posts">所有文章</a></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/posts/create">写文章</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/categories">分类管理</a></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/tags">标签管理</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i> 网站收录
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/websites">所有网站</a></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/websites/create">添加网站</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/website-categories">网站分类</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>/admin/comments">
                            <i class="fas fa-comments"></i> 评论管理
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>/admin/settings">
                            <i class="fas fa-cogs"></i> 系统设置
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>" target="_blank">
                            <i class="fas fa-external-link-alt"></i> 查看网站
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="theme-toggle" id="themeToggle" type="button" title="切换主题">
                            <i class="fas fa-moon"></i>
                            <span class="theme-text d-none d-md-inline">深色</span>
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                            <?= isset($_SESSION['admin_display_name']) ? htmlspecialchars($_SESSION['admin_display_name']) : '管理员' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/profile">
                                <i class="fas fa-user-edit"></i> 个人资料
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/logout">
                                <i class="fas fa-sign-out-alt"></i> 退出登录
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-content">
        <div class="container-fluid">
            <!-- Flash Messages -->
            <?php 
            $flash = isset($flash) ? $flash : [];
            foreach ($flash as $type => $message): 
            ?>
            <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endforeach; ?>

            <!-- Page Content -->
            <?= $content ?>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Login Page Content -->
    <div class="login-wrapper">
        <!-- Flash Messages -->
        <?php 
        $flash = isset($flash) ? $flash : [];
        foreach ($flash as $type => $message): 
        ?>
        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endforeach; ?>
        
        <?= $content ?>
    </div>
    <?php endif; ?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Admin JS -->
    <script src="<?= SITE_URL ?>/public/js/admin.js"></script>
</body>
</html>
