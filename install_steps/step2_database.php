<h2>数据库配置</h2>
<p>请输入您的数据库连接信息：</p>

<form method="POST">
    <div class="mb-3">
        <label for="db_host" class="form-label">数据库主机</label>
        <input type="text" class="form-control" id="db_host" name="db_host" 
               value="<?= $_POST['db_host'] ?? 'localhost' ?>" required>
        <div class="form-text">通常为 localhost</div>
    </div>
    
    <div class="mb-3">
        <label for="db_name" class="form-label">数据库名称</label>
        <input type="text" class="form-control" id="db_name" name="db_name" 
               value="<?= $_POST['db_name'] ?? 'koi_blog' ?>" required>
        <div class="form-text">请确保数据库已经创建</div>
    </div>
    
    <div class="mb-3">
        <label for="db_user" class="form-label">数据库用户名</label>
        <input type="text" class="form-control" id="db_user" name="db_user" 
               value="<?= $_POST['db_user'] ?? '' ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="db_pass" class="form-label">数据库密码</label>
        <input type="password" class="form-control" id="db_pass" name="db_pass" 
               value="<?= $_POST['db_pass'] ?? '' ?>">
    </div>
    
    <div class="alert alert-warning">
        <strong>注意：</strong>请确保数据库用户具有以下权限：
        <code>SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP</code>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="install.php?step=1" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 上一步
        </a>
        <button type="submit" class="btn btn-primary">
            测试连接 <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</form>
