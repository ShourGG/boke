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
        debug: false,
        retryAttempts: 3,
        retryDelay: 100
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

    // Main initialization function
    function initialize() {
        utils.log('Starting Bootstrap dependency manager initialization');

        // Resolve jQuery conflicts first
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
