// Fluid主题切换功能
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;

    // 检查本地存储中的主题偏好
    const savedTheme = localStorage.getItem('theme') || 'light';
    body.setAttribute('data-theme', savedTheme);

    // 更新按钮文本和图标
    updateThemeButton();

    // 主题切换事件
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();

            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            updateThemeButton();

            // 添加切换动画
            body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
            setTimeout(() => {
                body.style.transition = '';
            }, 300);
        });
    }

    function updateThemeButton() {
        if (themeToggle) {
            const currentTheme = body.getAttribute('data-theme');
            const icon = themeToggle.querySelector('i');

            if (currentTheme === 'light') {
                icon.className = 'fas fa-moon';
                themeToggle.innerHTML = '<i class="fas fa-moon"></i> 深色模式';
            } else {
                icon.className = 'fas fa-sun';
                themeToggle.innerHTML = '<i class="fas fa-sun"></i> 浅色模式';
            }
        }
    }
});

// 为按钮添加涟漪效果
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.classList.add('ripple');
    });
});

// 卡片进入动画
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, {
        threshold: 0.1
    });
    
    cards.forEach(card => {
        observer.observe(card);
    });
});

// 平滑滚动
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');

            // Skip invalid selectors or empty hrefs
            if (!targetId || targetId === '#') {
                return;
            }

            let targetElement = null;
            try {
                // Try direct querySelector first
                targetElement = document.querySelector(targetId);
            } catch (error) {
                // If selector is invalid, try to find by ID
                const id = targetId.substring(1); // Remove the #
                targetElement = document.getElementById(id);

                // If still not found, try to escape the ID for CSS selector
                if (!targetElement) {
                    try {
                        const escapedId = CSS.escape(id);
                        targetElement = document.querySelector('#' + escapedId);
                    } catch (escapeError) {
                        console.warn('Invalid anchor link in theme-toggle:', targetId);
                        return;
                    }
                }
            }

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});
