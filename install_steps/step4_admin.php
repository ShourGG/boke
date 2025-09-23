<h2>创建管理员账户</h2>
<p>请设置您的管理员账户信息：</p>

<form method="POST">
    <div class="mb-3">
        <label for="admin_username" class="form-label">管理员用户名</label>
        <input type="text" class="form-control" id="admin_username" name="admin_username" 
               value="<?= $_POST['admin_username'] ?? 'admin' ?>" required>
        <div class="form-text">用于登录后台管理</div>
    </div>
    
    <div class="mb-3">
        <label for="admin_email" class="form-label">管理员邮箱</label>
        <input type="email" class="form-control" id="admin_email" name="admin_email" 
               value="<?= $_POST['admin_email'] ?? '' ?>" required>
        <div class="form-text">用于接收系统通知</div>
    </div>
    
    <div class="mb-3">
        <label for="admin_password" class="form-label">管理员密码</label>
        <input type="password" class="form-control" id="admin_password" name="admin_password" required>
        <div class="form-text">密码长度至少6位</div>
    </div>
    
    <div class="mb-3">
        <label for="confirm_password" class="form-label">确认密码</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    
    <div class="alert alert-warning">
        <strong>重要：</strong>请牢记您的管理员账户信息，这是您管理博客的唯一凭证！
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="install.php?step=2" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 上一步
        </a>
        <button type="submit" class="btn btn-primary">
            创建账户 <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</form>
