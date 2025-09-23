<?php
/**
 * Admin Websites Edit View
 * 管理后台编辑网站页面
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-edit text-primary"></i> 编辑网站
    </h2>
    <div>
        <a href="<?= htmlspecialchars($website['url']) ?>" 
           target="_blank" class="btn btn-outline-info">
            <i class="fas fa-external-link-alt"></i> 访问网站
        </a>
        <a href="/admin/websites" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> 返回列表
        </a>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" id="websiteForm">
    <div class="row">
        <div class="col-lg-8">
            <!-- 基本信息 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">基本信息</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">网站名称 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($website['name']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="url" class="form-label">网站地址 <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="url" name="url" 
                               value="<?= htmlspecialchars($website['url']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">网站描述 <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($website['description']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">标签</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= htmlspecialchars($website['tags'] ?? '') ?>">
                        <div class="form-text">用逗号分隔多个标签</div>
                    </div>
                </div>
            </div>
            
            <!-- SEO设置 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO设置</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">SEO标题</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                               value="<?= htmlspecialchars($website['meta_title'] ?? '') ?>">
                        <div class="form-text">建议长度：50-60个字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">SEO描述</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?= htmlspecialchars($website['meta_description'] ?? '') ?></textarea>
                        <div class="form-text">建议长度：150-160个字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">关键词</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               value="<?= htmlspecialchars($website['meta_keywords'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- 发布设置 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">发布设置</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">网站分类</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">选择分类</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                        <?= $website['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">状态</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" <?= $website['status'] === 'pending' ? 'selected' : '' ?>>待审核</option>
                            <option value="approved" <?= $website['status'] === 'approved' ? 'selected' : '' ?>>已通过</option>
                            <option value="rejected" <?= $website['status'] === 'rejected' ? 'selected' : '' ?>>已拒绝</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rating" class="form-label">评分</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="0" <?= $website['rating'] == 0 ? 'selected' : '' ?>>未评分</option>
                            <option value="1" <?= $website['rating'] == 1 ? 'selected' : '' ?>>1星</option>
                            <option value="2" <?= $website['rating'] == 2 ? 'selected' : '' ?>>2星</option>
                            <option value="3" <?= $website['rating'] == 3 ? 'selected' : '' ?>>3星</option>
                            <option value="4" <?= $website['rating'] == 4 ? 'selected' : '' ?>>4星</option>
                            <option value="5" <?= $website['rating'] == 5 ? 'selected' : '' ?>>5星</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1"
                                   <?= $website['featured'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="featured">
                                推荐网站
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            <strong>创建时间：</strong><?= date('Y-m-d H:i', strtotime($website['created_at'])) ?><br>
                            <strong>更新时间：</strong><?= date('Y-m-d H:i', strtotime($website['updated_at'])) ?><br>
                            <strong>点击次数：</strong><?= number_format($website['click_count'] ?? 0) ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- 网站图标 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">网站图标</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($website['favicon'])): ?>
                        <div class="mb-3">
                            <img src="<?= SITE_URL ?>/uploads/favicons/<?= htmlspecialchars($website['favicon']) ?>" 
                                 alt="当前图标" class="img-fluid rounded" style="max-width: 64px;">
                            <div class="mt-2">
                                <label class="form-check-label">
                                    <input type="checkbox" name="remove_favicon" value="1" class="form-check-input">
                                    删除当前图标
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="favicon" class="form-label">
                            <?= !empty($website['favicon']) ? '更换图标' : '上传图标' ?>
                        </label>
                        <input type="file" class="form-control" id="favicon" name="favicon" 
                               accept="image/*">
                        <div class="form-text">支持JPG、PNG、ICO格式，建议尺寸：32x32px</div>
                    </div>
                    
                    <div id="faviconPreview" class="d-none">
                        <img id="previewImg" src="" alt="预览" class="img-fluid rounded" style="max-width: 64px;">
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeFavicon()">
                            <i class="fas fa-trash"></i> 移除新图标
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- 提交者信息 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">提交者信息</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="submitter_name" class="form-label">提交者姓名</label>
                        <input type="text" class="form-control" id="submitter_name" name="submitter_name" 
                               value="<?= htmlspecialchars($website['submitter_name'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="submitter_email" class="form-label">提交者邮箱</label>
                        <input type="email" class="form-control" id="submitter_email" name="submitter_email" 
                               value="<?= htmlspecialchars($website['submitter_email'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="submitter_ip" class="form-label">提交者IP</label>
                        <input type="text" class="form-control" id="submitter_ip" name="submitter_ip" 
                               value="<?= htmlspecialchars($website['submitter_ip'] ?? '') ?>" readonly>
                    </div>
                </div>
            </div>
            
            <!-- 操作按钮 -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 更新网站
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                            <i class="fas fa-file-alt"></i> 保存草稿
                        </button>
                        <a href="/admin/websites" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i> 取消
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// 图标预览
document.getElementById('favicon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('faviconPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

// 移除新图标
function removeFavicon() {
    document.getElementById('favicon').value = '';
    document.getElementById('faviconPreview').classList.add('d-none');
}

// 保存草稿
function saveDraft() {
    document.getElementById('status').value = 'pending';
    document.getElementById('websiteForm').submit();
}

// 表单验证
document.getElementById('websiteForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const url = document.getElementById('url').value.trim();
    const description = document.getElementById('description').value.trim();
    
    if (!name) {
        alert('请输入网站名称');
        e.preventDefault();
        return;
    }
    
    if (!url || !isValidUrl(url)) {
        alert('请输入有效的网站地址');
        e.preventDefault();
        return;
    }
    
    if (!description) {
        alert('请输入网站描述');
        e.preventDefault();
        return;
    }
});

// URL验证
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}
</script>
