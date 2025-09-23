# ADR-0001: Initial Technology Stack Selection

## Status
**Accepted** - 2025-01-23

## Context
We need to select a technology stack for the Koi Blog project that meets the following requirements:
- Easy deployment to Baota panel (shared hosting environment)
- Minimal server requirements and dependencies
- Fast development cycle for a personal blog with website directory
- Good performance for moderate traffic
- Maintainable by a single developer

## Decision
We will use the following technology stack:

### Backend
- **PHP 7.4+**: Native language support in Baota, no compilation needed
- **Custom MVC Framework**: Lightweight, tailored to project needs
- **PDO with MySQL**: Standard database access, prepared statements for security
- **File-based Sessions**: Simple session management without Redis/Memcached

### Frontend
- **Bootstrap 5**: Responsive design framework, CDN delivery
- **Vanilla JavaScript**: No build process, direct browser execution
- **CSS3**: Modern styling with flexbox and grid
- **Progressive Enhancement**: Works without JavaScript

### Database
- **MySQL 5.7+**: Standard in Baota panel installations
- **InnoDB Engine**: ACID compliance and foreign key support
- **UTF8MB4 Charset**: Full Unicode support including emojis

### Deployment
- **Direct File Upload**: No build process or package managers
- **Environment Configuration**: Single config file for different environments
- **SQL Migration Scripts**: Version-controlled database changes

## Alternatives Considered

### Laravel Framework
- **Pros**: Rich ecosystem, built-in features, ORM
- **Cons**: Heavy for simple blog, complex deployment, requires Composer
- **Rejected**: Overkill for project scope, deployment complexity

### WordPress
- **Pros**: Mature platform, extensive plugins, easy setup
- **Cons**: Security concerns, bloated for custom requirements, PHP legacy code
- **Rejected**: Too generic, doesn't fit custom website directory needs

### Node.js + Express
- **Pros**: JavaScript everywhere, modern ecosystem, fast development
- **Cons**: Not standard in Baota, requires Node.js installation, build process
- **Rejected**: Deployment complexity in shared hosting

### Static Site Generator (Hugo/Jekyll)
- **Pros**: Fast, secure, simple deployment
- **Cons**: No dynamic content, no admin interface, limited functionality
- **Rejected**: Doesn't support dynamic website directory and admin features

## Consequences

### Positive
- **Fast Deployment**: Direct file upload to Baota panel
- **Low Resource Usage**: Minimal server requirements
- **High Performance**: No framework overhead
- **Easy Maintenance**: Simple codebase, clear structure
- **Security**: Controlled dependencies, minimal attack surface

### Negative
- **Custom Development**: More initial development time
- **Limited Ecosystem**: Fewer third-party packages
- **Manual Implementation**: Need to build common features from scratch

### Mitigation Strategies
- **Code Reusability**: Build reusable components and utilities
- **Documentation**: Comprehensive code documentation
- **Testing**: Unit tests for critical business logic
- **Standards**: Strict coding standards and conventions

## Implementation Notes
- Use PSR-4 autoloading for class organization
- Implement dependency injection for testability
- Create base classes for common functionality
- Use environment variables for configuration
- Implement proper error handling and logging

## Review Date
This decision should be reviewed in 6 months or when deployment requirements change significantly.
