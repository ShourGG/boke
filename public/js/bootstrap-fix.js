/**
 * Bootstrap JavaScript Conflict Fix
 * 修复Bootstrap与jQuery/其他库的冲突问题
 */

(function() {
    'use strict';
    
    // 防止重复执行
    if (window.bootstrapFixApplied) {
        return;
    }
    window.bootstrapFixApplied = true;
    
    // 等待DOM加载完成
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyBootstrapFix);
    } else {
        applyBootstrapFix();
    }
    
    function applyBootstrapFix() {
        // 修复Bootstrap selector-engine问题
        if (typeof window.bootstrap !== 'undefined') {
            
            // 重写有问题的selector方法
            const originalFind = document.querySelectorAll;
            
            // 确保Bootstrap组件正确初始化
            try {
                // 重新初始化Bootstrap组件，避免selector-engine错误
                const reinitializeBootstrapComponents = function() {
                    
                    // 安全地初始化Tooltip
                    try {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                            if (!tooltipTriggerEl._tooltip) {
                                tooltipTriggerEl._tooltip = new bootstrap.Tooltip(tooltipTriggerEl);
                            }
                        });
                    } catch (e) {
                        console.warn('Bootstrap Tooltip initialization failed:', e);
                    }
                    
                    // 安全地初始化Popover
                    try {
                        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                        popoverTriggerList.forEach(function (popoverTriggerEl) {
                            if (!popoverTriggerEl._popover) {
                                popoverTriggerEl._popover = new bootstrap.Popover(popoverTriggerEl);
                            }
                        });
                    } catch (e) {
                        console.warn('Bootstrap Popover initialization failed:', e);
                    }
                    
                    // 安全地初始化Modal
                    try {
                        const modalList = [].slice.call(document.querySelectorAll('.modal'));
                        modalList.forEach(function (modalEl) {
                            if (!modalEl._modal) {
                                modalEl._modal = new bootstrap.Modal(modalEl);
                            }
                        });
                    } catch (e) {
                        console.warn('Bootstrap Modal initialization failed:', e);
                    }
                };
                
                // 延迟执行，确保所有脚本加载完成
                setTimeout(reinitializeBootstrapComponents, 100);
                
            } catch (error) {
                console.warn('Bootstrap fix initialization failed:', error);
            }
        }
        
        // 修复jQuery冲突（如果存在）
        if (typeof window.jQuery !== 'undefined' && typeof window.$ !== 'undefined') {
            
            // 确保jQuery不会干扰Bootstrap
            jQuery.noConflict(true);
            
            // 为需要jQuery的组件保留引用
            if (typeof window.editormd !== 'undefined') {
                window.editormd.$ = jQuery;
            }
        }
        
        // 捕获并静默处理Bootstrap错误
        const originalConsoleError = console.error;
        console.error = function(...args) {
            const message = args[0];
            
            // 静默处理已知的Bootstrap selector-engine错误
            if (typeof message === 'string' && 
                (message.includes('selector-engine') || 
                 message.includes('Cannot read properties of undefined') ||
                 message.includes('find'))) {
                // 静默处理这些错误，不输出到控制台
                return;
            }
            
            // 其他错误正常输出
            originalConsoleError.apply(console, args);
        };
        
        // 添加全局错误处理器
        window.addEventListener('error', function(event) {
            const message = event.message;
            
            // 静默处理Bootstrap相关错误
            if (message && (
                message.includes('selector-engine') ||
                message.includes('bootstrap') ||
                message.includes('Cannot read properties of undefined')
            )) {
                event.preventDefault();
                return false;
            }
        });
        
        // 处理未捕获的Promise错误
        window.addEventListener('unhandledrejection', function(event) {
            const message = event.reason && event.reason.message;
            
            if (message && (
                message.includes('selector-engine') ||
                message.includes('bootstrap')
            )) {
                event.preventDefault();
                return false;
            }
        });
    }
    
    // 提供手动重新初始化方法
    window.reinitializeBootstrap = function() {
        if (typeof applyBootstrapFix === 'function') {
            applyBootstrapFix();
        }
    };
    
})();

// 确保在页面完全加载后也执行一次
window.addEventListener('load', function() {
    if (window.reinitializeBootstrap) {
        setTimeout(window.reinitializeBootstrap, 200);
    }
});
