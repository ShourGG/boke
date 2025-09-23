<?php
/**
 * Admin Websites Index View
 * 管理后台网站列表页面
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-globe text-primary"></i> 网站管理
    </h2>
    <a href="/admin/websites/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> 添加网站
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
                        <i class="fas fa-globe fa-2x"></i>
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
                        <h5 class="card-title">待审核</h5>
                        <h3 class="mb-0"><?= $stats['pending'] ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
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
                        <h5 class="card-title">已通过</h5>
                        <h3 class="mb-0"><?= $stats['approved'] ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">已拒绝</h5>
                        <h3 class="mb-0"><?= $stats['rejected'] ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times fa-2x"></i>
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
                    <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>待审核</option>
                    <option value="approved" <?= $filters['status'] === 'approved' ? 'selected' : '' ?>>已通过</option>
                    <option value="rejected" <?= $filters['status'] === 'rejected' ? 'selected' : '' ?>>已拒绝</option>
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
                       placeholder="搜索网站名称或URL..." 
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
                <button type="button" class="btn btn-success btn-sm" onclick="batchAction('approve')">
                    <i class="fas fa-check"></i> 批量通过
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="batchAction('reject')">
                    <i class="fas fa-times"></i> 批量拒绝
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="batchAction('feature')">
                    <i class="fas fa-star"></i> 设为推荐
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="batchAction('delete')">
                    <i class="fas fa-trash"></i> 批量删除
                </button>
            </div>
            <div>
                <small class="text-muted">共 <?= $totalCount ?> 个网站</small>
            </div>
        </div>
    </div>
</div>

<!-- 网站列表 -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($websites)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>网站信息</th>
                            <th width="120">分类</th>
                            <th width="100">状态</th>
                            <th width="80">评分</th>
                            <th width="100">浏览量</th>
                            <th width="120">添加时间</th>
                            <th width="150">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($websites as $website): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input website-checkbox" 
                                           value="<?= $website['id'] ?>">
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="<?= htmlspecialchars($website['url']) ?>" 
                                               target="_blank" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($website['name']) ?>
                                                <i class="fas fa-external-link-alt fa-sm text-muted"></i>
                                            </a>
                                            <?php if ($website['featured']): ?>
                                                <span class="badge bg-warning">推荐</span>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted d-block">
                                            <?= htmlspecialchars($website['url']) ?>
                                        </small>
                                        <small class="text-muted">
                                            <?= mb_substr(htmlspecialchars($website['description']), 0, 80) ?>...
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: <?= $website['category_color'] ?>">
                                        <?= htmlspecialchars($website['category_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                    $statusText = [
                                        'pending' => '待审核',
                                        'approved' => '已通过',
                                        'rejected' => '已拒绝'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $statusClass[$website['status']] ?>">
                                        <?= $statusText[$website['status']] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-warning"></i>
                                        <span class="ms-1"><?= $website['rating'] ?></span>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-eye text-muted"></i>
                                    <?= number_format($website['views']) ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('Y-m-d', strtotime($website['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/websites/edit/<?= $website['id'] ?>" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                onclick="deleteWebsite(<?= $website['id'] ?>)">
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
                <i class="fas fa-globe fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">暂无网站数据</h5>
                <p class="text-muted">
                    <a href="/admin/websites/create" class="text-decoration-none">点击添加第一个网站</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// 全选功能
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.website-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// 批量操作
function batchAction(action) {
    const selectedIds = [];
    document.querySelectorAll('.website-checkbox:checked').forEach(checkbox => {
        selectedIds.push(checkbox.value);
    });
    
    if (selectedIds.length === 0) {
        alert('请选择要操作的网站');
        return;
    }
    
    const actionText = {
        'approve': '通过',
        'reject': '拒绝',
        'feature': '设为推荐',
        'unfeature': '取消推荐',
        'delete': '删除'
    };
    
    if (confirm(`确定要${actionText[action]}选中的 ${selectedIds.length} 个网站吗？`)) {
        fetch('/admin/websites/batch-action', {
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
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('操作失败：' + data.error);
            }
        })
        .catch(error => {
            alert('操作失败：' + error.message);
        });
    }
}

// 删除单个网站
function deleteWebsite(id) {
    if (confirm('确定要删除这个网站吗？')) {
        fetch(`/admin/websites/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('删除失败：' + data.error);
            }
        })
        .catch(error => {
            alert('删除失败：' + error.message);
        });
    }
}
</script>
