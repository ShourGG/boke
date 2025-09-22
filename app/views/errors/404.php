<?php
/**
 * 404 Error Page
 * User-friendly page not found error
 */
?>

<div class="error-page text-center py-5">
    <div class="error-content">
        <div class="error-code mb-4">
            <i class="fas fa-fish fa-4x text-muted mb-3"></i>
            <h1 class="display-1 fw-bold text-primary">404</h1>
        </div>
        
        <div class="error-message mb-4">
            <h2 class="h3 mb-3">页面未找到</h2>
            <p class="text-muted lead">
                抱歉，您访问的页面不存在或已被移动。
            </p>
        </div>
        
        <div class="error-actions">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Search Form -->
                    <form method="GET" action="<?= SITE_URL ?>/search" class="mb-4">
                        <div class="input-group input-group-lg">
                            <input type="text" name="q" class="form-control" 
                                   placeholder="搜索您要找的内容..." 
                                   value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> 搜索
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="quick-links">
                <a href="<?= SITE_URL ?>" class="btn btn-primary btn-lg me-2 mb-2">
                    <i class="fas fa-home"></i> 返回首页
                </a>
                <a href="<?= SITE_URL ?>/websites" class="btn btn-success btn-lg me-2 mb-2">
                    <i class="fas fa-globe"></i> 网站收录
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg mb-2">
                    <i class="fas fa-arrow-left"></i> 返回上页
                </a>
            </div>
        </div>
    </div>
    
    <!-- Helpful Links -->
    <div class="helpful-links mt-5">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-newspaper fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">最新文章</h5>
                        <p class="card-text text-muted">浏览最新发布的博客文章</p>
                        <a href="<?= SITE_URL ?>" class="btn btn-outline-primary">
                            查看文章
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-sitemap fa-2x text-success mb-3"></i>
                        <h5 class="card-title">网站收录</h5>
                        <p class="card-text text-muted">发现优质的网站资源</p>
                        <a href="<?= SITE_URL ?>/websites" class="btn btn-outline-success">
                            浏览网站
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope fa-2x text-info mb-3"></i>
                        <h5 class="card-title">联系我们</h5>
                        <p class="card-text text-muted">有问题？欢迎联系我们</p>
                        <a href="mailto:<?= defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@example.com' ?>" 
                           class="btn btn-outline-info">
                            发送邮件
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fun Facts -->
    <div class="fun-facts mt-5 p-4 bg-light rounded">
        <h5 class="mb-3"><i class="fas fa-lightbulb text-warning"></i> 小贴士</h5>
        <div class="row text-start">
            <div class="col-md-6">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        检查URL拼写是否正确
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        尝试使用搜索功能查找内容
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        浏览网站分类和标签
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        查看最新发布的内容
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    min-height: 60vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.error-code {
    position: relative;
}

.error-code::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(155, 89, 182, 0.1) 100%);
    border-radius: 50%;
    z-index: -1;
}

.error-code h1 {
    font-size: 8rem;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.quick-links .btn {
    transition: transform 0.2s ease;
}

.quick-links .btn:hover {
    transform: translateY(-2px);
}

.helpful-links .card {
    transition: transform 0.2s ease;
}

.helpful-links .card:hover {
    transform: translateY(-5px);
}

.fun-facts {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .error-code h1 {
        font-size: 4rem;
    }
    
    .quick-links .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* Animation */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.error-code i {
    animation: float 3s ease-in-out infinite;
}
</style>

<script>
// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Focus on search input
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        searchInput.focus();
    }
    
    // Add click tracking for helpful links
    const helpfulLinks = document.querySelectorAll('.helpful-links .btn');
    helpfulLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            // You could add analytics tracking here
            console.log('404 helpful link clicked:', this.textContent.trim());
        });
    });
    
    // Add some fun easter egg
    let clickCount = 0;
    const errorCode = document.querySelector('.error-code h1');
    if (errorCode) {
        errorCode.addEventListener('click', function() {
            clickCount++;
            if (clickCount === 5) {
                this.style.transform = 'rotate(360deg)';
                this.style.transition = 'transform 1s ease';
                setTimeout(() => {
                    this.style.transform = '';
                    clickCount = 0;
                }, 1000);
            }
        });
    }
});
</script>
