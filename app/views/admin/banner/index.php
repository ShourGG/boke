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
                                                  placeholder="留空则使用网站描述，支持多行文字用|分隔"><?= htmlspecialchars($settings['banner_subtitle']) ?></textarea>
                                        <small class="form-text text-muted">支持多行文字：用 | 分隔多行，如 "第一行|第二行|第三行"</small>
                                    </div>

                                    <!-- 副标题样式配置 -->
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="fas fa-palette me-2"></i>副标题样式配置
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- 文字颜色 -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="subtitle_color" class="form-label">文字颜色</label>
                                                    <div class="input-group">
                                                        <input type="color" class="form-control form-control-color" id="subtitle_color" name="subtitle_color"
                                                               value="<?= htmlspecialchars($settings['subtitle_color'] ?? '#ffffff') ?>" title="选择文字颜色">
                                                        <input type="text" class="form-control" id="subtitle_color_text"
                                                               value="<?= htmlspecialchars($settings['subtitle_color'] ?? '#ffffff') ?>" placeholder="#ffffff">
                                                    </div>
                                                </div>

                                                <!-- 字体家族 -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="subtitle_font_family" class="form-label">字体家族</label>
                                                    <select class="form-select" id="subtitle_font_family" name="subtitle_font_family">
                                                        <option value="inherit" <?= ($settings['subtitle_font_family'] ?? 'inherit') === 'inherit' ? 'selected' : '' ?>>继承默认</option>
                                                        <option value="'Helvetica Neue', Helvetica, Arial, sans-serif" <?= ($settings['subtitle_font_family'] ?? '') === "'Helvetica Neue', Helvetica, Arial, sans-serif" ? 'selected' : '' ?>>Helvetica</option>
                                                        <option value="'Times New Roman', Times, serif" <?= ($settings['subtitle_font_family'] ?? '') === "'Times New Roman', Times, serif" ? 'selected' : '' ?>>Times New Roman</option>
                                                        <option value="'Georgia', serif" <?= ($settings['subtitle_font_family'] ?? '') === "'Georgia', serif" ? 'selected' : '' ?>>Georgia</option>
                                                        <option value="'Courier New', Courier, monospace" <?= ($settings['subtitle_font_family'] ?? '') === "'Courier New', Courier, monospace" ? 'selected' : '' ?>>Courier New</option>
                                                        <option value="'Microsoft YaHei', '微软雅黑', sans-serif" <?= ($settings['subtitle_font_family'] ?? '') === "'Microsoft YaHei', '微软雅黑', sans-serif" ? 'selected' : '' ?>>微软雅黑</option>
                                                        <option value="'PingFang SC', 'Hiragino Sans GB', sans-serif" <?= ($settings['subtitle_font_family'] ?? '') === "'PingFang SC', 'Hiragino Sans GB', sans-serif" ? 'selected' : '' ?>>苹方</option>
                                                    </select>
                                                </div>

                                                <!-- 字体大小 -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="subtitle_font_size" class="form-label">字体大小</label>
                                                    <div class="input-group">
                                                        <input type="range" class="form-range" id="subtitle_font_size_range"
                                                               min="1" max="6" step="0.1" value="<?= floatval(str_replace('rem', '', $settings['subtitle_font_size'] ?? '3')) ?>">
                                                        <input type="text" class="form-control" id="subtitle_font_size" name="subtitle_font_size"
                                                               value="<?= htmlspecialchars($settings['subtitle_font_size'] ?? '3rem') ?>" placeholder="3rem">
                                                    </div>
                                                    <small class="form-text text-muted">当前: <span id="font_size_display"><?= htmlspecialchars($settings['subtitle_font_size'] ?? '3rem') ?></span></small>
                                                </div>

                                                <!-- 字体粗细 -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="subtitle_font_weight" class="form-label">字体粗细</label>
                                                    <select class="form-select" id="subtitle_font_weight" name="subtitle_font_weight">
                                                        <option value="100" <?= ($settings['subtitle_font_weight'] ?? '300') === '100' ? 'selected' : '' ?>>100 - 极细</option>
                                                        <option value="200" <?= ($settings['subtitle_font_weight'] ?? '300') === '200' ? 'selected' : '' ?>>200 - 很细</option>
                                                        <option value="300" <?= ($settings['subtitle_font_weight'] ?? '300') === '300' ? 'selected' : '' ?>>300 - 细体</option>
                                                        <option value="400" <?= ($settings['subtitle_font_weight'] ?? '300') === '400' ? 'selected' : '' ?>>400 - 正常</option>
                                                        <option value="500" <?= ($settings['subtitle_font_weight'] ?? '300') === '500' ? 'selected' : '' ?>>500 - 中等</option>
                                                        <option value="600" <?= ($settings['subtitle_font_weight'] ?? '300') === '600' ? 'selected' : '' ?>>600 - 半粗</option>
                                                        <option value="700" <?= ($settings['subtitle_font_weight'] ?? '300') === '700' ? 'selected' : '' ?>>700 - 粗体</option>
                                                        <option value="800" <?= ($settings['subtitle_font_weight'] ?? '300') === '800' ? 'selected' : '' ?>>800 - 很粗</option>
                                                        <option value="900" <?= ($settings['subtitle_font_weight'] ?? '300') === '900' ? 'selected' : '' ?>>900 - 极粗</option>
                                                    </select>
                                                </div>
                                                <!-- 渐变色配置 -->
                                                <div class="col-12 mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="subtitle_gradient_enabled" name="subtitle_gradient_enabled"
                                                               <?= ($settings['subtitle_gradient_enabled'] ?? 0) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="subtitle_gradient_enabled">
                                                            启用渐变色文字
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- 渐变色设置 -->
                                                <div id="gradient_settings" class="row" style="display: <?= ($settings['subtitle_gradient_enabled'] ?? 0) ? 'flex' : 'none' ?>;">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="subtitle_gradient_start" class="form-label">渐变起始色</label>
                                                        <input type="color" class="form-control form-control-color" id="subtitle_gradient_start" name="subtitle_gradient_start"
                                                               value="<?= htmlspecialchars($settings['subtitle_gradient_start'] ?? '#ffffff') ?>" title="选择渐变起始颜色">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="subtitle_gradient_end" class="form-label">渐变结束色</label>
                                                        <input type="color" class="form-control form-control-color" id="subtitle_gradient_end" name="subtitle_gradient_end"
                                                               value="<?= htmlspecialchars($settings['subtitle_gradient_end'] ?? '#f8f9fa') ?>" title="选择渐变结束颜色">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="subtitle_gradient_direction" class="form-label">渐变方向</label>
                                                        <select class="form-select" id="subtitle_gradient_direction" name="subtitle_gradient_direction">
                                                            <option value="135deg" <?= ($settings['subtitle_gradient_direction'] ?? '135deg') === '135deg' ? 'selected' : '' ?>>左上到右下</option>
                                                            <option value="45deg" <?= ($settings['subtitle_gradient_direction'] ?? '135deg') === '45deg' ? 'selected' : '' ?>>左下到右上</option>
                                                            <option value="90deg" <?= ($settings['subtitle_gradient_direction'] ?? '135deg') === '90deg' ? 'selected' : '' ?>>从上到下</option>
                                                            <option value="0deg" <?= ($settings['subtitle_gradient_direction'] ?? '135deg') === '0deg' ? 'selected' : '' ?>>从左到右</option>
                                                            <option value="180deg" <?= ($settings['subtitle_gradient_direction'] ?? '135deg') === '180deg' ? 'selected' : '' ?>>从右到左</option>
                                                            <option value="270deg" <?= ($settings['subtitle_gradient_direction'] ?? '135deg') === '270deg' ? 'selected' : '' ?>>从下到上</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- 动画效果 -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="subtitle_animation" class="form-label">动画效果</label>
                                                    <select class="form-select" id="subtitle_animation" name="subtitle_animation">
                                                        <option value="typewriter" <?= ($settings['subtitle_animation'] ?? 'typewriter') === 'typewriter' ? 'selected' : '' ?>>打字机效果</option>
                                                        <option value="fade" <?= ($settings['subtitle_animation'] ?? 'typewriter') === 'fade' ? 'selected' : '' ?>>淡入效果</option>
                                                        <option value="slide" <?= ($settings['subtitle_animation'] ?? 'typewriter') === 'slide' ? 'selected' : '' ?>>滑入效果</option>
                                                        <option value="none" <?= ($settings['subtitle_animation'] ?? 'typewriter') === 'none' ? 'selected' : '' ?>>无动画</option>
                                                    </select>
                                                </div>

                                                <!-- 文字阴影 -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="subtitle_text_shadow" class="form-label">文字阴影</label>
                                                    <select class="form-select" id="subtitle_text_shadow" name="subtitle_text_shadow">
                                                        <option value="none" <?= ($settings['subtitle_text_shadow'] ?? '') === 'none' ? 'selected' : '' ?>>无阴影</option>
                                                        <option value="0 1px 2px rgba(0,0,0,0.5)" <?= ($settings['subtitle_text_shadow'] ?? '') === '0 1px 2px rgba(0,0,0,0.5)' ? 'selected' : '' ?>>轻微阴影</option>
                                                        <option value="0 2px 4px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6)" <?= ($settings['subtitle_text_shadow'] ?? '') === '0 2px 4px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6)' ? 'selected' : '' ?>>标准阴影</option>
                                                        <option value="0 3px 6px rgba(0,0,0,0.9), 0 6px 12px rgba(0,0,0,0.7), 0 1px 0 rgba(0,0,0,1)" <?= ($settings['subtitle_text_shadow'] ?? '') === '0 3px 6px rgba(0,0,0,0.9), 0 6px 12px rgba(0,0,0,0.7), 0 1px 0 rgba(0,0,0,1)' ? 'selected' : '' ?>>强烈阴影</option>
                                                        <option value="2px 2px 0 rgba(0,0,0,0.8), 4px 4px 0 rgba(0,0,0,0.6)" <?= ($settings['subtitle_text_shadow'] ?? '') === '2px 2px 0 rgba(0,0,0,0.8), 4px 4px 0 rgba(0,0,0,0.6)' ? 'selected' : '' ?>>立体阴影</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
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
                                                    <h6 id="preview_title"><?= htmlspecialchars($settings['banner_title']) ?></h6>
                                                <?php endif; ?>
                                                <small id="preview_subtitle" style="
                                                    color: <?= htmlspecialchars($settings['subtitle_color'] ?? '#ffffff') ?>;
                                                    font-family: <?= htmlspecialchars($settings['subtitle_font_family'] ?? 'inherit') ?>;
                                                    font-size: <?= htmlspecialchars($settings['subtitle_font_size'] ?? '1rem') ?>;
                                                    font-weight: <?= htmlspecialchars($settings['subtitle_font_weight'] ?? '300') ?>;
                                                    text-shadow: <?= htmlspecialchars($settings['subtitle_text_shadow'] ?? '0 2px 4px rgba(0,0,0,0.8)') ?>;
                                                    <?php if (($settings['subtitle_gradient_enabled'] ?? 0)): ?>
                                                    background: linear-gradient(<?= htmlspecialchars($settings['subtitle_gradient_direction'] ?? '135deg') ?>, <?= htmlspecialchars($settings['subtitle_gradient_start'] ?? '#ffffff') ?> 0%, <?= htmlspecialchars($settings['subtitle_gradient_end'] ?? '#f8f9fa') ?> 100%);
                                                    -webkit-background-clip: text;
                                                    -webkit-text-fill-color: transparent;
                                                    background-clip: text;
                                                    <?php endif; ?>
                                                "><?= htmlspecialchars($settings['banner_subtitle'] ?: SITE_DESCRIPTION) ?></small>
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

        // 字体大小滑块实时更新
        document.getElementById('subtitle_font_size_range').addEventListener('input', function() {
            const value = this.value + 'rem';
            document.getElementById('subtitle_font_size').value = value;
            document.getElementById('font_size_display').textContent = value;
        });

        // 颜色选择器同步
        document.getElementById('subtitle_color').addEventListener('input', function() {
            document.getElementById('subtitle_color_text').value = this.value;
        });

        document.getElementById('subtitle_color_text').addEventListener('input', function() {
            document.getElementById('subtitle_color').value = this.value;
        });

        // 渐变色开关控制
        document.getElementById('subtitle_gradient_enabled').addEventListener('change', function() {
            const gradientSettings = document.getElementById('gradient_settings');
            gradientSettings.style.display = this.checked ? 'flex' : 'none';
            updatePreview();
        });

        // 实时预览更新函数
        function updatePreview() {
            const previewSubtitle = document.getElementById('preview_subtitle');
            if (!previewSubtitle) return;

            // 获取当前设置
            const color = document.getElementById('subtitle_color').value;
            const fontFamily = document.getElementById('subtitle_font_family').value;
            const fontSize = document.getElementById('subtitle_font_size').value;
            const fontWeight = document.getElementById('subtitle_font_weight').value;
            const textShadow = document.getElementById('subtitle_text_shadow').value;
            const gradientEnabled = document.getElementById('subtitle_gradient_enabled').checked;
            const gradientStart = document.getElementById('subtitle_gradient_start').value;
            const gradientEnd = document.getElementById('subtitle_gradient_end').value;
            const gradientDirection = document.getElementById('subtitle_gradient_direction').value;

            // 构建样式
            let style = `
                font-family: ${fontFamily};
                font-size: calc(${fontSize} * 0.3);
                font-weight: ${fontWeight};
                text-shadow: ${textShadow === 'none' ? 'none' : textShadow};
            `;

            if (gradientEnabled) {
                style += `
                    background: linear-gradient(${gradientDirection}, ${gradientStart} 0%, ${gradientEnd} 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                `;
            } else {
                style += `color: ${color};`;
            }

            previewSubtitle.style.cssText = style;
        }

        // 为所有样式控件添加事件监听
        const styleInputs = [
            'subtitle_color', 'subtitle_font_family', 'subtitle_font_size',
            'subtitle_font_weight', 'subtitle_text_shadow', 'subtitle_gradient_start',
            'subtitle_gradient_end', 'subtitle_gradient_direction'
        ];

        styleInputs.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            }
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
