<?php
/**
 * Admin Posts Create View
 * 管理后台添加文章页面
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-plus text-primary"></i> 添加文章
    </h2>
    <a href="/admin/posts" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> 返回列表
    </a>
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
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="slug" class="form-label">URL别名</label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               placeholder="留空自动生成">
                        <div class="form-text">用于生成文章链接，只能包含字母、数字和连字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">文章摘要</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                  placeholder="简短描述文章内容，用于列表页显示"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- 文章内容 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fab fa-markdown"></i> 文章内容
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            正文内容 <span class="text-danger">*</span>
                            <small class="text-muted">(支持Markdown语法)</small>
                        </label>
                        <div id="simple-markdown-editor" data-content=""></div>
                        <textarea id="content" name="content" style="display: none;" required></textarea>

                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle"></i>
                            支持Markdown语法：**粗体**、*斜体*、`代码`、[链接](url)、![图片](url)、表格、列表等
                        </div>
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
                               placeholder="留空使用文章标题">
                        <div class="form-text">建议长度：50-60个字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">SEO描述</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3" 
                                  placeholder="留空使用文章摘要"></textarea>
                        <div class="form-text">建议长度：150-160个字符</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">关键词</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               placeholder="用逗号分隔多个关键词">
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
                            <option value="draft">草稿</option>
                            <option value="published">发布</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="published_at" class="form-label">发布时间</label>
                        <input type="datetime-local" class="form-control" id="published_at" name="published_at">
                        <div class="form-text">留空使用当前时间</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                            <label class="form-check-label" for="is_featured">
                                推荐文章
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1" checked>
                            <label class="form-check-label" for="allow_comments">
                                允许评论
                            </label>
                        </div>
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
                                <option value="<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">标签</label>
                        <select class="form-select" id="tags" name="tags[]" multiple>
                            <?php foreach ($tags as $tag): ?>
                                <option value="<?= $tag['id'] ?>">
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
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">上传图片</label>
                        <input type="file" class="form-control" id="featured_image" name="featured_image" 
                               accept="image/*">
                        <div class="form-text">支持JPG、PNG、GIF格式，建议尺寸：800x400px</div>
                    </div>
                    
                    <div id="imagePreview" class="d-none">
                        <img id="previewImg" src="" alt="预览" class="img-fluid rounded">
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage()">
                            <i class="fas fa-trash"></i> 移除图片
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- 操作按钮 -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 保存文章
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

<!-- 简单编辑器资源 -->
<link rel="stylesheet" href="/css/simple-editor.css">
<script src="/js/simple-editor.js"></script>

<script>
// 自动生成slug
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^\w\s-]/g, '') // 移除特殊字符
        .replace(/[\s_-]+/g, '-') // 替换空格和下划线为连字符
        .replace(/^-+|-+$/g, ''); // 移除首尾连字符
    
    document.getElementById('slug').value = slug;
});

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

// 移除图片
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

// 简单编辑器初始化
let simpleEditor;

// 初始化编辑器
document.addEventListener('DOMContentLoaded', function() {
    initializeSimpleEditor();
});

// 初始化简单编辑器
function initializeSimpleEditor() {
    try {
        simpleEditor = new SimpleMarkdownEditor('simple-markdown-editor', {
            height: '400px',
            placeholder: '请输入文章内容，支持Markdown语法...',
            toolbar: true,
            preview: true
        });

        console.log('简单编辑器初始化成功');
    } catch (error) {
        console.error('编辑器初始化失败:', error);
        // 降级到普通textarea
        fallbackToSimpleTextarea();
    }
}

// 降级到简单textarea
function fallbackToSimpleTextarea() {
    const editorContainer = document.getElementById('simple-markdown-editor');

    if (editorContainer) {
        editorContainer.innerHTML = `
            <div class="simple-fallback">
                <textarea id="fallback-content" class="form-control" rows="20"
                          placeholder="请输入文章内容，支持Markdown语法..."
                          style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; font-size: 14px;"></textarea>
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        编辑器加载失败，已降级为简单文本模式。仍支持Markdown语法。
                    </small>
                </div>
            </div>
        `;

        // 绑定内容同步
        const fallbackTextarea = document.getElementById('fallback-content');
        const hiddenTextarea = document.getElementById('content');

        if (fallbackTextarea && hiddenTextarea) {
            fallbackTextarea.addEventListener('input', function() {
                hiddenTextarea.value = this.value;
            });
        }

        console.log('已降级到简单textarea模式');
    }
}

// 表单提交前处理
document.getElementById('postForm').addEventListener('submit', function(e) {
    // 同步编辑器内容到隐藏的textarea
    if (simpleEditor) {
        try {
            const content = simpleEditor.getValue();
            document.getElementById('content').value = content;
        } catch (error) {
            console.error('获取编辑器内容失败:', error);
        }
    } else {
        // 降级模式下，从fallback textarea获取内容
        const fallbackTextarea = document.getElementById('fallback-content');
        if (fallbackTextarea) {
            document.getElementById('content').value = fallbackTextarea.value;
        }
    }
});




</script>


