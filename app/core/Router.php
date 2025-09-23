<?php
/**
 * Router Class - Handle URL routing and dispatch requests
 * Simple but powerful routing system for SEO-friendly URLs
 */
class Router
{
    private $routes = [];
    private $params = [];
    
    public function __construct()
    {
        // Define default routes
        $this->addRoute('', 'HomeController@index');
        $this->addRoute('home', 'HomeController@index');
        $this->addRoute('post/{id}', 'PostController@show');
        $this->addRoute('category/{slug}', 'CategoryController@show');
        $this->addRoute('tag/{slug}', 'TagController@show');
        $this->addRoute('websites', 'WebsiteController@index');
        $this->addRoute('websites/submit', 'WebsiteController@submit');
        $this->addRoute('website/{id}', 'WebsiteController@show');
        $this->addRoute('search', 'SearchController@index');
        
        // Admin routes
        $this->addRoute('admin', 'AdminController@dashboard');
        $this->addRoute('admin/login', 'AdminController@login');
        $this->addRoute('admin/logout', 'AdminController@logout');
        $this->addRoute('admin/posts', 'AdminPostController@index');
        $this->addRoute('admin/posts/create', 'AdminPostController@create');
        $this->addRoute('admin/posts/edit/{id}', 'AdminPostController@edit');
        $this->addRoute('admin/websites', 'AdminWebsiteController@index');
        $this->addRoute('admin/websites/create', 'AdminWebsiteController@create');
        $this->addRoute('admin/settings', 'AdminSettingsController@index');
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
            
            // Map parameter values to names
            for ($i = 1; $i < count($matches); $i++) {
                if (isset($paramNames[1][$i - 1])) {
                    $this->params[$paramNames[1][$i - 1]] = $matches[$i];
                }
            }
            
            return true;
        }
        
        return false;
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
}
