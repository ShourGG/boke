<?php
/**
 * API Controller
 * RESTful API控制器 - 提供JSON格式的数据接口
 */
class ApiController extends BaseController
{
    private $postModel;
    private $websiteModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
        $this->websiteModel = new Website();
        $this->categoryModel = new Category();
        
        // 设置JSON响应头
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // 处理OPTIONS请求
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
    
    /**
     * API首页 - 返回API信息
     */
    public function index()
    {
        $this->jsonResponse([
            'name' => 'Koi Blog API',
            'version' => '1.0.0',
            'description' => 'RESTful API for Koi Blog System',
            'endpoints' => [
                'GET /api/posts' => '获取文章列表',
                'GET /api/posts/{id}' => '获取单篇文章',
                'GET /api/websites' => '获取网站列表',
                'GET /api/websites/{id}' => '获取单个网站',
                'GET /api/categories' => '获取分类列表',
                'GET /api/search' => '搜索内容',
                'GET /api/stats' => '获取统计信息'
            ],
            'timestamp' => time()
        ]);
    }
    
    /**
     * 获取文章列表
     * GET /api/posts
     */
    public function posts()
    {
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = min(50, max(1, intval($_GET['limit'] ?? 10)));
        $category = intval($_GET['category'] ?? 0);
        $featured = $_GET['featured'] ?? null;
        $sort = $_GET['sort'] ?? 'latest'; // latest, popular, views
        
        $offset = ($page - 1) * $limit;
        
        try {
            $posts = $this->postModel->getApiList($offset, $limit, $category, $featured, $sort);
            $total = $this->postModel->getApiCount($category, $featured);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $posts,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (Exception $e) {
            $this->jsonError('获取文章列表失败', 500);
        }
    }
    
    /**
     * 获取单篇文章
     * GET /api/posts/{id}
     */
    public function post($id)
    {
        try {
            $post = $this->postModel->getApiDetail($id);
            
            if (!$post) {
                $this->jsonError('文章不存在', 404);
                return;
            }
            
            // 增加浏览量
            $this->postModel->incrementViews($id);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $post
            ]);
        } catch (Exception $e) {
            $this->jsonError('获取文章详情失败', 500);
        }
    }
    
    /**
     * 获取网站列表
     * GET /api/websites
     */
    public function websites()
    {
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = min(50, max(1, intval($_GET['limit'] ?? 20)));
        $category = intval($_GET['category'] ?? 0);
        $featured = $_GET['featured'] ?? null;
        $sort = $_GET['sort'] ?? 'latest';
        
        $offset = ($page - 1) * $limit;
        
        try {
            $websites = $this->websiteModel->getApiList($offset, $limit, $category, $featured, $sort);
            $total = $this->websiteModel->getApiCount($category, $featured);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $websites,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (Exception $e) {
            $this->jsonError('获取网站列表失败', 500);
        }
    }
    
    /**
     * 获取单个网站
     * GET /api/websites/{id}
     */
    public function website($id)
    {
        try {
            $website = $this->websiteModel->getApiDetail($id);
            
            if (!$website) {
                $this->jsonError('网站不存在', 404);
                return;
            }
            
            // 增加浏览量
            $this->websiteModel->incrementViews($id);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $website
            ]);
        } catch (Exception $e) {
            $this->jsonError('获取网站详情失败', 500);
        }
    }
    
    /**
     * 获取分类列表
     * GET /api/categories
     */
    public function categories()
    {
        $type = $_GET['type'] ?? 'post'; // post, website
        
        try {
            if ($type === 'website') {
                $websiteCategoryModel = new WebsiteCategory();
                $categories = $websiteCategoryModel->getApiList();
            } else {
                $categories = $this->categoryModel->getApiList();
            }
            
            $this->jsonResponse([
                'success' => true,
                'data' => $categories
            ]);
        } catch (Exception $e) {
            $this->jsonError('获取分类列表失败', 500);
        }
    }
    
    /**
     * 搜索接口
     * GET /api/search
     */
    public function search()
    {
        $query = trim($_GET['q'] ?? '');
        $type = $_GET['type'] ?? 'all'; // all, posts, websites
        $limit = min(50, max(1, intval($_GET['limit'] ?? 10)));
        
        if (empty($query)) {
            $this->jsonError('搜索关键词不能为空', 400);
            return;
        }
        
        try {
            $results = [];
            
            switch ($type) {
                case 'posts':
                    $results = $this->postModel->apiSearch($query, $limit);
                    break;
                    
                case 'websites':
                    $results = $this->websiteModel->apiSearch($query, $limit);
                    break;
                    
                default:
                    $results = [
                        'posts' => $this->postModel->apiSearch($query, $limit / 2),
                        'websites' => $this->websiteModel->apiSearch($query, $limit / 2)
                    ];
                    break;
            }
            
            $this->jsonResponse([
                'success' => true,
                'query' => $query,
                'data' => $results
            ]);
        } catch (Exception $e) {
            $this->jsonError('搜索失败', 500);
        }
    }
    
    /**
     * 获取统计信息
     * GET /api/stats
     */
    public function stats()
    {
        try {
            $stats = [
                'posts' => [
                    'total' => $this->postModel->getTotalCount(),
                    'published' => $this->postModel->getPublishedCount(),
                    'total_views' => $this->postModel->getTotalViews()
                ],
                'websites' => [
                    'total' => $this->websiteModel->getTotalCount(),
                    'approved' => $this->websiteModel->getApprovedCount(),
                    'total_views' => $this->websiteModel->getTotalViews()
                ],
                'categories' => [
                    'post_categories' => $this->categoryModel->getCount(),
                    'website_categories' => (new WebsiteCategory())->getCount()
                ]
            ];
            
            $this->jsonResponse([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            $this->jsonError('获取统计信息失败', 500);
        }
    }
    
    /**
     * 收集前端性能指标
     * POST /api/metrics
     */
    public function metrics()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonError('Method not allowed', 405);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['metric'])) {
            $this->jsonError('Invalid data', 400);
            return;
        }
        
        try {
            // 这里可以将性能指标存储到数据库
            // 暂时只记录到日志
            error_log('PERFORMANCE_METRIC: ' . json_encode($input));
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Metric recorded'
            ]);
        } catch (Exception $e) {
            $this->jsonError('Failed to record metric', 500);
        }
    }
    
    /**
     * 返回JSON响应
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * 返回JSON错误响应
     */
    private function jsonError($message, $statusCode = 400)
    {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'code' => $statusCode
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
