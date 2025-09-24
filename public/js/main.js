/**
 * Koi Blog Main JavaScript
 * Enhanced user experience and interactive features
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Search form enhancement
    const searchForm = document.querySelector('form[action*="search"]');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="q"]');
        if (searchInput) {
            // Add search suggestions (placeholder for future enhancement)
            searchInput.addEventListener('input', function() {
                // Future: implement search suggestions
            });
        }
    }
    
    // Image lazy loading
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Copy to clipboard functionality
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                showToast('已复制到剪贴板', 'success');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('已复制到剪贴板', 'success');
        }
    }
    
    // Add copy buttons to code blocks
    const codeBlocks = document.querySelectorAll('pre code');
    codeBlocks.forEach(function(codeBlock) {
        const pre = codeBlock.parentNode;
        const button = document.createElement('button');
        button.className = 'btn btn-sm btn-outline-secondary copy-btn';
        button.innerHTML = '<i class="fas fa-copy"></i>';
        button.title = '复制代码';
        
        button.addEventListener('click', function() {
            copyToClipboard(codeBlock.textContent);
        });
        
        pre.style.position = 'relative';
        pre.appendChild(button);
    });
    
    // Toast notification system
    function showToast(message, type = 'info') {
        const toastContainer = getOrCreateToastContainer();
        const toast = createToast(message, type);
        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast element after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
    
    function getOrCreateToastContainer() {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1055';
            document.body.appendChild(container);
        }
        return container;
    }
    
    function createToast(message, type) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.setAttribute('role', 'alert');
        
        const iconMap = {
            'success': 'fas fa-check-circle text-success',
            'error': 'fas fa-exclamation-circle text-danger',
            'warning': 'fas fa-exclamation-triangle text-warning',
            'info': 'fas fa-info-circle text-info'
        };
        
        const icon = iconMap[type] || iconMap['info'];
        
        toast.innerHTML = `
            <div class="toast-header">
                <i class="${icon} me-2"></i>
                <strong class="me-auto">通知</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        return toast;
    }
    
    // Form validation enhancement
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                showToast('请检查表单中的错误', 'error');
            }
            form.classList.add('was-validated');
        });
    });
    
    // Back to top button
    const backToTopButton = document.createElement('button');
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.className = 'btn btn-primary btn-floating back-to-top';
    backToTopButton.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    
    document.body.appendChild(backToTopButton);
    
    // Show/hide back to top button
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    // Back to top functionality
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // External links handling
    const externalLinks = document.querySelectorAll('a[href^="http"]:not([href*="' + window.location.hostname + '"])');
    externalLinks.forEach(function(link) {
        link.setAttribute('target', '_blank');
        link.setAttribute('rel', 'noopener noreferrer');
        
        // Add external link icon
        if (!link.querySelector('.fa-external-link-alt')) {
            const icon = document.createElement('i');
            icon.className = 'fas fa-external-link-alt ms-1';
            icon.style.fontSize = '0.8em';
            link.appendChild(icon);
        }
    });
    
    // Reading progress bar (for post pages)
    if (document.querySelector('.post-content')) {
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(to right, #3498db, #2ecc71);
            z-index: 1000;
            transition: width 0.3s ease;
        `;
        document.body.appendChild(progressBar);
        
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        });
    }
    
    // Initialize Fluid Banner
    initializeFluidBanner();

    // Make functions globally available
    window.showToast = showToast;
    window.copyToClipboard = copyToClipboard;
});

/**
 * Fluid主题Banner功能
 */
function initializeFluidBanner() {
    const navbar = document.querySelector('.navbar.scrolling-navbar');
    const banner = document.querySelector('#banner');
    const scrollDownBar = document.querySelector('.scroll-down-bar');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const animatedIcon = document.querySelector('.animated-icon');

    if (!navbar || !banner) return;

    // 导航栏滚动效果
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // 添加/移除导航栏背景
        if (scrollTop > 50) {
            navbar.classList.add('top-nav-collapse');
        } else {
            navbar.classList.remove('top-nav-collapse');
        }

        lastScrollTop = scrollTop;
    });

    // 滚动箭头点击事件 (Enhanced with debugging)
    if (scrollDownBar) {
        console.log('Scroll down bar found, adding event listener');

        // Add multiple event listeners for better compatibility
        scrollDownBar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Scroll button clicked!');

            const bannerHeight = banner.offsetHeight;
            console.log('Banner height:', bannerHeight);

            window.scrollTo({
                top: bannerHeight,
                behavior: 'smooth'
            });
        });

        // Also add touchstart for mobile devices
        scrollDownBar.addEventListener('touchstart', function(e) {
            e.preventDefault();
            console.log('Scroll button touched!');

            const bannerHeight = banner.offsetHeight;
            window.scrollTo({
                top: bannerHeight,
                behavior: 'smooth'
            });
        });

        console.log('Scroll button event listeners added successfully');
    } else {
        console.error('Scroll down bar not found!');
    }

    // 移动端导航栏切换动画
    if (navbarToggler && animatedIcon) {
        navbarToggler.addEventListener('click', function() {
            animatedIcon.classList.toggle('open');
        });
    }

    // Banner视差效果（可选）
    if (banner.hasAttribute('parallax')) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.5;
            banner.style.transform = `translateY(${parallax}px)`;
        });
    }

    // 打字机效果（如果需要）
    const subtitle = document.querySelector('#subtitle');
    if (subtitle && subtitle.hasAttribute('data-typed-text')) {
        const text = subtitle.getAttribute('data-typed-text');
        subtitle.textContent = '';
        typeWriter(subtitle, text, 0);
    }
}

/**
 * 打字机效果
 */
function typeWriter(element, text, index) {
    if (index < text.length) {
        element.textContent += text.charAt(index);
        setTimeout(() => typeWriter(element, text, index + 1), 100);
    }
}
