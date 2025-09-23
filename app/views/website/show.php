<?php $this->layout('layouts/main', ['title' => $title, 'description' => $description, 'canonical' => $canonical ?? '']) ?>

<!-- Website Detail Header -->
<div class="website-detail-header bg-gradient text-white p-4 rounded mb-4">
    <div class="row align-items-center">
        <div class="col-md-2 text-center">
            <?php if (!empty($website['favicon'])): ?>
                <img src="<?= htmlspecialchars($website['favicon']) ?>" 
                     alt="<?= htmlspecialchars($website['title']) ?>" 
                     class="website-favicon rounded" 
                     style="width: 64px; height: 64px; object-fit: cover;">
            <?php else: ?>
                <div class="website-favicon-placeholder bg-white text-primary rounded d-flex align-items-center justify-content-center" 
                     style="width: 64px; height: 64px; margin: 0 auto;">
                    <i class="fas fa-globe fa-2x"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-10">
            <h1 class="h2 mb-2"><?= htmlspecialchars($website['title']) ?></h1>
            <p class="mb-3"><?= htmlspecialchars($website['description']) ?></p>
            
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <?php if (!empty($website['category_name'])): ?>
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-folder"></i> <?= htmlspecialchars($website['category_name']) ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($website['is_featured']): ?>
                    <span class="badge bg-success">
                        <i class="fas fa-star"></i> 推荐
                    </span>
                <?php endif; ?>
                
                <span class="badge bg-info">
                    <i class="fas fa-mouse-pointer"></i> <?= number_format($website['click_count'] ?? 0) ?> 次点击
                </span>
                
                <span class="badge bg-secondary">
                    <i class="fas fa-calendar"></i> <?= date('Y-m-d', strtotime($website['created_at'])) ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="text-center mb-4">
    <a href="<?= SITE_URL ?>/websites/click?id=<?= $website['id'] ?>" 
       class="btn btn-primary btn-lg me-3" target="_blank">
        <i class="fas fa-external-link-alt"></i> 访问网站
    </a>
    <button class="btn btn-outline-secondary" onclick="shareWebsite()">
        <i class="fas fa-share-alt"></i> 分享
    </button>
</div>

<!-- Website Screenshot -->
<?php if (!empty($website['screenshot'])): ?>
<div class="website-screenshot mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-image"></i> 网站预览</h5>
        </div>
        <div class="card-body text-center">
            <img src="<?= htmlspecialchars($website['screenshot']) ?>" 
                 alt="<?= htmlspecialchars($website['title']) ?> 截图" 
                 class="img-fluid rounded shadow"
                 style="max-height: 400px;">
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Website Details -->
<div class="row">
    <div class="col-md-8">
        <!-- Description -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> 详细介绍</h5>
            </div>
            <div class="card-body">
                <p class="lead"><?= nl2br(htmlspecialchars($website['description'])) ?></p>
                
                <?php if (!empty($website['tags'])): ?>
                    <hr>
                    <h6><i class="fas fa-tags"></i> 标签</h6>
                    <div class="tags">
                        <?php 
                        $tags = explode(',', $website['tags']);
                        foreach ($tags as $tag): 
                            $tag = trim($tag);
                            if (!empty($tag)):
                        ?>
                            <span class="badge bg-light text-dark me-1 mb-1"><?= htmlspecialchars($tag) ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Websites -->
        <?php if (!empty($relatedWebsites)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sitemap"></i> 相关网站</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($relatedWebsites as $related): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="<?= SITE_URL ?>/website/<?= $related['id'] ?>" 
                                       class="text-decoration-none">
                                        <?= htmlspecialchars($related['title']) ?>
                                    </a>
                                </h6>
                                <p class="card-text small text-muted">
                                    <?= htmlspecialchars(mb_substr($related['description'], 0, 80)) ?>...
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-mouse-pointer"></i> <?= $related['click_count'] ?? 0 ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <!-- Website Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> 网站信息</h5>
            </div>
            <div class="card-body">
                <div class="info-item mb-3">
                    <strong>网站地址：</strong><br>
                    <a href="<?= htmlspecialchars($website['url']) ?>" 
                       target="_blank" class="text-break">
                        <?= htmlspecialchars($website['url']) ?>
                    </a>
                </div>
                
                <div class="info-item mb-3">
                    <strong>收录时间：</strong><br>
                    <?= date('Y年m月d日', strtotime($website['created_at'])) ?>
                </div>
                
                <div class="info-item mb-3">
                    <strong>点击统计：</strong><br>
                    <span class="text-primary fw-bold"><?= number_format($website['click_count'] ?? 0) ?></span> 次
                </div>
                
                <?php if (!empty($website['category_name'])): ?>
                <div class="info-item mb-3">
                    <strong>所属分类：</strong><br>
                    <a href="<?= SITE_URL ?>/websites?category=<?= $website['category_id'] ?>" 
                       class="text-decoration-none">
                        <?= htmlspecialchars($website['category_name']) ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Websites -->
        <?php if (!empty($recentWebsites)): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock"></i> 最新收录</h5>
            </div>
            <div class="card-body">
                <?php foreach ($recentWebsites as $recent): ?>
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 me-3">
                        <?php if (!empty($recent['favicon'])): ?>
                            <img src="<?= htmlspecialchars($recent['favicon']) ?>" 
                                 alt="<?= htmlspecialchars($recent['title']) ?>" 
                                 class="rounded" style="width: 32px; height: 32px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" 
                                 style="width: 32px; height: 32px;">
                                <i class="fas fa-globe"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <a href="<?= SITE_URL ?>/website/<?= $recent['id'] ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($recent['title']) ?>
                            </a>
                        </h6>
                        <small class="text-muted">
                            <i class="fas fa-mouse-pointer"></i> <?= $recent['click_count'] ?? 0 ?>
                        </small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Navigation -->
<div class="text-center mt-4">
    <a href="<?= SITE_URL ?>/websites" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> 返回网站目录
    </a>
</div>

<script>
function shareWebsite() {
    if (navigator.share) {
        navigator.share({
            title: '<?= htmlspecialchars($website['title']) ?>',
            text: '<?= htmlspecialchars($website['description']) ?>',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('链接已复制到剪贴板！');
        });
    }
}
</script>

<style>
.website-detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.website-favicon, .website-favicon-placeholder {
    border: 3px solid rgba(255,255,255,0.2);
}

.info-item {
    border-bottom: 1px solid #eee;
    padding-bottom: 0.5rem;
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.tags .badge {
    font-size: 0.8em;
}
</style>
