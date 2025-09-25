<?php
/**
 * Font Awesome Loading Test
 * 测试Font Awesome加载情况
 */

// Load configuration
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Font Awesome Test - <?= SITE_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 图标 (多CDN备用 + 本地备用) -->
    <link id="fontawesome-primary" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link id="fontawesome-backup" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css" rel="stylesheet" media="none">
    
    <style>
        .icon-test {
            font-size: 2rem;
            margin: 0.5rem;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            display: inline-block;
            text-align: center;
            min-width: 80px;
        }
        .icon-test.success {
            border-color: #28a745;
            background-color: #d4edda;
        }
        .icon-test.failed {
            border-color: #dc3545;
            background-color: #f8d7da;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-success { background-color: #28a745; }
        .status-warning { background-color: #ffc107; }
        .status-error { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-font"></i> Font Awesome Loading Test
                </h1>
                
                <!-- Status Panel -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Loading Status</h5>
                    </div>
                    <div class="card-body">
                        <div id="loadingStatus">
                            <div class="d-flex align-items-center mb-2">
                                <span class="status-indicator status-warning"></span>
                                <span>Checking Font Awesome loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Icon Tests -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Icon Display Test</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Solid Icons (fas)</h6>
                                <div class="icon-test" data-icon="fas fa-home">
                                    <i class="fas fa-home"></i>
                                    <div><small>fa-home</small></div>
                                </div>
                                <div class="icon-test" data-icon="fas fa-user">
                                    <i class="fas fa-user"></i>
                                    <div><small>fa-user</small></div>
                                </div>
                                <div class="icon-test" data-icon="fas fa-cog">
                                    <i class="fas fa-cog"></i>
                                    <div><small>fa-cog</small></div>
                                </div>
                                <div class="icon-test" data-icon="fas fa-search">
                                    <i class="fas fa-search"></i>
                                    <div><small>fa-search</small></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Regular Icons (far)</h6>
                                <div class="icon-test" data-icon="far fa-heart">
                                    <i class="far fa-heart"></i>
                                    <div><small>fa-heart</small></div>
                                </div>
                                <div class="icon-test" data-icon="far fa-star">
                                    <i class="far fa-star"></i>
                                    <div><small>fa-star</small></div>
                                </div>
                                <div class="icon-test" data-icon="far fa-file">
                                    <i class="far fa-file"></i>
                                    <div><small>fa-file</small></div>
                                </div>
                                <div class="icon-test" data-icon="far fa-envelope">
                                    <i class="far fa-envelope"></i>
                                    <div><small>fa-envelope</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Debug Info -->
                <div class="card">
                    <div class="card-header">
                        <h5>Debug Information</h5>
                    </div>
                    <div class="card-body">
                        <div id="debugInfo" class="bg-dark text-light p-3 rounded" style="font-family: monospace; height: 300px; overflow-y: auto;">
                            <div class="text-success">Debug information will appear here...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome 加载检测和备用方案 -->
    <script>
    (function() {
        'use strict';
        
        const debugInfo = document.getElementById('debugInfo');
        const loadingStatus = document.getElementById('loadingStatus');
        
        function logDebug(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const colorClass = type === 'error' ? 'text-danger' : type === 'warn' ? 'text-warning' : 'text-success';
            debugInfo.innerHTML += `<div class="${colorClass}">[${timestamp}] ${message}</div>`;
            debugInfo.scrollTop = debugInfo.scrollHeight;
            console[type](message);
        }
        
        function updateStatus(message, type = 'warning') {
            const statusClass = type === 'success' ? 'status-success' : type === 'error' ? 'status-error' : 'status-warning';
            loadingStatus.innerHTML = `
                <div class="d-flex align-items-center mb-2">
                    <span class="status-indicator ${statusClass}"></span>
                    <span>${message}</span>
                </div>
            `;
        }
        
        let fontAwesomeLoaded = false;
        let checkAttempts = 0;
        const maxAttempts = 10;
        
        function checkFontAwesome() {
            checkAttempts++;
            logDebug(`Font Awesome check attempt ${checkAttempts}/${maxAttempts}`);
            
            // 创建测试元素
            const testElement = document.createElement('i');
            testElement.className = 'fas fa-home';
            testElement.style.position = 'absolute';
            testElement.style.left = '-9999px';
            testElement.style.visibility = 'hidden';
            testElement.style.fontSize = '16px';
            document.body.appendChild(testElement);
            
            const computedStyle = window.getComputedStyle(testElement);
            const fontFamily = computedStyle.getPropertyValue('font-family');
            
            logDebug(`Font family detected: ${fontFamily}`);
            
            // 检查是否加载成功
            const isLoaded = fontFamily && (
                fontFamily.includes('Font Awesome') ||
                fontFamily.includes('FontAwesome') ||
                fontFamily.includes('"Font Awesome')
            );
            
            document.body.removeChild(testElement);
            
            if (isLoaded) {
                fontAwesomeLoaded = true;
                logDebug('✅ Font Awesome loaded successfully!');
                updateStatus('Font Awesome loaded successfully', 'success');
                testIcons();
                return true;
            }
            
            // 如果主CDN失败，尝试备用CDN
            if (checkAttempts === 3) {
                logDebug('⚠️ Primary Font Awesome CDN failed, trying backup', 'warn');
                updateStatus('Primary CDN failed, trying backup...', 'warning');
                const backup = document.getElementById('fontawesome-backup');
                if (backup) {
                    backup.media = 'all';
                }
            }
            
            // 如果所有CDN都失败，启用icon-fallback
            if (checkAttempts >= maxAttempts) {
                logDebug('❌ All Font Awesome CDNs failed, enabling icon fallback', 'error');
                updateStatus('Font Awesome failed to load, fallback enabled', 'error');
                testIcons();
                return false;
            }
            
            // 继续检查
            setTimeout(checkFontAwesome, 500);
            return false;
        }
        
        function testIcons() {
            logDebug('Testing icon display...');
            const iconTests = document.querySelectorAll('.icon-test');
            
            iconTests.forEach(test => {
                const icon = test.querySelector('i');
                if (icon) {
                    const computedStyle = window.getComputedStyle(icon);
                    const fontFamily = computedStyle.getPropertyValue('font-family');
                    
                    if (fontFamily && (
                        fontFamily.includes('Font Awesome') ||
                        fontFamily.includes('FontAwesome')
                    )) {
                        test.classList.add('success');
                        logDebug(`✅ Icon ${test.dataset.icon} loaded correctly`);
                    } else {
                        test.classList.add('failed');
                        logDebug(`❌ Icon ${test.dataset.icon} failed to load`, 'error');
                    }
                }
            });
        }
        
        // 开始检查
        logDebug('Starting Font Awesome loading test...');
        updateStatus('Initializing Font Awesome check...', 'warning');
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(checkFontAwesome, 100);
            });
        } else {
            setTimeout(checkFontAwesome, 100);
        }
        
        // 导出状态供其他脚本使用
        window.fontAwesomeStatus = {
            isLoaded: function() { return fontAwesomeLoaded; },
            getAttempts: function() { return checkAttempts; }
        };
    })();
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
