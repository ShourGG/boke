<?php
/**
 * Post Controller
 * Handle individual post display and related functionality
 */
class PostController extends BaseController
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
     * Display single post
     */
    public function show()
    {
        $slug = $this->getParam('slug'); // Get slug parameter from route
        
        if (empty($slug)) {
            $this->show404();
            return;
        }
        
        // Get post by slug
        $post = $this->postModel->getBySlug($slug);
        
        if (!$post) {
            $this->show404();
            return;
        }
        
        // Increment view count
        $this->postModel->incrementViews($post['id']);
        $post['view_count']++;
        
        // Get post tags
        $tags = $this->tagModel->getByPostId($post['id']);
        
        // Get related posts
        $relatedPosts = [];
        if ($post['category_id']) {
            $relatedPosts = $this->postModel->getRelated($post['id'], $post['category_id'], 4);
        }
        
        // Get recent posts for sidebar
        $recentPosts = $this->postModel->getRecent(5, $post['id']);
        
        // Get categories for navigation
        $categories = $this->categoryModel->getForNavigation();
        
        // Get popular tags for sidebar
        $popularTags = $this->tagModel->getPopular(15);
        
        // SEO meta data
        $metaTitle = $post['meta_title'] ?: $post['title'];
        $metaDescription = $post['meta_description'] ?: ($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160));
        $metaKeywords = $post['meta_keywords'] ?: implode(', ', array_column($tags, 'name'));
        
        $this->data = [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $metaKeywords,
            'canonical' => SITE_URL . '/post/' . $post['slug'],
            'post' => $post,
            'tags' => $tags,
            'relatedPosts' => $relatedPosts,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'currentPage' => 'post'
        ];
        
        $this->render('post/show', $this->data);
    }
    
    /**
     * Show 404 error page
     */
    private function show404()
    {
        http_response_code(404);
        $this->data = [
            'title' => '页面未找到 - 404',
            'currentPage' => '404'
        ];
        $this->render('errors/404', $this->data);
    }
}
