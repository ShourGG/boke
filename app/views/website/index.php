<?php
/**
 * Website Directory Index View
 * Display website collection with categories and search
 */
?>

<!-- Hero Section -->
<div class="hero-section bg-gradient text-white p-4 rounded mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-3">
                <i class="fas fa-globe text-warning"></i> 网站收录目录
            </h1>
            <p class="lead mb-3">精选优质网站资源，发现有趣实用的在线工具和服务</p>
            <div class="d-flex gap-2">
                <a href="<?= SITE_URL ?>/websites/submit" class="btn btn-warning btn-lg">
                    <i class="fas fa-plus"></i> 提交网站
                </a>
                <a href="#websites" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-down"></i> 浏览网站
                </a>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-sitemap display-1 text-warning opacity-75"></i>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="search-filter-section mb-4">
    <div class="row">
        <div class="col-md-8">
            <form method="GET" action="<?= SITE_URL ?>/websites" class="d-flex">
                <input type="text" name="search" class="form-control form-control-lg" 
                       placeholder="搜索网站..." 
                       value="<?= isset($searchQuery) ? htmlspecialchars($searchQuery) : '' ?>">
                <button type="submit" class="btn btn-primary btn-lg ms-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-lg dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown">
                    <i class="fas fa-filter"></i> 
                    <?= $currentCategory ? htmlspecialchars($currentCategory['name']) : '所有分类' ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= SITE_URL ?>/websites">所有分类</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <?php foreach ($categories as $category): ?>
                    <li>
                        <a class="dropdown-item" href="<?= SITE_URL ?>/websites?category=<?= $category['id'] ?>">
                            <i class="<?= htmlspecialchars($category['icon']) ?>" 
                               style="color: <?= htmlspecialchars($category['color']) ?>"></i>
                            <?= htmlspecialchars($category['name']) ?>
                            <span class="badge bg-secondary ms-2"><?= $category['website_count'] ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Featured Websites -->
<?php if (!empty($featuredWebsites) && !isset($searchQuery)): ?>
<div class="featured-websites mb-5">
    <h2 class="h4 mb-3">
        <i class="fas fa-star text-warning"></i> 精选推荐
    </h2>
    <div class="row">
        <?php foreach ($featuredWebsites as $website): ?>
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card h-100 border-0 shadow-sm featured-card">
                <div class="card-body text-center p-3">
                    <?php if ($website['favicon']): ?>
                    <img src="<?= htmlspecialchars($website['favicon']) ?>" 
                         alt="<?= htmlspecialchars($website['title']) ?>" 
                         class="website-favicon mb-2">
                    <?php else: ?>
                    <i class="fas fa-globe fa-2x text-primary mb-2"></i>
                    <?php endif; ?>
                    
                    <h6 class="card-title">
                        <a href="<?= SITE_URL ?>/website/<?= $website['id'] ?>" 
                           class="text-decoration-none stretched-link">
                            <?= htmlspecialchars($website['title']) ?>
                        </a>
                    </h6>
                    
                    <small class="text-muted">
                        <i class="fas fa-mouse-pointer"></i> <?= $website['click_count'] ?>
                    </small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Categories Grid -->
<?php if (!isset($searchQuery) && !$currentCategory): ?>
<div class="categories-grid mb-5">
    <h2 class="h4 mb-3">
        <i class="fas fa-th-large"></i> 分类浏览
    </h2>
    <div class="row">
        <?php foreach ($categories as $category): ?>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="<?= SITE_URL ?>/websites?category=<?= $category['id'] ?>" 
               class="text-decoration-none">
                <div class="card category-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="<?= htmlspecialchars($category['icon']) ?> fa-2x mb-3" 
                           style="color: <?= htmlspecialchars($category['color']) ?>"></i>
                        <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                        <p class="card-text text-muted small">
                            <?= htmlspecialchars($category['description']) ?>
                        </p>
                        <span class="badge bg-primary"><?= $category['website_count'] ?> 个网站</span>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Websites List -->
<div id="websites" class="websites-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="fas fa-list"></i> 
            <?php if (isset($searchQuery)): ?>
                搜索结果
            <?php elseif ($currentCategory): ?>
                <?= htmlspecialchars($currentCategory['name']) ?>
            <?php else: ?>
                所有网站
            <?php endif; ?>
        </h2>
        <div class="text-muted">
            共 <?= $websites['total'] ?> 个网站
        </div>
    </div>
    
    <?php if (!empty($websites['data'])): ?>
        <div class="row">
            <?php foreach ($websites['data'] as $website): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card website-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <?php if ($website['favicon']): ?>
                            <img src="<?= htmlspecialchars($website['favicon']) ?>" 
                                 alt="<?= htmlspecialchars($website['title']) ?>" 
                                 class="website-favicon me-3">
                            <?php else: ?>
                            <i class="fas fa-globe fa-2x text-primary me-3"></i>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">
                                    <a href="<?= SITE_URL ?>/website/<?= $website['id'] ?>" 
                                       class="website-title text-decoration-none">
                                        <?= htmlspecialchars($website['title']) ?>
                                    </a>
                                </h5>
                                
                                <div class="website-url mb-2">
                                    <small class="text-success">
                                        <i class="fas fa-link"></i>
                                        <?= htmlspecialchars(parse_url($website['url'], PHP_URL_HOST)) ?>
                                    </small>
                                </div>
                            </div>
                            
                            <?php if ($website['is_featured']): ?>
                            <span class="badge bg-warning">
                                <i class="fas fa-star"></i>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="website-description text-muted mb-3">
                            <?= htmlspecialchars(substr($website['description'], 0, 120)) ?>
                            <?php if (strlen($website['description']) > 120): ?>...<?php endif; ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php if ($website['category_name']): ?>
                                <span class="badge me-2" 
                                      style="background-color: <?= htmlspecialchars($website['category_color']) ?>">
                                    <i class="<?= htmlspecialchars($website['category_icon']) ?>"></i>
                                    <?= htmlspecialchars($website['category_name']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="website-stats">
                                <small class="text-muted">
                                    <i class="fas fa-mouse-pointer"></i> <?= $website['click_count'] ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="<?= SITE_URL ?>/websites/click?id=<?= $website['id'] ?>" 
                               class="btn btn-primary btn-sm me-2" target="_blank">
                                <i class="fas fa-external-link-alt"></i> 访问网站
                            </a>
                            <a href="<?= SITE_URL ?>/website/<?= $website['id'] ?>" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-info-circle"></i> 详情
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($websites['last_page'] > 1): ?>
        <nav aria-label="网站分页" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Previous Page -->
                <?php if ($websites['current_page'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= SITE_URL ?>/websites?page=<?= $websites['current_page'] - 1 ?><?= isset($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?><?= $currentCategory ? '&category=' . $currentCategory['id'] : '' ?>">
                        <i class="fas fa-chevron-left"></i> 上一页
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- Page Numbers -->
                <?php
                $start = max(1, $websites['current_page'] - 2);
                $end = min($websites['last_page'], $websites['current_page'] + 2);
                
                for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= $i == $websites['current_page'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= SITE_URL ?>/websites?page=<?= $i ?><?= isset($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?><?= $currentCategory ? '&category=' . $currentCategory['id'] : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
                
                <!-- Next Page -->
                <?php if ($websites['current_page'] < $websites['last_page']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= SITE_URL ?>/websites?page=<?= $websites['current_page'] + 1 ?><?= isset($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?><?= $currentCategory ? '&category=' . $currentCategory['id'] : '' ?>">
                        下一页 <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">
                <?php if (isset($searchQuery)): ?>
                    未找到相关网站
                <?php else: ?>
                    暂无网站
                <?php endif; ?>
            </h4>
            <p class="text-muted">
                <?php if (isset($searchQuery)): ?>
                    尝试使用其他关键词搜索，或者
                    <a href="<?= SITE_URL ?>/websites">浏览所有网站</a>
                <?php else: ?>
                    还没有收录任何网站，
                    <a href="<?= SITE_URL ?>/websites/submit">提交第一个网站</a>
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.website-favicon {
    width: 32px;
    height: 32px;
    border-radius: 4px;
}

.featured-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-3px);
    transition: transform 0.3s ease;
    border-color: #3498db !important;
}

.website-card {
    transition: all 0.3s ease;
}

.website-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.website-title:hover {
    color: #3498db !important;
}

.website-url {
    font-family: 'Courier New', monospace;
}

.search-filter-section .form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}
</style>
