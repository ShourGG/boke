<?php
/**
 * Admin Settings Index View
 * 管理后台系统设置页面
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-cog text-primary"></i> 系统设置
    </h2>
    <div>
        <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
            <i class="fas fa-broom"></i> 清除缓存
        </button>
        <button type="button" class="btn btn-outline-info" onclick="backupDatabase()">
            <i class="fas fa-download"></i> 备份数据库
        </button>
    </div>
</div>

<form method="POST" id="settingsForm">
    <div class="row">
        <div class="col-lg-8">
            <!-- 网站基本信息 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">网站基本信息</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="site_name" class="form-label">网站名称</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" 
                               value="<?= htmlspecialchars($settings['site_name']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="site_description" class="form-label">网站描述</label>
                        <textarea class="form-control" id="site_description" name="site_description" rows="3"><?= htmlspecialchars($settings['site_description']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="site_keywords" class="form-label">网站关键词</label>
                        <input type="text" class="form-control" id="site_keywords" name="site_keywords" 
                               value="<?= htmlspecialchars($settings['site_keywords']) ?>">
                        <div class="form-text">多个关键词用逗号分隔</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_url" class="form-label">网站URL</label>
                                <input type="url" class="form-control" id="site_url" name="site_url" 
                                       value="<?= htmlspecialchars($settings['site_url']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">管理员邮箱</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                       value="<?= htmlspecialchars($settings['admin_email']) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 显示设置 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">显示设置</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="posts_per_page" class="form-label">每页文章数量</label>
                                <input type="number" class="form-control" id="posts_per_page" name="posts_per_page" 
                                       value="<?= $settings['posts_per_page'] ?>" min="1" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="websites_per_page" class="form-label">每页网站数量</label>
                                <input type="number" class="form-control" id="websites_per_page" name="websites_per_page" 
                                       value="<?= $settings['websites_per_page'] ?>" min="1" max="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 功能设置 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">功能设置</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1"
                                       <?= $settings['allow_comments'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="allow_comments">
                                    允许评论
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="comment_moderation" name="comment_moderation" value="1"
                                       <?= $settings['comment_moderation'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="comment_moderation">
                                    评论需要审核
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="enable_registration" name="enable_registration" value="1"
                                       <?= $settings['enable_registration'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="enable_registration">
                                    允许用户注册
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1"
                                       <?= $settings['maintenance_mode'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="maintenance_mode">
                                    维护模式
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 高级设置 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">高级设置</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="google_analytics" class="form-label">Google Analytics 跟踪ID</label>
                        <input type="text" class="form-control" id="google_analytics" name="google_analytics" 
                               value="<?= htmlspecialchars($settings['google_analytics']) ?>" 
                               placeholder="G-XXXXXXXXXX">
                    </div>
                    
                    <div class="mb-3">
                        <label for="footer_text" class="form-label">页脚文字</label>
                        <textarea class="form-control" id="footer_text" name="footer_text" rows="2"><?= htmlspecialchars($settings['footer_text']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- 操作面板 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">操作</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 保存设置
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-undo"></i> 重置
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- 系统信息 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">系统信息</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">PHP版本</small>
                            <div class="fw-bold"><?= PHP_VERSION ?></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">服务器时间</small>
                            <div class="fw-bold"><?= date('Y-m-d H:i:s') ?></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">内存使用</small>
                            <div class="fw-bold"><?= round(memory_get_usage() / 1024 / 1024, 2) ?>MB</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">磁盘空间</small>
                            <div class="fw-bold"><?= round(disk_free_space('.') / 1024 / 1024 / 1024, 2) ?>GB</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 快捷操作 -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">快捷操作</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/admin" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-tachometer-alt"></i> 返回仪表板
                        </a>
                        <a href="/admin/posts" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-edit"></i> 管理文章
                        </a>
                        <a href="/admin/websites" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-globe"></i> 管理网站
                        </a>
                        <a href="/" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-external-link-alt"></i> 查看网站
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// 清除缓存
function clearCache() {
    if (confirm('确定要清除所有缓存吗？')) {
        fetch('/admin/settings/cache/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('缓存清除成功：' + data.message);
            } else {
                alert('清除失败：' + data.message);
            }
        })
        .catch(error => {
            alert('操作失败：' + error.message);
        });
    }
}

// 备份数据库
function backupDatabase() {
    if (confirm('确定要备份数据库吗？')) {
        fetch('/admin/settings/backup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('数据库备份成功：' + data.message);
            } else {
                alert('备份失败：' + data.message);
            }
        })
        .catch(error => {
            alert('操作失败：' + error.message);
        });
    }
}

// 重置表单
function resetForm() {
    if (confirm('确定要重置所有设置吗？')) {
        document.getElementById('settingsForm').reset();
    }
}

// 表单验证
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    const siteName = document.getElementById('site_name').value.trim();
    const postsPerPage = parseInt(document.getElementById('posts_per_page').value);
    const websitesPerPage = parseInt(document.getElementById('websites_per_page').value);
    
    if (!siteName) {
        alert('请输入网站名称');
        e.preventDefault();
        return;
    }
    
    if (postsPerPage < 1 || postsPerPage > 100) {
        alert('每页文章数量必须在1-100之间');
        e.preventDefault();
        return;
    }
    
    if (websitesPerPage < 1 || websitesPerPage > 100) {
        alert('每页网站数量必须在1-100之间');
        e.preventDefault();
        return;
    }
});
</script>
