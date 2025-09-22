<?php
/**
 * Base Controller Class
 * All controllers extend this base class for common functionality
 */
class BaseController
{
    protected $params = [];
    protected $method = 'GET';
    protected $data = [];
    
    /**
     * Set route parameters
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
    
    /**
     * Set HTTP method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }
    
    /**
     * Get route parameter
     */
    protected function getParam($key, $default = null)
    {
        return isset($this->params[$key]) ? $this->params[$key] : $default;
    }
    
    /**
     * Get POST data
     */
    protected function getPost($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    
    /**
     * Get GET data
     */
    protected function getGet($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost()
    {
        return $this->method === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    protected function isGet()
    {
        return $this->method === 'GET';
    }
    
    /**
     * Render view template
     */
    protected function render($view, $data = [])
    {
        // Merge controller data with passed data
        $data = array_merge($this->data, $data);
        
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View file {$view} not found");
        }
        
        // Get the content
        $content = ob_get_clean();
        
        // If it's an admin view, use admin layout
        if (strpos($view, 'admin/') === 0) {
            $this->renderLayout('admin', ['content' => $content] + $data);
        } else {
            // Use main layout for frontend views
            $this->renderLayout('main', ['content' => $content] + $data);
        }
    }
    
    /**
     * Render layout template
     */
    private function renderLayout($layout, $data = [])
    {
        extract($data);
        
        $layoutFile = APP_PATH . '/views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            // Fallback to content only if layout not found
            echo $content;
        }
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        // Add base URL if relative
        if (!preg_match('/^https?:\/\//', $url)) {
            $baseUrl = $this->getBaseUrl();
            $url = $baseUrl . '/' . ltrim($url, '/');
        }
        
        header("Location: {$url}", true, $statusCode);
        exit;
    }
    
    /**
     * Get base URL
     */
    protected function getBaseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['SCRIPT_NAME']);
        
        return $protocol . '://' . $host . rtrim($path, '/');
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Get flash messages
     */
    protected function getFlash()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
        unset($_SESSION['flash']);
        
        return $flash;
    }
    
    /**
     * Check if user is logged in (for admin controllers)
     */
    protected function requireAuth()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('admin/login');
        }
    }
}
