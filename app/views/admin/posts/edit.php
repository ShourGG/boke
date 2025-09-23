<?php
/**
 * Admin Posts Edit View
 * 管理后台编辑文章页面
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-edit text-primary"></i> 编辑文章
    </h2>
    <div>
        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($post['slug']) ?>" 
           target="_blank" class="btn btn-outline-info">
            <i class="fas fa-external-link-alt"></i> 预览
        </a>
        <a href="/admin/posts" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> 返回列表
        </a>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" id="postForm">
    <div class="row">
        <div class="col-lg-8">
            <!-- 基本信息 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">基本信息</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">文章标题 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= htmlspecialchars($post['title']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="slug" class="form-label">URL别名</label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               value="<?= htmlspecialchars($post['slug']) ?>">
                        <div class="form-text">用于生成文章链接，只能包含字母、数字和连字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">文章摘要</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- 文章内容 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">文章内容</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="content" class="form-label">正文内容 <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="20" required><?= htmlspecialchars($post['content']) ?></textarea>
                        <div class="form-text">支持Markdown语法</div>
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
                               value="<?= htmlspecialchars($post['meta_title'] ?? '') ?>">
                        <div class="form-text">建议长度：50-60个字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">SEO描述</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?= htmlspecialchars($post['meta_description'] ?? '') ?></textarea>
                        <div class="form-text">建议长度：150-160个字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">关键词</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               value="<?= htmlspecialchars($post['meta_keywords'] ?? '') ?>">
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
                        <label for="status" class="form-label">状态</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>草稿</option>
                            <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>发布</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="published_at" class="form-label">发布时间</label>
                        <input type="datetime-local" class="form-control" id="published_at" name="published_at" 
                               value="<?= $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                                   <?= $post['is_featured'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_featured">
                                推荐文章
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1"
                                   <?= $post['allow_comments'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="allow_comments">
                                允许评论
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            <strong>创建时间：</strong><?= date('Y-m-d H:i', strtotime($post['created_at'])) ?><br>
                            <strong>更新时间：</strong><?= date('Y-m-d H:i', strtotime($post['updated_at'])) ?><br>
                            <strong>浏览次数：</strong><?= number_format($post['view_count']) ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- 分类和标签 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">分类和标签</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">文章分类</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">选择分类</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                        <?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">标签</label>
                        <select class="form-select" id="tags" name="tags[]" multiple>
                            <?php foreach ($tags as $tag): ?>
                                <option value="<?= $tag['id'] ?>" 
                                        <?= in_array($tag['id'], $postTags) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tag['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">按住Ctrl键可选择多个标签</div>
                    </div>
                </div>
            </div>
            
            <!-- 特色图片 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">特色图片</h5>
                </div>
                <div class="card-body">
                    <?php if ($post['featured_image']): ?>
                        <div class="mb-3">
                            <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>" 
                                 alt="当前特色图片" class="img-fluid rounded">
                            <div class="mt-2">
                                <label class="form-check-label">
                                    <input type="checkbox" name="remove_featured_image" value="1" class="form-check-input">
                                    删除当前图片
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">
                            <?= $post['featured_image'] ? '更换图片' : '上传图片' ?>
                        </label>
                        <input type="file" class="form-control" id="featured_image" name="featured_image" 
                               accept="image/*">
                        <div class="form-text">支持JPG、PNG、GIF格式，建议尺寸：800x400px</div>
                    </div>
                    
                    <div id="imagePreview" class="d-none">
                        <img id="previewImg" src="" alt="预览" class="img-fluid rounded">
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage()">
                            <i class="fas fa-trash"></i> 移除新图片
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- 操作按钮 -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 更新文章
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                            <i class="fas fa-file-alt"></i> 保存草稿
                        </button>
                        <a href="/admin/posts" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i> 取消
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// 图片预览
document.getElementById('featured_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

// 移除新图片
function removeImage() {
    document.getElementById('featured_image').value = '';
    document.getElementById('imagePreview').classList.add('d-none');
}

// 保存草稿
function saveDraft() {
    document.getElementById('status').value = 'draft';
    document.getElementById('postForm').submit();
}

// 表单验证
document.getElementById('postForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (!title) {
        alert('请输入文章标题');
        e.preventDefault();
        return;
    }
    
    if (!content) {
        alert('请输入文章内容');
        e.preventDefault();
        return;
    }
});
</script>
