<?php
/**
 * Search Controller
 * 搜索控制器 - 处理文章和网站搜索功能
 */
class SearchController extends BaseController
{
    private $postModel;
    private $websiteModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
        $this->websiteModel = new Website();
    }
    
    /**
     * 搜索页面
     */
    public function index()
    {
        $query = trim($_GET['q'] ?? '');
        $type = $_GET['type'] ?? 'all'; // all, posts, websites
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;
        
        $results = [];
        $totalResults = 0;
        $searchTime = 0;
        
        if (!empty($query)) {
            $startTime = microtime(true);
            
            switch ($type) {
                case 'posts':
                    $results = $this->searchPosts($query, $page, $perPage);
                    $totalResults = $this->postModel->getSearchCount($query);
                    break;
                    
                case 'websites':
                    $results = $this->searchWebsites($query, $page, $perPage);
                    $totalResults = $this->websiteModel->getSearchCount($query);
                    break;
                    
                default: // all
                    $postResults = $this->searchPosts($query, 1, 5);
                    $websiteResults = $this->searchWebsites($query, 1, 5);
                    
                    $results = [
                        'posts' => $postResults,
                        'websites' => $websiteResults
                    ];
                    
                    $totalResults = $this->postModel->getSearchCount($query) + 
                                   $this->websiteModel->getSearchCount($query);
                    break;
            }
            
            $searchTime = round((microtime(true) - $startTime) * 1000, 2);
        }
        
        // 计算分页
        $totalPages = ceil($totalResults / $perPage);
        
        $this->render('search/index', [
            'title' => empty($query) ? '搜索' : "搜索结果：{$query}",
            'query' => $query,
            'type' => $type,
            'results' => $results,
            'totalResults' => $totalResults,
            'searchTime' => $searchTime,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }
    
    /**
     * AJAX搜索建议
     */
    public function suggest()
    {
        header('Content-Type: application/json');
        
        $query = trim($_GET['q'] ?? '');
        
        if (empty($query) || strlen($query) < 2) {
            echo json_encode([]);
            return;
        }
        
        $suggestions = [];
        
        // 搜索文章标题
        $postSuggestions = $this->postModel->getSearchSuggestions($query, 5);
        foreach ($postSuggestions as $post) {
            $suggestions[] = [
                'type' => 'post',
                'title' => $post['title'],
                'url' => '/post/' . $post['slug'],
                'category' => $post['category_name'] ?? ''
            ];
        }
        
        // 搜索网站名称
        $websiteSuggestions = $this->websiteModel->getSearchSuggestions($query, 5);
        foreach ($websiteSuggestions as $website) {
            $suggestions[] = [
                'type' => 'website',
                'title' => $website['name'],
                'url' => '/website/' . $website['id'],
                'category' => $website['category_name'] ?? ''
            ];
        }
        
        echo json_encode($suggestions);
    }
    
    /**
     * 搜索文章
     */
    private function searchPosts($query, $page, $perPage)
    {
        $result = $this->postModel->search($query, $page, $perPage);
        return $result['data'] ?? [];
    }

    /**
     * 搜索网站
     */
    private function searchWebsites($query, $page, $perPage)
    {
        $result = $this->websiteModel->search($query, $page, $perPage);
        return $result['data'] ?? [];
    }
    
    /**
     * 高级搜索页面
     */
    public function advanced()
    {
        $this->render('search/advanced', [
            'title' => '高级搜索',
            'categories' => $this->getCategories(),
            'websiteCategories' => $this->getWebsiteCategories()
        ]);
    }
    
    /**
     * 处理高级搜索
     */
    public function advancedSearch()
    {
        $params = [
            'query' => trim($_GET['query'] ?? ''),
            'category' => intval($_GET['category'] ?? 0),
            'website_category' => intval($_GET['website_category'] ?? 0),
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'sort' => $_GET['sort'] ?? 'relevance', // relevance, date, views
            'type' => $_GET['type'] ?? 'all'
        ];
        
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $results = [];
        $totalResults = 0;
        
        if (!empty($params['query'])) {
            switch ($params['type']) {
                case 'posts':
                    $results = $this->postModel->advancedSearch($params, $offset, $perPage);
                    $totalResults = $this->postModel->getAdvancedSearchCount($params);
                    break;
                    
                case 'websites':
                    $results = $this->websiteModel->advancedSearch($params, $offset, $perPage);
                    $totalResults = $this->websiteModel->getAdvancedSearchCount($params);
                    break;
                    
                default:
                    $postResults = $this->postModel->advancedSearch($params, 0, 10);
                    $websiteResults = $this->websiteModel->advancedSearch($params, 0, 10);
                    
                    $results = [
                        'posts' => $postResults,
                        'websites' => $websiteResults
                    ];
                    
                    $totalResults = $this->postModel->getAdvancedSearchCount($params) + 
                                   $this->websiteModel->getAdvancedSearchCount($params);
                    break;
            }
        }
        
        $totalPages = ceil($totalResults / $perPage);
        
        $this->render('search/results', [
            'title' => '高级搜索结果',
            'params' => $params,
            'results' => $results,
            'totalResults' => $totalResults,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'categories' => $this->getCategories(),
            'websiteCategories' => $this->getWebsiteCategories()
        ]);
    }
    
    /**
     * 获取文章分类
     */
    private function getCategories()
    {
        $categoryModel = new Category();
        return $categoryModel->getAll();
    }
    
    /**
     * 获取网站分类
     */
    private function getWebsiteCategories()
    {
        $websiteCategoryModel = new WebsiteCategory();
        return $websiteCategoryModel->getAll();
    }
    
    /**
     * 热门搜索词
     */
    public function trending()
    {
        header('Content-Type: application/json');
        
        // 这里可以从数据库获取热门搜索词
        // 暂时返回静态数据
        $trending = [
            'PHP', 'JavaScript', 'Vue.js', 'React', 'Laravel',
            '前端开发', '后端开发', '数据库', 'MySQL', 'Redis'
        ];
        
        echo json_encode($trending);
    }
}
