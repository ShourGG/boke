<?php
/**
 * Admin Dashboard View
 * Overview of site statistics and recent activities
 */
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 text-gradient">
            <i class="fas fa-tachometer-alt"></i> 仪表盘
        </h1>
        <p class="text-muted">欢迎回来，<?= htmlspecialchars($_SESSION['admin_display_name']) ?>！</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card primary position-relative">
            <div class="stat-number"><?= $stats['total_posts'] ?></div>
            <div class="stat-label">总文章数</div>
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> +12%
            </div>
            <i class="fas fa-file-alt stat-icon"></i>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card success position-relative">
            <div class="stat-number"><?= $stats['published_posts'] ?></div>
            <div class="stat-label">已发布文章</div>
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> +8%
            </div>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card warning position-relative">
            <div class="stat-number"><?= $stats['draft_posts'] ?></div>
            <div class="stat-label">草稿文章</div>
            <div class="stat-trend down">
                <i class="fas fa-arrow-down"></i> -5%
            </div>
            <i class="fas fa-edit stat-icon"></i>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card info position-relative">
            <div class="stat-number"><?= $stats['total_comments'] ?></div>
            <div class="stat-label">评论总数</div>
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> +15%
            </div>
            <i class="fas fa-comments stat-icon"></i>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card secondary position-relative">
            <div class="stat-number"><?= $stats['approved_websites'] ?></div>
            <div class="stat-label">收录网站</div>
            <i class="fas fa-globe stat-icon"></i>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card danger position-relative">
            <div class="stat-number"><?= $stats['pending_websites'] ?></div>
            <div class="stat-label">待审核网站</div>
            <i class="fas fa-clock stat-icon"></i>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card primary position-relative">
            <div class="stat-number"><?= $stats['categories_count'] ?></div>
            <div class="stat-label">文章分类</div>
            <i class="fas fa-folder stat-icon"></i>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card success position-relative">
            <div class="stat-number"><?= $stats['tags_count'] ?></div>
            <div class="stat-label">标签数量</div>
            <i class="fas fa-tags stat-icon"></i>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-bolt"></i> 快速操作</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?= SITE_URL ?>/admin/posts/create" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> 写新文章
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?= SITE_URL ?>/admin/banner" class="btn btn-secondary w-100">
                            <i class="fas fa-image"></i> Banner设置
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?= SITE_URL ?>/admin/websites/create" class="btn btn-success w-100">
                            <i class="fas fa-globe"></i> 添加网站
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?= SITE_URL ?>/admin/posts" class="btn btn-info w-100">
                            <i class="fas fa-list"></i> 管理文章
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?= SITE_URL ?>/admin/settings" class="btn btn-warning w-100">
                            <i class="fas fa-cogs"></i> 系统设置
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities and System Info -->
<div class="row">
    <!-- Recent Activities -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> 最近活动</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <span class="activity-type <?= $activity['type'] ?>">
                            <?php
                            switch ($activity['type']) {
                                case 'post':
                                    echo '<i class="fas fa-file-alt"></i> 文章';
                                    break;
                                case 'website':
                                    echo '<i class="fas fa-globe"></i> 网站';
                                    break;
                                case 'comment':
                                    echo '<i class="fas fa-comment"></i> 评论';
                                    break;
                                default:
                                    echo '<i class="fas fa-info"></i> 活动';
                            }
                            ?>
                        </span>
                        <div class="activity-content">
                            <strong><?= htmlspecialchars($activity['name']) ?></strong>
                            <div class="text-muted small">
                                <i class="fas fa-clock"></i>
                                <?= date('Y-m-d H:i', strtotime($activity['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">暂无最近活动</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- System Information -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> 系统信息</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>网站名称：</strong><br>
                    <span class="text-muted"><?= SITE_NAME ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>PHP版本：</strong><br>
                    <span class="text-muted"><?= PHP_VERSION ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>服务器时间：</strong><br>
                    <span class="text-muted"><?= date('Y-m-d H:i:s') ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>数据库：</strong><br>
                    <span class="text-muted">MySQL</span>
                </div>
                
                <div class="mb-3">
                    <strong>上次登录：</strong><br>
                    <span class="text-muted">
                        <?= date('Y-m-d H:i:s') ?>
                    </span>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <a href="<?= SITE_URL ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt"></i> 查看网站
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Pending Items -->
        <?php if ($stats['pending_websites'] > 0 || $stats['pending_comments'] > 0): ?>
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> 待处理事项</h6>
            </div>
            <div class="card-body">
                <?php if ($stats['pending_websites'] > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>待审核网站</span>
                    <span class="badge bg-warning"><?= $stats['pending_websites'] ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($stats['pending_comments'] > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>待审核评论</span>
                    <span class="badge bg-warning"><?= $stats['pending_comments'] ?></span>
                </div>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="<?= SITE_URL ?>/admin/websites" class="btn btn-warning btn-sm">
                        <i class="fas fa-tasks"></i> 处理待审核
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
