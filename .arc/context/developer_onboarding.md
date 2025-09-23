# Koi Blog - Developer Onboarding Guide

## 🎯 Quick Start (5 Minutes)

### 1. Clone and Setup
```bash
# Clone repository
git clone https://github.com/ShourGG/boke.git
cd boke

# Setup development environment
scripts\setup-development.bat

# Validate project structure
scripts\validate-project.bat
```

### 2. Configure Database
```bash
# Copy configuration template
copy config\config.sample.php config\config.php

# Edit config\config.php with your database credentials:
# DB_HOST, DB_NAME, DB_USER, DB_PASS
```

### 3. Start Development
```bash
# Start local server
start-dev.bat
# OR manually: php -S localhost:8000 -t .

# Visit: http://localhost:8000
```

## 📚 Project Understanding (10 Minutes)

### Core Concept
**Koi Blog** = Personal Blog + Website Directory  
- **Blog**: Articles with categories and tags
- **Directory**: Curated website collection
- **Admin**: Content management interface

### Architecture Overview
```
Frontend (Bootstrap 5) → Controllers → Models → Database (MySQL)
                      ↓
                   Views (PHP Templates)
```

### Key Directories
- **`.arc/`**: 📋 Project documentation and standards (READ FIRST!)
- **`app/`**: 💻 MVC application code
- **`config/`**: ⚙️ Configuration files
- **`public/`**: 🌐 Web assets (CSS, JS, images)
- **`scripts/`**: 🔧 Development and deployment tools

## 🏗️ Architecture Deep Dive (15 Minutes)

### Domain Model (Business Logic)
Read: `.arc/domain_model/README.md`

**Blog Context**:
- `Post` → Articles with SEO optimization
- `Category` → Hierarchical organization  
- `Tag` → Cross-cutting labels

**Website Directory Context**:
- `Website` → External site entries
- `WebsiteCategory` → Directory structure
- `WebsiteTag` → Classification system

**Admin Context**:
- `Admin` → System users
- `Session` → Authentication
- `Settings` → Configuration

### Technical Principles
Read: `.arc/architecture/principles.md`

1. **KISS**: Simple, lightweight implementation
2. **DDD**: Domain-driven design patterns
3. **Security First**: Input validation, output escaping
4. **Performance**: Optimized queries and caching
5. **Deployment**: Baota panel compatibility

## 💻 Development Standards (20 Minutes)

### PHP Coding Standards
Read: `.arc/coding_standards/php_standards.md`

**Key Rules**:
```php
// Naming: PascalCase classes, camelCase methods/variables
class BlogPost {
    public function getUserById($userId) {
        // Always use prepared statements
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->fetch($sql, [$userId]);
    }
}

// Security: Validate input, escape output
$clean = htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');

// Documentation: PHPDoc for all public methods
/**
 * Retrieves blog posts with pagination
 * @param int $page Page number
 * @param int $limit Posts per page
 * @return array Post objects
 */
```

### Security Requirements
Read: `.arc/security/threat_model.md`

**Critical Rules**:
- ✅ **SQL Injection**: Use prepared statements ALWAYS
- ✅ **XSS**: Escape all output with `htmlspecialchars()`
- ✅ **CSRF**: Token validation for state changes
- ✅ **File Upload**: Validate type, size, and storage location
- ✅ **Authentication**: Secure session management

## 🔧 Development Workflow (10 Minutes)

### Daily Development Process
```bash
# 1. Pull latest changes
git pull origin master

# 2. Make your changes
# Edit files in app/, public/, etc.

# 3. Test locally
php -S localhost:8000 -t .

# 4. Validate project
scripts\validate-project.bat

# 5. Deploy (commit + push)
deploy.bat
```

### Code Review Checklist
Before committing, ensure:
- [ ] All inputs validated and sanitized
- [ ] Database queries use prepared statements  
- [ ] Output properly escaped
- [ ] PHPDoc comments added
- [ ] No hardcoded values or magic numbers
- [ ] Error handling implemented
- [ ] Security considerations addressed

## 🗄️ Database Development (15 Minutes)

### Current Schema Status
**Status**: Architecture planned, implementation pending  
**Location**: See `.arc/context/technical_snapshot.md`

### Database Access Pattern
```php
// Get database instance (singleton)
$db = Database::getInstance();

// Fetch multiple records
$posts = $db->fetchAll(
    "SELECT * FROM posts WHERE status = ? ORDER BY created_at DESC LIMIT ?",
    ['published', 10]
);

// Fetch single record
$post = $db->fetch(
    "SELECT * FROM posts WHERE id = ? AND status = ?",
    [$postId, 'published']
);

// Execute insert/update/delete
$affected = $db->execute(
    "UPDATE posts SET views = views + 1 WHERE id = ?",
    [$postId]
);
```

### Model Implementation Pattern
```php
class Post extends BaseModel {
    protected $table = 'posts';
    
    public function findPublished($limit = 10) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE status = ? ORDER BY created_at DESC LIMIT ?",
            ['published', $limit]
        );
    }
    
    public function validate($data) {
        if (empty($data['title'])) {
            throw new ValidationException('Title is required');
        }
        // More validation...
    }
}
```

## 🎨 Frontend Development (10 Minutes)

### Technology Stack
- **CSS**: Bootstrap 5 (CDN) + custom styles
- **JavaScript**: Vanilla JS (no build process)
- **Templates**: PHP with HTML
- **Icons**: Bootstrap Icons

### Template Structure
```php
// layouts/base.php - Master template
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'components/header.php'; ?>
    <main><?= $content ?></main>
    <?php include 'components/footer.php'; ?>
</body>
</html>
```

### Responsive Design Rules
- **Mobile First**: Design for mobile, enhance for desktop
- **Bootstrap Grid**: Use container → row → col structure
- **Performance**: Optimize images, minimize HTTP requests
- **Accessibility**: Proper semantic HTML and ARIA labels

## 🚀 Deployment Process (5 Minutes)

### Development to Production
```bash
# 1. Local development and testing
php -S localhost:8000 -t .

# 2. Commit and push changes
deploy.bat

# 3. Pull changes in Baota panel
# Login to Baota → File Manager → Git Pull

# 4. Verify production site
# Check https://yourdomain.com
```

### Baota Panel Configuration
Read: `.arc/deployment/baota_deployment.md`

**Key Steps**:
1. Create website in Baota panel
2. Setup database and user
3. Configure SSL certificate
4. Set file permissions (644/755)
5. Import database schema
6. Test functionality

## 🐛 Troubleshooting (5 Minutes)

### Common Issues

**Database Connection Failed**:
```bash
# Check config/config.php credentials
# Verify MySQL service is running
# Test connection manually
```

**500 Internal Server Error**:
```bash
# Check PHP error logs
# Verify file permissions (644 for files, 755 for directories)
# Check .htaccess syntax (Apache)
```

**Git Issues**:
```bash
# Reset to last working commit
git reset --hard HEAD~1

# Force push (careful!)
git push -f origin master
```

### Debug Mode
```php
// In config/config.php
define('DEBUG', true);

// Shows detailed error messages
// NEVER enable in production!
```

## 📞 Getting Help

### Documentation Hierarchy
1. **`.arc/README.md`** - Start here for navigation
2. **`.arc/domain_model/`** - Business logic questions
3. **`.arc/architecture/`** - Technical architecture
4. **`.arc/coding_standards/`** - Code style questions
5. **`.arc/security/`** - Security implementation
6. **`.arc/deployment/`** - Deployment issues

### Development Resources
- **PHP Manual**: https://www.php.net/manual/
- **Bootstrap Docs**: https://getbootstrap.com/docs/5.1/
- **MySQL Reference**: https://dev.mysql.com/doc/
- **Git Tutorial**: https://git-scm.com/docs/gittutorial

### Project Contacts
- **Technical Lead**: 老王 (Lao Wang)
- **Repository**: https://github.com/ShourGG/boke
- **Issues**: Use GitHub Issues for bug reports

---

## 🎯 Next Steps After Onboarding

1. **Read Architecture Docs**: Spend 30 minutes in `.arc/` directory
2. **Setup Local Environment**: Get development server running
3. **Understand Domain Model**: Review business logic and entities
4. **Practice Security**: Implement input validation and output escaping
5. **Start Small**: Pick a simple feature to implement first

**Welcome to the Koi Blog project! 🎉**
