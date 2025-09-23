# Koi Blog - Baota Panel Deployment Guide

## 🎯 Overview
This guide provides step-by-step instructions for deploying Koi Blog to a Baota (宝塔) panel environment, including initial setup, configuration, and maintenance procedures.

## 📋 Prerequisites

### Server Requirements
- **OS**: Linux (CentOS 7+, Ubuntu 18+, Debian 9+)
- **PHP**: 7.4+ with extensions: PDO, MySQL, GD, mbstring, curl
- **MySQL**: 5.7+ or MariaDB 10.3+
- **Web Server**: Nginx or Apache
- **Disk Space**: Minimum 1GB free space
- **Memory**: Minimum 512MB RAM

### Baota Panel Setup
- Baota Panel 7.0+ installed and configured
- Website created in Baota panel
- Database created with user credentials
- SSL certificate configured (recommended)

## 🚀 Deployment Process

### Step 1: Prepare Project Files
```bash
# Create deployment package (exclude development files)
zip -r koi-blog-deploy.zip . \
  -x "*.git*" \
  -x "node_modules/*" \
  -x "*.log" \
  -x "config/config.php" \
  -x "uploads/*"
```

### Step 2: Upload to Baota Panel
1. **Access File Manager**: Login to Baota panel → File Manager
2. **Navigate to Website**: Go to `/www/wwwroot/yourdomain.com/`
3. **Upload Files**: Upload `koi-blog-deploy.zip`
4. **Extract Files**: Right-click → Extract to current directory
5. **Set Permissions**: 
   - Files: 644 (`chmod 644 *.php`)
   - Directories: 755 (`chmod 755 app/ config/ public/`)
   - Uploads: 777 (`chmod 777 uploads/`)

### Step 3: Database Setup
1. **Create Database**: Baota Panel → Database → Add Database
   - Database Name: `koi_blog`
   - Username: `koi_blog`
   - Password: Generate strong password
2. **Import Schema**: Upload and execute `database.sql`
3. **Verify Tables**: Check that all tables are created correctly

### Step 4: Configuration
1. **Copy Config Template**:
   ```bash
   cp config/config.sample.php config/config.php
   ```

2. **Edit Configuration**:
   ```php
   // Database Configuration
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'koi_blog');
   define('DB_USER', 'koi_blog');
   define('DB_PASS', 'your_generated_password');
   
   // Site Configuration
   define('SITE_URL', 'https://yourdomain.com');
   define('SITE_NAME', 'Your Blog Name');
   
   // Security (generate new keys)
   define('SECRET_KEY', 'generate_32_char_random_string');
   define('PASSWORD_SALT', 'generate_16_char_random_string');
   ```

### Step 5: Web Server Configuration

#### Nginx Configuration (Recommended)
```nginx
server {
    listen 80;
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    root /www/wwwroot/yourdomain.com;
    index index.php index.html;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    
    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/tmp/php-cgi-74.sock;
        fastcgi_index index.php;
        include fastcgi.conf;
    }
    
    # URL rewriting for clean URLs
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Deny access to sensitive files
    location ~ /\.(ht|git|env) {
        deny all;
    }
    
    location ~ /config/ {
        deny all;
    }
    
    # Static file caching
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

#### Apache Configuration (.htaccess)
```apache
# URL Rewriting
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security
<Files "config.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files ".env">
    Order Allow,Deny
    Deny from all
</Files>

# Cache static files
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
</IfModule>
```

## 🔧 Post-Deployment Configuration

### Step 6: Initial Setup
1. **Access Installation**: Visit `https://yourdomain.com/install.php`
2. **System Check**: Verify all requirements are met
3. **Database Test**: Confirm database connection
4. **Admin Account**: Create initial admin user
5. **Remove Installer**: Delete `install.php` after completion

### Step 7: Security Hardening
```bash
# Set proper file permissions
find /www/wwwroot/yourdomain.com -type f -exec chmod 644 {} \;
find /www/wwwroot/yourdomain.com -type d -exec chmod 755 {} \;
chmod 777 /www/wwwroot/yourdomain.com/uploads/

# Secure config file
chmod 600 /www/wwwroot/yourdomain.com/config/config.php

# Remove unnecessary files
rm -f /www/wwwroot/yourdomain.com/README.md
rm -f /www/wwwroot/yourdomain.com/deploy.bat
rm -rf /www/wwwroot/yourdomain.com/.git/
```

## 📊 Performance Optimization

### PHP Configuration (php.ini)
```ini
# Memory and execution
memory_limit = 256M
max_execution_time = 30
max_input_time = 60

# File uploads
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20

# Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1

# OPcache (if available)
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 4000
```

### Database Optimization
```sql
-- Add indexes for better performance
ALTER TABLE posts ADD INDEX idx_status_created (status, created_at);
ALTER TABLE posts ADD INDEX idx_category_status (category_id, status);
ALTER TABLE websites ADD INDEX idx_category_status (category_id, status);

-- Optimize tables
OPTIMIZE TABLE posts, categories, tags, websites, website_categories;
```

## 🔄 Maintenance Procedures

### Regular Backups
```bash
# Database backup (daily)
mysqldump -u koi_blog -p koi_blog > backup_$(date +%Y%m%d).sql

# File backup (weekly)
tar -czf files_backup_$(date +%Y%m%d).tar.gz /www/wwwroot/yourdomain.com/
```

### Log Monitoring
```bash
# Check error logs
tail -f /www/wwwroot/yourdomain.com/logs/error.log

# Check access logs
tail -f /www/server/nginx/logs/yourdomain.com.log
```

### Security Updates
1. **Monthly**: Update PHP and MySQL via Baota panel
2. **Weekly**: Check application logs for security issues
3. **Daily**: Monitor failed login attempts

## 🚨 Troubleshooting

### Common Issues

#### 500 Internal Server Error
- Check PHP error logs: `/www/server/php/74/var/log/php-fpm.log`
- Verify file permissions: 644 for files, 755 for directories
- Check `.htaccess` syntax (Apache) or Nginx configuration

#### Database Connection Failed
- Verify database credentials in `config/config.php`
- Check MySQL service status in Baota panel
- Ensure database user has proper privileges

#### File Upload Issues
- Check `uploads/` directory permissions (777)
- Verify PHP upload settings (`upload_max_filesize`, `post_max_size`)
- Ensure disk space is available

#### Performance Issues
- Enable OPcache in PHP settings
- Optimize database queries and add indexes
- Configure static file caching
- Consider CDN for static assets

### Emergency Recovery
```bash
# Restore from backup
mysql -u koi_blog -p koi_blog < backup_20250123.sql

# Reset file permissions
chmod -R 644 /www/wwwroot/yourdomain.com/
find /www/wwwroot/yourdomain.com -type d -exec chmod 755 {} \;
chmod 777 /www/wwwroot/yourdomain.com/uploads/
```

## 📞 Support Resources
- **Baota Panel Documentation**: https://www.bt.cn/bbs/
- **PHP Documentation**: https://www.php.net/docs.php
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **Project Issues**: Check `issues/` directory for known problems
