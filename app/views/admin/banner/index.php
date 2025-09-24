<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- 侧边栏 -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>主题设置</span>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/admin">
                                <i class="fas fa-tachometer-alt"></i> 仪表板
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= SITE_URL ?>/admin/banner">
                                <i class="fas fa-image"></i> Banner设置
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/admin/posts">
                                <i class="fas fa-file-alt"></i> 文章管理
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= SITE_URL ?>/admin/websites">
                                <i class="fas fa-globe"></i> 网站管理
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- 主内容区域 -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-image text-primary me-2"></i>
                        Banner设置
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?= SITE_URL ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-eye"></i> 查看网站
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if (isset($flash) && !empty($flash)): ?>
                    <?php foreach ($flash as $type => $message): ?>
                        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Banner设置表单 -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-cog me-2"></i>Banner配置
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="<?= SITE_URL ?>/admin/banner/update" method="POST" enctype="multipart/form-data">
                                    <!-- Banner启用状态 -->
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="banner_enabled" name="banner_enabled"
                                                   <?= $settings['banner_enabled'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="banner_enabled">
                                                启用Banner
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">关闭后将不显示Banner区域</small>
                                    </div>

                                    <!-- Banner图片 -->
                                    <div class="mb-3">
                                        <label for="banner_image" class="form-label">Banner背景图片URL</label>
                                        <input type="url" class="form-control" id="banner_image" name="banner_image"
                                               value="<?= htmlspecialchars($settings['banner_image']) ?>"
                                               placeholder="https://example.com/banner.jpg">
                                        <small class="form-text text-muted">推荐尺寸：1920x1080px，支持JPG、PNG、WebP格式</small>
                                    </div>

                                    <!-- 或上传图片 -->
                                    <div class="mb-3">
                                        <label for="banner_image_file" class="form-label">或上传新图片</label>
                                        <input type="file" class="form-control" id="banner_image_file" name="banner_image_file" 
                                               accept="image/jpeg,image/png,image/gif,image/webp">
                                        <small class="form-text text-muted">最大文件大小：5MB</small>
                                    </div>

                                    <!-- Banner标题 -->
                                    <div class="mb-3">
                                        <label for="banner_title" class="form-label">Banner标题</label>
                                        <input type="text" class="form-control" id="banner_title" name="banner_title"
                                               value="<?= htmlspecialchars($settings['banner_title']) ?>"
                                               placeholder="留空则不显示标题">
                                    </div>

                                    <!-- Banner副标题 -->
                                    <div class="mb-3">
                                        <label for="banner_subtitle" class="form-label">Banner副标题</label>
                                        <textarea class="form-control" id="banner_subtitle" name="banner_subtitle" rows="2"
                                                  placeholder="留空则使用网站描述"><?= htmlspecialchars($settings['banner_subtitle']) ?></textarea>
                                    </div>

                                    <!-- 遮罩透明度 -->
                                    <div class="mb-3">
                                        <label for="overlay_opacity" class="form-label">遮罩透明度</label>
                                        <input type="range" class="form-range" id="overlay_opacity" name="overlay_opacity"
                                               min="0" max="1" step="0.05" value="<?= $settings['overlay_opacity'] ?>">
                                        <div class="d-flex justify-content-between">
                                            <small>透明</small>
                                            <small id="opacity_value"><?= round($settings['overlay_opacity'] * 100) ?>%</small>
                                            <small>不透明</small>
                                        </div>
                                    </div>

                                    <!-- 视差效果 -->
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="parallax_enabled" name="parallax_enabled"
                                                   <?= $settings['parallax_enabled'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="parallax_enabled">
                                                启用视差滚动效果
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">滚动时Banner背景图片会有轻微移动效果</small>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> 保存设置
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="previewBanner()">
                                            <i class="fas fa-eye"></i> 预览效果
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 预览区域 -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-eye me-2"></i>当前效果
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="banner-preview" style="height: 200px; position: relative; overflow: hidden; border-radius: 8px;">
                                    <div style="background: url('<?= htmlspecialchars($settings['banner_image']) ?>') no-repeat center center;
                                                background-size: cover; width: 100%; height: 100%; position: absolute;">
                                        <div style="position: absolute; width: 100%; height: 100%;
                                                    background-color: rgba(0, 0, 0, <?= $settings['overlay_opacity'] ?>);
                                                    display: flex; align-items: center; justify-content: center;">
                                            <div class="text-center text-white">
                                                <?php if ($settings['banner_title']): ?>
                                                    <h6><?= htmlspecialchars($settings['banner_title']) ?></h6>
                                                <?php endif; ?>
                                                <small><?= htmlspecialchars($settings['banner_subtitle'] ?: SITE_DESCRIPTION) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">这是Banner的缩略预览效果</small>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 透明度滑块实时更新
        document.getElementById('overlay_opacity').addEventListener('input', function() {
            document.getElementById('opacity_value').textContent = Math.round(this.value * 100) + '%';
        });

        // 预览功能
        function previewBanner() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            
            // 创建预览表单
            const previewForm = document.createElement('form');
            previewForm.method = 'POST';
            previewForm.action = '<?= SITE_URL ?>/admin/banner/preview';
            previewForm.target = '_blank';
            
            // 添加表单数据
            for (let [key, value] of formData.entries()) {
                if (key !== 'banner_image_file') { // 排除文件上传
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    previewForm.appendChild(input);
                }
            }
            
            document.body.appendChild(previewForm);
            previewForm.submit();
            document.body.removeChild(previewForm);
        }
    </script>
</body>
</html>
