/**
 * Admin Panel JavaScript
 * Handles admin interface interactions and functionality
 */

// Global admin functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme system first
    initThemeSystem();

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                alert.classList.remove('show');
                setTimeout(function() {
                    if (alert && alert.parentNode) {
                        alert.remove();
                    }
                }, 150);
            }
        }, 5000);
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-action="delete"]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('确定要删除这个项目吗？此操作不可撤销。')) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Batch actions functionality
    initBatchActions();

    // Form validation
    initFormValidation();

    // Auto-save drafts (if applicable)
    initAutoSave();
});

/**
 * Initialize batch actions for admin lists
 */
function initBatchActions() {
    const batchForm = document.getElementById('batchForm');
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('input[name="ids[]"]');
    const batchActionSelect = document.getElementById('batchAction');
    const batchSubmitBtn = document.getElementById('batchSubmit');

    if (!batchForm) return;

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBatchButtonState();
        });
    }

    // Individual checkbox change
    itemCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBatchButtonState();
        });
    });

    // Batch action form submission
    if (batchForm) {
        batchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedIds = Array.from(itemCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            const action = batchActionSelect ? batchActionSelect.value : '';
            
            if (selectedIds.length === 0) {
                alert('请选择要操作的项目');
                return;
            }
            
            if (!action) {
                alert('请选择要执行的操作');
                return;
            }
            
            // Confirm action
            let confirmMessage = '确定要对选中的 ' + selectedIds.length + ' 个项目执行此操作吗？';
            if (action === 'delete') {
                confirmMessage = '确定要删除选中的 ' + selectedIds.length + ' 个项目吗？此操作不可撤销！';
            }
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            // Submit batch action
            submitBatchAction(selectedIds, action);
        });
    }

    function updateSelectAllState() {
        if (!selectAllCheckbox) return;
        
        const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
        const totalCount = itemCheckboxes.length;
        
        if (checkedCount === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedCount === totalCount) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
    }

    function updateBatchButtonState() {
        if (!batchSubmitBtn) return;
        
        const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
        batchSubmitBtn.disabled = checkedCount === 0;
    }
}

/**
 * Submit batch action via AJAX
 */
function submitBatchAction(ids, action) {
    const currentPath = window.location.pathname;
    const batchUrl = currentPath + '/batch-action';
    
    // Show loading state
    const submitBtn = document.getElementById('batchSubmit');
    const originalText = submitBtn ? submitBtn.textContent : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 处理中...';
    }
    
    fetch(batchUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: action,
            ids: ids
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('success', data.message || '操作成功完成');
            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('danger', data.message || '操作失败');
        }
    })
    .catch(error => {
        console.error('Batch action error:', error);
        showAlert('danger', '操作失败，请稍后重试');
    })
    .finally(() => {
        // Restore button state
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

/**
 * Show alert message
 */
function showAlert(type, message) {
    const alertContainer = document.querySelector('.container-fluid');
    if (!alertContainer) return;
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.insertBefore(alertDiv, alertContainer.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.classList.remove('show');
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 150);
        }
    }, 5000);
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

/**
 * Initialize auto-save functionality for forms
 */
function initAutoSave() {
    const autoSaveForms = document.querySelectorAll('[data-auto-save]');
    
    autoSaveForms.forEach(function(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        let saveTimeout;
        
        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    saveFormData(form);
                }, 2000); // Save after 2 seconds of inactivity
            });
        });
    });
}

/**
 * Save form data to localStorage
 */
function saveFormData(form) {
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    const formId = form.id || 'autoSaveForm';
    localStorage.setItem('autoSave_' + formId, JSON.stringify(data));
    
    // Show save indicator
    showSaveIndicator();
}

/**
 * Show save indicator
 */
function showSaveIndicator() {
    let indicator = document.getElementById('autoSaveIndicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'autoSaveIndicator';
        indicator.className = 'position-fixed top-0 end-0 m-3 alert alert-info alert-sm';
        indicator.style.zIndex = '9999';
        document.body.appendChild(indicator);
    }
    
    indicator.innerHTML = '<i class="fas fa-save"></i> 已自动保存';
    indicator.style.display = 'block';
    
    setTimeout(() => {
        indicator.style.display = 'none';
    }, 2000);
}

/**
 * Load saved form data from localStorage
 */
function loadSavedFormData(formId) {
    const savedData = localStorage.getItem('autoSave_' + formId);
    
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            const form = document.getElementById(formId);
            
            if (form) {
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input && input.value === '') {
                        input.value = data[key];
                    }
                });
            }
        } catch (e) {
            console.error('Error loading saved form data:', e);
        }
    }
}

/**
 * Clear saved form data
 */
function clearSavedFormData(formId) {
    localStorage.removeItem('autoSave_' + formId);
}

/**
 * Theme System - Dark Mode Support
 */
function initThemeSystem() {
    // Apply saved theme or detect system preference
    const savedTheme = getThemePreference();
    applyTheme(savedTheme);

    // Initialize theme toggle button if it exists
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
        updateThemeToggleIcon(savedTheme);
    }

    // Listen for system theme changes
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', handleSystemThemeChange);
    }
}

/**
 * Get user's theme preference
 */
function getThemePreference() {
    // Check localStorage first
    const savedTheme = localStorage.getItem('admin_theme');
    if (savedTheme && (savedTheme === 'light' || savedTheme === 'dark')) {
        return savedTheme;
    }

    // Check system preference
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
    }

    return 'light';
}

/**
 * Apply theme to the document
 */
function applyTheme(theme) {
    const html = document.documentElement;

    // Add transition class to prevent flashing
    html.classList.add('theme-changing');

    // Apply theme
    if (theme === 'dark') {
        html.setAttribute('data-theme', 'dark');
    } else {
        html.removeAttribute('data-theme');
    }

    // Remove transition class after a short delay
    setTimeout(() => {
        html.classList.remove('theme-changing');
    }, 50);

    // Save preference
    localStorage.setItem('admin_theme', theme);

    // Update toggle button
    updateThemeToggleIcon(theme);
}

/**
 * Toggle between light and dark themes
 */
function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
}

/**
 * Update theme toggle button icon and text
 */
function updateThemeToggleIcon(theme) {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;

    const icon = themeToggle.querySelector('i');
    const text = themeToggle.querySelector('.theme-text');

    if (theme === 'dark') {
        if (icon) {
            icon.className = 'fas fa-sun';
        }
        if (text) {
            text.textContent = '浅色';
        }
        themeToggle.setAttribute('title', '切换到浅色模式');
    } else {
        if (icon) {
            icon.className = 'fas fa-moon';
        }
        if (text) {
            text.textContent = '深色';
        }
        themeToggle.setAttribute('title', '切换到深色模式');
    }
}

/**
 * Handle system theme changes
 */
function handleSystemThemeChange(e) {
    // Only auto-switch if user hasn't manually set a preference
    const savedTheme = localStorage.getItem('admin_theme');
    if (!savedTheme) {
        const newTheme = e.matches ? 'dark' : 'light';
        applyTheme(newTheme);
    }
}
