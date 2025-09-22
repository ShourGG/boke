<?php
/**
 * Home Controller
 * Handle homepage and main blog functionality
 */
class HomeController extends BaseController
{
    private $postModel;
    private $categoryModel;
    private $tagModel;
    
    public function __construct()
    {
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
    }
    
    /**
     * Display homepage with recent posts
     */
    public function index()
    {
        $page = (int)$this->getGet('page', 1);
        $perPage = defined('POSTS_PER_PAGE') ? POSTS_PER_PAGE : 10;
        
        // Get published posts with pagination
        $posts = $this->postModel->getPublished($page, $perPage);
        
        // Get featured posts for sidebar
        $featuredPosts = $this->postModel->getFeatured(3);
        
        // Get categories for navigation
        $categories = $this->categoryModel->getForNavigation();
        
        // Get popular tags for tag cloud
        $tags = $this->tagModel->getPopular(15);
        
        // Get recent posts for sidebar
        $recentPosts = $this->postModel->getRecent(5);
        
        $this->data = [
            'title' => '首页',
            'posts' => $posts,
            'featuredPosts' => $featuredPosts,
            'categories' => $categories,
            'tags' => $tags,
            'recentPosts' => $recentPosts,
            'currentPage' => 'home'
        ];
        
        $this->render('home/index', $this->data);
    }
}
