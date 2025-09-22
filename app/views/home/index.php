<?php
/**
 * Homepage View
 * Display blog posts with pagination
 */
?>

<!-- Hero Section -->
<div class="hero-section text-white p-5 rounded-4 mb-5 position-relative overflow-hidden">
    <div class="hero-bg-animation"></div>
    <div class="row align-items-center position-relative">
        <div class="col-lg-8">
            <div class="hero-content">
                <h1 class="display-4 fw-bold mb-4 hero-title">
                    <i class="fas fa-fish text-warning me-3 bounce-animation"></i>
                    欢迎来到 <?= SITE_NAME ?>
                </h1>
                <p class="lead mb-4 hero-subtitle"><?= SITE_DESCRIPTION ?></p>
                <div class="hero-stats mb-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="stat-number"><?= $posts['total'] ?? 0 ?></h3>
                                <p class="stat-label">篇文章</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="stat-number"><?= $websiteCount ?? 0 ?></h3>
                                <p class="stat-label">个网站</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="stat-number"><?= $totalViews ?? 0 ?></h3>
                                <p class="stat-label">次访问</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-buttons d-flex flex-wrap gap-3">
                    <a href="<?= SITE_URL ?>/websites" class="btn btn-warning btn-lg px-4 py-3 rounded-pill">
                        <i class="fas fa-globe me-2"></i> 浏览网站收录
                    </a>
                    <a href="#posts" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill">
                        <i class="fas fa-arrow-down me-2"></i> 阅读文章
                    </a>
                    <a href="<?= SITE_URL ?>/admin" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill">
                        <i class="fas fa-cog me-2"></i> 管理后台
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-center">
            <div class="hero-icon-container">
                <i class="fas fa-blog hero-main-icon text-warning"></i>
                <div class="floating-icons">
                    <i class="fas fa-pen-fancy floating-icon icon-1"></i>
                    <i class="fas fa-heart floating-icon icon-2"></i>
                    <i class="fas fa-star floating-icon icon-3"></i>
                    <i class="fas fa-code floating-icon icon-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Posts -->
<?php if (!empty($featuredPosts)): ?>
<div class="featured-posts mb-5">
    <div class="section-header text-center mb-5">
        <h2 class="section-title">
            <i class="fas fa-star text-warning me-2"></i> 精选文章
        </h2>
        <p class="section-subtitle text-muted">博主精心挑选的优质内容</p>
        <div class="section-divider"></div>
    </div>
    <div class="row g-4">
        <?php foreach ($featuredPosts as $index => $post): ?>
        <div class="col-lg-4 col-md-6">
            <div class="featured-card h-100 position-relative">
                <div class="featured-badge">
                    <i class="fas fa-crown"></i>
                </div>
                <?php if ($post['featured_image']): ?>
                <div class="featured-image-container">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>"
                         class="featured-image" alt="<?= htmlspecialchars($post['title']) ?>">
                    <div class="image-overlay"></div>
                </div>
                <?php else: ?>
                <div class="featured-image-container no-image">
                    <div class="default-image-bg">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="image-overlay"></div>
                </div>
                <?php endif; ?>
                <div class="featured-content">
                    <div class="featured-meta mb-2">
                        <span class="featured-date">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?= date('m月d日', strtotime($post['published_at'])) ?>
                        </span>
                        <span class="featured-views">
                            <i class="fas fa-eye me-1"></i> <?= $post['view_count'] ?>
                        </span>
                    </div>
                    <h3 class="featured-title">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    <?php if ($post['excerpt']): ?>
                    <p class="featured-excerpt">
                        <?= htmlspecialchars(substr($post['excerpt'], 0, 120)) ?>
                        <?php if (strlen($post['excerpt']) > 120): ?>...<?php endif; ?>
                    </p>
                    <?php endif; ?>
                    <div class="featured-footer">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>"
                           class="read-more-btn">
                            阅读全文 <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<!-- Quick Start Section when no featured posts -->
<div class="quick-start-section mb-5">
    <div class="section-header text-center mb-4">
        <h2 class="section-title">
            <i class="fas fa-rocket text-primary me-2"></i> 快速开始
        </h2>
        <p class="section-subtitle text-muted">探索博客的精彩内容</p>
        <div class="section-divider"></div>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="quick-start-card text-center">
                <div class="quick-start-icon">
                    <i class="fas fa-blog"></i>
                </div>
                <h4>阅读文章</h4>
                <p class="text-muted">浏览最新的博客文章和技术分享</p>
                <a href="#posts" class="btn btn-outline-primary">开始阅读</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="quick-start-card text-center">
                <div class="quick-start-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h4>网站收录</h4>
                <p class="text-muted">发现有趣的网站和在线工具</p>
                <a href="<?= SITE_URL ?>/websites" class="btn btn-outline-primary">浏览网站</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="quick-start-card text-center">
                <div class="quick-start-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h4>关于博主</h4>
                <p class="text-muted">了解更多关于博主的信息</p>
                <a href="<?= SITE_URL ?>/about" class="btn btn-outline-primary">了解更多</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Posts Section -->
<div id="posts" class="posts-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="fas fa-newspaper"></i> 最新文章
        </h2>
        <div class="text-muted">
            共 <?= $posts['total'] ?> 篇文章
        </div>
    </div>
    
    <?php if (!empty($posts['data'])): ?>
        <?php foreach ($posts['data'] as $post): ?>
        <article class="post-card card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <?php if ($post['featured_image']): ?>
                    <div class="col-md-3">
                        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>" 
                             class="img-fluid rounded" alt="<?= htmlspecialchars($post['title']) ?>"
                             style="height: 150px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                    <?php else: ?>
                    <div class="col-12">
                    <?php endif; ?>
                        <h3 class="post-title h5 mb-2">
                            <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h3>
                        
                        <div class="post-meta mb-2">
                            <span class="text-muted me-3">
                                <i class="fas fa-calendar"></i>
                                <?= date('Y年m月d日', strtotime($post['published_at'])) ?>
                            </span>
                            
                            <?php if (isset($post['category_name'])): ?>
                            <span class="me-3">
                                <a href="<?= SITE_URL ?>/category/<?= htmlspecialchars($post['category_slug']) ?>" 
                                   class="badge text-decoration-none"
                                   style="background-color: <?= htmlspecialchars($post['category_color'] ?? '#3498db') ?>">
                                    <i class="fas fa-folder"></i> <?= htmlspecialchars($post['category_name']) ?>
                                </a>
                            </span>
                            <?php endif; ?>
                            
                            <span class="text-muted me-3">
                                <i class="fas fa-eye"></i> <?= $post['view_count'] ?>
                            </span>
                            
                            <?php if ($post['comment_count'] > 0): ?>
                            <span class="text-muted">
                                <i class="fas fa-comments"></i> <?= $post['comment_count'] ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($post['excerpt']): ?>
                        <p class="post-excerpt text-muted mb-3">
                            <?= htmlspecialchars(substr($post['excerpt'], 0, 200)) ?>
                            <?php if (strlen($post['excerpt']) > 200): ?>...<?php endif; ?>
                        </p>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-right"></i> 阅读全文
                            </a>
                            
                            <?php if ($post['is_featured']): ?>
                            <span class="badge bg-warning">
                                <i class="fas fa-star"></i> 精选
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
        
        <!-- Pagination -->
        <?php if ($posts['last_page'] > 1): ?>
        <nav aria-label="文章分页">
            <ul class="pagination justify-content-center">
                <!-- Previous Page -->
                <?php if ($posts['current_page'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= SITE_URL ?>/?page=<?= $posts['current_page'] - 1 ?>">
                        <i class="fas fa-chevron-left"></i> 上一页
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- Page Numbers -->
                <?php
                $start = max(1, $posts['current_page'] - 2);
                $end = min($posts['last_page'], $posts['current_page'] + 2);
                
                if ($start > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= SITE_URL ?>/?page=1">1</a>
                </li>
                <?php if ($start > 2): ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= $i == $posts['current_page'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= SITE_URL ?>/?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if ($end < $posts['last_page']): ?>
                <?php if ($end < $posts['last_page'] - 1): ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="<?= SITE_URL ?>/?page=<?= $posts['last_page'] ?>"><?= $posts['last_page'] ?></a>
                </li>
                <?php endif; ?>
                
                <!-- Next Page -->
                <?php if ($posts['current_page'] < $posts['last_page']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= SITE_URL ?>/?page=<?= $posts['current_page'] + 1 ?>">
                        下一页 <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">暂无文章</h4>
            <p class="text-muted">博主还没有发布任何文章，请稍后再来查看。</p>
        </div>
    <?php endif; ?>
</div>

<style>
/* Hero Section Styles */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.hero-bg-animation {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    animation: float 20s ease-in-out infinite;
}

.hero-title {
    animation: slideInUp 1s ease-out;
}

.hero-subtitle {
    animation: slideInUp 1s ease-out 0.2s both;
}

.hero-stats {
    animation: slideInUp 1s ease-out 0.4s both;
}

.hero-buttons {
    animation: slideInUp 1s ease-out 0.6s both;
}

.bounce-animation {
    animation: bounce 2s infinite;
}

.hero-main-icon {
    font-size: 8rem;
    opacity: 0.8;
    animation: pulse 3s ease-in-out infinite;
}

.floating-icons {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 200px;
}

.floating-icon {
    position: absolute;
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.6);
    animation: float 4s ease-in-out infinite;
}

.icon-1 { top: 20%; left: 20%; animation-delay: 0s; }
.icon-2 { top: 20%; right: 20%; animation-delay: 1s; }
.icon-3 { bottom: 20%; left: 20%; animation-delay: 2s; }
.icon-4 { bottom: 20%; right: 20%; animation-delay: 3s; }

.stat-item {
    padding: 1rem;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    margin: 0.5rem 0;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: #ffd700;
}

.stat-label {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Section Headers */
.section-header {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.section-divider {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    margin: 0 auto;
    border-radius: 2px;
}

/* Featured Posts */
.featured-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.featured-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.featured-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #333;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    z-index: 2;
    box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
}

.featured-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.featured-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.featured-card:hover .featured-image {
    transform: scale(1.1);
}

.no-image .default-image-bg {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: rgba(255, 255, 255, 0.8);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
}

.featured-content {
    padding: 2rem;
}

.featured-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: #666;
}

.featured-title {
    font-size: 1.4rem;
    font-weight: bold;
    margin: 1rem 0;
    line-height: 1.4;
}

.featured-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.featured-title a:hover {
    color: #667eea;
}

.featured-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.read-more-btn {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.read-more-btn:hover {
    color: #764ba2;
    transform: translateX(5px);
}

/* Quick Start Section */
.quick-start-card {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.quick-start-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.quick-start-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
}

.quick-start-card h4 {
    color: #333;
    margin-bottom: 1rem;
    font-weight: bold;
}

/* Post Cards */
.post-card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

.post-title a:hover {
    color: #3498db !important;
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.8;
        transform: scale(1);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        padding: 2rem !important;
    }

    .section-title {
        font-size: 2rem;
    }

    .hero-main-icon {
        font-size: 4rem;
    }

    .floating-icons {
        display: none;
    }

    .stat-number {
        font-size: 1.5rem;
    }

    .hero-buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .hero-buttons .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
