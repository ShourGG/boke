<div class="container mt-4">
    <!-- 搜索表单 -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="/search" class="search-form">
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   class="form-control" 
                                   name="q" 
                                   value="<?= htmlspecialchars($query) ?>" 
                                   placeholder="搜索文章和网站..."
                                   autocomplete="off"
                                   id="searchInput">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> 搜索
                                </button>
                            </div>
                        </div>
                        
                        <!-- 搜索类型选择 -->
                        <div class="mt-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-secondary <?= $type === 'all' ? 'active' : '' ?>">
                                    <input type="radio" name="type" value="all" <?= $type === 'all' ? 'checked' : '' ?>> 全部
                                </label>
                                <label class="btn btn-outline-secondary <?= $type === 'posts' ? 'active' : '' ?>">
                                    <input type="radio" name="type" value="posts" <?= $type === 'posts' ? 'checked' : '' ?>> 文章
                                </label>
                                <label class="btn btn-outline-secondary <?= $type === 'websites' ? 'active' : '' ?>">
                                    <input type="radio" name="type" value="websites" <?= $type === 'websites' ? 'checked' : '' ?>> 网站
                                </label>
                            </div>
                        </div>
                    </form>
                    
                    <!-- 搜索建议 -->
                    <div id="searchSuggestions" class="search-suggestions mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($query)): ?>
        <!-- 搜索结果信息 -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        搜索结果：<span class="text-primary"><?= htmlspecialchars($query) ?></span>
                    </h4>
                    <small class="text-muted">
                        找到 <?= $totalResults ?> 个结果 (用时 <?= $searchTime ?>ms)
                    </small>
                </div>
            </div>
        </div>

        <?php if ($totalResults > 0): ?>
            <?php if ($type === 'all'): ?>
                <!-- 综合搜索结果 -->
                <?php if (!empty($results['posts'])): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-file-alt text-primary"></i> 相关文章
                                <a href="/search?q=<?= urlencode($query) ?>&type=posts" class="btn btn-sm btn-outline-primary float-right">
                                    查看更多
                                </a>
                            </h5>
                            <div class="row">
                                <?php foreach ($results['posts'] as $post): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <a href="/post/<?= $post['slug'] ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($post['title']) ?>
                                                    </a>
                                                </h6>
                                                <p class="card-text text-muted small">
                                                    <?= mb_substr(strip_tags($post['excerpt']), 0, 100) ?>...
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar"></i> <?= date('Y-m-d', strtotime($post['created_at'])) ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-eye"></i> <?= $post['views'] ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($results['websites'])): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-globe text-success"></i> 相关网站
                                <a href="/search?q=<?= urlencode($query) ?>&type=websites" class="btn btn-sm btn-outline-success float-right">
                                    查看更多
                                </a>
                            </h5>
                            <div class="row">
                                <?php foreach ($results['websites'] as $website): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <a href="<?= htmlspecialchars($website['url']) ?>" 
                                                       target="_blank" 
                                                       class="text-decoration-none">
                                                        <?= htmlspecialchars($website['name']) ?>
                                                        <i class="fas fa-external-link-alt fa-sm"></i>
                                                    </a>
                                                </h6>
                                                <p class="card-text text-muted small">
                                                    <?= htmlspecialchars($website['description']) ?>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge badge-secondary">
                                                        <?= htmlspecialchars($website['category_name']) ?>
                                                    </span>
                                                    <small class="text-muted">
                                                        <i class="fas fa-star"></i> <?= $website['rating'] ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- 单类型搜索结果 -->
                <div class="row">
                    <?php if ($type === 'posts'): ?>
                        <?php foreach ($results as $post): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="/post/<?= $post['slug'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($post['title']) ?>
                                            </a>
                                        </h5>
                                        <p class="card-text">
                                            <?= mb_substr(strip_tags($post['excerpt']), 0, 150) ?>...
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge badge-primary">
                                                    <?= htmlspecialchars($post['category_name']) ?>
                                                </span>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-calendar"></i> <?= date('Y-m-d', strtotime($post['created_at'])) ?>
                                                <i class="fas fa-eye ml-2"></i> <?= $post['views'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($results as $website): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="<?= htmlspecialchars($website['url']) ?>" 
                                               target="_blank" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($website['name']) ?>
                                                <i class="fas fa-external-link-alt fa-sm"></i>
                                            </a>
                                        </h5>
                                        <p class="card-text">
                                            <?= htmlspecialchars($website['description']) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge badge-secondary">
                                                <?= htmlspecialchars($website['category_name']) ?>
                                            </span>
                                            <div class="text-muted small">
                                                <i class="fas fa-star"></i> <?= $website['rating'] ?>
                                                <i class="fas fa-eye ml-2"></i> <?= $website['views'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- 分页 -->
                <?php if ($totalPages > 1): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <nav aria-label="搜索结果分页">
                                <ul class="pagination justify-content-center">
                                    <?php if ($currentPage > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="/search?q=<?= urlencode($query) ?>&type=<?= $type ?>&page=<?= $currentPage - 1 ?>">
                                                上一页
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="/search?q=<?= urlencode($query) ?>&type=<?= $type ?>&page=<?= $i ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($currentPage < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="/search?q=<?= urlencode($query) ?>&type=<?= $type ?>&page=<?= $currentPage + 1 ?>">
                                                下一页
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <!-- 无搜索结果 -->
            <div class="row">
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">没有找到相关内容</h4>
                        <p class="text-muted">
                            尝试使用不同的关键词，或者
                            <a href="/search/advanced" class="text-decoration-none">使用高级搜索</a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- 搜索提示 -->
        <div class="row">
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">开始搜索</h4>
                    <p class="text-muted">
                        输入关键词搜索文章和网站，或者
                        <a href="/search/advanced" class="text-decoration-none">使用高级搜索</a>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- 搜索建议JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const suggestions = document.getElementById('searchSuggestions');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            suggestions.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`/search/suggest?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        showSuggestions(data);
                    } else {
                        suggestions.style.display = 'none';
                    }
                })
                .catch(() => {
                    suggestions.style.display = 'none';
                });
        }, 300);
    });

    function showSuggestions(data) {
        let html = '<div class="list-group">';
        
        data.forEach(item => {
            const icon = item.type === 'post' ? 'fas fa-file-alt' : 'fas fa-globe';
            const color = item.type === 'post' ? 'text-primary' : 'text-success';
            
            html += `
                <a href="${item.url}" class="list-group-item list-group-item-action">
                    <i class="${icon} ${color}"></i>
                    ${item.title}
                    ${item.category ? `<small class="text-muted ml-2">${item.category}</small>` : ''}
                </a>
            `;
        });
        
        html += '</div>';
        
        suggestions.innerHTML = html;
        suggestions.style.display = 'block';
    }

    // 点击其他地方隐藏建议
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
});
</script>

<style>
.search-suggestions {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.search-form {
    position: relative;
}
</style>
