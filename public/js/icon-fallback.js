/**
 * Icon Fallback System
 * 图标备用系统 - 确保图标正常显示
 */

(function() {
    'use strict';
    
    // 等待DOM加载完成
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIconFallback);
    } else {
        initIconFallback();
    }
    
    function initIconFallback() {
        // 检查Font Awesome是否加载成功
        setTimeout(function() {
            checkAndFixIcons();
        }, 1000);
        
        // 页面完全加载后再次检查
        window.addEventListener('load', function() {
            setTimeout(checkAndFixIcons, 500);
        });
    }
    
    function checkAndFixIcons() {
        // 检查Font Awesome是否正确加载
        const testIcon = document.createElement('i');
        testIcon.className = 'fas fa-home';
        testIcon.style.position = 'absolute';
        testIcon.style.left = '-9999px';
        document.body.appendChild(testIcon);
        
        const computedStyle = window.getComputedStyle(testIcon, ':before');
        const fontFamily = computedStyle.getPropertyValue('font-family');
        
        document.body.removeChild(testIcon);
        
        // 如果Font Awesome没有正确加载，使用Unicode备用图标
        if (!fontFamily || !fontFamily.includes('Font Awesome')) {
            console.warn('Font Awesome not loaded, using Unicode fallbacks');
            applyUnicodeFallbacks();
        }
    }
    
    function applyUnicodeFallbacks() {
        // Font Awesome到Unicode的映射
        const iconMap = {
            // 常用图标
            'fa-home': '🏠',
            'fa-user': '👤',
            'fa-users': '👥',
            'fa-cog': '⚙️',
            'fa-settings': '⚙️',
            'fa-search': '🔍',
            'fa-edit': '✏️',
            'fa-trash': '🗑️',
            'fa-delete': '🗑️',
            'fa-plus': '+',
            'fa-minus': '−',
            'fa-times': '✕',
            'fa-close': '✕',
            'fa-check': '✓',
            'fa-arrow-left': '←',
            'fa-arrow-right': '→',
            'fa-arrow-up': '↑',
            'fa-arrow-down': '↓',
            'fa-chevron-left': '‹',
            'fa-chevron-right': '›',
            'fa-chevron-up': '⌃',
            'fa-chevron-down': '⌄',
            
            // 博客相关
            'fa-blog': '📝',
            'fa-pen': '✏️',
            'fa-pencil': '✏️',
            'fa-file': '📄',
            'fa-folder': '📁',
            'fa-image': '🖼️',
            'fa-images': '🖼️',
            'fa-camera': '📷',
            'fa-video': '🎥',
            'fa-music': '🎵',
            'fa-book': '📚',
            'fa-bookmark': '🔖',
            'fa-tag': '🏷️',
            'fa-tags': '🏷️',
            'fa-calendar': '📅',
            'fa-clock': '🕐',
            'fa-comment': '💬',
            'fa-comments': '💬',
            'fa-heart': '❤️',
            'fa-star': '⭐',
            'fa-thumbs-up': '👍',
            'fa-share': '📤',
            'fa-link': '🔗',
            
            // 导航和UI
            'fa-bars': '☰',
            'fa-menu': '☰',
            'fa-list': '📋',
            'fa-grid': '⊞',
            'fa-th': '⊞',
            'fa-eye': '👁️',
            'fa-eye-slash': '🙈',
            'fa-lock': '🔒',
            'fa-unlock': '🔓',
            'fa-key': '🔑',
            'fa-bell': '🔔',
            'fa-envelope': '✉️',
            'fa-mail': '✉️',
            'fa-phone': '📞',
            'fa-mobile': '📱',
            'fa-globe': '🌐',
            'fa-wifi': '📶',
            'fa-signal': '📶',
            'fa-battery': '🔋',
            'fa-power-off': '⏻',
            
            // 社交媒体
            'fa-facebook': 'f',
            'fa-twitter': 't',
            'fa-instagram': 'i',
            'fa-linkedin': 'in',
            'fa-github': 'gh',
            'fa-youtube': 'yt',
            'fa-weibo': 'wb',
            'fa-wechat': 'wx',
            'fa-qq': 'qq',
            
            // 状态和反馈
            'fa-info': 'ℹ️',
            'fa-warning': '⚠️',
            'fa-exclamation': '❗',
            'fa-question': '❓',
            'fa-success': '✅',
            'fa-error': '❌',
            'fa-spinner': '⟳',
            'fa-loading': '⟳',
            
            // 媒体控制
            'fa-play': '▶️',
            'fa-pause': '⏸️',
            'fa-stop': '⏹️',
            'fa-forward': '⏩',
            'fa-backward': '⏪',
            'fa-volume-up': '🔊',
            'fa-volume-down': '🔉',
            'fa-volume-off': '🔇',
            
            // 工具和设置
            'fa-wrench': '🔧',
            'fa-hammer': '🔨',
            'fa-screwdriver': '🪛',
            'fa-paint-brush': '🖌️',
            'fa-palette': '🎨',
            'fa-magic': '✨',
            'fa-wand': '🪄',
            
            // 灯箱专用图标
            'fa-search-plus': '+',
            'fa-search-minus': '−',
            'fa-expand-arrows-alt': '⌂',
            'fa-compress': '⌂'
        };
        
        // 查找所有Font Awesome图标并替换
        const icons = document.querySelectorAll('i[class*="fa-"], i[class*="fas"], i[class*="far"], i[class*="fab"]');
        
        icons.forEach(function(icon) {
            const classes = icon.className.split(' ');
            let replacement = null;
            
            // 查找匹配的图标
            for (let i = 0; i < classes.length; i++) {
                const className = classes[i];
                if (iconMap[className]) {
                    replacement = iconMap[className];
                    break;
                }
            }
            
            // 如果找到替换图标
            if (replacement) {
                icon.innerHTML = replacement;
                icon.style.fontFamily = 'inherit';
                icon.style.fontSize = '1em';
                icon.style.fontWeight = 'normal';
                icon.style.fontStyle = 'normal';
                icon.style.display = 'inline-block';
                icon.style.textAlign = 'center';
                icon.style.lineHeight = '1';
                
                // 添加标识类
                icon.classList.add('icon-fallback');
            }
        });
        
        console.log('Applied Unicode fallbacks to', icons.length, 'icons');
    }
    
    // 提供手动修复方法
    window.fixIcons = function() {
        checkAndFixIcons();
    };
    
})();
