# Koi Blog - Architecture Repository (.arc)

## 🎯 Purpose
This `.arc/` directory serves as the **Single Source of Truth** for all architectural decisions, domain models, coding standards, and project governance for the Koi Blog project.

## 📁 Directory Structure
```
.arc/
├── README.md                    # This file - overview and navigation
├── domain_model/               # Domain-driven design artifacts
│   ├── README.md               # Domain model overview
│   ├── bounded_contexts.md     # Context boundaries and relationships
│   └── ubiquitous_language.md  # Common terminology
├── architecture/               # System architecture documentation
│   ├── principles.md           # Architectural principles and guidelines
│   ├── decisions/              # Architecture Decision Records (ADRs)
│   │   └── 0001-initial-tech-stack.md
│   └── diagrams/               # System diagrams and visual models
├── coding_standards/           # Development standards and conventions
│   ├── php_standards.md        # PHP coding standards
│   ├── frontend_standards.md   # HTML/CSS/JS standards
│   └── database_standards.md   # Database design standards
├── security/                   # Security policies and threat models
│   ├── threat_model.md         # Security threat analysis
│   └── security_policies.md    # Security implementation guidelines
└── deployment/                 # Deployment and operations
    ├── baota_deployment.md     # Baota panel specific deployment
    └── environment_config.md   # Environment configuration guide
```

## 🚀 Quick Navigation
- **New Developer?** Start with `domain_model/README.md`
- **Architecture Questions?** Check `architecture/principles.md`
- **Coding Standards?** See `coding_standards/php_standards.md`
- **Security Concerns?** Review `security/threat_model.md`
- **Deployment Issues?** Consult `deployment/baota_deployment.md`

## 📋 Maintenance
This directory should be updated whenever:
- New architectural decisions are made
- Domain model evolves
- Security requirements change
- Deployment procedures are modified
- Coding standards are updated

---
**Last Updated**: 2025-01-23  
**Maintained By**: Development Team
