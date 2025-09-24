<!-- Fluid主题欢迎区域 -->
<div class="text-center mb-5">
    <h1 style="display: none"><?= SITE_NAME ?></h1>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h2 class="mb-3">
                <i class="fas fa-fish text-primary me-2"></i>
                欢迎来到 <?= SITE_NAME ?>
            </h2>
            <p class="lead text-muted mb-4"><?= SITE_DESCRIPTION ?></p>

            <!-- 搜索框 -->
            <form action="<?= SITE_URL ?>/search" method="get" class="mb-4">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control"
                           placeholder="搜索文章或网站..."
                           value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- 统计信息 -->
            <div class="row text-center mb-4">
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                        <h4 class="mb-1"><?= $posts['total'] ?? 0 ?></h4>
                        <small class="text-muted">篇文章</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="fas fa-globe fa-2x text-primary mb-2"></i>
                        <h4 class="mb-1"><?= $websiteCount ?? 0 ?></h4>
                        <small class="text-muted">个网站</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                        <h4 class="mb-1"><?= $totalViews ?? 0 ?></h4>
                        <small class="text-muted">次访问</small>
                    </div>
                </div>
            </div>

            <!-- 快速导航 -->
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?= SITE_URL ?>/websites" class="btn btn-primary">
                    <i class="fas fa-globe me-2"></i>网站收录
                </a>
                <a href="<?= SITE_URL ?>/admin" class="btn btn-outline-primary">
                    <i class="fas fa-cog me-2"></i>管理后台
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Fluid主题精选文章 -->
<?php if (!empty($featuredPosts)): ?>
<div class="mb-5">
    <h2 class="text-center mb-4">
        <i class="fas fa-star text-warning me-2"></i>精选文章
    </h2>
    <div class="row">
        <?php foreach ($featuredPosts as $post): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="index-card h-100">
                <?php if ($post['featured_image']): ?>
                <div class="index-img mb-3">
                    <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>">
                        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>"
                             alt="<?= htmlspecialchars($post['title']) ?>">
                    </a>
                </div>
                <?php endif; ?>
                <div class="index-info">
                    <h3 class="index-header">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>

                    <?php if ($post['excerpt']): ?>
                    <div class="index-excerpt">
                        <div>
                            <?= htmlspecialchars(substr($post['excerpt'], 0, 120)) ?>
                            <?php if (strlen($post['excerpt']) > 120): ?>...<?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="index-btm post-metas">
                        <div class="post-meta">
                            <i class="fas fa-calendar"></i>
                            <time><?= date('m月d日', strtotime($post['published_at'])) ?></time>
                        </div>
                        <div class="post-meta">
                            <i class="fas fa-eye"></i>
                            <span><?= $post['view_count'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Fluid主题最新文章 -->
<div id="posts">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-newspaper me-2"></i>最新文章
        </h2>
        <small class="text-muted">
            共 <?= $posts['total'] ?> 篇文章
        </small>
    </div>

    <?php if (!empty($posts['data'])): ?>
        <?php foreach ($posts['data'] as $post): ?>
        <div class="row mx-auto index-card">
            <?php $index_img = $post['featured_image'] ?>
            <?php if ($index_img): ?>
            <div class="col-12 col-md-4 m-auto index-img">
                <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($index_img) ?>"
                         alt="<?= htmlspecialchars($post['title']) ?>">
                </a>
            </div>
            <?php endif; ?>
            <article class="col-12 col-md-<?= $index_img ? '8' : '12' ?> mx-auto index-info">
                <h2 class="index-header">
                    <?php if ($post['is_featured']): ?>
                    <i class="fas fa-thumbtack index-pin text-warning me-2" title="精选文章"></i>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h2>

                <?php if ($post['excerpt']): ?>
                <a class="index-excerpt <?= $index_img ? '' : 'index-excerpt__noimg' ?>"
                   href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>">
                    <div>
                        <?= htmlspecialchars(substr($post['excerpt'], 0, 200)) ?>
                        <?php if (strlen($post['excerpt']) > 200): ?>...<?php endif; ?>
                    </div>
                </a>
                <?php endif; ?>

                <div class="index-btm post-metas">
                    <div class="post-meta me-3">
                        <i class="fas fa-calendar"></i>
                        <time datetime="<?= date('Y-m-d H:i', strtotime($post['published_at'])) ?>">
                            <?= date('Y年m月d日', strtotime($post['published_at'])) ?>
                        </time>
                    </div>

                    <?php if (isset($post['category_name'])): ?>
                    <div class="post-meta me-3">
                        <i class="fas fa-folder"></i>
                        <span><?= htmlspecialchars($post['category_name']) ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="post-meta me-3">
                        <i class="fas fa-eye"></i>
                        <span><?= $post['view_count'] ?></span>
                    </div>

                    <?php if ($post['comment_count'] > 0): ?>
                    <div class="post-meta">
                        <i class="fas fa-comments"></i>
                        <span><?= $post['comment_count'] ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
        </div>
        <?php endforeach; ?>

        <!-- Fluid主题分页 -->
        <?php if ($posts['last_page'] > 1): ?>
        <nav aria-label="文章分页" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- 上一页 -->
                <?php if ($posts['current_page'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= SITE_URL ?>/?page=<?= $posts['current_page'] - 1 ?>">
                        <i class="fas fa-chevron-left"></i> 上一页
                    </a>
                </li>
                <?php endif; ?>

                <!-- 页码 -->
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

                <!-- 下一页 -->
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


