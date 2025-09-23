<h2>站点配置</h2>
<p>请设置您的站点基本信息：</p>

<form method="POST">
    <div class="mb-3">
        <label for="site_name" class="form-label">站点名称</label>
        <input type="text" class="form-control" id="site_name" name="site_name" 
               value="<?= $_POST['site_name'] ?? 'Koi Blog' ?>" required>
        <div class="form-text">将显示在网站标题和页面头部</div>
    </div>
    
    <div class="mb-3">
        <label for="site_url" class="form-label">站点URL</label>
        <input type="url" class="form-control" id="site_url" name="site_url" 
               value="<?= $_POST['site_url'] ?? 'http://' . $_SERVER['HTTP_HOST'] ?>" required>
        <div class="form-text">您的网站完整地址，不要以斜杠结尾</div>
    </div>
    
    <div class="mb-3">
        <label for="admin_email" class="form-label">联系邮箱</label>
        <input type="email" class="form-control" id="admin_email" name="admin_email" 
               value="<?= $_POST['admin_email'] ?? '' ?>" required>
        <div class="form-text">用于网站联系和系统通知</div>
    </div>
    
    <div class="alert alert-info">
        <h6>系统将自动配置：</h6>
        <ul class="mb-0">
            <li>安全密钥和加密盐值</li>
            <li>文件上传限制（5MB）</li>
            <li>分页设置（文章10篇/页，网站20个/页）</li>
            <li>时区设置（Asia/Shanghai）</li>
        </ul>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="install.php?step=4" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 上一步
        </a>
        <button type="submit" class="btn btn-success">
            生成配置文件 <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</form>
