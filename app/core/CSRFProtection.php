<?php
/**
 * CSRF Protection Class
 * Provides Cross-Site Request Forgery protection for forms and AJAX requests
 */
class CSRFProtection
{
    private static $tokenName = 'csrf_token';
    private static $sessionKey = 'csrf_tokens';
    
    /**
     * Generate a new CSRF token
     * @return string The generated token
     */
    public static function generateToken()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Generate a secure random token
        $token = bin2hex(random_bytes(32));
        
        // Store token in session with timestamp
        if (!isset($_SESSION[self::$sessionKey])) {
            $_SESSION[self::$sessionKey] = [];
        }
        
        // Clean old tokens (older than 1 hour)
        self::cleanOldTokens();
        
        // Store new token
        $_SESSION[self::$sessionKey][$token] = time();
        
        return $token;
    }
    
    /**
     * Validate CSRF token
     * @param string $token The token to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateToken($token)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (empty($token) || !isset($_SESSION[self::$sessionKey])) {
            return false;
        }
        
        // Check if token exists and is not expired (1 hour)
        if (isset($_SESSION[self::$sessionKey][$token])) {
            $tokenTime = $_SESSION[self::$sessionKey][$token];
            
            // Token is valid for 1 hour
            if (time() - $tokenTime <= 3600) {
                // Remove used token (one-time use)
                unset($_SESSION[self::$sessionKey][$token]);
                return true;
            } else {
                // Remove expired token
                unset($_SESSION[self::$sessionKey][$token]);
            }
        }
        
        return false;
    }
    
    /**
     * Get CSRF token from request
     * @return string|null The token from POST, GET, or headers
     */
    public static function getTokenFromRequest()
    {
        // Check POST data first
        if (isset($_POST[self::$tokenName])) {
            return $_POST[self::$tokenName];
        }
        
        // Check GET data (for AJAX requests)
        if (isset($_GET[self::$tokenName])) {
            return $_GET[self::$tokenName];
        }
        
        // Check headers (for AJAX requests)
        $headers = getallheaders();
        if (isset($headers['X-CSRF-Token'])) {
            return $headers['X-CSRF-Token'];
        }
        
        return null;
    }
    
    /**
     * Generate HTML input field for CSRF token
     * @return string HTML input field
     */
    public static function getTokenField()
    {
        $token = self::generateToken();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Generate meta tag for CSRF token (for AJAX requests)
     * @return string HTML meta tag
     */
    public static function getTokenMeta()
    {
        $token = self::generateToken();
        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Verify CSRF token from current request
     * @return bool True if valid, false otherwise
     */
    public static function verifyRequest()
    {
        $token = self::getTokenFromRequest();
        return self::validateToken($token);
    }
    
    /**
     * Clean old tokens from session
     */
    private static function cleanOldTokens()
    {
        if (!isset($_SESSION[self::$sessionKey])) {
            return;
        }
        
        $currentTime = time();
        foreach ($_SESSION[self::$sessionKey] as $token => $timestamp) {
            // Remove tokens older than 1 hour
            if ($currentTime - $timestamp > 3600) {
                unset($_SESSION[self::$sessionKey][$token]);
            }
        }
    }
    
    /**
     * Handle CSRF validation failure
     * @param string $message Custom error message
     */
    public static function handleFailure($message = 'CSRF token validation failed')
    {
        http_response_code(403);
        
        // If it's an AJAX request, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $message,
                'code' => 'CSRF_VALIDATION_FAILED'
            ]);
            exit;
        }
        
        // For regular requests, redirect with error
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash']['error'] = $message;
        
        // Redirect to referer or admin dashboard
        $referer = $_SERVER['HTTP_REFERER'] ?? '/admin';
        header('Location: ' . $referer);
        exit;
    }
    
    /**
     * Middleware function to protect routes
     * @param array $excludedMethods HTTP methods to exclude from CSRF protection
     */
    public static function protect($excludedMethods = ['GET', 'HEAD', 'OPTIONS'])
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Skip CSRF protection for safe methods
        if (in_array($method, $excludedMethods)) {
            return true;
        }
        
        // Verify CSRF token
        if (!self::verifyRequest()) {
            self::handleFailure('安全验证失败，请重新提交表单');
            return false;
        }
        
        return true;
    }
}
