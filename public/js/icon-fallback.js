/**
 * Icon Fallback System v2.0
 * 图标备用系统 - 确保图标正常显示，优化性能避免无限循环
 *
 * @author Old Wang (The Grumpy Developer)
 * @version 2.0 - Performance optimized
 */

(function() {
    'use strict';

    // Configuration
    const config = {
        debug: false,
        maxProcessingAttempts: 3,
        processingDelay: 200,
        observerThrottle: 500,
        excludeContainers: [
            '.editormd-preview-container',
            '.editormd-html-preview',
            '.editormd-markdown-doc',
            '.flowchart',
            '.sequence-diagram'
        ]
    };

    // State management
    let isProcessing = false;
    let processingAttempts = 0;
    let lastProcessingTime = 0;
    let observer = null;
    let processedIcons = new WeakSet();

    // Utility functions
    const utils = {
        log: function(message, type = 'info') {
            if (config.debug) {
                console[type]('[Icon Fallback]', message);
            }
        },

        throttle: function(func, delay) {
            let timeoutId;
            let lastExecTime = 0;
            return function(...args) {
                const currentTime = Date.now();

                if (currentTime - lastExecTime > delay) {
                    func.apply(this, args);
                    lastExecTime = currentTime;
                } else {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        func.apply(this, args);
                        lastExecTime = Date.now();
                    }, delay - (currentTime - lastExecTime));
                }
            };
        },

        isInExcludedContainer: function(element) {
            for (let selector of config.excludeContainers) {
                if (element.closest && element.closest(selector)) {
                    return true;
                }
            }
            return false;
        },

        isValidFontAwesomeIcon: function(element) {
            if (element.tagName !== 'I') return false;
            if (processedIcons.has(element)) return false;
            if (element.classList.contains('icon-fallback')) return false;
            if (utils.isInExcludedContainer(element)) return false;

            const classList = element.classList;
            const isFontAwesome = classList.contains('fas') ||
                                 classList.contains('far') ||
                                 classList.contains('fab') ||
                                 classList.contains('fal') ||
                                 Array.from(classList).some(cls => cls.startsWith('fa-'));

            // Only process if it's a Font Awesome icon AND Font Awesome failed to load
            return isFontAwesome && !utils.isFontAwesomeLoaded();
        },

        isFontAwesomeLoaded: function() {
            // Check if external Font Awesome status is available
            if (window.fontAwesomeStatus && typeof window.fontAwesomeStatus.isLoaded === 'function') {
                const externalStatus = window.fontAwesomeStatus.isLoaded();
                if (externalStatus) {
                    utils.log('Font Awesome confirmed loaded by external checker');
                    return true;
                }
            }

            // Fallback to internal checks
            try {
                const testElement = document.createElement('i');
                testElement.className = 'fas fa-home';
                testElement.style.position = 'absolute';
                testElement.style.left = '-9999px';
                testElement.style.visibility = 'hidden';
                testElement.style.fontSize = '16px';
                document.body.appendChild(testElement);

                const computedStyle = window.getComputedStyle(testElement);
                const fontFamily = computedStyle.getPropertyValue('font-family');

                document.body.removeChild(testElement);

                // Check if Font Awesome font is applied
                const hasFontAwesome = fontFamily && (
                    fontFamily.includes('Font Awesome') ||
                    fontFamily.includes('FontAwesome') ||
                    fontFamily.includes('"Font Awesome')
                );

                if (hasFontAwesome) {
                    utils.log('Font Awesome detected via internal CSS test');
                    return true;
                }
            } catch (error) {
                utils.log('Font Awesome CSS test failed: ' + error.message, 'warn');
            }

            // Check for Font Awesome link elements
            const faLinks = document.querySelectorAll('link[href*="font-awesome"], link[href*="fontawesome"]');
            if (faLinks.length > 0) {
                // Check if any of the links have loaded successfully
                for (let link of faLinks) {
                    if (link.sheet && link.sheet.cssRules) {
                        utils.log('Font Awesome detected via loaded stylesheet');
                        return true;
                    }
                }
            }

            utils.log('Font Awesome not detected - fallback needed');
            return false;
        }
    };

    // DOM ready handler
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIconFallback);
    } else {
        initIconFallback();
    }

    function initIconFallback() {
        utils.log('Initializing icon fallback system');

        // Wait for Font Awesome to load before checking
        setTimeout(function() {
            if (!utils.isFontAwesomeLoaded()) {
                utils.log('Font Awesome not detected, enabling fallback system');
                checkAndFixIcons();
            } else {
                utils.log('Font Awesome loaded successfully, fallback not needed');
            }
        }, 2000);

        // Final check after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (!utils.isFontAwesomeLoaded()) {
                    checkAndFixIcons();
                }
            }, 1000);
        });

        // Setup optimized mutation observer only if Font Awesome fails
        setTimeout(function() {
            if (!utils.isFontAwesomeLoaded()) {
                setupMutationObserver();
            }
        }, 3000);

        utils.log('Icon fallback system initialized');
    }

    function setupMutationObserver() {
        if (typeof MutationObserver === 'undefined') {
            utils.log('MutationObserver not supported', 'warn');
            return;
        }

        const throttledCheck = utils.throttle(function() {
            if (!isProcessing && processingAttempts < config.maxProcessingAttempts) {
                checkAndFixIcons();
            }
        }, config.observerThrottle);

        observer = new MutationObserver(function(mutations) {
            let hasRelevantChanges = false;

            for (let mutation of mutations) {
                if (mutation.type === 'childList') {
                    for (let node of mutation.addedNodes) {
                        if (node.nodeType === 1) { // Element node
                            // Check if the added node or its children contain FA icons
                            if (utils.isValidFontAwesomeIcon(node)) {
                                hasRelevantChanges = true;
                                break;
                            } else if (node.querySelectorAll) {
                                const faIcons = node.querySelectorAll('i.fas, i.far, i.fab, i.fal, i[class*="fa-"]');
                                if (faIcons.length > 0) {
                                    // Check if any of these icons need processing
                                    for (let icon of faIcons) {
                                        if (utils.isValidFontAwesomeIcon(icon)) {
                                            hasRelevantChanges = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (hasRelevantChanges) break;
                    }
                }
                if (hasRelevantChanges) break;
            }

            if (hasRelevantChanges) {
                utils.log('Relevant DOM changes detected, scheduling icon check');
                throttledCheck();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        utils.log('MutationObserver setup complete');
    }
    
    function checkAndFixIcons() {
        // Prevent concurrent processing
        if (isProcessing) {
            utils.log('Already processing, skipping');
            return;
        }

        // Throttle processing attempts
        const currentTime = Date.now();
        if (currentTime - lastProcessingTime < config.processingDelay) {
            utils.log('Processing throttled, too soon since last attempt');
            return;
        }

        // Limit processing attempts
        if (processingAttempts >= config.maxProcessingAttempts) {
            utils.log('Max processing attempts reached, stopping');
            return;
        }

        isProcessing = true;
        lastProcessingTime = currentTime;
        processingAttempts++;

        try {
            // Find unprocessed Font Awesome icons with better filtering
            const unprocessedIcons = Array.from(
                document.querySelectorAll('i.fas, i.far, i.fab, i.fal, i[class*="fa-"]')
            ).filter(icon => utils.isValidFontAwesomeIcon(icon));

            if (unprocessedIcons.length > 0) {
                utils.log(`Processing ${unprocessedIcons.length} unprocessed icons`);
                applyUnicodeFallbacks(unprocessedIcons);
            } else {
                utils.log('No unprocessed icons found');
                // Reset attempts counter if no icons need processing
                processingAttempts = Math.max(0, processingAttempts - 1);
            }
        } catch (error) {
            utils.log('Error during icon processing: ' + error.message, 'error');
        } finally {
            isProcessing = false;
        }
    }
    }
    
    function applyUnicodeFallbacks(iconsToProcess) {
        // Use provided icons or find all unprocessed icons
        const icons = iconsToProcess || Array.from(
            document.querySelectorAll('i.fas, i.far, i.fab, i.fal, i[class*="fa-"]')
        ).filter(icon => utils.isValidFontAwesomeIcon(icon));
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

                // Mark as processed to prevent reprocessing
                processedIcons.add(icon);
            }
        });

        utils.log(`Applied Unicode fallbacks to ${icons.length} icons`);
    }
    
    // Public API
    window.iconFallback = {
        fixIcons: function() {
            processingAttempts = 0; // Reset attempts
            checkAndFixIcons();
        },

        reset: function() {
            isProcessing = false;
            processingAttempts = 0;
            lastProcessingTime = 0;
            processedIcons = new WeakSet();
            utils.log('Icon fallback system reset');
        },

        config: config,

        // Legacy support
        checkAndFixIcons: function() {
            this.fixIcons();
        }
    };

    // Legacy global function
    window.fixIcons = window.iconFallback.fixIcons;
    
})();
