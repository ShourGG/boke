# Koi Blog - Domain Model

## 🎯 Domain Overview
Koi Blog is a **dual-purpose content management system** that combines:
1. **Personal Blog Platform** - Article publishing and content management
2. **Website Directory** - Curated collection of useful websites

## 🏗️ Core Bounded Contexts

### 1. Blog Context
**Purpose**: Content creation, publishing, and organization
**Core Entities**:
- `Post` - Blog articles with content, metadata, and categorization
- `Category` - Hierarchical content organization
- `Tag` - Cross-cutting content labels
- `Author` - Content creators (admin users)

**Key Operations**:
- Create, edit, publish, and archive posts
- Organize content by categories and tags
- Search and filter content

### 2. Website Directory Context
**Purpose**: Curated website collection and discovery
**Core Entities**:
- `Website` - External website entries with metadata
- `WebsiteCategory` - Directory organization structure
- `WebsiteTag` - Website classification labels

**Key Operations**:
- Add, edit, and categorize websites
- Browse and search website directory
- Manage website metadata and descriptions

### 3. Administration Context
**Purpose**: System management and user authentication
**Core Entities**:
- `Admin` - System administrators
- `Session` - Authentication sessions
- `SystemSettings` - Global configuration

**Key Operations**:
- User authentication and authorization
- System configuration management
- Content moderation and management

## 🔗 Context Relationships
```
Blog Context ←→ Administration Context (Content Management)
Website Directory ←→ Administration Context (Directory Management)
Blog Context ⟷ Website Directory (Shared UI/Navigation)
```

## 📝 Ubiquitous Language
See `ubiquitous_language.md` for detailed terminology definitions.

## 🎯 Business Rules
1. **Content Visibility**: All published content is publicly accessible
2. **Admin Access**: Only authenticated admins can create/edit content
3. **SEO Optimization**: All content must be SEO-friendly
4. **Performance**: System must handle moderate traffic efficiently
5. **Security**: All user inputs must be sanitized and validated
