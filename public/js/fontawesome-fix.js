/**
 * Font Awesome 强制修复脚本
 * 解决CSS优先级和样式冲突问题
 * 老王出品，专治各种不服
 */

(function() {
    'use strict';
    
    // 等待DOM加载完成
    function waitForDOM(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }
    
    // 强制应用Font Awesome样式
    function forceApplyFontAwesome() {
        console.log('%c🔧 Font Awesome 强制修复启动...', 'color: #ff6b6b; font-size: 16px; font-weight: bold;');
        
        // 创建强制样式
        const forceStyle = document.createElement('style');
        forceStyle.id = 'fontawesome-force-fix';
        forceStyle.innerHTML = `
            /* Font Awesome 强制修复样式 - 老王出品 */
            
            /* 基础Font Awesome样式 */
            .fa, .fas, .fa-solid, .far, .fa-regular, .fab, .fa-brands {
                -moz-osx-font-smoothing: grayscale !important;
                -webkit-font-smoothing: antialiased !important;
                display: inline-block !important;
                font-style: normal !important;
                font-variant: normal !important;
                text-rendering: auto !important;
                line-height: 1 !important;
            }
            
            /* Solid 图标 */
            .fas, .fa-solid {
                font-family: "Font Awesome 6 Free" !important;
                font-weight: 900 !important;
            }
            
            /* Regular 图标 */
            .far, .fa-regular {
                font-family: "Font Awesome 6 Free" !important;
                font-weight: 400 !important;
            }
            
            /* Brands 图标 */
            .fab, .fa-brands {
                font-family: "Font Awesome 6 Brands" !important;
                font-weight: 400 !important;
            }
            
            /* 确保伪元素正确显示 */
            .fa:before, .fas:before, .fa-solid:before,
            .far:before, .fa-regular:before,
            .fab:before, .fa-brands:before {
                display: inline-block !important;
                text-rendering: auto !important;
                -webkit-font-smoothing: antialiased !important;
            }
            
            /* 常用图标的具体内容 - 防止CSS规则丢失 */
            .fa-home:before { content: "\\f015" !important; }
            .fa-user:before { content: "\\f007" !important; }
            .fa-heart:before { content: "\\f004" !important; }
            .fa-star:before { content: "\\f005" !important; }
            .fa-cog:before, .fa-gear:before { content: "\\f013" !important; }
            .fa-envelope:before { content: "\\f0e0" !important; }
            .fa-phone:before { content: "\\f095" !important; }
            .fa-search:before { content: "\\f002" !important; }
            .fa-edit:before, .fa-pen-to-square:before { content: "\\f044" !important; }
            .fa-trash:before, .fa-trash-can:before { content: "\\f2ed" !important; }
            .fa-plus:before { content: "\\f067" !important; }
            .fa-minus:before { content: "\\f068" !important; }
            .fa-times:before, .fa-xmark:before { content: "\\f00d" !important; }
            .fa-check:before { content: "\\f00c" !important; }
            .fa-arrow-up:before { content: "\\f062" !important; }
            .fa-arrow-down:before { content: "\\f063" !important; }
            .fa-arrow-left:before { content: "\\f060" !important; }
            .fa-arrow-right:before { content: "\\f061" !important; }
            .fa-globe:before { content: "\\f0ac" !important; }
            .fa-sitemap:before { content: "\\f0e8" !important; }
            .fa-thumbtack:before { content: "\\f08d" !important; }
            .fa-calendar:before { content: "\\f133" !important; }
            .fa-clock:before { content: "\\f017" !important; }
            .fa-tag:before { content: "\\f02b" !important; }
            .fa-tags:before { content: "\\f02c" !important; }
            .fa-comment:before { content: "\\f075" !important; }
            .fa-comments:before { content: "\\f086" !important; }
            .fa-eye:before { content: "\\f06e" !important; }
            .fa-eye-slash:before { content: "\\f070" !important; }
            .fa-download:before { content: "\\f019" !important; }
            .fa-upload:before { content: "\\f093" !important; }
            .fa-link:before { content: "\\f0c1" !important; }
            .fa-external-link:before, .fa-external-link-alt:before { content: "\\f35d" !important; }
            
            /* Brands 图标 */
            .fa-github:before { content: "\\f09b" !important; }
            .fa-twitter:before { content: "\\f099" !important; }
            .fa-facebook:before { content: "\\f09a" !important; }
            .fa-google:before { content: "\\f1a0" !important; }
            .fa-apple:before { content: "\\f179" !important; }
            .fa-microsoft:before { content: "\\f3ca" !important; }
            .fa-linkedin:before { content: "\\f08c" !important; }
            .fa-youtube:before { content: "\\f167" !important; }
            .fa-instagram:before { content: "\\f16d" !important; }
            .fa-weibo:before { content: "\\f18a" !important; }
            .fa-qq:before { content: "\\f1d6" !important; }
            .fa-wechat:before, .fa-weixin:before { content: "\\f1d7" !important; }
            
            /* Regular 图标特殊处理 */
            .far.fa-heart:before { content: "\\f004" !important; }
            .far.fa-star:before { content: "\\f005" !important; }
            .far.fa-user:before { content: "\\f007" !important; }
            .far.fa-envelope:before { content: "\\f0e0" !important; }
            .far.fa-clock:before { content: "\\f017" !important; }
            .far.fa-calendar:before { content: "\\f133" !important; }
            .far.fa-comment:before { content: "\\f075" !important; }
            .far.fa-comments:before { content: "\\f086" !important; }
            .far.fa-eye:before { content: "\\f06e" !important; }
            .far.fa-thumbs-up:before { content: "\\f164" !important; }
            .far.fa-thumbs-down:before { content: "\\f165" !important; }
        `;
        
        // 移除旧的强制样式
        const oldForceStyle = document.getElementById('fontawesome-force-fix');
        if (oldForceStyle) {
            oldForceStyle.remove();
        }
        
        // 添加新的强制样式到head的最后，确保优先级最高
        document.head.appendChild(forceStyle);
        
        console.log('%c✅ Font Awesome 强制样式已应用！', 'color: #4caf50; font-size: 14px; font-weight: bold;');

        // 检测修复效果，但只在初次修复时检测
        if (fixAttempts === 0) {
            setTimeout(() => {
                const success = checkFixResult();
                if (!success) {
                    console.log('%c🔄 初次修复未完全成功，启动持续监听修复...', 'color: #ff9800; font-weight: bold;');
                    startContinuousMonitoring();
                }
            }, 500);
        }
    }
    
    // 性能优化的检测修复效果 - 使用缓存和现有元素
    let cachedTestResult = null;
    let lastTestTime = 0;
    const TEST_CACHE_DURATION = 1000; // 1秒内使用缓存结果

    function checkFixResult() {
        const now = Date.now();

        // 使用缓存结果，避免频繁DOM操作
        if (cachedTestResult !== null && (now - lastTestTime) < TEST_CACHE_DURATION) {
            return cachedTestResult;
        }

        // 尝试使用页面中已存在的Font Awesome图标
        const existingIcon = document.querySelector('.fa, .fas, .far, .fab');
        if (existingIcon) {
            const styles = window.getComputedStyle(existingIcon, '::before');
            const content = styles.content;
            const fontFamily = styles.fontFamily;

            console.log('%c🔍 检测结果 (现有图标) - 内容:', 'color: #2196f3; font-weight: bold;', content);
            console.log('%c🔍 检测结果 (现有图标) - 字体:', 'color: #2196f3; font-weight: bold;', fontFamily);

            const isSuccess = content && content !== 'normal' && content !== 'none' && content.includes('\\');

            // 缓存结果
            cachedTestResult = isSuccess;
            lastTestTime = now;

            if (isSuccess) {
                console.log('%c🎯 修复成功！Font Awesome 图标正常显示！', 'color: #4caf50; font-size: 16px; font-weight: bold;');
            } else {
                console.log('%c⚠️ 修复未完全成功，但已应用强制样式', 'color: #ff9800; font-weight: bold;');
            }

            return isSuccess;
        }

        // 如果没有现有图标，创建临时测试元素（降级方案）
        const testIcon = document.createElement('i');
        testIcon.className = 'fas fa-home';
        testIcon.style.position = 'absolute';
        testIcon.style.left = '-9999px';
        testIcon.style.visibility = 'hidden';
        document.body.appendChild(testIcon);

        const styles = window.getComputedStyle(testIcon, '::before');
        const content = styles.content;
        const fontFamily = styles.fontFamily;

        document.body.removeChild(testIcon);

        console.log('%c🔍 检测结果 (临时元素) - 内容:', 'color: #2196f3; font-weight: bold;', content);
        console.log('%c🔍 检测结果 (临时元素) - 字体:', 'color: #2196f3; font-weight: bold;', fontFamily);

        const isSuccess = content && content !== 'normal' && content !== 'none' && content.includes('\\');

        // 缓存结果
        cachedTestResult = isSuccess;
        lastTestTime = now;

        if (isSuccess) {
            console.log('%c🎯 修复成功！Font Awesome 图标正常显示！', 'color: #4caf50; font-size: 16px; font-weight: bold;');
        } else {
            console.log('%c⚠️ 修复未完全成功，但已应用强制样式', 'color: #ff9800; font-weight: bold;');
        }

        return isSuccess;
    }
    
    // 防止无限循环的计数器
    let fixAttempts = 0;
    const maxFixAttempts = 3;

    // 性能优化的智能监听修复方法
    function startContinuousMonitoring() {
        console.log('%c🔄 启动智能监听修复模式...', 'color: #ff9800; font-size: 16px; font-weight: bold;');

        let lastCheckTime = 0;
        let isChecking = false;
        const CHECK_THROTTLE = 2000; // 2秒内最多检查一次

        // 节流检查函数
        function throttledCheck(eventType = 'unknown') {
            const now = Date.now();
            if (isChecking || (now - lastCheckTime) < CHECK_THROTTLE) {
                return; // 跳过频繁检查
            }

            isChecking = true;
            lastCheckTime = now;

            setTimeout(() => {
                const success = checkFixResult();
                if (!success) {
                    console.log(`%c🔄 ${eventType}触发检查，发现图标问题，重新修复...`, 'color: #ff9800; font-weight: bold;');
                    resetFixState();
                    forceApplyFontAwesome();
                }
                isChecking = false;
            }, 100);
        }

        // 精确的MutationObserver - 只监听目录区域
        const tocContainer = document.querySelector('.editormd-toc-menu, .markdown-toc, #post-content-view');
        if (tocContainer) {
            const observer = new MutationObserver(function(mutations) {
                let needsRefix = false;
                mutations.forEach(function(mutation) {
                    // 只关心class属性变化
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const target = mutation.target;
                        if (target.classList && (
                            target.classList.contains('fa') ||
                            target.classList.contains('fas') ||
                            target.classList.contains('far') ||
                            target.classList.contains('fab')
                        )) {
                            needsRefix = true;
                        }
                    }
                });

                if (needsRefix) {
                    throttledCheck('DOM变化');
                }
            });

            // 只观察目录容器，减少监听范围
            observer.observe(tocContainer, {
                attributes: true,
                attributeFilter: ['class'],
                subtree: true
            });

            console.log('%c📍 精确监听已设置：仅监听目录区域', 'color: #2196f3; font-weight: bold;');
        }

        // 优化的用户交互监听 - 只监听关键事件
        let scrollTimeout;
        document.addEventListener('scroll', function() {
            // 滚动节流：停止滚动500ms后才检查
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                throttledCheck('滚动停止');
            }, 500);
        }, { passive: true });

        // 点击事件监听 - 只监听目录区域
        if (tocContainer) {
            tocContainer.addEventListener('click', function() {
                setTimeout(() => throttledCheck('目录点击'), 300);
            }, { passive: true });
        }

        // 降低定期检查频率（每30秒）
        setInterval(() => {
            throttledCheck('定期巡检');
        }, 30000);

        console.log('%c✅ 智能监听修复已启动 (性能优化版)', 'color: #4caf50; font-weight: bold;');
        console.log('%c⚡ 性能优化：节流检查、精确监听、降低频率', 'color: #9c27b0; font-weight: bold;');
    }
    
    // 监听Editor.md渲染完成
    function watchForEditorMd() {
        // 检查是否是文章详情页
        if (document.getElementById('post-content-view')) {
            console.log('%c📝 检测到文章详情页，监听Editor.md渲染', 'color: #ff9800; font-weight: bold;');

            // 监听DOM变化，当Editor.md渲染完成后重新修复
            const observer = new MutationObserver(function(mutations) {
                let shouldRefix = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        // 检查是否有新的内容被添加
                        for (let node of mutation.addedNodes) {
                            if (node.nodeType === 1 && (
                                node.classList.contains('markdown-body') ||
                                node.querySelector && node.querySelector('.markdown-body')
                            )) {
                                shouldRefix = true;
                                break;
                            }
                        }
                    }
                });

                if (shouldRefix) {
                    console.log('%c🔄 Editor.md渲染完成，重新修复Font Awesome', 'color: #ff9800; font-weight: bold;');
                    setTimeout(() => {
                        resetFixState();
                        forceApplyFontAwesome();
                    }, 500);
                }
            });

            // 开始观察
            observer.observe(document.getElementById('post-content-view'), {
                childList: true,
                subtree: true
            });

            // 5秒后停止观察（避免无限监听）
            setTimeout(() => {
                observer.disconnect();
                console.log('%c⏹️ Editor.md监听已停止', 'color: #9e9e9e; font-weight: bold;');
            }, 5000);
        }
    }

    // 初始化
    waitForDOM(function() {
        console.log('%c🚀 Font Awesome 修复脚本已加载', 'color: #2196f3; font-size: 14px; font-weight: bold;');

        // 启动Editor.md监听
        watchForEditorMd();

        // 延迟执行，确保其他CSS都已加载
        setTimeout(forceApplyFontAwesome, 1000);

        // 如果是文章页面，额外延迟3秒再修复一次（确保Editor.md渲染完成）
        if (document.getElementById('post-content-view')) {
            setTimeout(() => {
                console.log('%c🔄 文章页面额外修复', 'color: #ff9800; font-weight: bold;');
                resetFixState();
                forceApplyFontAwesome();
            }, 3000);
        }
    });
    
    // 重置修复状态和缓存
    function resetFixState() {
        fixAttempts = 0;
        cachedTestResult = null; // 重置检测缓存
        lastTestTime = 0;
        console.log('%c🔄 修复状态已重置', 'color: #2196f3; font-weight: bold;');
    }

    // 暴露全局函数
    window.fixFontAwesome = function() {
        resetFixState();
        forceApplyFontAwesome();
    };

    window.resetFontAwesome = resetFixState;
    
})();
