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
        $page = max(1, intval($this->getGet('page', 1)));
        $perPage = defined('POSTS_PER_PAGE') ? POSTS_PER_PAGE : 10;

        // Get published posts with pagination
        $posts = $this->postModel->getPublished($page, $perPage);

        // Get featured posts for hero section
        $featuredPosts = $this->postModel->getFeatured(6);

        // Get categories for navigation
        $categories = $this->categoryModel->getForNavigation();

        // Get popular tags for tag cloud
        $tags = $this->tagModel->getPopular(15);

        // Get recent posts for sidebar
        $recentPosts = $this->postModel->getRecent(5);

        // Get statistics for hero section
        $websiteModel = new Website();
        $websiteCount = $websiteModel->getTotalCount();
        $totalViews = $this->postModel->getTotalViews();

        $this->data = [
            'title' => '首页',
            'posts' => $posts,
            'featuredPosts' => $featuredPosts,
            'categories' => $categories,
            'tags' => $tags,
            'recentPosts' => $recentPosts,
            'websiteCount' => $websiteCount,
            'totalViews' => $totalViews,
            'currentPage' => 'home'
        ];
        
        $this->render('home/index', $this->data);
    }
}
