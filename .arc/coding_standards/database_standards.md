# Koi Blog - Database Design Standards

## 🎯 Overview
This document defines the database design standards, naming conventions, and best practices for the Koi Blog project using MySQL.

## 🗄️ Database Design Principles

### 1. Normalization Strategy
- **Target**: 3rd Normal Form (3NF) for most tables
- **Exceptions**: Denormalization allowed for performance-critical read operations
- **Rule**: Eliminate redundancy while maintaining query performance

### 2. Data Integrity
- **Primary Keys**: Every table must have a primary key
- **Foreign Keys**: Use foreign key constraints for referential integrity
- **Constraints**: Use CHECK constraints for business rule enforcement
- **Indexes**: Strategic indexing for query optimization

## 📝 Naming Conventions

### Table Names
```sql
-- Use lowercase with underscores (snake_case)
posts                    -- ✅ Good
categories              -- ✅ Good
post_tags               -- ✅ Good (junction table)
website_categories      -- ✅ Good

-- Avoid
Posts                   -- ❌ Mixed case
post-tags              -- ❌ Hyphens
postTags               -- ❌ camelCase
tbl_posts              -- ❌ Prefixes
```

### Column Names
```sql
-- Use descriptive, lowercase names with underscores
id                     -- ✅ Primary key
title                  -- ✅ Simple name
created_at             -- ✅ Timestamp
updated_at             -- ✅ Timestamp
category_id            -- ✅ Foreign key
is_published           -- ✅ Boolean
view_count             -- ✅ Counter
seo_title              -- ✅ Descriptive

-- Avoid
Title                  -- ❌ Mixed case
createdAt              -- ❌ camelCase
cat_id                 -- ❌ Abbreviations
published              -- ❌ Ambiguous boolean
views                  -- ❌ Could be confusing
```

### Index Names
```sql
-- Primary key (automatic)
PRIMARY KEY (id)

-- Foreign key indexes
KEY idx_posts_category_id (category_id)
KEY idx_posts_author_id (author_id)

-- Composite indexes
KEY idx_posts_status_created (status, created_at)
KEY idx_posts_category_status (category_id, status)

-- Unique indexes
UNIQUE KEY uk_categories_slug (slug)
UNIQUE KEY uk_admins_email (email)

-- Full-text indexes
FULLTEXT KEY ft_posts_content (title, content)
```

## 🏗️ Table Structure Standards

### Standard Columns
Every table should include these standard columns:
```sql
CREATE TABLE example_table (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Business columns here
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Optional audit columns
    created_by INT UNSIGNED NULL,
    updated_by INT UNSIGNED NULL,
    
    -- Soft delete (if needed)
    deleted_at TIMESTAMP NULL,
    
    -- Indexes
    KEY idx_created_at (created_at),
    KEY idx_updated_at (updated_at)
);
```

### Data Types Standards
```sql
-- Primary Keys
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

-- Foreign Keys
category_id INT UNSIGNED NOT NULL
author_id INT UNSIGNED NULL

-- Strings
title VARCHAR(255) NOT NULL                    -- Short text
slug VARCHAR(255) NOT NULL                     -- URL slugs
content TEXT                                   -- Medium text
description MEDIUMTEXT                         -- Long text
meta_data JSON                                 -- JSON data

-- Numbers
view_count INT UNSIGNED DEFAULT 0              -- Counters
rating DECIMAL(3,2) DEFAULT 0.00              -- Ratings (0.00-9.99)
price DECIMAL(10,2)                           -- Money values

-- Booleans
is_published BOOLEAN DEFAULT FALSE             -- Status flags
is_featured BOOLEAN DEFAULT FALSE             -- Feature flags

-- Dates and Times
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
published_at TIMESTAMP NULL                   -- Optional dates
expires_at TIMESTAMP NULL                     -- Expiration

-- Enums (use sparingly)
status ENUM('draft', 'published', 'archived') DEFAULT 'draft'
```

## 📊 Schema Design Patterns

### Blog Context Tables
```sql
-- Posts table
CREATE TABLE posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    excerpt TEXT,
    content MEDIUMTEXT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    view_count INT UNSIGNED DEFAULT 0,
    category_id INT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_posts_slug (slug),
    KEY idx_posts_status_published (status, published_at),
    KEY idx_posts_category_status (category_id, status),
    KEY idx_posts_author (author_id),
    KEY idx_posts_featured (featured),
    FULLTEXT KEY ft_posts_search (title, excerpt, content),
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (author_id) REFERENCES admins(id) ON DELETE RESTRICT
);

-- Categories table (hierarchical)
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    description TEXT,
    parent_id INT UNSIGNED NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_categories_slug (slug),
    KEY idx_categories_parent (parent_id),
    KEY idx_categories_sort (sort_order),
    
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Tags table
CREATE TABLE tags (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL,
    description TEXT,
    usage_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_tags_slug (slug),
    KEY idx_tags_usage (usage_count)
);

-- Many-to-many relationship
CREATE TABLE post_tags (
    post_id INT UNSIGNED NOT NULL,
    tag_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (post_id, tag_id),
    KEY idx_post_tags_tag (tag_id),
    
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
```

### Website Directory Context Tables
```sql
-- Websites table
CREATE TABLE websites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(500) NOT NULL,
    description TEXT,
    screenshot_url VARCHAR(500),
    category_id INT UNSIGNED NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    featured BOOLEAN DEFAULT FALSE,
    rating DECIMAL(3,2) DEFAULT 0.00,
    view_count INT UNSIGNED DEFAULT 0,
    submitted_by INT UNSIGNED NULL,
    reviewed_by INT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    KEY idx_websites_category_status (category_id, status),
    KEY idx_websites_status (status),
    KEY idx_websites_featured (featured),
    KEY idx_websites_rating (rating),
    FULLTEXT KEY ft_websites_search (name, description),
    
    FOREIGN KEY (category_id) REFERENCES website_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (submitted_by) REFERENCES admins(id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES admins(id) ON DELETE SET NULL
);
```

### Administration Context Tables
```sql
-- Admins table
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin', 'editor', 'moderator') DEFAULT 'editor',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45),
    failed_login_attempts INT UNSIGNED DEFAULT 0,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_admins_username (username),
    UNIQUE KEY uk_admins_email (email),
    KEY idx_admins_status (status),
    KEY idx_admins_role (role)
);

-- Sessions table
CREATE TABLE sessions (
    id VARCHAR(128) PRIMARY KEY,
    admin_id INT UNSIGNED NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload MEDIUMTEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    KEY idx_sessions_admin (admin_id),
    KEY idx_sessions_expires (expires_at),
    KEY idx_sessions_last_activity (last_activity),
    
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);
```

## 🔍 Indexing Strategy

### Primary Indexes (Automatic)
- Every table has a primary key with automatic index
- Use INT UNSIGNED AUTO_INCREMENT for most cases

### Foreign Key Indexes
```sql
-- Always index foreign keys
KEY idx_posts_category_id (category_id)
KEY idx_posts_author_id (author_id)
KEY idx_websites_category_id (category_id)
```

### Composite Indexes (Query Optimization)
```sql
-- For common query patterns
KEY idx_posts_status_created (status, created_at)      -- List published posts
KEY idx_posts_category_status (category_id, status)    -- Posts by category
KEY idx_websites_category_status (category_id, status) -- Websites by category
```

### Full-Text Indexes (Search)
```sql
-- For search functionality
FULLTEXT KEY ft_posts_search (title, excerpt, content)
FULLTEXT KEY ft_websites_search (name, description)
```

### Performance Indexes
```sql
-- For sorting and filtering
KEY idx_posts_view_count (view_count)          -- Popular posts
KEY idx_posts_published_at (published_at)      -- Recent posts
KEY idx_websites_rating (rating)               -- Top rated websites
```

## 🔄 Migration Strategy

### Migration File Naming
```
migrations/
├── 001_create_initial_schema.sql
├── 002_add_post_tags_table.sql
├── 003_add_website_rating_column.sql
├── 004_create_sessions_table.sql
└── 005_add_fulltext_indexes.sql
```

### Migration Template
```sql
-- Migration: 001_create_initial_schema.sql
-- Description: Create initial database schema for blog and website directory
-- Date: 2025-01-23
-- Author: Development Team

-- Start transaction
START TRANSACTION;

-- Create tables
CREATE TABLE categories (
    -- Table definition
);

CREATE TABLE posts (
    -- Table definition
);

-- Add indexes
CREATE INDEX idx_posts_status_created ON posts(status, created_at);

-- Insert initial data
INSERT INTO categories (name, slug, description) VALUES
('Technology', 'technology', 'Technology related posts'),
('Lifestyle', 'lifestyle', 'Lifestyle and personal posts');

-- Commit transaction
COMMIT;
```

### Rollback Strategy
```sql
-- Rollback: 001_create_initial_schema.sql
-- Description: Rollback initial schema creation
-- Date: 2025-01-23

START TRANSACTION;

-- Drop tables in reverse order
DROP TABLE IF EXISTS post_tags;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;

COMMIT;
```

## 📊 Performance Optimization

### Query Optimization Rules
```sql
-- Use EXPLAIN to analyze queries
EXPLAIN SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 10;

-- Prefer covering indexes
KEY idx_posts_list (status, created_at, id, title)  -- Covers common list query

-- Avoid SELECT *
SELECT id, title, excerpt, created_at FROM posts WHERE status = 'published';

-- Use LIMIT for pagination
SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 10 OFFSET 20;
```

### Connection and Configuration
```sql
-- Recommended MySQL settings for Koi Blog
[mysqld]
innodb_buffer_pool_size = 256M      -- Adjust based on available RAM
innodb_log_file_size = 64M
innodb_flush_log_at_trx_commit = 2
query_cache_size = 32M
query_cache_type = 1
max_connections = 100
```

## ✅ Quality Checklist

### Before Deployment
- [ ] All tables have primary keys
- [ ] Foreign key constraints defined
- [ ] Appropriate indexes created
- [ ] Column data types optimized
- [ ] Naming conventions followed
- [ ] Migration scripts tested
- [ ] Rollback scripts prepared
- [ ] Performance tested with sample data
- [ ] Backup strategy implemented

### Maintenance Tasks
- **Daily**: Monitor slow query log
- **Weekly**: Analyze table usage and optimize indexes
- **Monthly**: Update table statistics and optimize tables
- **Quarterly**: Review and archive old data
