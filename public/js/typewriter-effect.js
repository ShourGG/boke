/**
 * Typewriter Effect for Banner Text
 * @author Old Wang
 * @version 1.0 - Professional Typewriter Animation
 */

console.log('%c🖨️ Typewriter Effect Loading...', 'color: #4caf50; font-size: 16px; font-weight: bold;');

class TypewriterEffect {
    constructor(options = {}) {
        this.options = {
            // 基本配置
            titleElement: options.titleElement || '.banner-text .h1',
            subtitleElement: options.subtitleElement || '#subtitle',
            
            // 打字速度配置
            typeSpeed: options.typeSpeed || 100,        // 打字速度 (ms)
            deleteSpeed: options.deleteSpeed || 50,     // 删除速度 (ms)
            pauseTime: options.pauseTime || 2000,       // 每行完成后暂停时间 (ms)
            
            // 动画配置
            cursorChar: options.cursorChar || '|',      // 光标字符
            cursorBlinkSpeed: options.cursorBlinkSpeed || 500, // 光标闪烁速度 (ms)
            
            // 文字配置
            texts: options.texts || [],                 // 要显示的文字数组
            loop: options.loop !== false,               // 是否循环播放
            
            // 调试模式
            debug: options.debug || false
        };
        
        this.currentTextIndex = 0;
        this.currentCharIndex = 0;
        this.isDeleting = false;
        this.isRunning = false;
        this.cursorInterval = null;
        this.typeInterval = null;
        
        this.init();
    }
    
    init() {
        console.log('%c🚀 Initializing Typewriter Effect...', 'color: #2196f3; font-weight: bold;');
        
        // 获取元素
        this.titleEl = document.querySelector(this.options.titleElement);
        this.subtitleEl = document.querySelector(this.options.subtitleElement);
        
        if (!this.subtitleEl) {
            console.error('%c❌ Subtitle element not found:', 'color: #f44336;', this.options.subtitleElement);
            return;
        }
        
        // 获取原始文字
        this.getOriginalTexts();
        
        // 设置初始状态
        this.setupInitialState();
        
        // 启动光标闪烁
        this.startCursorBlink();
        
        // 启动打字机效果
        this.start();
        
        console.log('%c✅ Typewriter Effect initialized!', 'color: #4caf50; font-weight: bold;');
    }
    
    getOriginalTexts() {
        // 如果没有提供文字数组，从DOM获取
        if (this.options.texts.length === 0) {
            // 首先尝试从data属性获取
            const dataTexts = this.subtitleEl.getAttribute('data-typewriter-texts');

            if (dataTexts) {
                // 从data属性获取多行文字
                this.options.texts = dataTexts.split('|').map(text => text.trim()).filter(text => text.length > 0);
            } else {
                // 从文字内容获取
                const originalText = this.subtitleEl.textContent.trim();

                // 检查是否包含多行文字（用 | 或 \n 分隔）
                if (originalText.includes('|')) {
                    this.options.texts = originalText.split('|').map(text => text.trim());
                } else if (originalText.includes('\n')) {
                    this.options.texts = originalText.split('\n').map(text => text.trim());
                } else {
                    // 单行文字，只显示这一行文字
                    this.options.texts = [originalText];
                }
            }
        }

        // 过滤空文字
        this.options.texts = this.options.texts.filter(text => text.length > 0);

        // 如果只有一行文字，禁用循环模式
        if (this.options.texts.length === 1) {
            this.options.loop = false;
            console.log('%c📝 Single text detected, loop disabled:', 'color: #ff9800;', this.options.texts[0]);
        } else {
            console.log('%c📝 Multiple texts to display:', 'color: #ff9800;', this.options.texts);
        }
    }
    
    setupInitialState() {
        // 清空文字内容
        this.subtitleEl.innerHTML = '<span class="typewriter-text"></span><span class="typewriter-cursor">' + this.options.cursorChar + '</span>';

        this.textSpan = this.subtitleEl.querySelector('.typewriter-text');
        this.cursorSpan = this.subtitleEl.querySelector('.typewriter-cursor');

        // 设置光标样式
        this.cursorSpan.style.opacity = '1';
        this.cursorSpan.style.animation = 'none';

        // 添加激活类，显示打字机效果
        this.subtitleEl.classList.add('typewriter-active');
        this.subtitleEl.style.opacity = '1';
    }
    
    startCursorBlink() {
        this.cursorInterval = setInterval(() => {
            if (this.cursorSpan) {
                this.cursorSpan.style.opacity = this.cursorSpan.style.opacity === '0' ? '1' : '0';
            }
        }, this.options.cursorBlinkSpeed);
    }
    
    stopCursorBlink() {
        if (this.cursorInterval) {
            clearInterval(this.cursorInterval);
            this.cursorInterval = null;
        }
    }
    
    start() {
        if (this.isRunning) return;
        
        this.isRunning = true;
        console.log('%c🖨️ Starting typewriter animation...', 'color: #4caf50;');
        
        this.typeText();
    }
    
    stop() {
        this.isRunning = false;
        
        if (this.typeInterval) {
            clearTimeout(this.typeInterval);
            this.typeInterval = null;
        }
        
        this.stopCursorBlink();
        console.log('%c⏹️ Typewriter animation stopped', 'color: #ff9800;');
    }
    
    typeText() {
        if (!this.isRunning) return;
        
        const currentText = this.options.texts[this.currentTextIndex];
        
        if (this.options.debug) {
            console.log('%c🔤 Typing:', 'color: #2196f3;', `"${currentText}" [${this.currentCharIndex}/${currentText.length}]`);
        }
        
        if (!this.isDeleting) {
            // 打字模式
            if (this.currentCharIndex < currentText.length) {
                this.textSpan.textContent = currentText.substring(0, this.currentCharIndex + 1);
                this.currentCharIndex++;
                
                this.typeInterval = setTimeout(() => this.typeText(), this.options.typeSpeed);
            } else {
                // 当前行打字完成
                if (!this.options.loop && this.options.texts.length === 1) {
                    // 单行文字且不循环，保持显示
                    console.log('%c✅ Single text typing completed, keeping displayed', 'color: #4caf50; font-weight: bold;');
                    return;
                } else {
                    // 多行文字或循环模式，暂停后开始删除
                    this.typeInterval = setTimeout(() => {
                        this.isDeleting = true;
                        this.typeText();
                    }, this.options.pauseTime);
                }
            }
        } else {
            // 删除模式
            if (this.currentCharIndex > 0) {
                this.textSpan.textContent = currentText.substring(0, this.currentCharIndex - 1);
                this.currentCharIndex--;
                
                this.typeInterval = setTimeout(() => this.typeText(), this.options.deleteSpeed);
            } else {
                // 当前行删除完成，切换到下一行
                this.isDeleting = false;
                this.currentTextIndex = (this.currentTextIndex + 1) % this.options.texts.length;
                
                // 如果不循环且已经显示完所有文字，停止
                if (!this.options.loop && this.currentTextIndex === 0) {
                    this.stop();
                    return;
                }
                
                this.typeInterval = setTimeout(() => this.typeText(), 500); // 短暂暂停后开始下一行
            }
        }
    }
    
    // 公共方法：更新文字数组
    updateTexts(newTexts) {
        this.options.texts = newTexts;
        this.currentTextIndex = 0;
        this.currentCharIndex = 0;
        this.isDeleting = false;
        
        if (this.options.debug) {
            console.log('%c📝 Updated texts:', 'color: #ff9800;', newTexts);
        }
    }
    
    // 公共方法：重新开始
    restart() {
        this.stop();
        this.currentTextIndex = 0;
        this.currentCharIndex = 0;
        this.isDeleting = false;
        this.setupInitialState();
        this.startCursorBlink();
        this.start();
    }
}

// 全局初始化函数
function initTypewriterEffect() {
    console.log('%c🎬 Initializing Banner Typewriter Effect...', 'color: #2196f3; font-weight: bold;');

    // 等待DOM完全加载
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(initTypewriterEffect, 50); // 最小延迟50ms
        });
        return;
    }

    // 检查是否在首页且有Banner
    const banner = document.querySelector('#banner');
    const subtitleEl = document.querySelector('#subtitle');

    if (!banner || !subtitleEl) {
        console.log('%c⚠️ Banner or subtitle not found, skipping typewriter effect', 'color: #ff9800;');
        return;
    }

    // 立即启动打字机效果（CSS已经预隐藏了文字）
    window.bannerTypewriter = new TypewriterEffect({
        typeSpeed: 80,           // 打字速度
        deleteSpeed: 40,         // 删除速度
        pauseTime: 3000,         // 每行完成后暂停3秒
        cursorChar: '|',         // 光标字符
        cursorBlinkSpeed: 600,   // 光标闪烁速度
        loop: true,              // 循环播放
        debug: false             // 调试模式
    });

    console.log('%c🎉 Banner Typewriter Effect started!', 'color: #4caf50; font-size: 16px; font-weight: bold;');
}

// 自动初始化
initTypewriterEffect();

console.log('%c✅ Typewriter Effect Script Loaded!', 'color: #4caf50; font-size: 16px; font-weight: bold;');
