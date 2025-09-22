<?php
/**
 * Homepage View
 * Display blog posts with pagination
 */
?>

<!-- Hero Section -->
<div class="hero-section bg-gradient text-white p-4 rounded mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold mb-3">
                <i class="fas fa-fish text-warning"></i> 欢迎来到 <?= SITE_NAME ?>
            </h1>
            <p class="lead mb-3"><?= SITE_DESCRIPTION ?></p>
            <div class="d-flex gap-2">
                <a href="<?= SITE_URL ?>/websites" class="btn btn-warning btn-lg">
                    <i class="fas fa-globe"></i> 浏览网站收录
                </a>
                <a href="#posts" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-down"></i> 阅读文章
                </a>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-blog display-1 text-warning opacity-75"></i>
        </div>
    </div>
</div>

<!-- Featured Posts -->
<?php if (!empty($featuredPosts)): ?>
<div class="featured-posts mb-5">
    <h2 class="h4 mb-3">
        <i class="fas fa-star text-warning"></i> 精选文章
    </h2>
    <div class="row">
        <?php foreach ($featuredPosts as $post): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <?php if ($post['featured_image']): ?>
                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>" 
                     class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>" 
                           class="text-decoration-none">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h5>
                    <?php if ($post['excerpt']): ?>
                    <p class="card-text text-muted small">
                        <?= htmlspecialchars(substr($post['excerpt'], 0, 100)) ?>...
                    </p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i>
                            <?= date('Y-m-d', strtotime($post['published_at'])) ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-eye"></i> <?= $post['view_count'] ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
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
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
}

.post-card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

.post-title a:hover {
    color: #3498db !important;
}

.featured-posts .card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease-in-out;
}
</style>
