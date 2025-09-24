<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .banner-preview {
            height: 400px;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .banner-bg {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
            position: absolute;
        }
        .banner-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .banner-content {
            text-align: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .banner-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .banner-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .settings-panel {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2">
                        <i class="fas fa-eye text-primary me-2"></i>
                        Banner预览
                    </h1>
                    <div>
                        <a href="<?= SITE_URL ?>/admin/banner" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> 返回设置
                        </a>
                        <a href="<?= SITE_URL ?>" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i> 查看网站
                        </a>
                    </div>
                </div>

                <!-- 当前设置信息 -->
                <div class="settings-panel">
                    <h5><i class="fas fa-cog me-2"></i>当前预览设置</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>背景图片：</strong> <?= htmlspecialchars(substr($settings['banner_image'], 0, 60)) ?>...</p>
                            <p><strong>标题：</strong> <?= htmlspecialchars($settings['banner_title'] ?: '(无标题)') ?></p>
                            <p><strong>副标题：</strong> <?= htmlspecialchars($settings['banner_subtitle'] ?: SITE_DESCRIPTION) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>遮罩透明度：</strong> <?= round($settings['overlay_opacity'] * 100) ?>%</p>
                            <p><strong>启用状态：</strong> <?= isset($settings['banner_enabled']) ? '启用' : '禁用' ?></p>
                            <p><strong>视差效果：</strong> <?= isset($settings['parallax_enabled']) ? '启用' : '禁用' ?></p>
                        </div>
                    </div>
                </div>

                <!-- Banner预览 -->
                <div class="banner-preview">
                    <div class="banner-bg" style="background-image: url('<?= htmlspecialchars($settings['banner_image']) ?>');"></div>
                    <div class="banner-overlay" style="background-color: rgba(0, 0, 0, <?= $settings['overlay_opacity'] ?>);">
                        <div class="banner-content">
                            <?php if (!empty($settings['banner_title'])): ?>
                                <div class="banner-title">
                                    <?= htmlspecialchars($settings['banner_title']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="banner-subtitle">
                                <?= htmlspecialchars($settings['banner_subtitle'] ?: SITE_DESCRIPTION) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 预览说明 -->
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>预览说明：</strong>
                    这是Banner的预览效果。实际网站中的Banner可能会根据不同的屏幕尺寸和设备有所调整。
                    如果满意当前效果，请返回设置页面保存配置。
                </div>

                <!-- 操作按钮 -->
                <div class="text-center mt-4">
                    <a href="<?= SITE_URL ?>/admin/banner" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>返回并保存设置
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-lg ms-2">
                        <i class="fas fa-print me-2"></i>打印预览
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 自动调整预览高度
        function adjustPreviewHeight() {
            const preview = document.querySelector('.banner-preview');
            const windowHeight = window.innerHeight;
            const maxHeight = Math.min(windowHeight * 0.6, 500);
            preview.style.height = maxHeight + 'px';
        }

        // 页面加载时调整高度
        document.addEventListener('DOMContentLoaded', adjustPreviewHeight);
        window.addEventListener('resize', adjustPreviewHeight);

        // 添加一些交互效果
        document.querySelector('.banner-preview').addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.3s ease';
        });

        document.querySelector('.banner-preview').addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    </script>
</body>
</html>
