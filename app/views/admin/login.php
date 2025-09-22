<?php
/**
 * Admin Login View
 * Clean and modern login interface
 */
?>

<div class="login-card">
    <div class="login-header">
        <i class="fas fa-shield-alt fa-2x mb-3"></i>
        <h2>管理员登录</h2>
        <p class="mb-0">请输入您的登录凭据</p>
    </div>
    
    <div class="login-body">
        <form method="POST" action="<?= SITE_URL ?>/admin/login" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> 用户名或邮箱
                </label>
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="请输入用户名或邮箱" required autofocus>
                <div class="invalid-feedback">
                    请输入用户名或邮箱
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> 密码
                </label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="请输入密码" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback">
                    请输入密码
                </div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                <label class="form-check-label" for="remember">
                    记住我 (30天)
                </label>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt"></i> 登录
                </button>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-home"></i>
                <a href="<?= SITE_URL ?>" class="text-decoration-none">返回网站首页</a>
            </small>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
