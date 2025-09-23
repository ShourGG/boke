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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">文章内容</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="editorMode" id="markdownMode" value="markdown" checked>
                        <label class="btn btn-outline-primary" for="markdownMode">
                            <i class="fab fa-markdown"></i> Markdown
                        </label>
                        <input type="radio" class="btn-check" name="editorMode" id="richMode" value="rich">
                        <label class="btn btn-outline-primary" for="richMode">
                            <i class="fas fa-edit"></i> 富文本
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="content" class="form-label">正文内容 <span class="text-danger">*</span></label>

                        <!-- Markdown编辑器 -->
                        <div id="markdownEditor" class="editor-container">
                            <div id="editormd">
                                <textarea id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
                            </div>
                        </div>

                        <!-- 富文本编辑器 -->
                        <div id="richEditor" class="editor-container d-none">
                            <div id="quillEditor" style="height: 400px;"></div>
                            <textarea id="richContent" name="rich_content" style="display: none;"></textarea>
                        </div>

                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle"></i>
                            <strong>Markdown模式：</strong>支持完整Markdown语法、代码高亮、数学公式、流程图等
                            <br>
                            <strong>富文本模式：</strong>所见即所得编辑，支持图片上传、表格、链接等
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

// 编辑器初始化
let markdownEditor;
let quillEditor;

// 初始化编辑器
document.addEventListener('DOMContentLoaded', function() {
    initializeEditors();

    // 编辑器模式切换
    document.querySelectorAll('input[name="editorMode"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            switchEditorMode(this.value);
        });
    });
});

// 初始化编辑器
function initializeEditors() {
    // 初始化Markdown编辑器 (Editor.md)
    initMarkdownEditor();

    // 初始化富文本编辑器 (Quill)
    initRichEditor();
}

// 初始化Markdown编辑器
function initMarkdownEditor() {
    // 动态加载Editor.md
    if (typeof editormd === 'undefined') {
        loadEditorMd();
    } else {
        createMarkdownEditor();
    }
}

// 加载Editor.md资源
function loadEditorMd() {
    // 加载CSS
    const editormdCSS = document.createElement('link');
    editormdCSS.rel = 'stylesheet';
    editormdCSS.href = 'https://unpkg.com/editor.md@1.5.0/css/editormd.min.css';
    document.head.appendChild(editormdCSS);

    // 加载依赖库
    const jqueryScript = document.createElement('script');
    jqueryScript.src = 'https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js';
    jqueryScript.onload = function() {
        // jQuery加载完成后加载Editor.md
        const editormdJS = document.createElement('script');
        editormdJS.src = 'https://unpkg.com/editor.md@1.5.0/editormd.min.js';
        editormdJS.onload = function() {
            // 确保editormd全局可用
            setTimeout(function() {
                if (typeof editormd !== 'undefined') {
                    createMarkdownEditor();
                } else {
                    console.error('Editor.md failed to load');
                    // 降级到简单textarea
                    fallbackToTextarea();
                }
            }, 100);
        };
        editormdJS.onerror = function() {
            console.error('Failed to load Editor.md');
            fallbackToTextarea();
        };
        document.head.appendChild(editormdJS);
    };
    jqueryScript.onerror = function() {
        console.error('Failed to load jQuery');
        fallbackToTextarea();
    };
    document.head.appendChild(jqueryScript);
}

// 创建Markdown编辑器
function createMarkdownEditor() {
    try {
        markdownEditor = editormd("editormd", {
            width: "100%",
            height: 500,
            syncScrolling: "single",
            path: "https://unpkg.com/editor.md@1.5.0/lib/",
            placeholder: "请输入文章内容，支持Markdown语法...",
            saveHTMLToTextarea: true,
            searchReplace: true,
            watch: true,
            htmlDecode: "style,script,iframe|on*",
            toolbar: true,
            previewCodeHighlight: true,
            emoji: true,
            taskList: true,
            tocm: true,
            tex: true,
            flowChart: true,
            sequenceDiagram: true,
            dialogLockScreen: false,
            dialogShowMask: false,
            dialogDraggable: false,
            dialogMaskOpacity: 0.4,
            dialogMaskBgColor: "#000",
            imageUpload: true,
            imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL: "/admin/upload/image",
            onload: function() {
                console.log('Markdown编辑器加载完成');
            }
        });
    } catch (error) {
        console.error('创建Markdown编辑器失败:', error);
        fallbackToTextarea();
    }
}

// 降级到简单textarea
function fallbackToTextarea() {
    const markdownContainer = document.getElementById('markdownEditor');
    const editormdDiv = document.getElementById('editormd');

    // 隐藏编辑器模式切换按钮
    const modeButtons = document.querySelector('.btn-group');
    if (modeButtons) {
        modeButtons.style.display = 'none';
    }

    // 获取现有内容
    const existingContent = document.getElementById('content').value || '';

    // 创建简单的textarea
    editormdDiv.innerHTML = `
        <textarea id="content" name="content" class="form-control" rows="20"
                  placeholder="请输入文章内容，支持Markdown语法..." required>${existingContent}</textarea>
        <div class="mt-2">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                编辑器加载失败，已降级为简单文本模式。仍支持Markdown语法。
            </small>
        </div>
    `;

    console.log('已降级到简单textarea模式');
}

// 隐藏富文本选项
function hideRichTextOption() {
    const richModeButton = document.getElementById('richMode');
    const richModeLabel = document.querySelector('label[for="richMode"]');

    if (richModeButton && richModeLabel) {
        richModeButton.style.display = 'none';
        richModeLabel.style.display = 'none';

        // 确保Markdown模式被选中
        const markdownMode = document.getElementById('markdownMode');
        if (markdownMode) {
            markdownMode.checked = true;
        }

        console.log('富文本编辑器不可用，已隐藏选项');
    }
}

// 初始化富文本编辑器
function initRichEditor() {
    // 动态加载Quill
    if (typeof Quill === 'undefined') {
        loadQuill();
    } else {
        createRichEditor();
    }
}

// 加载Quill资源
function loadQuill() {
    // 加载CSS
    const quillCSS = document.createElement('link');
    quillCSS.rel = 'stylesheet';
    quillCSS.href = 'https://unpkg.com/quill@1.3.7/dist/quill.snow.css';
    document.head.appendChild(quillCSS);

    // 加载JS
    const quillJS = document.createElement('script');
    quillJS.src = 'https://unpkg.com/quill@1.3.7/dist/quill.min.js';
    quillJS.onload = function() {
        // 确保Quill全局可用
        setTimeout(function() {
            if (typeof Quill !== 'undefined') {
                createRichEditor();
            } else {
                console.error('Quill failed to load');
                // 富文本编辑器加载失败，隐藏富文本选项
                hideRichTextOption();
            }
        }, 100);
    };
    quillJS.onerror = function() {
        console.error('Failed to load Quill');
        hideRichTextOption();
    };
    document.head.appendChild(quillJS);
}

// 创建富文本编辑器
function createRichEditor() {
    try {
        quillEditor = new Quill('#quillEditor', {
            theme: 'snow',
            placeholder: '请输入文章内容...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'font': [] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // 监听内容变化
        quillEditor.on('text-change', function() {
            document.getElementById('richContent').value = quillEditor.root.innerHTML;
        });

        console.log('富文本编辑器创建成功');
    } catch (error) {
        console.error('创建富文本编辑器失败:', error);
        hideRichTextOption();
    }
}

// 切换编辑器模式
function switchEditorMode(mode) {
    const markdownContainer = document.getElementById('markdownEditor');
    const richContainer = document.getElementById('richEditor');

    if (mode === 'markdown') {
        markdownContainer.classList.remove('d-none');
        richContainer.classList.add('d-none');

        // 如果有富文本内容，尝试转换为Markdown
        if (quillEditor && quillEditor.getText().trim()) {
            const richText = quillEditor.getText();
            if (markdownEditor) {
                markdownEditor.setValue(richText);
            }
        }
    } else {
        markdownContainer.classList.add('d-none');
        richContainer.classList.remove('d-none');

        // 如果有Markdown内容，设置到富文本编辑器
        if (markdownEditor && markdownEditor.getValue().trim()) {
            const markdownText = markdownEditor.getValue();
            if (quillEditor) {
                quillEditor.setText(markdownText);
            }
        }
    }
}

// 表单提交前处理
document.getElementById('postForm').addEventListener('submit', function(e) {
    const mode = document.querySelector('input[name="editorMode"]:checked').value;

    if (mode === 'markdown' && markdownEditor) {
        // Markdown模式：确保内容同步到textarea
        document.getElementById('content').value = markdownEditor.getValue();
    } else if (mode === 'rich' && quillEditor) {
        // 富文本模式：将HTML内容设置到content字段
        document.getElementById('content').value = quillEditor.root.innerHTML;
    }
});
</script>

<!-- 编辑器样式 -->
<style>
.editor-container {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
}

.btn-group .btn-check:checked + .btn {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

/* Markdown编辑器样式调整 */
.editormd {
    border: none !important;
}

.editormd .editormd-toolbar {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

/* 富文本编辑器样式调整 */
.ql-toolbar {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    background: #f8f9fa;
}

.ql-container {
    border-bottom: none !important;
    border-left: none !important;
    border-right: none !important;
}
</style>
