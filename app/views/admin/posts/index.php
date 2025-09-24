<?php
/**
 * Admin Posts Index View
 * 管理后台文章列表页面
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-file-alt text-primary"></i> 文章管理
    </h2>
    <a href="/admin/posts/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> 添加文章
    </a>
</div>

<!-- 统计卡片 -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">总数</h5>
                        <h3 class="mb-0"><?= $stats['total'] ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">已发布</h5>
                        <h3 class="mb-0"><?= $stats['published'] ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">草稿</h5>
                        <h3 class="mb-0"><?= $stats['draft'] ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-edit fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">总浏览</h5>
                        <h3 class="mb-0"><?= number_format($stats['total_views']) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 筛选和搜索 -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">状态</label>
                <select name="status" class="form-select">
                    <option value="all" <?= $filters['status'] === 'all' ? 'selected' : '' ?>>全部</option>
                    <option value="published" <?= $filters['status'] === 'published' ? 'selected' : '' ?>>已发布</option>
                    <option value="draft" <?= $filters['status'] === 'draft' ? 'selected' : '' ?>>草稿</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">分类</label>
                <select name="category" class="form-select">
                    <option value="0">全部分类</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $filters['category'] == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">搜索</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="搜索文章标题或内容..." 
                       value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> 搜索
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 批量操作 -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="button" class="btn btn-success btn-sm" onclick="batchAction('publish')">
                    <i class="fas fa-check"></i> 批量发布
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="batchAction('draft')">
                    <i class="fas fa-edit"></i> 设为草稿
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="batchAction('feature')">
                    <i class="fas fa-star"></i> 设为推荐
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="batchAction('delete')">
                    <i class="fas fa-trash"></i> 批量删除
                </button>
            </div>
            <div>
                <small class="text-muted">共 <?= $totalCount ?> 篇文章</small>
            </div>
        </div>
    </div>
</div>

<!-- 文章列表 -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($posts)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>文章信息</th>
                            <th width="120">分类</th>
                            <th width="100">状态</th>
                            <th width="100">浏览量</th>
                            <th width="120">发布时间</th>
                            <th width="150">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input post-checkbox" 
                                           value="<?= $post['id'] ?>">
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>" 
                                               target="_blank" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($post['title']) ?>
                                                <i class="fas fa-external-link-alt fa-sm text-muted"></i>
                                            </a>
                                            <?php if ($post['is_featured']): ?>
                                                <span class="badge bg-warning">推荐</span>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= mb_substr(htmlspecialchars($post['excerpt'] ?? ''), 0, 80) ?>...
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($post['category_name']): ?>
                                        <span class="badge" style="background-color: <?= $post['category_color'] ?>">
                                            <?= htmlspecialchars($post['category_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">未分类</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'draft' => 'draft',
                                        'published' => 'published'
                                    ];
                                    $statusText = [
                                        'draft' => '草稿',
                                        'published' => '已发布'
                                    ];
                                    ?>
                                    <span class="status-badge <?= $statusClass[$post['status']] ?>">
                                        <?= $statusText[$post['status']] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-eye text-muted"></i>
                                    <?= number_format($post['view_count']) ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= $post['published_at'] ? date('Y-m-d', strtotime($post['published_at'])) : '-' ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/posts/edit/<?= $post['id'] ?>" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                onclick="deletePost(<?= $post['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 分页 -->
            <?php if ($totalPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?>&<?= http_build_query($filters) ?>">
                                    上一页
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&<?= http_build_query($filters) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?>&<?= http_build_query($filters) ?>">
                                    下一页
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">暂无文章数据</h5>
                <p class="text-muted">
                    <a href="/admin/posts/create" class="text-decoration-none">点击添加第一篇文章</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Floating Batch Action Button -->
<button id="batchActionBtn" class="btn btn-primary" onclick="showBatchActionModal()">
    <i class="fas fa-tasks me-2"></i>批量操作
</button>

<!-- Batch Action Modal -->
<div class="modal fade" id="batchActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">批量操作</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">选择操作</label>
                    <select class="form-control" id="batchActionSelect">
                        <option value="">请选择操作</option>
                        <option value="publish">批量发布</option>
                        <option value="draft">设为草稿</option>
                        <option value="delete">批量删除</option>
                    </select>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    已选择 <span id="selectedCount">0</span> 个项目
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="executeBatchAction()">执行操作</button>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced batch operations with modal
function showBatchActionModal() {
    const selectedCount = document.querySelectorAll('.post-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selectedCount;

    const modal = new bootstrap.Modal(document.getElementById('batchActionModal'));
    modal.show();
}

function executeBatchAction() {
    const selectedIds = [];
    document.querySelectorAll('.post-checkbox:checked').forEach(checkbox => {
        selectedIds.push(checkbox.value);
    });

    const action = document.getElementById('batchActionSelect').value;

    if (selectedIds.length === 0) {
        if (typeof showNotification === 'function') {
            showNotification('请选择要操作的文章', 'warning');
        } else {
            alert('请选择要操作的文章');
        }
        return;
    }

    if (!action) {
        if (typeof showNotification === 'function') {
            showNotification('请选择要执行的操作', 'warning');
        } else {
            alert('请选择要执行的操作');
        }
        return;
    }

    // Confirm action
    const actionNames = {
        'publish': '发布',
        'draft': '设为草稿',
        'delete': '删除'
    };

    if (confirm(`确定要${actionNames[action]}选中的${selectedIds.length}篇文章吗？`)) {
        // Show loading state
        const executeBtn = document.querySelector('#batchActionModal .btn-primary');
        const originalText = executeBtn.innerHTML;
        executeBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>处理中...';
        executeBtn.disabled = true;

        // Execute batch action
        fetch('/admin/posts/batch-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: action,
                ids: selectedIds
            })
        })
        .then(response => response.json())
        .then(data => {
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('batchActionModal')).hide();

            if (data.success) {
                if (typeof showNotification === 'function') {
                    showNotification(`成功${actionNames[action]}了${selectedIds.length}篇文章`, 'success');
                } else {
                    alert(data.message);
                }

                // Clear selections and reload
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                if (typeof showNotification === 'function') {
                    showNotification('操作失败：' + data.error, 'danger');
                } else {
                    alert('操作失败：' + data.error);
                }
            }
        })
        .catch(error => {
            if (typeof showNotification === 'function') {
                showNotification('操作失败：' + error.message, 'danger');
            } else {
                alert('操作失败：' + error.message);
            }
        })
        .finally(() => {
            // Reset button
            executeBtn.innerHTML = originalText;
            executeBtn.disabled = false;
        });
    }
}

// Delete individual post with enhanced UX
function deletePost(id) {
    if (confirm('确定要删除这篇文章吗？此操作不可撤销。')) {
        const row = document.querySelector(`input[value="${id}"]`).closest('tr');
        if (row) {
            row.classList.add('animate-shake');
        }

        fetch(`/admin/posts/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (row) {
                    row.classList.add('animate-fade-out');
                    setTimeout(() => {
                        row.remove();
                    }, 300);
                }

                if (typeof showNotification === 'function') {
                    showNotification('文章已删除', 'success');
                } else {
                    alert(data.message);
                    location.reload();
                }
            } else {
                if (typeof showNotification === 'function') {
                    showNotification('删除失败：' + data.error, 'danger');
                } else {
                    alert('删除失败：' + data.error);
                }
            }
        })
        .catch(error => {
            if (typeof showNotification === 'function') {
                showNotification('删除失败：' + error.message, 'danger');
            } else {
                alert('删除失败：' + error.message);
            }
        });
    }
}
</script>
