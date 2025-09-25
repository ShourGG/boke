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
    
    // 检测修复效果
    function checkFixResult() {
        const testIcon = document.createElement('i');
        testIcon.className = 'fas fa-home';
        testIcon.style.position = 'absolute';
        testIcon.style.left = '-9999px';
        testIcon.style.visibility = 'hidden';
        document.body.appendChild(testIcon);

        const styles = window.getComputedStyle(testIcon);
        const content = styles.content;
        const fontFamily = styles.fontFamily;

        document.body.removeChild(testIcon);

        console.log('%c🔍 检测结果 - 内容:', 'color: #2196f3; font-weight: bold;', content);
        console.log('%c🔍 检测结果 - 字体:', 'color: #2196f3; font-weight: bold;', fontFamily);

        if (content && content !== 'normal' && content !== 'none' && content.includes('\\')) {
            console.log('%c🎯 修复成功！Font Awesome 图标正常显示！', 'color: #4caf50; font-size: 16px; font-weight: bold;');
            return true;
        } else {
            console.log('%c⚠️ 修复未完全成功，但已应用强制样式', 'color: #ff9800; font-weight: bold;');
            return false;
        }
    }
    
    // 防止无限循环的计数器
    let fixAttempts = 0;
    const maxFixAttempts = 3;

    // 持续监听修复方法 - 监听DOM变化和用户交互
    function startContinuousMonitoring() {
        console.log('%c🔄 启动持续监听修复模式...', 'color: #ff9800; font-size: 16px; font-weight: bold;');

        // 创建MutationObserver监听DOM变化
        const observer = new MutationObserver(function(mutations) {
            let needsRefix = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' || mutation.type === 'childList') {
                    // 检查是否有样式相关的变化
                    if (mutation.target.classList && (
                        mutation.target.classList.contains('fa') ||
                        mutation.target.classList.contains('fas') ||
                        mutation.target.classList.contains('far') ||
                        mutation.target.classList.contains('fab')
                    )) {
                        needsRefix = true;
                    }
                }
            });

            if (needsRefix) {
                console.log('%c🔄 检测到图标相关DOM变化，重新修复...', 'color: #ff9800; font-weight: bold;');
                setTimeout(() => {
                    resetFixState();
                    forceApplyFontAwesome();
                }, 100);
            }
        });

        // 开始观察整个文档
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'style']
        });

        // 监听用户交互事件
        const interactionEvents = ['click', 'mouseover', 'focus', 'scroll'];
        interactionEvents.forEach(eventType => {
            document.addEventListener(eventType, function() {
                // 延迟检查，避免频繁触发
                setTimeout(() => {
                    const success = checkFixResult();
                    if (!success) {
                        console.log(`%c🔄 ${eventType}事件后检测到图标问题，重新修复...`, 'color: #ff9800; font-weight: bold;');
                        resetFixState();
                        forceApplyFontAwesome();
                    }
                }, 200);
            }, { passive: true, once: false });
        });

        // 定期检查（每5秒）
        setInterval(() => {
            const success = checkFixResult();
            if (!success) {
                console.log('%c🔄 定期检查发现图标问题，重新修复...', 'color: #ff9800; font-weight: bold;');
                resetFixState();
                forceApplyFontAwesome();
            }
        }, 5000);

        console.log('%c✅ 持续监听修复已启动', 'color: #4caf50; font-weight: bold;');
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
    
    // 重置修复状态
    function resetFixState() {
        fixAttempts = 0;
        console.log('%c🔄 修复状态已重置', 'color: #2196f3; font-weight: bold;');
    }

    // 暴露全局函数
    window.fixFontAwesome = function() {
        resetFixState();
        forceApplyFontAwesome();
    };

    window.resetFontAwesome = resetFixState;
    
})();
