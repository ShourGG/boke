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
        // 立即执行一次
        checkAndFixIcons();

        // 1秒后再次执行（减少频率）
        setTimeout(function() {
            checkAndFixIcons();
        }, 1000);

        // 页面完全加载后最后检查一次
        window.addEventListener('load', function() {
            setTimeout(checkAndFixIcons, 500);
        });

        // 监听DOM变化，但避免无限循环
        if (typeof MutationObserver !== 'undefined') {
            let isProcessing = false;
            const observer = new MutationObserver(function(mutations) {
                if (isProcessing) return; // 防止无限循环

                let hasNewIcons = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                // 只处理真正的Font Awesome图标，忽略已处理的
                                if (node.tagName === 'I' &&
                                    (node.className.includes('fa-') || node.className.includes('fas') || node.className.includes('far') || node.className.includes('fab')) &&
                                    !node.classList.contains('icon-fallback')) {
                                    hasNewIcons = true;
                                } else if (node.querySelectorAll) {
                                    const newFaIcons = node.querySelectorAll('i[class*="fa-"]:not(.icon-fallback), i.fas:not(.icon-fallback), i.far:not(.icon-fallback), i.fab:not(.icon-fallback)');
                                    if (newFaIcons.length > 0) {
                                        hasNewIcons = true;
                                    }
                                }
                            }
                        });
                    }
                });

                if (hasNewIcons) {
                    isProcessing = true;
                    setTimeout(function() {
                        checkAndFixIcons();
                        isProcessing = false;
                    }, 100);
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    function checkAndFixIcons() {
        // 只处理未处理的Font Awesome图标
        const unprocessedIcons = document.querySelectorAll('i[class*="fa-"]:not(.icon-fallback), i.fas:not(.icon-fallback), i.far:not(.icon-fallback), i.fab:not(.icon-fallback)');

        if (unprocessedIcons.length > 0) {
            console.warn('Applying Unicode fallbacks for', unprocessedIcons.length, 'unprocessed icons');
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

            // 博客导航专用图标
            'fa-blog': '📝',
            'fa-rss': '📡',
            'fa-newspaper': '📰',
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
        
        // 只查找未处理的Font Awesome图标
        const icons = document.querySelectorAll('i[class*="fa-"]:not(.icon-fallback), i.fas:not(.icon-fallback), i.far:not(.icon-fallback), i.fab:not(.icon-fallback)');

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

            // 如果没找到特定图标，使用通用替换
            if (!replacement) {
                // 根据常见模式提供默认图标
                const classString = icon.className.toLowerCase();
                if (classString.includes('home')) replacement = '🏠';
                else if (classString.includes('user')) replacement = '👤';
                else if (classString.includes('search')) replacement = '🔍';
                else if (classString.includes('edit') || classString.includes('pen')) replacement = '✏️';
                else if (classString.includes('trash') || classString.includes('delete')) replacement = '🗑️';
                else if (classString.includes('plus') || classString.includes('add')) replacement = '+';
                else if (classString.includes('minus') || classString.includes('subtract')) replacement = '−';
                else if (classString.includes('times') || classString.includes('close') || classString.includes('x')) replacement = '✕';
                else if (classString.includes('check') || classString.includes('ok')) replacement = '✓';
                else if (classString.includes('cog') || classString.includes('setting')) replacement = '⚙️';
                else if (classString.includes('list') || classString.includes('menu') || classString.includes('bars')) replacement = '☰';
                else if (classString.includes('eye')) replacement = '👁️';
                else if (classString.includes('heart')) replacement = '❤️';
                else if (classString.includes('star')) replacement = '⭐';
                else if (classString.includes('comment')) replacement = '💬';
                else if (classString.includes('calendar')) replacement = '📅';
                else if (classString.includes('clock') || classString.includes('time')) replacement = '🕐';
                else if (classString.includes('mail') || classString.includes('envelope')) replacement = '✉️';
                else if (classString.includes('phone')) replacement = '📞';
                else if (classString.includes('globe') || classString.includes('world')) replacement = '🌐';
                else if (classString.includes('lock')) replacement = '🔒';
                else if (classString.includes('key')) replacement = '🔑';
                else if (classString.includes('bell')) replacement = '🔔';
                else if (classString.includes('tag')) replacement = '🏷️';
                else if (classString.includes('bookmark')) replacement = '🔖';
                else if (classString.includes('file')) replacement = '📄';
                else if (classString.includes('folder')) replacement = '📁';
                else if (classString.includes('image') || classString.includes('picture')) replacement = '🖼️';
                else if (classString.includes('video')) replacement = '🎥';
                else if (classString.includes('music') || classString.includes('audio')) replacement = '🎵';
                else if (classString.includes('download')) replacement = '⬇️';
                else if (classString.includes('upload')) replacement = '⬆️';
                else if (classString.includes('share')) replacement = '📤';
                else if (classString.includes('link')) replacement = '🔗';
                else if (classString.includes('info')) replacement = 'ℹ️';
                else if (classString.includes('warning') || classString.includes('alert')) replacement = '⚠️';
                else if (classString.includes('error') || classString.includes('exclamation')) replacement = '❗';
                else if (classString.includes('question')) replacement = '❓';
                else if (classString.includes('success')) replacement = '✅';
                else if (classString.includes('spinner') || classString.includes('loading')) replacement = '⟳';
                else replacement = '●'; // 默认圆点图标
            }

            // 应用替换图标
            if (replacement) {
                icon.innerHTML = replacement;
                icon.style.fontFamily = 'inherit';
                icon.style.fontSize = '1em';
                icon.style.fontWeight = 'normal';
                icon.style.fontStyle = 'normal';
                icon.style.display = 'inline-block';
                icon.style.textAlign = 'center';
                icon.style.lineHeight = '1';
                icon.style.width = 'auto';
                icon.style.height = 'auto';

                // 移除Font Awesome类，添加标识类
                icon.className = icon.className.replace(/fa[srb]?/g, '').replace(/fa-[\w-]+/g, '').trim();
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
