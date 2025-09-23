<div class="text-center">
    <div class="mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
    </div>
    
    <h2 class="text-success">安装完成！</h2>
    <p class="lead">恭喜您，Koi Blog 已经成功安装！</p>
    
    <div class="alert alert-success">
        <h6>安装摘要：</h6>
        <ul class="mb-0 text-start">
            <li>✅ 数据库连接配置完成</li>
            <li>✅ 数据库表创建成功</li>
            <li>✅ 管理员账户创建完成</li>
            <li>✅ 配置文件生成成功</li>
        </ul>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🏠 访问网站</h5>
                    <p class="card-text">查看您的博客首页</p>
                    <a href="index.php" class="btn btn-primary">访问首页</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">⚙️ 管理后台</h5>
                    <p class="card-text">登录管理后台发布内容</p>
                    <a href="admin.php" class="btn btn-success">进入后台</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-warning mt-4">
        <h6>安全提醒：</h6>
        <ul class="mb-0 text-start">
            <li>🔒 请删除 <code>install.php</code> 文件以防止重复安装</li>
            <li>🔒 建议修改 <code>config/config.php</code> 文件权限为只读</li>
            <li>🔒 定期备份数据库和网站文件</li>
            <li>🔒 保持系统和插件更新</li>
        </ul>
    </div>
    
    <div class="mt-4">
        <h6>需要帮助？</h6>
        <p class="text-muted">
            查看项目文档：<code>.arc/</code> 目录<br>
            开发者指南：<code>.arc/context/developer_onboarding.md</code>
        </p>
    </div>
    
    <div class="mt-4">
        <button onclick="deleteInstaller()" class="btn btn-danger">
            <i class="fas fa-trash"></i> 删除安装文件
        </button>
    </div>
</div>

<script>
function deleteInstaller() {
    if (confirm('确定要删除安装文件吗？删除后将无法重新安装。')) {
        fetch('install.php?action=delete', {method: 'POST'})
            .then(response => response.text())
            .then(data => {
                alert('安装文件已删除！');
                window.location.href = 'index.php';
            })
            .catch(error => {
                alert('删除失败，请手动删除 install.php 文件');
            });
    }
}
</script>

<?php
// 处理删除安装文件的请求
if (isset($_GET['action']) && $_GET['action'] === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $files_to_delete = [
        'install.php',
        'install_steps/step1_welcome.php',
        'install_steps/step2_database.php',
        'install_steps/step3_tables.php',
        'install_steps/step4_admin.php',
        'install_steps/step5_config.php',
        'install_steps/step6_complete.php'
    ];
    
    foreach ($files_to_delete as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }
    
    // 删除安装步骤目录
    if (is_dir('install_steps')) {
        rmdir('install_steps');
    }
    
    echo 'success';
    exit;
}
?>
