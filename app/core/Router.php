<?php
/**
 * Router Class - Handle URL routing and dispatch requests
 * Simple but powerful routing system for SEO-friendly URLs
 */
class Router
{
    private $routes = [];
    private $params = [];
    private $paramValidators = [];
    
    public function __construct()
    {
        // Define default routes
        $this->addRoute('', 'HomeController@index');
        $this->addRoute('home', 'HomeController@index');

        // Post routes
        $this->addRoute('post/{slug}', 'PostController@show');

        // Category routes
        $this->addRoute('categories', 'CategoryController@index');
        $this->addRoute('category/{slug}', 'CategoryController@show');
        $this->addRoute('category/{id}/posts', 'CategoryController@posts');

        // Tag routes
        $this->addRoute('tags', 'TagController@index');
        $this->addRoute('tag/{slug}', 'TagController@show');
        $this->addRoute('tag/{id}/posts', 'TagController@posts');
        $this->addRoute('tags/popular', 'TagController@popular');
        $this->addRoute('tags/search', 'TagController@search');

        // Website routes
        $this->addRoute('websites', 'WebsiteController@index');
        $this->addRoute('websites/submit', 'WebsiteController@submit');
        $this->addRoute('websites/click', 'WebsiteController@click');
        $this->addRoute('website/{id}', 'WebsiteController@show');

        // Search routes
        $this->addRoute('search', 'SearchController@index');
        $this->addRoute('search/advanced', 'SearchController@advanced');

        // API routes
        $this->addRoute('api/posts', 'ApiController@posts');
        $this->addRoute('api/websites', 'ApiController@websites');
        $this->addRoute('api/categories', 'ApiController@categories');
        $this->addRoute('api/search', 'ApiController@search');
        $this->addRoute('api/stats', 'ApiController@stats');

        // Admin routes
        $this->addRoute('admin', 'AdminController@dashboard');
        $this->addRoute('admin/login', 'AdminController@login');
        $this->addRoute('admin/logout', 'AdminController@logout');
        $this->addRoute('admin/profile', 'AdminController@profile');

        // Admin Posts
        $this->addRoute('admin/posts', 'AdminPostController@index');
        $this->addRoute('admin/posts/create', 'AdminPostController@create');
        $this->addRoute('admin/posts/edit/{id}', 'AdminPostController@edit');
        $this->addRoute('admin/posts/delete/{id}', 'AdminPostController@delete');
        $this->addRoute('admin/posts/batch-action', 'AdminPostController@batchAction');

        // Admin Categories
        $this->addRoute('admin/categories', 'AdminCategoryController@index');
        $this->addRoute('admin/categories/create', 'AdminCategoryController@create');
        $this->addRoute('admin/categories/edit/{id}', 'AdminCategoryController@edit');
        $this->addRoute('admin/categories/delete/{id}', 'AdminCategoryController@delete');
        $this->addRoute('admin/categories/sort', 'AdminCategoryController@updateSort');

        // Admin Tags
        $this->addRoute('admin/tags', 'AdminTagController@index');
        $this->addRoute('admin/tags/create', 'AdminTagController@create');
        $this->addRoute('admin/tags/edit/{id}', 'AdminTagController@edit');
        $this->addRoute('admin/tags/delete/{id}', 'AdminTagController@delete');
        $this->addRoute('admin/tags/batch', 'AdminTagController@batchAction');
        $this->addRoute('admin/tags/cleanup', 'AdminTagController@cleanup');

        // Admin Websites
        $this->addRoute('admin/websites', 'AdminWebsiteController@index');
        $this->addRoute('admin/websites/create', 'AdminWebsiteController@create');
        $this->addRoute('admin/websites/edit/{id}', 'AdminWebsiteController@edit');
        $this->addRoute('admin/websites/delete/{id}', 'AdminWebsiteController@delete');
        $this->addRoute('admin/websites/batch-action', 'AdminWebsiteController@batchAction');

        // Admin Banner Management
        $this->addRoute('admin/banner', 'BannerController@index');
        $this->addRoute('admin/banner/update', 'BannerController@update');
        $this->addRoute('admin/banner/preview', 'BannerController@preview');
        $this->addRoute('admin/banner/settings', 'BannerController@getSettings');

        // Admin Settings
        $this->addRoute('admin/settings', 'AdminSettingsController@index');
        $this->addRoute('admin/settings/cache/clear', 'AdminSettingsController@clearCache');
        $this->addRoute('admin/settings/backup', 'AdminSettingsController@backup');

        // Set parameter validation rules
        $this->setParameterValidationRules();
    }

    /**
     * Set parameter validation rules for routes
     */
    private function setParameterValidationRules()
    {
        // ID parameters must be positive integers
        $this->paramValidators['id'] = '/^\d+$/';

        // Slug parameters: lowercase letters, numbers, hyphens
        $this->paramValidators['slug'] = '/^[a-z0-9\-]+$/';

        // Category slug: same as regular slug
        $this->paramValidators['category_slug'] = '/^[a-z0-9\-]+$/';

        // Tag slug: same as regular slug
        $this->paramValidators['tag_slug'] = '/^[a-z0-9\-]+$/';

        // Website ID: positive integer
        $this->paramValidators['website_id'] = '/^\d+$/';

        // Post ID: positive integer
        $this->paramValidators['post_id'] = '/^\d+$/';

        // Category ID: positive integer
        $this->paramValidators['category_id'] = '/^\d+$/';

        // Tag ID: positive integer
        $this->paramValidators['tag_id'] = '/^\d+$/';
    }
    
    /**
     * Add a route to the routing table
     */
    public function addRoute($pattern, $handler)
    {
        $this->routes[$pattern] = $handler;
    }
    
    /**
     * Dispatch the current request
     */
    public function dispatch()
    {
        $uri = $this->getUri();
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Find matching route
        foreach ($this->routes as $pattern => $handler) {
            if ($this->matchRoute($pattern, $uri)) {
                $this->callHandler($handler, $method);
                return;
            }
        }
        
        // No route found, show 404
        $this->show404();
    }
    
    /**
     * Get clean URI from request
     */
    private function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove leading and trailing slashes
        $uri = trim($uri, '/');
        
        return $uri;
    }
    
    /**
     * Check if route pattern matches URI
     */
    private function matchRoute($pattern, $uri)
    {
        // Reset parameters
        $this->params = [];

        // Convert pattern to regex
        $regex = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            // Extract parameter names from pattern
            preg_match_all('/\{([^}]+)\}/', $pattern, $paramNames);

            // Map parameter values to names and validate
            for ($i = 1; $i < count($matches); $i++) {
                if (isset($paramNames[1][$i - 1])) {
                    $paramName = $paramNames[1][$i - 1];
                    $paramValue = $matches[$i];

                    // Validate parameter format
                    if (!$this->validateParameter($paramName, $paramValue)) {
                        // Log security event for invalid parameter
                        $this->logSecurityEvent('invalid_parameter', [
                            'pattern' => $pattern,
                            'uri' => $uri,
                            'param_name' => $paramName,
                            'param_value' => $paramValue,
                            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                        ]);
                        return false;
                    }

                    $this->params[$paramName] = $paramValue;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Validate route parameter
     */
    private function validateParameter($name, $value)
    {
        // Basic security: prevent null bytes and control characters
        if (strpos($value, "\0") !== false || preg_match('/[\x00-\x1F\x7F]/', $value)) {
            return false;
        }

        // Length check: prevent extremely long parameters
        if (strlen($value) > 255) {
            return false;
        }

        // Check specific parameter validation rules
        if (isset($this->paramValidators[$name])) {
            return preg_match($this->paramValidators[$name], $value);
        }

        // Default validation: alphanumeric, hyphens, underscores
        return preg_match('/^[a-zA-Z0-9\-_]+$/', $value);
    }
    
    /**
     * Call the route handler
     */
    private function callHandler($handler, $method)
    {
        list($controller, $action) = explode('@', $handler);
        
        if (!class_exists($controller)) {
            throw new Exception("Controller {$controller} not found");
        }
        
        $controllerInstance = new $controller();
        
        if (!method_exists($controllerInstance, $action)) {
            throw new Exception("Method {$action} not found in {$controller}");
        }
        
        // Pass parameters to controller
        $controllerInstance->setParams($this->params);
        $controllerInstance->setMethod($method);
        
        // Call the action
        call_user_func([$controllerInstance, $action]);
    }
    
    /**
     * Show 404 error page
     */
    private function show404()
    {
        http_response_code(404);
        include APP_PATH . '/views/errors/404.php';
    }
    
    /**
     * Get route parameters
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Log security events
     */
    private function logSecurityEvent($event, $data = [])
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'data' => $data,
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];

        // Log to file
        $logFile = __DIR__ . '/../../logs/router_security.log';
        $logDir = dirname($logFile);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }

    /**
     * Add custom parameter validator
     */
    public function addParameterValidator($paramName, $regex)
    {
        $this->paramValidators[$paramName] = $regex;
    }

    /**
     * Get all current parameters
     */
    public function getAllParams()
    {
        return $this->params;
    }
}
