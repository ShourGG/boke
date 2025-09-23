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

        // Post routes
        $this->addRoute('post/{id}', 'PostController@show');

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

        // Admin Settings
        $this->addRoute('admin/settings', 'AdminSettingsController@index');
        $this->addRoute('admin/settings/cache/clear', 'AdminSettingsController@clearCache');
        $this->addRoute('admin/settings/backup', 'AdminSettingsController@backup');
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
