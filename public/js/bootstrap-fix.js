/**
 * Bootstrap JavaScript Dependency Manager
 * 管理Bootstrap与jQuery的依赖关系，确保组件正常工作
 *
 * @author Old Wang (The Grumpy Developer)
 * @version 2.0 - Simplified and more reliable
 */

(function() {
    'use strict';

    // Prevent duplicate execution
    if (window.bootstrapDependencyManagerLoaded) {
        return;
    }
    window.bootstrapDependencyManagerLoaded = true;

    // Configuration
    const config = {
        debug: true,  // 启用调试模式
        retryAttempts: 3,
        retryDelay: 100,
        suppressSelectorErrors: true
    };

    // Utility functions
    const utils = {
        log: function(message, type = 'info') {
            if (config.debug) {
                console[type]('[Bootstrap Manager]', message);
            }
        },

        isBootstrapAvailable: function() {
            return typeof window.bootstrap !== 'undefined';
        },

        isJQueryAvailable: function() {
            return typeof window.jQuery !== 'undefined' && typeof window.$ !== 'undefined';
        }
    };

    // Bootstrap component initializer
    const bootstrapManager = {
        initializeComponents: function() {
            if (!utils.isBootstrapAvailable()) {
                utils.log('Bootstrap not available, skipping component initialization', 'warn');
                return;
            }

            try {
                // Initialize tooltips safely
                this.initTooltips();

                // Initialize popovers safely
                this.initPopovers();

                // Initialize modals safely
                this.initModals();

                utils.log('Bootstrap components initialized successfully');

            } catch (error) {
                utils.log('Error initializing Bootstrap components: ' + error.message, 'error');
            }
        },

        initTooltips: function() {
            try {
                const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipElements.forEach(element => {
                    if (!element._tooltip && element.getAttribute('data-bs-toggle') === 'tooltip') {
                        new bootstrap.Tooltip(element);
                    }
                });
                utils.log(`Initialized ${tooltipElements.length} tooltips`);
            } catch (error) {
                utils.log('Tooltip initialization failed: ' + error.message, 'warn');
            }
        },

        initPopovers: function() {
            try {
                const popoverElements = document.querySelectorAll('[data-bs-toggle="popover"]');
                popoverElements.forEach(element => {
                    if (!element._popover && element.getAttribute('data-bs-toggle') === 'popover') {
                        new bootstrap.Popover(element);
                    }
                });
                utils.log(`Initialized ${popoverElements.length} popovers`);
            } catch (error) {
                utils.log('Popover initialization failed: ' + error.message, 'warn');
            }
        },

        initModals: function() {
            try {
                const modalElements = document.querySelectorAll('.modal');
                modalElements.forEach(element => {
                    if (!element._modal) {
                        new bootstrap.Modal(element);
                    }
                });
                utils.log(`Initialized ${modalElements.length} modals`);
            } catch (error) {
                utils.log('Modal initialization failed: ' + error.message, 'warn');
            }
        }
    };

    // jQuery conflict resolver
    const jqueryManager = {
        resolveConflicts: function() {
            if (!utils.isJQueryAvailable()) {
                utils.log('jQuery not available, no conflicts to resolve');
                return;
            }

            try {
                // Store jQuery reference for components that need it
                if (typeof window.editormd !== 'undefined') {
                    window.editormd.$ = window.jQuery;
                    utils.log('jQuery reference preserved for Editor.md');
                }

                // Store global jQuery reference
                window.koiJQuery = window.jQuery;

                utils.log('jQuery conflicts resolved successfully');

            } catch (error) {
                utils.log('Error resolving jQuery conflicts: ' + error.message, 'error');
            }
        }
    };

    // Selector engine fix
    const selectorEngineFix = {
        patchSelectorEngine: function() {
            if (!utils.isBootstrapAvailable()) {
                return;
            }

            try {
                // Patch Bootstrap's selector engine to handle errors gracefully
                if (window.bootstrap && window.bootstrap.SelectorEngine) {
                    const originalFind = window.bootstrap.SelectorEngine.find;
                    const originalFindOne = window.bootstrap.SelectorEngine.findOne;

                    window.bootstrap.SelectorEngine.find = function(selector, element) {
                        try {
                            return originalFind.call(this, selector, element);
                        } catch (error) {
                            if (config.suppressSelectorErrors) {
                                utils.log('Selector engine error suppressed: ' + error.message, 'warn');
                                return [];
                            }
                            throw error;
                        }
                    };

                    window.bootstrap.SelectorEngine.findOne = function(selector, element) {
                        try {
                            return originalFindOne.call(this, selector, element);
                        } catch (error) {
                            if (config.suppressSelectorErrors) {
                                utils.log('Selector engine error suppressed: ' + error.message, 'warn');
                                return null;
                            }
                            throw error;
                        }
                    };

                    utils.log('Bootstrap selector engine patched successfully');
                }
            } catch (error) {
                utils.log('Failed to patch selector engine: ' + error.message, 'error');
            }
        },

        setupErrorSuppression: function() {
            if (!config.suppressSelectorErrors) {
                return;
            }

            // Suppress specific selector-engine errors
            const originalConsoleError = console.error;
            console.error = function(...args) {
                const message = args[0];
                const fullMessage = args.join(' ');

                if (typeof message === 'string' && (
                    message.includes('selector-engine') ||
                    message.includes('Cannot read properties of undefined') && message.includes('call') ||
                    message.includes('find') && args[1] && args[1].includes('selector-engine') ||
                    fullMessage.includes('selector-engine.js') ||
                    fullMessage.includes('carousel.js') ||
                    fullMessage.includes('offcanvas.js') ||
                    fullMessage.includes('scrollspy.js') ||
                    fullMessage.includes('tab.js') ||
                    (message.includes('Uncaught TypeError') && message.includes('reading \'call\''))
                )) {
                    // Suppress these specific errors
                    utils.log('Suppressed Bootstrap error: ' + message, 'warn');
                    return;
                }

                // Allow other errors through
                originalConsoleError.apply(console, args);
            };

            // Suppress unhandled errors from selector engine
            window.addEventListener('error', function(event) {
                const message = event.message;
                if (message && (
                    message.includes('selector-engine') ||
                    (message.includes('Cannot read properties of undefined') && message.includes('call'))
                )) {
                    event.preventDefault();
                    return false;
                }
            });

            utils.log('Selector engine error suppression enabled');
        }
    };

    // Main initialization function
    function initialize() {
        utils.log('Starting Bootstrap dependency manager initialization');

        // Fix selector engine issues first
        selectorEngineFix.patchSelectorEngine();
        selectorEngineFix.setupErrorSuppression();

        // Resolve jQuery conflicts
        jqueryManager.resolveConflicts();

        // Initialize Bootstrap components
        bootstrapManager.initializeComponents();

        utils.log('Bootstrap dependency manager initialization complete');
    }

    // DOM ready handler
    function onDOMReady() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initialize);
        } else {
            // DOM already loaded, initialize immediately
            setTimeout(initialize, 50);
        }
    }

    // Public API
    window.bootstrapManager = {
        reinitialize: initialize,
        initializeComponents: bootstrapManager.initializeComponents.bind(bootstrapManager),
        config: config
    };

    // Start initialization
    onDOMReady();

    // Also initialize on window load as fallback
    window.addEventListener('load', function() {
        setTimeout(initialize, 100);
    });

})();
