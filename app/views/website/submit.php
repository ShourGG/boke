<?php
/**
 * Website Submit View
 * 网站提交页面
 */
?>

<!-- Hero Section -->
<div class="hero-section bg-gradient text-white p-4 rounded mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-3">
                <i class="fas fa-plus text-warning"></i> 提交网站
            </h1>
            <p class="lead mb-3">分享您发现的优质网站资源，让更多人受益</p>
            <div class="d-flex gap-2">
                <a href="<?= SITE_URL ?>/websites" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-left"></i> 返回目录
                </a>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-upload display-1 text-warning opacity-75"></i>
        </div>
    </div>
</div>

<!-- 提交须知 -->
<div class="alert alert-info mb-4">
    <h5 class="alert-heading">
        <i class="fas fa-info-circle"></i> 提交须知
    </h5>
    <ul class="mb-0">
        <li>请确保提交的网站内容健康、合法，符合相关法律法规</li>
        <li>网站应具有一定的实用性或娱乐价值</li>
        <li>我们会在1-3个工作日内审核您的提交</li>
        <li>审核通过后，网站将出现在收录目录中</li>
        <li>如有疑问，请联系管理员</li>
    </ul>
</div>

<!-- 提交表单 -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-edit"></i> 网站信息
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" id="submitForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    网站名称 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="请输入网站名称" required>
                                <div class="form-text">网站的正式名称或标题</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url" class="form-label">
                                    网站地址 <span class="text-danger">*</span>
                                </label>
                                <input type="url" class="form-control" id="url" name="url" 
                                       placeholder="https://example.com" required>
                                <div class="form-text">完整的网站URL地址</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            网站描述 <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="请详细描述网站的功能、特色和用途..." required></textarea>
                        <div class="form-text">详细介绍网站的功能和特色，建议100-500字</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    网站分类 <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">请选择分类</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">选择最符合的网站分类</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tags" class="form-label">标签</label>
                                <input type="text" class="form-control" id="tags" name="tags" 
                                       placeholder="工具, 设计, 开发">
                                <div class="form-text">用逗号分隔多个标签，有助于用户搜索</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="mb-3">
                        <i class="fas fa-user"></i> 提交者信息
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="submitter_name" class="form-label">
                                    您的姓名 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="submitter_name" name="submitter_name" 
                                       placeholder="请输入您的姓名" required>
                                <div class="form-text">用于联系和确认提交</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="submitter_email" class="form-label">
                                    邮箱地址 <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="submitter_email" name="submitter_email" 
                                       placeholder="your@email.com" required>
                                <div class="form-text">用于接收审核结果通知</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                我确认提交的网站内容健康合法，同意网站收录条款 <span class="text-danger">*</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= SITE_URL ?>/websites" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> 取消
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> 提交网站
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 常见问题 -->
<div class="row mt-5">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle"></i> 常见问题
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#faq1">
                                什么样的网站会被收录？
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                我们优先收录具有实用价值、内容优质、设计精美的网站。包括但不限于：
                                工具类网站、学习资源、设计素材、开发工具、生活服务等。
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#faq2">
                                审核需要多长时间？
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                通常在1-3个工作日内完成审核。审核结果会通过邮件通知您。
                                如果超过一周未收到回复，请联系管理员。
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#faq3">
                                可以提交自己的网站吗？
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                当然可以！只要您的网站符合收录标准，我们欢迎您提交自己的作品。
                                请确保网站内容完整、功能正常。
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 表单验证
document.getElementById('submitForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const url = document.getElementById('url').value.trim();
    const description = document.getElementById('description').value.trim();
    const categoryId = document.getElementById('category_id').value;
    const submitterName = document.getElementById('submitter_name').value.trim();
    const submitterEmail = document.getElementById('submitter_email').value.trim();
    const agreeTerms = document.getElementById('agree_terms').checked;
    
    let errors = [];
    
    if (!title) {
        errors.push('请输入网站名称');
    }
    
    if (!url) {
        errors.push('请输入网站地址');
    } else if (!isValidUrl(url)) {
        errors.push('请输入有效的网站地址');
    }
    
    if (!description) {
        errors.push('请输入网站描述');
    } else if (description.length < 20) {
        errors.push('网站描述至少需要20个字符');
    }
    
    if (!categoryId) {
        errors.push('请选择网站分类');
    }
    
    if (!submitterName) {
        errors.push('请输入您的姓名');
    }
    
    if (!submitterEmail) {
        errors.push('请输入邮箱地址');
    } else if (!isValidEmail(submitterEmail)) {
        errors.push('请输入有效的邮箱地址');
    }
    
    if (!agreeTerms) {
        errors.push('请同意网站收录条款');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('请修正以下问题：\n\n' + errors.join('\n'));
        return false;
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

// 邮箱验证
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// 自动获取网站标题
document.getElementById('url').addEventListener('blur', function() {
    const url = this.value.trim();
    const titleField = document.getElementById('title');
    
    if (url && !titleField.value.trim() && isValidUrl(url)) {
        // 这里可以添加AJAX请求来获取网站标题
        // 暂时显示提示
        titleField.placeholder = '正在获取网站标题...';
        setTimeout(() => {
            titleField.placeholder = '请输入网站名称';
        }, 2000);
    }
});
</script>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}

.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #667eea;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}
</style>
