# Koi Blog - Architectural Principles

## 🎯 Core Principles

### 1. Simplicity First (KISS)
- **Principle**: Keep implementations simple and straightforward
- **Application**: 
  - Use lightweight MVC without heavy frameworks
  - Prefer direct SQL over complex ORM
  - Minimize dependencies and external libraries
- **Rationale**: Easier maintenance, faster deployment, better performance

### 2. Domain-Driven Design (DDD)
- **Principle**: Code structure reflects business domain
- **Application**:
  - Clear separation between Blog and Website Directory contexts
  - Business logic encapsulated in domain models
  - Ubiquitous language used throughout codebase
- **Rationale**: Better maintainability and business alignment

### 3. Security by Design
- **Principle**: Security considerations in every component
- **Application**:
  - All inputs sanitized and validated
  - SQL injection prevention via prepared statements
  - XSS protection through output escaping
  - CSRF tokens for state-changing operations
- **Rationale**: Protect user data and system integrity

### 4. Performance Optimization
- **Principle**: Efficient resource usage and fast response times
- **Application**:
  - Database query optimization
  - Minimal HTTP requests
  - Efficient caching strategies
  - Optimized asset loading
- **Rationale**: Better user experience and server efficiency

### 5. Deployment Simplicity
- **Principle**: Easy deployment to Baota panel
- **Application**:
  - No complex build processes
  - File-based deployment
  - Environment-specific configuration
  - Automated installation scripts
- **Rationale**: Reduce deployment complexity and errors

## 🏗️ Architectural Patterns

### MVC Architecture
```
Controllers/ (Request handling and flow control)
├── Models/ (Business logic and data access)
└── Views/ (Presentation layer)
```

### Database Layer
- **Pattern**: Singleton Database Connection
- **Implementation**: PDO with prepared statements
- **Benefits**: Connection reuse, security, consistency

### Security Layer
- **Pattern**: Input Validation Pipeline
- **Implementation**: Sanitize → Validate → Process
- **Benefits**: Consistent security across all inputs

### Caching Strategy
- **Pattern**: File-based caching for static content
- **Implementation**: Generated HTML caching
- **Benefits**: Reduced database load, faster response

## 🔧 Technology Constraints

### Required Technologies
- **PHP**: 7.4+ (for compatibility with Baota)
- **MySQL**: 5.7+ (standard Baota configuration)
- **Bootstrap**: 5.x (for responsive design)
- **Vanilla JavaScript**: No heavy frameworks

### Forbidden Technologies
- **Heavy Frameworks**: Laravel, Symfony (deployment complexity)
- **Node.js Dependencies**: NPM build processes
- **Complex ORMs**: Doctrine, Eloquent (overkill for project size)
- **External APIs**: Minimize third-party dependencies

## 📊 Quality Attributes

### Performance Targets
- **Page Load**: < 2 seconds on 3G connection
- **Database Queries**: < 10 per page load
- **Memory Usage**: < 64MB per request
- **Concurrent Users**: Support 100+ simultaneous users

### Security Requirements
- **Input Validation**: 100% of user inputs
- **SQL Injection**: Zero tolerance via prepared statements
- **XSS Protection**: All output properly escaped
- **Authentication**: Secure session management

### Maintainability Goals
- **Code Coverage**: > 80% for critical business logic
- **Documentation**: All public APIs documented
- **Naming**: Clear, descriptive variable and function names
- **Complexity**: Functions < 50 lines, classes < 500 lines
