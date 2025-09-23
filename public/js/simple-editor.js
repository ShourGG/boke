/**
 * Simple Markdown Editor
 * 轻量级本地Markdown编辑器，无需外部依赖
 */

class SimpleMarkdownEditor {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        this.options = {
            height: options.height || '400px',
            placeholder: options.placeholder || '请输入内容...',
            toolbar: options.toolbar !== false,
            preview: options.preview !== false,
            ...options
        };
        
        this.init();
    }
    
    init() {
        this.createEditor();
        this.bindEvents();
    }
    
    createEditor() {
        this.container.innerHTML = `
            <div class="simple-editor">
                ${this.options.toolbar ? this.createToolbar() : ''}
                <div class="editor-content">
                    <div class="editor-panes">
                        <div class="editor-pane editor-input">
                            <textarea id="markdown-input" placeholder="${this.options.placeholder}" 
                                      style="height: ${this.options.height};">${this.container.dataset.content || ''}</textarea>
                        </div>
                        ${this.options.preview ? `
                        <div class="editor-pane editor-preview">
                            <div id="markdown-preview" class="preview-content"></div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        this.textarea = this.container.querySelector('#markdown-input');
        this.preview = this.container.querySelector('#markdown-preview');
        
        // 初始预览
        if (this.preview) {
            this.updatePreview();
        }
    }
    
    createToolbar() {
        return `
            <div class="editor-toolbar">
                <div class="toolbar-group">
                    <button type="button" class="toolbar-btn" data-action="bold" title="粗体">
                        <i class="fas fa-bold"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="italic" title="斜体">
                        <i class="fas fa-italic"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="strikethrough" title="删除线">
                        <i class="fas fa-strikethrough"></i>
                    </button>
                </div>
                <div class="toolbar-group">
                    <button type="button" class="toolbar-btn" data-action="heading" title="标题">
                        <i class="fas fa-heading"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="quote" title="引用">
                        <i class="fas fa-quote-left"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="code" title="代码">
                        <i class="fas fa-code"></i>
                    </button>
                </div>
                <div class="toolbar-group">
                    <button type="button" class="toolbar-btn" data-action="list" title="列表">
                        <i class="fas fa-list-ul"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="ordered-list" title="有序列表">
                        <i class="fas fa-list-ol"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="link" title="链接">
                        <i class="fas fa-link"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="image" title="图片">
                        <i class="fas fa-image"></i>
                    </button>
                </div>
                <div class="toolbar-group">
                    <button type="button" class="toolbar-btn" data-action="table" title="表格">
                        <i class="fas fa-table"></i>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="hr" title="分割线">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                ${this.options.preview ? `
                <div class="toolbar-group">
                    <button type="button" class="toolbar-btn toggle-preview" data-action="toggle-preview" title="切换预览">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                ` : ''}
            </div>
        `;
    }
    
    bindEvents() {
        // 工具栏事件
        if (this.options.toolbar) {
            this.container.addEventListener('click', (e) => {
                if (e.target.closest('.toolbar-btn')) {
                    e.preventDefault();
                    const action = e.target.closest('.toolbar-btn').dataset.action;
                    this.executeAction(action);
                }
            });
        }
        
        // 输入事件
        this.textarea.addEventListener('input', () => {
            if (this.preview) {
                this.updatePreview();
            }
        });
        
        // 快捷键
        this.textarea.addEventListener('keydown', (e) => {
            this.handleKeydown(e);
        });
    }
    
    executeAction(action) {
        const start = this.textarea.selectionStart;
        const end = this.textarea.selectionEnd;
        const selectedText = this.textarea.value.substring(start, end);
        
        let replacement = '';
        let cursorOffset = 0;
        
        switch (action) {
            case 'bold':
                replacement = `**${selectedText || '粗体文本'}**`;
                cursorOffset = selectedText ? 0 : -2;
                break;
            case 'italic':
                replacement = `*${selectedText || '斜体文本'}*`;
                cursorOffset = selectedText ? 0 : -1;
                break;
            case 'strikethrough':
                replacement = `~~${selectedText || '删除线文本'}~~`;
                cursorOffset = selectedText ? 0 : -2;
                break;
            case 'heading':
                replacement = `## ${selectedText || '标题'}`;
                cursorOffset = selectedText ? 0 : -2;
                break;
            case 'quote':
                replacement = `> ${selectedText || '引用文本'}`;
                cursorOffset = selectedText ? 0 : -4;
                break;
            case 'code':
                if (selectedText.includes('\n')) {
                    replacement = `\`\`\`\n${selectedText || '代码块'}\n\`\`\``;
                    cursorOffset = selectedText ? 0 : -4;
                } else {
                    replacement = `\`${selectedText || '代码'}\``;
                    cursorOffset = selectedText ? 0 : -1;
                }
                break;
            case 'list':
                replacement = `- ${selectedText || '列表项'}`;
                cursorOffset = selectedText ? 0 : -3;
                break;
            case 'ordered-list':
                replacement = `1. ${selectedText || '列表项'}`;
                cursorOffset = selectedText ? 0 : -3;
                break;
            case 'link':
                replacement = `[${selectedText || '链接文本'}](url)`;
                cursorOffset = selectedText ? -5 : -9;
                break;
            case 'image':
                replacement = `![${selectedText || '图片描述'}](图片链接)`;
                cursorOffset = selectedText ? -7 : -11;
                break;
            case 'table':
                replacement = `| 列1 | 列2 | 列3 |\n|-----|-----|-----|\n| 内容 | 内容 | 内容 |`;
                cursorOffset = -35;
                break;
            case 'hr':
                replacement = '\n---\n';
                cursorOffset = 0;
                break;
            case 'toggle-preview':
                this.togglePreview();
                return;
        }
        
        this.insertText(replacement, cursorOffset);
    }
    
    insertText(text, cursorOffset = 0) {
        const start = this.textarea.selectionStart;
        const end = this.textarea.selectionEnd;
        const value = this.textarea.value;
        
        this.textarea.value = value.substring(0, start) + text + value.substring(end);
        
        const newCursorPos = start + text.length + cursorOffset;
        this.textarea.setSelectionRange(newCursorPos, newCursorPos);
        this.textarea.focus();
        
        if (this.preview) {
            this.updatePreview();
        }
    }
    
    handleKeydown(e) {
        // Tab键插入空格
        if (e.key === 'Tab') {
            e.preventDefault();
            this.insertText('    ');
        }
        
        // Ctrl+快捷键
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case 'b':
                    e.preventDefault();
                    this.executeAction('bold');
                    break;
                case 'i':
                    e.preventDefault();
                    this.executeAction('italic');
                    break;
                case 'k':
                    e.preventDefault();
                    this.executeAction('link');
                    break;
            }
        }
    }
    
    updatePreview() {
        if (!this.preview) return;
        
        const markdown = this.textarea.value;
        const html = this.markdownToHtml(markdown);
        this.preview.innerHTML = html;
    }
    
    togglePreview() {
        const editorPanes = this.container.querySelector('.editor-panes');
        const toggleBtn = this.container.querySelector('.toggle-preview i');
        
        editorPanes.classList.toggle('preview-only');
        
        if (editorPanes.classList.contains('preview-only')) {
            toggleBtn.className = 'fas fa-edit';
            this.updatePreview();
        } else {
            toggleBtn.className = 'fas fa-eye';
        }
    }
    
    // 简单的Markdown转HTML
    markdownToHtml(markdown) {
        return markdown
            // 标题
            .replace(/^### (.*$)/gim, '<h3>$1</h3>')
            .replace(/^## (.*$)/gim, '<h2>$1</h2>')
            .replace(/^# (.*$)/gim, '<h1>$1</h1>')
            // 粗体
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            // 斜体
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            // 删除线
            .replace(/~~(.*?)~~/g, '<del>$1</del>')
            // 代码块
            .replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>')
            // 行内代码
            .replace(/`(.*?)`/g, '<code>$1</code>')
            // 链接
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>')
            // 图片
            .replace(/!\[([^\]]*)\]\(([^)]+)\)/g, '<img src="$2" alt="$1" style="max-width: 100%;">')
            // 引用
            .replace(/^> (.*$)/gim, '<blockquote>$1</blockquote>')
            // 分割线
            .replace(/^---$/gim, '<hr>')
            // 列表
            .replace(/^\* (.*$)/gim, '<ul><li>$1</li></ul>')
            .replace(/^\d+\. (.*$)/gim, '<ol><li>$1</li></ol>')
            // 段落
            .replace(/\n\n/g, '</p><p>')
            .replace(/^(.*)$/gim, '<p>$1</p>')
            // 清理空段落
            .replace(/<p><\/p>/g, '')
            .replace(/<p>(<h[1-6]>.*<\/h[1-6]>)<\/p>/g, '$1')
            .replace(/<p>(<blockquote>.*<\/blockquote>)<\/p>/g, '$1')
            .replace(/<p>(<hr>)<\/p>/g, '$1')
            .replace(/<p>(<pre>.*<\/pre>)<\/p>/g, '$1');
    }
    
    getValue() {
        return this.textarea.value;
    }
    
    setValue(value) {
        this.textarea.value = value;
        if (this.preview) {
            this.updatePreview();
        }
    }
}
