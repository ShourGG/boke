# Koi Blog - Frontend Development Standards

## 🎯 Overview
This document defines the frontend development standards for HTML, CSS, and JavaScript in the Koi Blog project, ensuring consistency, performance, and maintainability.

## 🌐 HTML Standards

### Document Structure
```html
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page description (max 160 chars)">
    <meta name="keywords" content="keyword1, keyword2, keyword3">
    <title>Page Title - Koi Blog</title>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="/css/main.css" as="style">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/main.css" rel="stylesheet">
</head>
<body>
    <!-- Content -->
    
    <!-- JavaScript at bottom -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/main.js"></script>
</body>
</html>
```

### Semantic HTML Rules
```html
<!-- Use semantic elements -->
<header>
    <nav aria-label="Main navigation">
        <ul>
            <li><a href="/" aria-current="page">Home</a></li>
            <li><a href="/blog">Blog</a></li>
        </ul>
    </nav>
</header>

<main>
    <article>
        <header>
            <h1>Article Title</h1>
            <time datetime="2025-01-23">January 23, 2025</time>
        </header>
        <section>
            <p>Article content...</p>
        </section>
    </article>
</main>

<aside>
    <section aria-labelledby="recent-posts">
        <h2 id="recent-posts">Recent Posts</h2>
        <!-- Sidebar content -->
    </section>
</aside>

<footer>
    <p>&copy; 2025 Koi Blog. All rights reserved.</p>
</footer>
```

### Accessibility Requirements
- **Alt Text**: All images must have descriptive alt attributes
- **ARIA Labels**: Use ARIA labels for complex UI components
- **Keyboard Navigation**: All interactive elements must be keyboard accessible
- **Color Contrast**: Minimum 4.5:1 contrast ratio for normal text
- **Focus Indicators**: Visible focus indicators for all interactive elements

## 🎨 CSS Standards

### Architecture (BEM Methodology)
```css
/* Block */
.blog-post {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

/* Element */
.blog-post__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.blog-post__meta {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 1rem;
}

.blog-post__content {
    line-height: 1.6;
    color: #444;
}

/* Modifier */
.blog-post--featured {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.blog-post__title--large {
    font-size: 2rem;
}
```

### CSS Organization
```
public/css/
├── main.css              # Main stylesheet
├── components/           # Component-specific styles
│   ├── header.css
│   ├── navigation.css
│   ├── blog-post.css
│   ├── sidebar.css
│   └── footer.css
├── pages/               # Page-specific styles
│   ├── home.css
│   ├── blog.css
│   ├── admin.css
│   └── error.css
└── utilities/           # Utility classes
    ├── spacing.css
    ├── typography.css
    └── colors.css
```

### Performance Rules
```css
/* Use efficient selectors */
.blog-post { /* Good: class selector */ }
#main-nav { /* Good: ID selector */ }
div.content { /* Avoid: unnecessary tag qualifier */ }
.nav ul li a { /* Avoid: deep nesting */ }

/* Optimize animations */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-in.active {
    opacity: 1;
    transform: translateY(0);
}

/* Use will-change for animations */
.animated-element {
    will-change: transform, opacity;
}

.animated-element.finished {
    will-change: auto; /* Remove after animation */
}
```

### Responsive Design
```css
/* Mobile-first approach */
.container {
    width: 100%;
    padding: 0 1rem;
}

/* Tablet */
@media (min-width: 768px) {
    .container {
        max-width: 750px;
        margin: 0 auto;
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .container {
        max-width: 1200px;
        padding: 0 2rem;
    }
}

/* Large screens */
@media (min-width: 1440px) {
    .container {
        max-width: 1400px;
    }
}
```

## ⚡ JavaScript Standards

### Code Organization
```javascript
// main.js - Main application file
(function() {
    'use strict';
    
    // Application namespace
    window.KoiBlog = window.KoiBlog || {};
    
    // Utility functions
    KoiBlog.utils = {
        // DOM ready function
        ready: function(fn) {
            if (document.readyState !== 'loading') {
                fn();
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        },
        
        // AJAX helper
        ajax: function(url, options) {
            return fetch(url, {
                method: options.method || 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: options.data ? JSON.stringify(options.data) : null
            });
        },
        
        // Debounce function
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };
    
    // Component modules
    KoiBlog.components = {};
    
})();
```

### Component Pattern
```javascript
// components/search.js
KoiBlog.components.Search = (function() {
    'use strict';
    
    let searchInput;
    let searchResults;
    let searchTimeout;
    
    function init() {
        searchInput = document.getElementById('search-input');
        searchResults = document.getElementById('search-results');
        
        if (searchInput) {
            bindEvents();
        }
    }
    
    function bindEvents() {
        searchInput.addEventListener('input', KoiBlog.utils.debounce(handleSearch, 300));
        searchInput.addEventListener('keydown', handleKeydown);
    }
    
    function handleSearch(event) {
        const query = event.target.value.trim();
        
        if (query.length < 2) {
            clearResults();
            return;
        }
        
        performSearch(query);
    }
    
    function performSearch(query) {
        KoiBlog.utils.ajax('/api/search', {
            method: 'POST',
            data: { query: query }
        })
        .then(response => response.json())
        .then(data => displayResults(data))
        .catch(error => console.error('Search error:', error));
    }
    
    function displayResults(results) {
        // Implementation
    }
    
    function clearResults() {
        if (searchResults) {
            searchResults.innerHTML = '';
            searchResults.style.display = 'none';
        }
    }
    
    // Public API
    return {
        init: init
    };
})();
```

### Security Rules
```javascript
// Input sanitization
function sanitizeInput(input) {
    const div = document.createElement('div');
    div.textContent = input;
    return div.innerHTML;
}

// Safe DOM manipulation
function safeSetHTML(element, html) {
    // Use textContent for user input
    element.textContent = html;
    
    // Or use DOMPurify for rich content
    // element.innerHTML = DOMPurify.sanitize(html);
}

// CSRF token handling
function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

// Secure AJAX requests
function secureAjax(url, options) {
    const token = getCSRFToken();
    if (token) {
        options.headers = options.headers || {};
        options.headers['X-CSRF-Token'] = token;
    }
    return KoiBlog.utils.ajax(url, options);
}
```

## 📱 Progressive Enhancement

### Core Principles
1. **Content First**: Ensure content is accessible without JavaScript
2. **Feature Detection**: Use feature detection, not browser detection
3. **Graceful Degradation**: Provide fallbacks for advanced features

### Implementation Example
```javascript
// Feature detection
if ('IntersectionObserver' in window) {
    // Use Intersection Observer for lazy loading
    initLazyLoading();
} else {
    // Fallback: load all images immediately
    loadAllImages();
}

// Progressive form enhancement
function enhanceForm(form) {
    // Basic form works without JavaScript
    
    // Add JavaScript enhancements
    if (form.checkValidity) {
        addClientSideValidation(form);
    }
    
    if (window.fetch) {
        addAjaxSubmission(form);
    }
}
```

## 🚀 Performance Optimization

### Critical Rendering Path
```html
<!-- Inline critical CSS -->
<style>
    /* Critical above-the-fold styles */
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    .header { background: #fff; padding: 1rem 0; }
</style>

<!-- Preload important resources -->
<link rel="preload" href="/fonts/main.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/css/main.css" as="style">

<!-- Load non-critical CSS asynchronously -->
<link rel="preload" href="/css/non-critical.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
```

### Image Optimization
```html
<!-- Responsive images -->
<img src="/images/post-small.jpg"
     srcset="/images/post-small.jpg 400w,
             /images/post-medium.jpg 800w,
             /images/post-large.jpg 1200w"
     sizes="(max-width: 768px) 100vw,
            (max-width: 1024px) 50vw,
            33vw"
     alt="Descriptive alt text"
     loading="lazy">

<!-- WebP with fallback -->
<picture>
    <source srcset="/images/post.webp" type="image/webp">
    <img src="/images/post.jpg" alt="Descriptive alt text">
</picture>
```

## ✅ Quality Checklist

### Before Deployment
- [ ] HTML validates (W3C Validator)
- [ ] CSS validates (W3C CSS Validator)
- [ ] JavaScript passes ESLint
- [ ] Accessibility tested (WAVE, axe)
- [ ] Performance tested (Lighthouse)
- [ ] Cross-browser tested (Chrome, Firefox, Safari, Edge)
- [ ] Mobile responsive tested
- [ ] Images optimized and compressed
- [ ] Critical CSS inlined
- [ ] JavaScript minified for production

### Performance Targets
- **First Contentful Paint**: < 1.5s
- **Largest Contentful Paint**: < 2.5s
- **Cumulative Layout Shift**: < 0.1
- **First Input Delay**: < 100ms
- **Lighthouse Score**: > 90

### Browser Support
- **Modern Browsers**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Graceful Degradation**: IE 11 (basic functionality only)
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+
