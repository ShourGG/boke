/**
 * Modern Image Lightbox JavaScript
 * 现代化图片灯箱功能 - 支持缩放、拖拽、键盘控制
 */

class ImageLightbox {
    constructor() {
        this.overlay = null;
        this.container = null;
        this.image = null;
        this.loader = null;
        this.info = null;
        this.currentScale = 1;
        this.minScale = 0.5;
        this.maxScale = 3;
        this.isDragging = false;
        this.startX = 0;
        this.startY = 0;
        this.translateX = 0;
        this.translateY = 0;
        
        this.init();
    }
    
    init() {
        this.createLightboxHTML();
        this.bindEvents();
        this.initImageClickHandlers();
    }
    
    createLightboxHTML() {
        // 创建灯箱HTML结构
        const lightboxHTML = `
            <div class="lightbox-overlay" id="lightbox-overlay">
                <div class="lightbox-container">
                    <div class="lightbox-loader" id="lightbox-loader"></div>
                    <img class="lightbox-image" id="lightbox-image" alt="Lightbox Image">
                    <button class="lightbox-close" id="lightbox-close" aria-label="关闭灯箱">
                        <span class="lightbox-icon">✕</span>
                    </button>
                    <div class="lightbox-controls">
                        <button class="lightbox-btn" id="lightbox-zoom-out" aria-label="缩小">
                            <span class="lightbox-icon">−</span>
                        </button>
                        <button class="lightbox-btn" id="lightbox-reset" aria-label="重置">
                            <span class="lightbox-icon">⌂</span>
                        </button>
                        <button class="lightbox-btn" id="lightbox-zoom-in" aria-label="放大">
                            <span class="lightbox-icon">+</span>
                        </button>
                    </div>
                    <div class="lightbox-info" id="lightbox-info"></div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', lightboxHTML);
        
        // 获取元素引用
        this.overlay = document.getElementById('lightbox-overlay');
        this.container = this.overlay.querySelector('.lightbox-container');
        this.image = document.getElementById('lightbox-image');
        this.loader = document.getElementById('lightbox-loader');
        this.info = document.getElementById('lightbox-info');
    }
    
    bindEvents() {
        // 关闭按钮
        document.getElementById('lightbox-close').addEventListener('click', () => this.close());
        
        // 背景点击关闭
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.close();
            }
        });
        
        // 键盘事件
        document.addEventListener('keydown', (e) => {
            if (!this.overlay.classList.contains('active')) return;
            
            switch(e.key) {
                case 'Escape':
                    this.close();
                    break;
                case '+':
                case '=':
                    this.zoomIn();
                    break;
                case '-':
                    this.zoomOut();
                    break;
                case '0':
                    this.resetZoom();
                    break;
            }
        });
        
        // 缩放控制按钮
        document.getElementById('lightbox-zoom-in').addEventListener('click', () => this.zoomIn());
        document.getElementById('lightbox-zoom-out').addEventListener('click', () => this.zoomOut());
        document.getElementById('lightbox-reset').addEventListener('click', () => this.resetZoom());
        
        // 鼠标滚轮缩放
        this.container.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.1 : 0.1;
            this.zoom(delta);
        });
        
        // 拖拽功能
        this.image.addEventListener('mousedown', (e) => this.startDrag(e));
        document.addEventListener('mousemove', (e) => this.drag(e));
        document.addEventListener('mouseup', () => this.endDrag());
        
        // 触摸事件（移动端）
        this.image.addEventListener('touchstart', (e) => this.startTouch(e));
        this.image.addEventListener('touchmove', (e) => this.moveTouch(e));
        this.image.addEventListener('touchend', () => this.endTouch());
        
        // 双击重置
        this.image.addEventListener('dblclick', () => this.resetZoom());
    }
    
    initImageClickHandlers() {
        // 为所有内容区域的图片添加点击事件
        const selectors = [
            '.post-content img',
            '.card-body img', 
            '.content img',
            'article img',
            '.blog-content img'
        ];
        
        selectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(img => {
                img.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.open(img.src, img.alt || '');
                });
            });
        });
    }
    
    open(imageSrc, imageAlt = '') {
        // 显示加载器
        this.loader.style.display = 'block';
        this.image.style.display = 'none';
        
        // 重置状态
        this.currentScale = 1;
        this.translateX = 0;
        this.translateY = 0;
        
        // 显示灯箱
        this.overlay.classList.add('active');
        document.body.classList.add('lightbox-open');
        
        // 加载图片
        const img = new Image();
        img.onload = () => {
            this.image.src = imageSrc;
            this.image.alt = imageAlt;
            this.loader.style.display = 'none';
            this.image.style.display = 'block';
            
            // 显示图片信息
            if (imageAlt) {
                this.info.textContent = imageAlt;
                this.info.classList.add('show');
                setTimeout(() => this.info.classList.remove('show'), 3000);
            }
            
            this.updateTransform();
            this.updateControls();
        };
        
        img.onerror = () => {
            this.loader.style.display = 'none';
            this.info.textContent = '图片加载失败';
            this.info.classList.add('show');
            setTimeout(() => this.close(), 2000);
        };
        
        img.src = imageSrc;
    }
    
    close() {
        this.overlay.classList.remove('active');
        document.body.classList.remove('lightbox-open');
        
        // 清理状态
        setTimeout(() => {
            this.image.src = '';
            this.info.classList.remove('show');
        }, 300);
    }
    
    zoomIn() {
        this.zoom(0.2);
    }
    
    zoomOut() {
        this.zoom(-0.2);
    }
    
    zoom(delta) {
        const newScale = Math.max(this.minScale, Math.min(this.maxScale, this.currentScale + delta));
        if (newScale !== this.currentScale) {
            this.currentScale = newScale;
            this.updateTransform();
            this.updateControls();
        }
    }
    
    resetZoom() {
        this.currentScale = 1;
        this.translateX = 0;
        this.translateY = 0;
        this.updateTransform();
        this.updateControls();
    }
    
    updateTransform() {
        this.image.style.transform = `scale(${this.currentScale}) translate(${this.translateX}px, ${this.translateY}px)`;
        
        // 更新光标样式
        if (this.currentScale > 1) {
            this.image.classList.add('zoomed');
            this.overlay.style.cursor = 'grab';
        } else {
            this.image.classList.remove('zoomed');
            this.overlay.style.cursor = 'zoom-out';
        }
    }
    
    updateControls() {
        const zoomInBtn = document.getElementById('lightbox-zoom-in');
        const zoomOutBtn = document.getElementById('lightbox-zoom-out');
        
        // 更新按钮状态
        if (this.currentScale >= this.maxScale) {
            zoomInBtn.classList.add('disabled');
        } else {
            zoomInBtn.classList.remove('disabled');
        }
        
        if (this.currentScale <= this.minScale) {
            zoomOutBtn.classList.add('disabled');
        } else {
            zoomOutBtn.classList.remove('disabled');
        }
    }
    
    startDrag(e) {
        if (this.currentScale <= 1) return;

        this.isDragging = true;
        this.startX = e.clientX - this.translateX;
        this.startY = e.clientY - this.translateY;
        this.overlay.style.cursor = 'grabbing';
        this.image.style.transition = 'none'; // 禁用过渡动画以提高拖拽流畅度
        e.preventDefault();
    }

    drag(e) {
        if (!this.isDragging) return;

        e.preventDefault();

        // 使用requestAnimationFrame优化拖拽性能
        requestAnimationFrame(() => {
            this.translateX = e.clientX - this.startX;
            this.translateY = e.clientY - this.startY;
            this.updateTransform();
        });
    }

    endDrag() {
        this.isDragging = false;
        this.image.style.transition = 'transform 0.2s ease'; // 恢复过渡动画
        if (this.currentScale > 1) {
            this.overlay.style.cursor = 'grab';
        }
    }
    
    // 触摸事件处理
    startTouch(e) {
        if (e.touches.length === 1 && this.currentScale > 1) {
            this.isDragging = true;
            const touch = e.touches[0];
            this.startX = touch.clientX - this.translateX;
            this.startY = touch.clientY - this.translateY;
            this.image.style.transition = 'none'; // 禁用过渡动画
            e.preventDefault();
        }
    }

    moveTouch(e) {
        if (!this.isDragging || e.touches.length !== 1) return;

        e.preventDefault();

        // 使用requestAnimationFrame优化触摸拖拽性能
        requestAnimationFrame(() => {
            const touch = e.touches[0];
            this.translateX = touch.clientX - this.startX;
            this.translateY = touch.clientY - this.startY;
            this.updateTransform();
        });
    }

    endTouch() {
        this.isDragging = false;
        this.image.style.transition = 'transform 0.2s ease'; // 恢复过渡动画
    }
}

// 页面加载完成后初始化灯箱
document.addEventListener('DOMContentLoaded', () => {
    new ImageLightbox();
});

// 为动态添加的图片重新绑定事件
window.reinitLightbox = function() {
    if (window.lightboxInstance) {
        window.lightboxInstance.initImageClickHandlers();
    }
};
