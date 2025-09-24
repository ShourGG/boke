<?php
/**
 * 图片灯箱功能测试页面
 * Test page for image lightbox functionality
 */

// 加载配置
require_once 'config/config.php';

$title = '图片灯箱功能测试';
$description = '测试博客文章中的图片灯箱功能';

// 包含主布局头部
include 'app/views/layouts/main.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-images me-2"></i>图片灯箱功能测试</h2>
                </div>
                <div class="card-body">
                    <p class="lead">点击下面的任意图片来测试灯箱功能。灯箱支持以下功能：</p>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>🖱️ 鼠标操作</h5>
                            <ul>
                                <li>点击图片打开灯箱</li>
                                <li>点击背景或X按钮关闭</li>
                                <li>鼠标滚轮缩放图片</li>
                                <li>拖拽移动放大的图片</li>
                                <li>双击重置缩放</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>⌨️ 键盘操作</h5>
                            <ul>
                                <li><kbd>ESC</kbd> - 关闭灯箱</li>
                                <li><kbd>+</kbd> - 放大图片</li>
                                <li><kbd>-</kbd> - 缩小图片</li>
                                <li><kbd>0</kbd> - 重置缩放</li>
                            </ul>
                        </div>
                    </div>

                    <h4>📸 测试图片</h4>
                    <p>以下图片来自Unsplash，点击任意图片测试灯箱效果：</p>
                    
                    <div class="row">
                        <!-- 风景图片 -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="card-img-top" 
                                     alt="美丽的山景风光 - 测试图片1"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6>山景风光</h6>
                                    <p class="text-muted small">点击图片查看大图</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 城市图片 -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="card-img-top" 
                                     alt="现代城市夜景 - 测试图片2"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6>城市夜景</h6>
                                    <p class="text-muted small">点击图片查看大图</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 自然图片 -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="card-img-top" 
                                     alt="森林小径自然风光 - 测试图片3"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6>森林小径</h6>
                                    <p class="text-muted small">点击图片查看大图</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 海洋图片 -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="card-img-top" 
                                     alt="湖泊倒影美景 - 测试图片4"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6>湖泊倒影</h6>
                                    <p class="text-muted small">点击图片查看大图</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 建筑图片 -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="card-img-top" 
                                     alt="现代建筑设计 - 测试图片5"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6>现代建筑</h6>
                                    <p class="text-muted small">点击图片查看大图</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 花卉图片 -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="https://images.unsplash.com/photo-1490750967868-88aa4486c946?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="card-img-top" 
                                     alt="美丽花卉特写 - 测试图片6"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6>花卉特写</h6>
                                    <p class="text-muted small">点击图片查看大图</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 文章内容中的图片测试 -->
                    <div class="post-content mt-5">
                        <h4>📝 文章内容中的图片</h4>
                        <p>这里模拟博客文章内容中的图片，这些图片也会自动支持灯箱功能：</p>
                        
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                             alt="文章中的风景图片" 
                             style="width: 100%; max-width: 600px; height: auto; margin: 20px 0; border-radius: 8px;">
                        
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 alt="居中的森林图片" 
                                 style="width: 80%; max-width: 500px; height: auto; border-radius: 8px;">
                        </div>
                        
                        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    </div>
                    
                    <!-- 功能说明 -->
                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle me-2"></i>功能特点</h5>
                        <ul class="mb-0">
                            <li><strong>响应式设计</strong>：在桌面和移动设备上都能完美工作</li>
                            <li><strong>缩放功能</strong>：支持0.5x到3x的缩放范围</li>
                            <li><strong>拖拽移动</strong>：放大后可以拖拽查看图片不同部分</li>
                            <li><strong>键盘支持</strong>：完整的键盘快捷键支持</li>
                            <li><strong>触摸优化</strong>：移动设备触摸手势支持</li>
                            <li><strong>加载指示</strong>：图片加载时显示加载动画</li>
                            <li><strong>无障碍支持</strong>：支持屏幕阅读器和键盘导航</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 测试页面特定样式 */
.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

kbd {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    padding: 2px 6px;
    font-size: 0.875em;
    color: #495057;
}
</style>

<?php
// 包含主布局底部
// 注意：main.php 已经包含了完整的HTML结构，所以这里不需要额外的结束标签
?>
