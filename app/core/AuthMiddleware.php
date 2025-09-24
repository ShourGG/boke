<?php
/**
 * Authentication Middleware
 * Provides role-based access control and session management
 */
class AuthMiddleware
{
    private static $sessionTimeout = 3600; // 1 hour
    private static $maxLoginAttempts = 5;
    private static $lockoutTime = 900; // 15 minutes
    
    /**
     * Check if user is authenticated
     * @return bool
     */
    public static function isAuthenticated()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Check if admin session exists
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > self::$sessionTimeout) {
                self::logout();
                return false;
            }
        }
        
        // Update last activity
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Require authentication - redirect if not authenticated
     * @param string $redirectTo Where to redirect if not authenticated
     */
    public static function requireAuth($redirectTo = '/admin/login')
    {
        if (!self::isAuthenticated()) {
            // Store intended URL for redirect after login
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/admin';
            
            // Set flash message
            $_SESSION['flash']['warning'] = '请先登录以访问管理后台';
            
            // Redirect to login
            header('Location: ' . $redirectTo);
            exit;
        }
    }
    
    /**
     * Check if user has specific permission
     * @param string $permission Permission to check
     * @return bool
     */
    public static function hasPermission($permission)
    {
        if (!self::isAuthenticated()) {
            return false;
        }
        
        // For now, all authenticated admins have all permissions
        // In the future, this can be extended with role-based permissions
        return true;
    }
    
    /**
     * Require specific permission
     * @param string $permission Permission required
     * @param string $errorMessage Error message if permission denied
     */
    public static function requirePermission($permission, $errorMessage = '您没有权限执行此操作')
    {
        if (!self::hasPermission($permission)) {
            http_response_code(403);
            
            // If it's an AJAX request, return JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => $errorMessage,
                    'code' => 'PERMISSION_DENIED'
                ]);
                exit;
            }
            
            // For regular requests, redirect with error
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['flash']['error'] = $errorMessage;
            
            $referer = $_SERVER['HTTP_REFERER'] ?? '/admin';
            header('Location: ' . $referer);
            exit;
        }
    }
    
    /**
     * Login rate limiting
     * @param string $username Username attempting login
     * @return bool True if login attempt is allowed
     */
    public static function checkLoginAttempts($username)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $key = 'login_attempts_' . md5($username . $_SERVER['REMOTE_ADDR']);
        $attempts = $_SESSION[$key] ?? [];
        
        // Clean old attempts (older than lockout time)
        $currentTime = time();
        $attempts = array_filter($attempts, function($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) < self::$lockoutTime;
        });
        
        // Check if too many attempts
        if (count($attempts) >= self::$maxLoginAttempts) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Record failed login attempt
     * @param string $username Username that failed login
     */
    public static function recordFailedLogin($username)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $key = 'login_attempts_' . md5($username . $_SERVER['REMOTE_ADDR']);
        $attempts = $_SESSION[$key] ?? [];
        $attempts[] = time();
        $_SESSION[$key] = $attempts;
        
        // Log security event
        self::logSecurityEvent('failed_login', [
            'username' => $username,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'attempts_count' => count($attempts)
        ]);
    }
    
    /**
     * Clear login attempts after successful login
     * @param string $username Username that successfully logged in
     */
    public static function clearLoginAttempts($username)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $key = 'login_attempts_' . md5($username . $_SERVER['REMOTE_ADDR']);
        unset($_SESSION[$key]);
        
        // Log successful login
        self::logSecurityEvent('successful_login', [
            'username' => $username,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    }
    
    /**
     * Logout user
     */
    public static function logout()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Log logout event
        if (isset($_SESSION['admin_username'])) {
            self::logSecurityEvent('logout', [
                'username' => $_SESSION['admin_username'],
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);
        }
        
        // Clear admin session data
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_display_name']);
        unset($_SESSION['last_activity']);
        
        // Regenerate session ID for security
        session_regenerate_id(true);
    }
    
    /**
     * Get current admin user info
     * @return array|null
     */
    public static function getCurrentUser()
    {
        if (!self::isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'display_name' => $_SESSION['admin_display_name'] ?? $_SESSION['admin_username']
        ];
    }
    
    /**
     * Log security events
     * @param string $event Event type
     * @param array $data Event data
     */
    private static function logSecurityEvent($event, $data = [])
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'data' => $data,
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? ''
        ];
        
        // Log to file (in production, consider using a proper logging system)
        $logFile = __DIR__ . '/../../logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Middleware function to protect admin routes
     */
    public static function protectAdminRoute()
    {
        // Check authentication
        self::requireAuth();
        
        // Additional security checks can be added here
        // For example: IP whitelist, time-based access, etc.
        
        return true;
    }
    
    /**
     * Check if current request is from admin area
     * @return bool
     */
    public static function isAdminRequest()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/admin') === 0;
    }
}
