<?php
/**
 * Single Post View
 * Display individual blog post with comments and related posts
 */
?>

<article class="post-content">
    <!-- Post Header -->
    <header class="post-header mb-4">
        <h1 class="post-title display-6 fw-bold mb-3">
            <?= htmlspecialchars($post['title']) ?>
        </h1>
        
        <div class="post-meta d-flex flex-wrap align-items-center gap-3 mb-3">
            <span class="text-muted">
                <i class="fas fa-calendar"></i>
                发布于 <?= date('Y年m月d日', strtotime($post['published_at'])) ?>
            </span>
            
            <?php if ($post['category_name']): ?>
            <span>
                <a href="<?= SITE_URL ?>/category/<?= htmlspecialchars($post['category_slug']) ?>" 
                   class="badge text-decoration-none"
                   style="background-color: <?= htmlspecialchars($post['category_color'] ?? '#3498db') ?>">
                    <i class="fas fa-folder"></i> <?= htmlspecialchars($post['category_name']) ?>
                </a>
            </span>
            <?php endif; ?>
            
            <span class="text-muted">
                <i class="fas fa-eye"></i> <?= $post['view_count'] ?> 次阅读
            </span>
            
            <?php if ($post['comment_count'] > 0): ?>
            <span class="text-muted">
                <i class="fas fa-comments"></i> <?= $post['comment_count'] ?> 条评论
            </span>
            <?php endif; ?>
        </div>
        
        <!-- Tags -->
        <?php if (!empty($tags)): ?>
        <div class="post-tags mb-3">
            <i class="fas fa-tags text-muted me-2"></i>
            <?php foreach ($tags as $tag): ?>
            <a href="<?= SITE_URL ?>/tag/<?= htmlspecialchars($tag['slug']) ?>" 
               class="badge text-decoration-none me-1"
               style="background-color: <?= htmlspecialchars($tag['color']) ?>">
                <?= htmlspecialchars($tag['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </header>
    
    <!-- Featured Image -->
    <?php if ($post['featured_image']): ?>
    <div class="post-featured-image mb-4">
        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>" 
             class="img-fluid rounded shadow-sm" 
             alt="<?= htmlspecialchars($post['title']) ?>">
    </div>
    <?php endif; ?>
    
    <!-- Post Content -->
    <div class="post-body" id="post-content-view">
        <textarea style="display:none;" id="post-markdown-content"><?= htmlspecialchars($post['content']) ?></textarea>
    </div>
    
    <!-- Post Footer -->
    <footer class="post-footer mt-5 pt-4 border-top">
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">分享这篇文章</h6>
                <div class="social-share">
                    <a href="javascript:void(0)" onclick="shareToWeibo()" class="btn btn-outline-danger btn-sm me-2">
                        <i class="fab fa-weibo"></i> 微博
                    </a>
                    <a href="javascript:void(0)" onclick="copyLink()" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-link"></i> 复制链接
                    </a>
                    <a href="javascript:void(0)" onclick="shareToQQ()" class="btn btn-outline-info btn-sm">
                        <i class="fab fa-qq"></i> QQ空间
                    </a>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    最后更新：<?= date('Y年m月d日', strtotime($post['updated_at'])) ?>
                </small>
            </div>
        </div>
    </footer>
</article>

<!-- Related Posts -->
<?php if (!empty($relatedPosts)): ?>
<section class="related-posts mt-5">
    <h3 class="h5 mb-4">
        <i class="fas fa-thumbs-up text-primary"></i> 相关推荐
    </h3>
    <div class="row">
        <?php foreach ($relatedPosts as $relatedPost): ?>
        <div class="col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">
                        <a href="<?= SITE_URL ?>/post/<?= htmlspecialchars($relatedPost['slug']) ?>" 
                           class="text-decoration-none">
                            <?= htmlspecialchars($relatedPost['title']) ?>
                        </a>
                    </h6>
                    <?php if ($relatedPost['excerpt']): ?>
                    <p class="card-text text-muted small">
                        <?= htmlspecialchars(substr($relatedPost['excerpt'], 0, 80)) ?>...
                    </p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <?= date('m-d', strtotime($relatedPost['published_at'])) ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-eye"></i> <?= $relatedPost['view_count'] ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Comments Section -->
<section class="comments-section mt-5">
    <h3 class="h5 mb-4">
        <i class="fas fa-comments text-primary"></i> 
        评论 <span class="badge bg-secondary"><?= $post['comment_count'] ?></span>
    </h3>
    
    <!-- Comment Form -->
    <div class="comment-form mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">发表评论</h6>
                <form id="commentForm" class="needs-validation" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="author_name" class="form-label">姓名 *</label>
                            <input type="text" class="form-control" id="author_name" name="author_name" required>
                            <div class="invalid-feedback">请输入您的姓名</div>
                        </div>
                        <div class="col-md-6">
                            <label for="author_email" class="form-label">邮箱 *</label>
                            <input type="email" class="form-control" id="author_email" name="author_email" required>
                            <div class="invalid-feedback">请输入有效的邮箱地址</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="author_url" class="form-label">网站 (可选)</label>
                        <input type="url" class="form-control" id="author_url" name="author_url">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">评论内容 *</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required 
                                  placeholder="请输入您的评论..."></textarea>
                        <div class="invalid-feedback">请输入评论内容</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> 发表评论
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Comments List -->
    <div id="commentsList">
        <div class="text-center py-4">
            <i class="fas fa-comments fa-2x text-muted mb-2"></i>
            <p class="text-muted">暂无评论，快来发表第一条评论吧！</p>
        </div>
    </div>
</section>

<script>
// Share functions
function shareToWeibo() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://service.weibo.com/share/share.php?url=${url}&title=${title}`, '_blank');
}

function shareToQQ() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=${url}&title=${title}`, '_blank');
}

function copyLink() {
    if (window.copyToClipboard) {
        window.copyToClipboard(window.location.href);
    } else {
        // Fallback
        const textArea = document.createElement('textarea');
        textArea.value = window.location.href;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        if (window.showToast) {
            window.showToast('链接已复制到剪贴板', 'success');
        } else {
            alert('链接已复制到剪贴板');
        }
    }
}

// Comment form handling
document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        return;
    }
    
    // Here you would normally submit the comment via AJAX
    // For now, just show a message
    if (window.showToast) {
        window.showToast('评论功能正在开发中，敬请期待！', 'info');
    } else {
        alert('评论功能正在开发中，敬请期待！');
    }
});
</script>

<style>
.post-content {
    font-size: 1.1rem;
    line-height: 1.8;
}

.post-title {
    color: #2c3e50;
    line-height: 1.3;
}

.post-body {
    color: #444;
}

.post-body h1, .post-body h2, .post-body h3, 
.post-body h4, .post-body h5, .post-body h6 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.post-body p {
    margin-bottom: 1.5rem;
}

.post-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.post-body blockquote {
    border-left: 4px solid #3498db;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #666;
}

.post-body code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.9em;
}

.post-body pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
}

.social-share .btn {
    transition: transform 0.2s ease;
}

.social-share .btn:hover {
    transform: translateY(-2px);
}

.related-posts .card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

.comment-form .card {
    border: 2px dashed #dee2e6;
}

.comment-form .card:hover {
    border-color: #3498db;
}
</style>

<!-- Editor.md CSS for Markdown display -->
<link rel="stylesheet" href="<?= SITE_URL ?>/public/editor.md/css/editormd.preview.css">

<!-- Custom CSS to fix code block display issues -->
<style>
/* Fix code block display - remove line-by-line copy buttons */
.markdown-body pre code {
    display: block !important;
    white-space: pre !important;
    word-wrap: normal !important;
}

/* Improve code block styling */
.markdown-body pre {
    background: #f8f8f8 !important;
    border: 1px solid #e1e4e8 !important;
    border-radius: 6px !important;
    padding: 16px !important;
    overflow: auto !important;
    line-height: 1.45 !important;
}

/* Custom copy button styling */
.code-block-container {
    position: relative;
    margin: 20px 0;
}

.copy-code-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #007bff;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    z-index: 10;
    opacity: 0.8;
    transition: opacity 0.3s;
}

.copy-code-btn:hover {
    opacity: 1;
    background: #0056b3;
}

.copy-code-btn.copied {
    background: #28a745;
}

/* Hide any unwanted copy buttons */
.markdown-body pre .copy-btn,
.markdown-body pre .copy-button {
    display: none !important;
}

/* Ensure proper code formatting */
.markdown-body code {
    background: rgba(175, 184, 193, 0.2) !important;
    padding: 0.2em 0.4em !important;
    border-radius: 3px !important;
    font-size: 85% !important;
}

/* Fix inline code in pre blocks */
.markdown-body pre code {
    background: transparent !important;
    padding: 0 !important;
    border: none !important;
    font-size: 100% !important;
}
</style>

<!-- Editor.md JS for Markdown to HTML conversion -->
<script src="<?= SITE_URL ?>/public/editor.md/lib/jquery.min.js"></script>
<script src="<?= SITE_URL ?>/public/editor.md/lib/marked.min.js"></script>
<script src="<?= SITE_URL ?>/public/editor.md/lib/prettify.min.js"></script>

<!-- Additional libraries for advanced features -->
<script src="<?= SITE_URL ?>/public/editor.md/lib/raphael.min.js"></script>
<script src="<?= SITE_URL ?>/public/editor.md/lib/underscore.min.js"></script>
<script src="<?= SITE_URL ?>/public/editor.md/lib/flowchart.min.js"></script>
<script src="<?= SITE_URL ?>/public/editor.md/lib/jquery.flowchart.min.js"></script>
<script src="<?= SITE_URL ?>/public/editor.md/lib/sequence-diagram.min.js"></script>

<!-- KaTeX for math formulas - Use Editor.md compatible version -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.3.0/katex.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.3.0/katex.min.js"></script>

<script src="<?= SITE_URL ?>/public/editor.md/editormd.min.js"></script>

<script>
// Convert Markdown to HTML when page loads
$(document).ready(function() {
    // Get the markdown content
    var markdownContent = $('#post-markdown-content').val();

    if (markdownContent) {
        // Set Editor.md KaTeX URL to use the same version we loaded
        editormd.katexURL = {
            css: "https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.3.0/katex.min",
            js: "https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.3.0/katex.min"
        };

        // Mark KaTeX as loaded since we already loaded it
        editormd.$katex = katex;
        editormd.kaTeXLoaded = true;

        // Ensure jQuery flowChart plugin is available
        if (typeof $.fn.flowChart === 'undefined') {
            console.warn('flowChart plugin not loaded, disabling flowChart feature');
        }

        // Convert markdown to HTML using Editor.md
        editormd.markdownToHTML("post-content-view", {
            markdown: markdownContent,
            htmlDecode: "style,script,iframe",  // Filter dangerous tags
            emoji: true,
            taskList: true,
            tex: true,  // Math formulas with KaTeX
            flowChart: false,  // Disable auto flowChart to prevent content corruption
            sequenceDiagram: false,  // Disable auto sequenceDiagram to prevent content corruption
            tocm: true,  // Table of contents
            autoLoadKaTeX: false,  // Don't auto-load since we already loaded it
            // Improve code block rendering
            previewCodeHighlight: true,
        });

        // Manual processing for flowChart and sequenceDiagram from original markdown
        setTimeout(function() {
            try {
                // Extract and process flow charts from original markdown
                var flowRegex = /```flow\s*\n([\s\S]*?)\n```/g;
                var sequenceRegex = /```sequence\s*\n([\s\S]*?)\n```/g;

                // Debug: log the original markdown content
                console.log('Original markdown content:', markdownContent.substring(0, 500) + '...');
                console.log('Looking for flow and sequence patterns...');

                var flowMatch;
                var flowIndex = 0;
                while ((flowMatch = flowRegex.exec(markdownContent)) !== null) {
                    var flowCode = flowMatch[1].trim();
                    console.log('Found flow chart:', flowCode);

                    if (flowCode && typeof $.fn.flowChart !== 'undefined') {
                        try {
                            // Create a new div for the flowchart
                            var flowDiv = $('<div class="flowchart-custom" id="flowchart-' + flowIndex + '" style="text-align: center; margin: 20px 0;"></div>');
                            flowDiv.text(flowCode);

                            // Find the corresponding code block and replace it
                            var replaced = false;

                            // Debug: log all code blocks
                            console.log('Looking for code blocks to replace...');
                            $("#post-content-view pre").each(function(index) {
                                var codeText = $(this).find('code').text().trim();
                                console.log('Code block ' + index + ':', codeText.substring(0, 100) + '...');
                            });

                            // Try different selectors to find the flow chart text
                            var selectors = [
                                "#post-content-view pre code",
                                "#post-content-view code",
                                "#post-content-view p",
                                "#post-content-view div"
                            ];

                            for (var s = 0; s < selectors.length && !replaced; s++) {
                                $(selectors[s]).each(function() {
                                    var elementText = $(this).text().trim();
                                    // Check if this element contains the flow chart syntax
                                    if (elementText.indexOf('st=>start') !== -1 && elementText.indexOf('用户登陆') !== -1) {
                                        console.log('Found matching element with selector:', selectors[s]);
                                        console.log('Element text:', elementText.substring(0, 100) + '...');

                                        // Replace the parent element
                                        var parentToReplace = $(this).closest('pre').length > 0 ? $(this).closest('pre') : $(this);
                                        parentToReplace.replaceWith(flowDiv);
                                        flowDiv.flowChart();
                                        console.log('FlowChart ' + flowIndex + ' replaced and initialized successfully');
                                        replaced = true;
                                        return false; // break the loop
                                    }
                                });
                            }

                            if (!replaced) {
                                console.warn('Could not find matching element for flowchart, using fallback');
                                // Append to the end as fallback
                                $("#post-content-view").append(flowDiv);
                                flowDiv.flowChart();
                                console.log('FlowChart ' + flowIndex + ' appended and initialized');
                            }
                            flowIndex++;
                        } catch (flowError) {
                            console.error('FlowChart error:', flowError);
                        }
                    }
                }

                // Extract and process sequence diagrams from original markdown
                console.log('Starting sequence diagram processing...');
                var sequenceMatch;
                var sequenceIndex = 0;
                while ((sequenceMatch = sequenceRegex.exec(markdownContent)) !== null) {
                    var sequenceCode = sequenceMatch[1].trim();
                    console.log('Found sequence diagram:', sequenceCode);

                    if (sequenceCode && typeof $.fn.sequenceDiagram !== 'undefined') {
                        try {
                            // Create a new div for the sequence diagram
                            var sequenceDiv = $('<div class="sequence-diagram-custom" id="sequence-' + sequenceIndex + '" style="text-align: center; margin: 20px 0;"></div>');
                            sequenceDiv.text(sequenceCode);

                            // Find the corresponding code block and replace it
                            var replaced = false;

                            // Debug: log all code blocks for sequence diagram
                            console.log('Looking for sequence diagram code blocks...');
                            $("#post-content-view pre").each(function(index) {
                                var codeText = $(this).find('code').text().trim();
                                console.log('Code block ' + index + ' for sequence:', codeText.substring(0, 100) + '...');
                            });

                            // Try different selectors to find the sequence diagram text
                            var selectors = [
                                "#post-content-view pre code",
                                "#post-content-view code",
                                "#post-content-view p",
                                "#post-content-view div"
                            ];

                            for (var s = 0; s < selectors.length && !replaced; s++) {
                                $(selectors[s]).each(function() {
                                    var elementText = $(this).text().trim();
                                    // Check if this element contains the sequence diagram syntax
                                    if (elementText.indexOf('Andrew->China') !== -1 && elementText.indexOf('Says Hello') !== -1) {
                                        console.log('Found matching sequence element with selector:', selectors[s]);
                                        console.log('Element text:', elementText.substring(0, 100) + '...');

                                        // Replace the parent element
                                        var parentToReplace = $(this).closest('pre').length > 0 ? $(this).closest('pre') : $(this);
                                        parentToReplace.replaceWith(sequenceDiv);
                                        sequenceDiv.sequenceDiagram({theme: "simple"});
                                        console.log('SequenceDiagram ' + sequenceIndex + ' replaced and initialized successfully');
                                        replaced = true;
                                        return false; // break the loop
                                    }
                                });
                            }

                            if (!replaced) {
                                console.warn('Could not find matching element for sequence diagram, using fallback');
                                // Append to the end as fallback
                                $("#post-content-view").append(sequenceDiv);
                                sequenceDiv.sequenceDiagram({theme: "simple"});
                                console.log('SequenceDiagram ' + sequenceIndex + ' appended and initialized');
                            }
                            sequenceIndex++;
                        } catch (seqError) {
                            console.error('SequenceDiagram error:', seqError);
                        }
                    }
                }

                console.log('Diagram processing summary:');
                console.log('- Flow charts found and processed:', flowIndex);
                console.log('- Sequence diagrams found and processed:', sequenceIndex);

                if (flowIndex === 0 && sequenceIndex === 0) {
                    console.log('No flow charts or sequence diagrams found in markdown');
                }

            } catch (e) {
                console.error('Error processing diagrams:', e);
            }
        }, 200);

        // Add copy buttons to code blocks
        setTimeout(function() {
            $("#post-content-view pre").each(function(index) {
                var $pre = $(this);
                var $code = $pre.find('code');

                // Skip if this is a diagram or already has a copy button
                if ($pre.hasClass('flowchart-custom') || $pre.hasClass('sequence-diagram-custom') || $pre.find('.copy-code-btn').length > 0) {
                    return;
                }

                // Wrap the pre element in a container
                var $container = $('<div class="code-block-container"></div>');
                $pre.wrap($container);

                // Create copy button
                var $copyBtn = $('<button class="copy-code-btn" title="复制代码">复制</button>');

                // Add click event
                $copyBtn.click(function() {
                    var codeText = $code.text();

                    // Use modern clipboard API if available
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(codeText).then(function() {
                            showCopySuccess($copyBtn);
                        }).catch(function(err) {
                            console.error('复制失败:', err);
                            fallbackCopyTextToClipboard(codeText, $copyBtn);
                        });
                    } else {
                        // Fallback for older browsers
                        fallbackCopyTextToClipboard(codeText, $copyBtn);
                    }
                });

                // Add button to container
                $pre.parent().append($copyBtn);
            });

            console.log('Copy buttons added to code blocks');
        }, 300);

        // Copy success feedback
        function showCopySuccess($btn) {
            $btn.text('已复制!').addClass('copied');
            setTimeout(function() {
                $btn.text('复制').removeClass('copied');
            }, 2000);
        }

        // Fallback copy function for older browsers
        function fallbackCopyTextToClipboard(text, $btn) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess($btn);
                } else {
                    console.error('复制命令失败');
                }
            } catch (err) {
                console.error('复制失败:', err);
            }

            document.body.removeChild(textArea);
        }
    }
});
</script>
