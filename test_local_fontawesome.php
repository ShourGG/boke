<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Font Awesome 本地测试</title>
    
    <!-- 本地Font Awesome -->
    <link href="public/fontawesome-free-6.5.1-web/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .icon-test {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            text-align: center;
            min-width: 80px;
        }
        .icon-test i {
            font-size: 24px;
            margin-bottom: 5px;
            display: block;
        }
        .icon-test span {
            font-size: 12px;
            color: #666;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        .status {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .status.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <h1><i class="fas fa-check-circle"></i> Font Awesome 本地测试</h1>
    
    <div class="test-section">
        <h2>加载状态检测</h2>
        <div id="load-status" class="status">检测中...</div>
    </div>
    
    <div class="test-section">
        <h2>常用图标测试</h2>
        <div class="icon-test">
            <i class="fas fa-home"></i>
            <span>fa-home</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-user"></i>
            <span>fa-user</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-search"></i>
            <span>fa-search</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-heart"></i>
            <span>fa-heart</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-star"></i>
            <span>fa-star</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-cog"></i>
            <span>fa-cog</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-envelope"></i>
            <span>fa-envelope</span>
        </div>
        <div class="icon-test">
            <i class="fas fa-phone"></i>
            <span>fa-phone</span>
        </div>
    </div>
    
    <div class="test-section">
        <h2>品牌图标测试</h2>
        <div class="icon-test">
            <i class="fab fa-github"></i>
            <span>fa-github</span>
        </div>
        <div class="icon-test">
            <i class="fab fa-twitter"></i>
            <span>fa-twitter</span>
        </div>
        <div class="icon-test">
            <i class="fab fa-facebook"></i>
            <span>fa-facebook</span>
        </div>
        <div class="icon-test">
            <i class="fab fa-google"></i>
            <span>fa-google</span>
        </div>
    </div>
    
    <div class="test-section">
        <h2>Regular图标测试</h2>
        <div class="icon-test">
            <i class="far fa-heart"></i>
            <span>far fa-heart</span>
        </div>
        <div class="icon-test">
            <i class="far fa-star"></i>
            <span>far fa-star</span>
        </div>
        <div class="icon-test">
            <i class="far fa-user"></i>
            <span>far fa-user</span>
        </div>
        <div class="icon-test">
            <i class="far fa-envelope"></i>
            <span>far fa-envelope</span>
        </div>
    </div>
    
    <script>
    // 检测Font Awesome是否正确加载
    function checkFontAwesome() {
        const testElement = document.createElement('i');
        testElement.className = 'fas fa-home';
        testElement.style.position = 'absolute';
        testElement.style.left = '-9999px';
        testElement.style.visibility = 'hidden';
        document.body.appendChild(testElement);
        
        const computedStyle = window.getComputedStyle(testElement);
        const fontFamily = computedStyle.getPropertyValue('font-family');
        
        document.body.removeChild(testElement);
        
        const statusDiv = document.getElementById('load-status');
        
        if (fontFamily && (fontFamily.includes('Font Awesome') || fontFamily.includes('FontAwesome'))) {
            statusDiv.className = 'status success';
            statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> Font Awesome 加载成功！字体族：' + fontFamily;
            console.log('Font Awesome loaded successfully:', fontFamily);
        } else {
            statusDiv.className = 'status error';
            statusDiv.innerHTML = '<i class="fas fa-times-circle"></i> Font Awesome 加载失败！字体族：' + fontFamily;
            console.error('Font Awesome failed to load:', fontFamily);
        }
    }
    
    // 页面加载完成后检测
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkFontAwesome, 100);
        });
    } else {
        setTimeout(checkFontAwesome, 100);
    }
    </script>
</body>
</html>
