<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Scroll Button</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .debug-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .test-banner {
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 20px;
        }
        .scroll-down-bar {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 1.5rem;
            animation: bounce 2s infinite;
            cursor: pointer;
            z-index: 1001;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        .scroll-down-bar:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(-50%) scale(1.1);
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-10px);
            }
            60% {
                transform: translateX(-50%) translateY(-5px);
            }
        }
        .content-section {
            height: 800px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="text-center py-4">🔍 Scroll Button Debug</h1>
        
        <!-- Debug Information -->
        <div class="debug-section">
            <h3>Debug Information</h3>
            <div id="debug-info">
                <p>Loading debug information...</p>
            </div>
        </div>

        <!-- Test Banner -->
        <div class="test-banner" id="banner">
            <div>
                <h2>Test Banner</h2>
                <p>This is a test banner with scroll button</p>
            </div>
            
            <!-- Scroll Down Button -->
            <div class="scroll-down-bar" id="scroll-down-test">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <div>
                <h3>Content Section</h3>
                <p>The scroll button should scroll to here</p>
            </div>
        </div>

        <!-- Manual Test Buttons -->
        <div class="debug-section">
            <h3>Manual Tests</h3>
            <button class="btn btn-primary" onclick="testScrollButton()">Test Scroll Button</button>
            <button class="btn btn-secondary" onclick="checkElements()">Check Elements</button>
            <button class="btn btn-info" onclick="testScrollTo()">Test ScrollTo Function</button>
        </div>

        <!-- Results -->
        <div class="debug-section">
            <h3>Test Results</h3>
            <div id="test-results"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug information
            const debugInfo = document.getElementById('debug-info');
            const banner = document.querySelector('#banner');
            const scrollDownBar = document.querySelector('.scroll-down-bar');
            
            let info = '<ul>';
            info += '<li><strong>Banner element:</strong> ' + (banner ? 'Found ✅' : 'Not found ❌') + '</li>';
            info += '<li><strong>Scroll button element:</strong> ' + (scrollDownBar ? 'Found ✅' : 'Not found ❌') + '</li>';
            info += '<li><strong>Banner height:</strong> ' + (banner ? banner.offsetHeight + 'px' : 'N/A') + '</li>';
            info += '<li><strong>Window height:</strong> ' + window.innerHeight + 'px</li>';
            info += '<li><strong>Document height:</strong> ' + document.documentElement.scrollHeight + 'px</li>';
            info += '</ul>';
            
            debugInfo.innerHTML = info;

            // Initialize scroll button (same as main.js)
            if (scrollDownBar && banner) {
                scrollDownBar.addEventListener('click', function() {
                    console.log('Scroll button clicked!');
                    const bannerHeight = banner.offsetHeight;
                    console.log('Banner height:', bannerHeight);
                    
                    window.scrollTo({
                        top: bannerHeight,
                        behavior: 'smooth'
                    });
                });
                
                console.log('Scroll button event listener added successfully');
            } else {
                console.error('Failed to add scroll button event listener');
                console.log('Banner:', banner);
                console.log('ScrollDownBar:', scrollDownBar);
            }
        });

        function testScrollButton() {
            const scrollDownBar = document.querySelector('.scroll-down-bar');
            if (scrollDownBar) {
                scrollDownBar.click();
                document.getElementById('test-results').innerHTML += '<p>✅ Scroll button clicked programmatically</p>';
            } else {
                document.getElementById('test-results').innerHTML += '<p>❌ Scroll button not found</p>';
            }
        }

        function checkElements() {
            const banner = document.querySelector('#banner');
            const scrollDownBar = document.querySelector('.scroll-down-bar');
            
            let result = '<h4>Element Check Results:</h4><ul>';
            result += '<li>Banner: ' + (banner ? 'Found ✅' : 'Not found ❌') + '</li>';
            result += '<li>Scroll Button: ' + (scrollDownBar ? 'Found ✅' : 'Not found ❌') + '</li>';
            
            if (banner) {
                result += '<li>Banner height: ' + banner.offsetHeight + 'px</li>';
                result += '<li>Banner top: ' + banner.offsetTop + 'px</li>';
            }
            
            result += '</ul>';
            document.getElementById('test-results').innerHTML = result;
        }

        function testScrollTo() {
            const banner = document.querySelector('#banner');
            if (banner) {
                const bannerHeight = banner.offsetHeight;
                window.scrollTo({
                    top: bannerHeight,
                    behavior: 'smooth'
                });
                document.getElementById('test-results').innerHTML += '<p>✅ ScrollTo executed with banner height: ' + bannerHeight + 'px</p>';
            } else {
                document.getElementById('test-results').innerHTML += '<p>❌ Banner not found for scrollTo test</p>';
            }
        }
    </script>
</body>
</html>
