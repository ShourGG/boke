<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' : '' ?><?= SITE_NAME ?></title>
    <meta name="description" content="<?= isset($description) ? htmlspecialchars($description) : SITE_DESCRIPTION ?>">
    <meta name="keywords" content="<?= isset($keywords) ? htmlspecialchars($keywords) : SITE_KEYWORDS ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= SITE_URL ?>/public/css/style.css" rel="stylesheet">
    
    <!-- SEO Meta Tags -->
    <meta property="og:title" content="<?= isset($title) ? htmlspecialchars($title) : SITE_NAME ?>">
    <meta property="og:description" content="<?= isset($description) ? htmlspecialchars($description) : SITE_DESCRIPTION ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= SITE_URL . $_SERVER['REQUEST_URI'] ?>">
    
    <?php if (isset($canonical)): ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= SITE_URL ?>">
                <i class="fas fa-fish"></i> <?= SITE_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-folder"></i> 分类
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Categories will be loaded here -->
                            <li><a class="dropdown-item" href="#">技术分享</a></li>
                            <li><a class="dropdown-item" href="#">生活随笔</a></li>
                        </ul>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex" method="GET" action="<?= SITE_URL ?>/search">
                    <input class="form-control me-2" type="search" name="q" placeholder="搜索..." 
                           value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php 
    $flash = isset($flash) ? $flash : [];
    foreach ($flash as $type => $message): 
    ?>
    <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>

    <!-- Main Content -->
    <main class="container my-4">
        <div class="row">
            <!-- Content Area -->
            <div class="col-lg-8">
                <?= $content ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- About Widget -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-user"></i> 关于博主</h5>
                        </div>
                        <div class="card-body">
                            <p>欢迎来到我的个人博客！这里分享技术心得和生活感悟。</p>
                        </div>
                    </div>
                    
                    <!-- Recent Posts Widget -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-clock"></i> 最新文章</h5>
                        </div>
                        <div class="card-body">
                            <!-- Recent posts will be loaded here -->
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-file-alt text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <a href="#" class="text-decoration-none">示例文章标题</a>
                                    <small class="text-muted d-block">2024-01-22</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categories Widget -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-folder"></i> 文章分类</h5>
                        </div>
                        <div class="card-body">
                            <!-- Categories will be loaded here -->
                            <a href="#" class="badge bg-secondary text-decoration-none me-1 mb-1">技术分享</a>
                            <a href="#" class="badge bg-secondary text-decoration-none me-1 mb-1">生活随笔</a>
                        </div>
                    </div>
                    
                    <!-- Tags Widget -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-tags"></i> 标签云</h5>
                        </div>
                        <div class="card-body">
                            <!-- Tags will be loaded here -->
                            <a href="#" class="badge bg-primary text-decoration-none me-1 mb-1">PHP</a>
                            <a href="#" class="badge bg-primary text-decoration-none me-1 mb-1">JavaScript</a>
                            <a href="#" class="badge bg-primary text-decoration-none me-1 mb-1">MySQL</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= SITE_NAME ?></h5>
                    <p><?= SITE_DESCRIPTION ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
                    <p>
                        <a href="<?= SITE_URL ?>/admin" class="text-light">
                            <i class="fas fa-cog"></i> 管理后台
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= SITE_URL ?>/public/js/main.js"></script>
</body>
</html>
