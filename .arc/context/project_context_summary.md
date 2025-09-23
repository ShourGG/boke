# Koi Blog - Project Context Summary

## 📅 Context Snapshot
**Generated**: 2025-01-23 14:32:45 UTC  
**Project Phase**: Foundation & Architecture Setup Complete  
**Git Commit**: 6bc6a75 (Architecture repository initialization)  
**Status**: Ready for core development

## 🎯 Project Overview

### Core Identity
**Koi Blog** is a dual-purpose content management system combining:
1. **Personal Blog Platform** - Article publishing with categories and tags
2. **Website Directory** - Curated collection of useful websites

### Technical Foundation
- **Language**: PHP 7.4+ with custom lightweight MVC framework
- **Database**: MySQL 5.7+ with PDO abstraction layer
- **Frontend**: Bootstrap 5 + Vanilla JavaScript
- **Deployment**: Baota Panel (宝塔) with direct file upload
- **Architecture**: Domain-Driven Design with clear bounded contexts

## 🏗️ Current Project Structure
```
koi/
├── .arc/                           # Architecture repository (Single Source of Truth)
│   ├── domain_model/              # Business domain definitions
│   ├── architecture/              # System principles and ADRs
│   ├── coding_standards/          # Development standards
│   ├── security/                  # Threat model and policies
│   ├── deployment/                # Baota deployment procedures
│   └── context/                   # This context summary
├── app/                           # MVC application core
│   ├── controllers/               # Request handlers
│   ├── core/                      # Framework components
│   ├── models/                    # Business logic and data access
│   └── views/                     # Presentation templates
├── config/                        # Configuration files
├── public/                        # Web-accessible assets
├── scripts/                       # Development and deployment tools
├── uploads/                       # User-uploaded files
├── issues/                        # Development planning documents
├── deploy.bat                     # One-click deployment script
└── index.php                      # Application entry point
```

## 🎯 Domain Model Summary

### Blog Context
- **Post**: Articles with content, metadata, SEO optimization
- **Category**: Hierarchical content organization
- **Tag**: Cross-cutting content labels
- **Author**: Content creators (admin users)

### Website Directory Context
- **Website**: External site entries with metadata and ratings
- **WebsiteCategory**: Directory organization structure
- **WebsiteTag**: Website classification system

### Administration Context
- **Admin**: System administrators with full access
- **Session**: Secure authentication management
- **SystemSettings**: Global configuration parameters

## 🏛️ Architectural Decisions

### ADR-0001: Technology Stack Selection
**Decision**: Custom PHP MVC + MySQL + Bootstrap 5  
**Rationale**: 
- Baota panel compatibility (no build process)
- Minimal dependencies and server requirements
- Fast development for personal blog scope
- Easy maintenance by single developer

### Core Principles Applied
1. **Simplicity First (KISS)**: Lightweight MVC, direct SQL, minimal dependencies
2. **Domain-Driven Design**: Clear bounded contexts, ubiquitous language
3. **Security by Design**: Input validation, prepared statements, XSS protection
4. **Performance Optimization**: Query optimization, caching, asset optimization
5. **Deployment Simplicity**: File-based deployment, environment configuration

## 🛡️ Security Implementation

### Threat Mitigation Status
- **SQL Injection**: ✅ Prevented via PDO prepared statements
- **XSS Attacks**: ✅ Output escaping with htmlspecialchars()
- **CSRF**: ✅ Token-based protection for state changes
- **File Upload**: ✅ Type validation and secure storage
- **Session Security**: ✅ HTTPOnly, Secure flags, regeneration

### Security Monitoring
- Failed login attempt logging
- Suspicious activity detection
- Security header implementation
- Regular security audit procedures

## 🔧 Development Workflow

### Established Scripts
1. **`deploy.bat`**: One-click Git commit and push to GitHub
2. **`scripts/validate-project.bat`**: Project structure validation
3. **`scripts/setup-development.bat`**: Development environment setup

### Git Workflow
- **Repository**: https://github.com/ShourGG/boke
- **Branch**: master (main development)
- **Commit Strategy**: Descriptive commits with emoji prefixes
- **Deployment**: Push to GitHub → Pull in Baota panel

## 📊 Current Implementation Status

### Completed Components
- [x] **Project Foundation**: Directory structure, Git repository
- [x] **Architecture Documentation**: Complete .arc/ repository
- [x] **Core Database Class**: Singleton PDO wrapper with security
- [x] **MVC Structure**: Controllers, Models, Views directories
- [x] **Configuration System**: Environment-based config management
- [x] **Security Framework**: Input validation, output escaping
- [x] **Deployment Scripts**: Automated deployment workflow

### Next Development Priorities
1. **Database Schema**: Create tables for posts, categories, websites
2. **Base Models**: Implement Post, Category, Website models
3. **Frontend Templates**: Create responsive Bootstrap layouts
4. **Admin Authentication**: Secure login and session management
5. **Content Management**: CRUD operations for posts and websites

## 🎯 Business Rules & Constraints

### Content Management
- All published content publicly accessible
- Only authenticated admins can create/edit content
- SEO optimization required for all content
- Performance target: <2 seconds page load

### Security Requirements
- 100% input validation coverage
- Zero tolerance for SQL injection
- All outputs properly escaped
- Secure session management with timeout

### Deployment Constraints
- Must work with Baota panel shared hosting
- No Node.js build processes allowed
- File-based deployment only
- Environment-specific configuration

## 🔄 Maintenance Procedures

### Regular Tasks
- **Daily**: Monitor error logs and failed logins
- **Weekly**: Check security logs and update content
- **Monthly**: Update PHP/MySQL via Baota panel
- **Quarterly**: Full security audit and performance review

### Backup Strategy
- **Database**: Daily automated backups
- **Files**: Weekly full backup
- **Git**: Continuous version control backup

## 📞 Key Resources

### Documentation Locations
- **Architecture**: `.arc/` directory (single source of truth)
- **Development Issues**: `issues/koi-blog-development.md`
- **Deployment Guide**: `.arc/deployment/baota_deployment.md`
- **Security Policies**: `.arc/security/threat_model.md`

### External Resources
- **GitHub Repository**: https://github.com/ShourGG/boke
- **Baota Panel**: Server management interface
- **PHP Documentation**: https://www.php.net/docs.php
- **Bootstrap 5**: https://getbootstrap.com/docs/5.0/

## 🎊 Project Health Indicators

### Quality Metrics
- **Architecture Documentation**: ✅ Complete and up-to-date
- **Security Implementation**: ✅ Threat model defined and implemented
- **Code Standards**: ✅ PHP standards documented and enforced
- **Deployment Process**: ✅ Automated and tested
- **Version Control**: ✅ Proper Git workflow established

### Success Criteria
- [x] Professional project structure established
- [x] Security-first development approach implemented
- [x] Automated deployment workflow functional
- [x] Comprehensive documentation created
- [ ] Core functionality implemented (next phase)
- [ ] Production deployment completed
- [ ] User acceptance testing passed

---

**Last Updated**: 2025-01-23 14:32:45 UTC  
**Next Review**: When core development phase begins  
**Maintained By**: Development Team (老王)
