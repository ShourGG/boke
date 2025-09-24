<!-- 焦点内容区 -->
<div class="card" style="margin-bottom: 24px;">
    <h1 style="margin: 0 0 16px 0; font-size: 1.5rem; color: var(--md-text-primary);">
        <span class="material-icons" style="vertical-align: middle; margin-right: 8px; color: var(--md-primary);">pets</span>
        欢迎来到 <?= SITE_NAME ?>
    </h1>
    <p style="color: var(--md-text-secondary); margin-bottom: 16px;"><?= SITE_DESCRIPTION ?></p>

    <!-- 搜索框 -->
    <form action="<?= SITE_URL ?>/search" method="get" style="display: flex; gap: 8px; margin-bottom: 16px;">
        <input type="text" name="q" placeholder="搜索文章或网站..."
               class="form-input" style="flex: 1; margin-bottom: 0;">
        <button type="submit" class="btn btn-raised">
            <span class="material-icons" style="font-size: 1rem;">search</span>
            搜索
        </button>
    </form>

    <!-- 统计信息 -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px;">
        <div style="text-align: center; padding: 16px; background: var(--md-primary-light); border-radius: 4px;">
            <span class="material-icons" style="font-size: 2rem; color: var(--md-primary); display: block; margin-bottom: 8px;">article</span>
            <div style="font-size: 1.5rem; font-weight: 500; color: var(--md-primary);"><?= $posts['total'] ?? 0 ?></div>
            <div style="font-size: 0.875rem; color: var(--md-text-secondary);">篇文章</div>
        </div>
        <div style="text-align: center; padding: 16px; background: var(--md-primary-light); border-radius: 4px;">
            <span class="material-icons" style="font-size: 2rem; color: var(--md-primary); display: block; margin-bottom: 8px;">language</span>
            <div style="font-size: 1.5rem; font-weight: 500; color: var(--md-primary);"><?= $websiteCount ?? 0 ?></div>
            <div style="font-size: 0.875rem; color: var(--md-text-secondary);">个网站</div>
        </div>
        <div style="text-align: center; padding: 16px; background: var(--md-primary-light); border-radius: 4px;">
            <span class="material-icons" style="font-size: 2rem; color: var(--md-primary); display: block; margin-bottom: 8px;">visibility</span>
            <div style="font-size: 1.5rem; font-weight: 500; color: var(--md-primary);"><?= $totalViews ?? 0 ?></div>
            <div style="font-size: 0.875rem; color: var(--md-text-secondary);">次访问</div>
        </div>
    </div>

    <!-- 快速导航 -->
    <div style="display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap;">
        <a href="<?= SITE_URL ?>/websites" class="btn btn-raised">
            <span class="material-icons" style="font-size: 1rem; margin-right: 4px;">language</span>
            网站收录
        </a>
        <a href="<?= SITE_URL ?>/admin" class="btn btn-outlined">
            <span class="material-icons" style="font-size: 1rem; margin-right: 4px;">settings</span>
            管理后台
        </a>
    </div>
</div>

<!-- 精选文章 -->
<?php if (!empty($featuredPosts)): ?>
<div style="margin-bottom: 24px;">
    <h2 style="margin: 0 0 16px 0; font-size: 1.25rem; color: var(--md-text-primary);">
        <span class="material-icons" style="vertical-align: middle; margin-right: 8px; color: #FFC107;">star</span>
        精选文章
    </h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
        <?php foreach ($featuredPosts as $post): ?>
        <div class="card hover-lift">
            <?php if ($post['featured_image']): ?>
            <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>"
                 alt="<?= htmlspecialchars($post['title']) ?>"
                 style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px 4px 0 0;">
            <?php else: ?>
            <div style="width: 100%; height: 200px; background: var(--md-primary-light); display: flex; align-items: center; justify-content: center; border-radius: 4px 4px 0 0;">
                <span class="material-icons" style="font-size: 3rem; color: var(--md-primary);">article</span>
            </div>
            <?php endif; ?>
            <div style="padding: 16px;">
                <h3 style="margin: 0 0 8px 0; font-size: 1.125rem; line-height: 1.4;">
                    <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>"
                       style="color: var(--md-text-primary); text-decoration: none;">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h3>
                <div style="display: flex; gap: 16px; margin-bottom: 8px; font-size: 0.875rem; color: var(--md-text-secondary);">
                    <span>
                        <span class="material-icons" style="font-size: 1rem; vertical-align: middle; margin-right: 4px;">schedule</span>
                        <?= date('m月d日', strtotime($post['published_at'])) ?>
                    </span>
                    <span>
                        <span class="material-icons" style="font-size: 1rem; vertical-align: middle; margin-right: 4px;">visibility</span>
                        <?= $post['view_count'] ?>
                    </span>
                </div>
                <?php if ($post['excerpt']): ?>
                <p style="color: var(--md-text-secondary); margin: 0 0 12px 0; line-height: 1.5; font-size: 0.875rem;">
                    <?= htmlspecialchars(substr($post['excerpt'], 0, 100)) ?>
                    <?php if (strlen($post['excerpt']) > 100): ?>...<?php endif; ?>
                </p>
                <?php endif; ?>
                <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>"
                   class="btn btn-outlined" style="font-size: 0.875rem;">
                    阅读全文
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- 最新文章 -->
<div id="posts">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="margin: 0; font-size: 1.25rem; color: var(--md-text-primary);">
            <span class="material-icons" style="vertical-align: middle; margin-right: 8px; color: var(--md-primary);">article</span>
            最新文章
        </h2>
        <span style="color: var(--md-text-secondary); font-size: 0.875rem;">
            共 <?= $posts['total'] ?> 篇文章
        </span>
    </div>

    <?php if (!empty($posts['data'])): ?>
        <?php foreach ($posts['data'] as $post): ?>
        <article class="card hover-lift" style="margin-bottom: 16px;">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <?php if ($post['featured_image']): ?>
                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>"
                     alt="<?= htmlspecialchars($post['title']) ?>"
                     style="width: 120px; height: 80px; object-fit: cover; border-radius: 4px; flex-shrink: 0;">
                <?php else: ?>
                <div style="width: 120px; height: 80px; background: var(--md-primary-light); display: flex; align-items: center; justify-content: center; border-radius: 4px; flex-shrink: 0;">
                    <span class="material-icons" style="color: var(--md-primary);">article</span>
                </div>
                <?php endif; ?>

                <div style="flex: 1; min-width: 0;">
                    <h3 style="margin: 0 0 8px 0; font-size: 1.125rem; line-height: 1.4;">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>"
                           style="color: var(--md-text-primary); text-decoration: none;">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>

                    <div style="display: flex; gap: 16px; margin-bottom: 8px; font-size: 0.875rem; color: var(--md-text-secondary); flex-wrap: wrap;">
                        <span>
                            <span class="material-icons" style="font-size: 1rem; vertical-align: middle; margin-right: 4px;">schedule</span>
                            <?= date('Y年m月d日', strtotime($post['published_at'])) ?>
                        </span>

                        <?php if (isset($post['category_name'])): ?>
                        <span>
                            <span class="material-icons" style="font-size: 1rem; vertical-align: middle; margin-right: 4px;">folder</span>
                            <?= htmlspecialchars($post['category_name']) ?>
                        </span>
                        <?php endif; ?>

                        <span>
                            <span class="material-icons" style="font-size: 1rem; vertical-align: middle; margin-right: 4px;">visibility</span>
                            <?= $post['view_count'] ?>
                        </span>

                        <?php if ($post['comment_count'] > 0): ?>
                        <span>
                            <span class="material-icons" style="font-size: 1rem; vertical-align: middle; margin-right: 4px;">comment</span>
                            <?= $post['comment_count'] ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($post['excerpt']): ?>
                    <p style="color: var(--md-text-secondary); margin: 0 0 12px 0; line-height: 1.5; font-size: 0.875rem;">
                        <?= htmlspecialchars(substr($post['excerpt'], 0, 150)) ?>
                        <?php if (strlen($post['excerpt']) > 150): ?>...<?php endif; ?>
                    </p>
                    <?php endif; ?>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>"
                           class="btn btn-outlined" style="font-size: 0.875rem;">
                            阅读全文
                        </a>

                        <?php if ($post['is_featured']): ?>
                        <span style="background: #FFC107; color: #333; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">
                            <span class="material-icons" style="font-size: 0.875rem; vertical-align: middle; margin-right: 2px;">star</span>
                            精选
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>
        <?php endforeach; ?>

        <!-- 分页 -->
        <?php if ($posts['last_page'] > 1): ?>
        <div style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 24px;">
            <!-- 上一页 -->
            <?php if ($posts['current_page'] > 1): ?>
            <a href="<?= SITE_URL ?>/?page=<?= $posts['current_page'] - 1 ?>"
               class="btn btn-outlined" style="font-size: 0.875rem;">
                <span class="material-icons" style="font-size: 1rem; margin-right: 4px;">chevron_left</span>
                上一页
            </a>
            <?php endif; ?>

            <!-- 页码 -->
            <?php
            $start = max(1, $posts['current_page'] - 2);
            $end = min($posts['last_page'], $posts['current_page'] + 2);

            if ($start > 1): ?>
            <a href="<?= SITE_URL ?>/?page=1" class="btn btn-outlined" style="min-width: 40px;">1</a>
            <?php if ($start > 2): ?>
            <span style="color: var(--md-text-secondary);">...</span>
            <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
            <a href="<?= SITE_URL ?>/?page=<?= $i ?>"
               class="btn <?= $i == $posts['current_page'] ? 'btn-raised' : 'btn-outlined' ?>"
               style="min-width: 40px; font-size: 0.875rem;">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($end < $posts['last_page']): ?>
            <?php if ($end < $posts['last_page'] - 1): ?>
            <span style="color: var(--md-text-secondary);">...</span>
            <?php endif; ?>
            <a href="<?= SITE_URL ?>/?page=<?= $posts['last_page'] ?>"
               class="btn btn-outlined" style="min-width: 40px;">
                <?= $posts['last_page'] ?>
            </a>
            <?php endif; ?>

            <!-- 下一页 -->
            <?php if ($posts['current_page'] < $posts['last_page']): ?>
            <a href="<?= SITE_URL ?>/?page=<?= $posts['current_page'] + 1 ?>"
               class="btn btn-outlined" style="font-size: 0.875rem;">
                下一页
                <span class="material-icons" style="font-size: 1rem; margin-left: 4px;">chevron_right</span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="card" style="text-align: center; padding: 48px 16px;">
            <span class="material-icons" style="font-size: 4rem; color: var(--md-text-secondary); margin-bottom: 16px;">article</span>
            <h3 style="color: var(--md-text-secondary); margin: 0 0 8px 0;">暂无文章</h3>
            <p style="color: var(--md-text-secondary); margin: 0;">博主还没有发布任何文章，请稍后再来查看。</p>
        </div>
    <?php endif; ?>
</div>


