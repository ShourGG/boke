<?php
/**
 * Category Controller
 * Handle category display functionality
 */
class CategoryController extends BaseController
{
    private $categoryModel;
    private $postModel;
    
    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->postModel = new Post();
    }
    
    /**
     * Display category page with posts
     */
    public function show()
    {
        $slug = $this->getParam('slug');
        
        if (empty($slug)) {
            $this->show404();
            return;
        }
        
        // Get category by slug
        $category = $this->categoryModel->getBySlug($slug);
        
        if (!$category) {
            $this->show404();
            return;
        }
        
        // Get pagination parameters
        $page = (int)$this->getGet('page', 1);
        $perPage = defined('POSTS_PER_PAGE') ? POSTS_PER_PAGE : 10;
        
        // Get posts in this category
        $posts = $this->postModel->getByCategory($category['id'], $page, $perPage);
        
        // Get total posts count for pagination
        $totalPosts = $this->postModel->countByCategory($category['id']);
        $totalPages = ceil($totalPosts / $perPage);
        
        // Get related categories
        $relatedCategories = $this->categoryModel->getRelated($category['id'], 5);
        
        // Get popular posts in this category
        $popularPosts = $this->postModel->getPopularByCategory($category['id'], 5);
        
        $this->data = [
            'title' => $category['name'] . ' - 分类',
            'description' => $category['description'] ?: '查看' . $category['name'] . '分类下的所有文章',
            'keywords' => $category['name'] . ',分类,文章',
            'category' => $category,
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'relatedCategories' => $relatedCategories,
            'popularPosts' => $popularPosts,
            'currentCategory' => 'category'
        ];
        
        $this->render('category/show', $this->data);
    }
    
    /**
     * Display all categories
     */
    public function index()
    {
        // Get all categories with post counts
        $categories = $this->categoryModel->getAllWithPostCount();
        
        // Get category statistics
        $stats = [
            'total_categories' => count($categories),
            'total_posts' => array_sum(array_column($categories, 'post_count')),
            'most_popular' => !empty($categories) ? max(array_column($categories, 'post_count')) : 0
        ];
        
        $this->data = [
            'title' => '所有分类',
            'description' => '浏览所有文章分类，发现感兴趣的内容',
            'keywords' => '分类,文章分类,博客分类',
            'categories' => $categories,
            'stats' => $stats,
            'currentPage' => 'categories'
        ];
        
        $this->render('category/index', $this->data);
    }
    
    /**
     * Get category posts via AJAX
     */
    public function posts()
    {
        if (!$this->isAjax()) {
            $this->jsonError('Invalid request', 400);
            return;
        }
        
        $categoryId = (int)$this->getGet('category_id');
        $page = (int)$this->getGet('page', 1);
        $perPage = (int)$this->getGet('per_page', 10);
        
        if (!$categoryId) {
            $this->jsonError('Category ID is required', 400);
            return;
        }
        
        try {
            $posts = $this->postModel->getByCategory($categoryId, $page, $perPage);
            $totalPosts = $this->postModel->countByCategory($categoryId);
            
            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'posts' => $posts,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total' => $totalPosts,
                        'total_pages' => ceil($totalPosts / $perPage)
                    ]
                ]
            ]);
        } catch (Exception $e) {
            $this->jsonError('Failed to load posts', 500);
        }
    }
    
    /**
     * Check if request is AJAX
     */
    private function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
