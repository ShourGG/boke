# Koi Blog - Security Threat Model

## 🎯 Security Overview
This document identifies potential security threats to the Koi Blog system and defines mitigation strategies to protect against common attack vectors.

## 🏗️ System Architecture Security

### Trust Boundaries
```
Internet → Web Server → PHP Application → Database
   ↓           ↓              ↓            ↓
Untrusted   Semi-Trusted   Trusted     Trusted
```

### Attack Surface
- **Web Interface**: Public blog and admin panel
- **Database**: MySQL with user data and content
- **File System**: Uploaded files and application code
- **Session Management**: User authentication state

## 🚨 Threat Categories

### 1. Injection Attacks

#### SQL Injection
**Risk Level**: HIGH
**Description**: Malicious SQL code injection through user inputs
**Attack Vectors**:
- Login forms (username/password)
- Search functionality
- URL parameters
- Form submissions

**Mitigation Strategies**:
```php
// ALWAYS use prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = ?");
$stmt->execute([$email, 'active']);

// NEVER concatenate user input
// $sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "'"; // VULNERABLE!
```

#### Cross-Site Scripting (XSS)
**Risk Level**: HIGH
**Description**: Injection of malicious scripts into web pages
**Attack Vectors**:
- Blog post content
- Comments (if implemented)
- User profile data
- Search results

**Mitigation Strategies**:
```php
// Escape all output
echo htmlspecialchars($userContent, ENT_QUOTES, 'UTF-8');

// For rich content, use allowlist-based sanitization
$cleanHtml = strip_tags($content, '<p><br><strong><em><ul><ol><li>');
```

### 2. Authentication & Authorization

#### Brute Force Attacks
**Risk Level**: MEDIUM
**Description**: Automated attempts to guess passwords
**Attack Vectors**:
- Admin login page
- Password reset functionality

**Mitigation Strategies**:
- Rate limiting (max 5 attempts per 15 minutes)
- Account lockout after failed attempts
- Strong password requirements
- CAPTCHA after multiple failures

```php
// Rate limiting implementation
if ($this->getFailedAttempts($ip) >= 5) {
    if ($this->getLastAttemptTime($ip) > time() - 900) { // 15 minutes
        throw new SecurityException('Too many failed attempts. Try again later.');
    }
}
```

#### Session Hijacking
**Risk Level**: MEDIUM
**Description**: Stealing or predicting session tokens
**Attack Vectors**:
- Network sniffing (if no HTTPS)
- XSS attacks stealing cookies
- Session fixation

**Mitigation Strategies**:
```php
// Secure session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // HTTPS only
ini_set('session.use_strict_mode', 1);
session_regenerate_id(true); // Regenerate on login
```

### 3. File Upload Vulnerabilities

#### Malicious File Upload
**Risk Level**: HIGH
**Description**: Uploading executable files or malware
**Attack Vectors**:
- Image uploads with embedded PHP code
- File type spoofing
- Path traversal in filenames

**Mitigation Strategies**:
```php
// Strict file validation
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

// Validate MIME type
if (!in_array($_FILES['upload']['type'], $allowedTypes)) {
    throw new SecurityException('Invalid file type');
}

// Validate extension
$extension = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));
if (!in_array($extension, $allowedExtensions)) {
    throw new SecurityException('Invalid file extension');
}

// Store outside web root
$uploadPath = '/var/uploads/' . uniqid() . '.' . $extension;
```

### 4. Cross-Site Request Forgery (CSRF)

#### CSRF Attacks
**Risk Level**: MEDIUM
**Description**: Unauthorized actions performed on behalf of authenticated users
**Attack Vectors**:
- Admin actions (delete posts, change settings)
- Form submissions from external sites

**Mitigation Strategies**:
```php
// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}
```

## 🛡️ Security Implementation Checklist

### Input Validation
- [ ] All user inputs validated and sanitized
- [ ] File uploads restricted by type and size
- [ ] URL parameters validated
- [ ] Form data validated server-side

### Output Encoding
- [ ] HTML output escaped with htmlspecialchars()
- [ ] URL parameters encoded with urlencode()
- [ ] JSON output properly encoded
- [ ] SQL queries use prepared statements

### Authentication & Sessions
- [ ] Strong password requirements enforced
- [ ] Session tokens regenerated on login
- [ ] Secure session configuration
- [ ] Rate limiting on login attempts
- [ ] Account lockout mechanism

### CSRF Protection
- [ ] CSRF tokens on all state-changing forms
- [ ] Token validation on form submission
- [ ] SameSite cookie attribute set
- [ ] Referer header validation

### File Security
- [ ] Uploaded files stored outside web root
- [ ] File type validation (MIME and extension)
- [ ] File size limits enforced
- [ ] Virus scanning (if applicable)

### Database Security
- [ ] Database user has minimal required privileges
- [ ] Prepared statements for all queries
- [ ] Database connection encrypted (if possible)
- [ ] Regular security updates applied

## 🔍 Security Monitoring

### Logging Requirements
```php
// Security event logging
function logSecurityEvent($event, $details = []) {
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event' => $event,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'details' => $details
    ];
    
    error_log('SECURITY: ' . json_encode($logEntry));
}

// Log failed login attempts
logSecurityEvent('failed_login', ['username' => $username]);

// Log successful logins
logSecurityEvent('successful_login', ['user_id' => $userId]);

// Log suspicious activity
logSecurityEvent('suspicious_activity', ['reason' => 'multiple_failed_attempts']);
```

### Monitoring Alerts
- Failed login attempts > 10 per hour
- File upload failures
- SQL error patterns
- Unusual traffic patterns
- Admin actions outside business hours

## 🚀 Security Deployment

### Production Security Checklist
- [ ] HTTPS enabled with valid SSL certificate
- [ ] Debug mode disabled
- [ ] Error reporting disabled for users
- [ ] Database credentials secured
- [ ] File permissions properly set (644 for files, 755 for directories)
- [ ] Unnecessary files removed (.git, .env.example, etc.)
- [ ] Security headers configured
- [ ] Regular security updates scheduled

### Security Headers
```php
// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
header('Content-Security-Policy: default-src \'self\'');
```

## 📅 Security Review Schedule
- **Weekly**: Review security logs and failed login attempts
- **Monthly**: Update dependencies and security patches
- **Quarterly**: Full security audit and penetration testing
- **Annually**: Review and update threat model
