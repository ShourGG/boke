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
        
        // 检测修复效果
        setTimeout(checkFixResult, 500);
    }
    
    // 检测修复效果
    function checkFixResult() {
        const testIcon = document.createElement('i');
        testIcon.className = 'fas fa-home';
        testIcon.style.position = 'absolute';
        testIcon.style.left = '-9999px';
        document.body.appendChild(testIcon);
        
        const styles = window.getComputedStyle(testIcon);
        const content = styles.getPropertyValue('content');
        const fontFamily = styles.getPropertyValue('font-family');
        
        document.body.removeChild(testIcon);
        
        if (content && content !== 'normal' && content !== 'none') {
            console.log('%c🎯 修复成功！图标内容:', content, 'color: #4caf50; font-weight: bold;');
            console.log('%c🎯 字体族:', fontFamily, 'color: #4caf50; font-weight: bold;');
        } else {
            console.log('%c❌ 修复失败，内容仍为:', content, 'color: #f44336; font-weight: bold;');
            console.log('%c❌ 字体族:', fontFamily, 'color: #f44336; font-weight: bold;');
            
            // 如果还是失败，尝试更激进的修复
            setTimeout(aggressiveFix, 1000);
        }
    }
    
    // 更激进的修复方法
    function aggressiveFix() {
        console.log('%c🚨 启动激进修复模式...', 'color: #ff9800; font-size: 16px; font-weight: bold;');
        
        // 强制重新加载Font Awesome CSS
        const fontAwesomeLinks = document.querySelectorAll('link[href*="fontawesome"]');
        fontAwesomeLinks.forEach(link => {
            const newLink = link.cloneNode();
            newLink.href = link.href + '&force=' + Date.now();
            link.parentNode.insertBefore(newLink, link.nextSibling);
            setTimeout(() => link.remove(), 1000);
        });
        
        // 延迟重新应用样式
        setTimeout(forceApplyFontAwesome, 2000);
    }
    
    // 初始化
    waitForDOM(function() {
        console.log('%c🚀 Font Awesome 修复脚本已加载', 'color: #2196f3; font-size: 14px; font-weight: bold;');
        
        // 延迟执行，确保其他CSS都已加载
        setTimeout(forceApplyFontAwesome, 1000);
    });
    
    // 暴露全局修复函数
    window.fixFontAwesome = forceApplyFontAwesome;
    
})();
