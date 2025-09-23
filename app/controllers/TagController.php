<?php
/**
 * Tag Controller
 * Handle tag display functionality
 */
class TagController extends BaseController
{
    private $tagModel;
    private $postModel;
    
    public function __construct()
    {
        $this->tagModel = new Tag();
        $this->postModel = new Post();
    }
    
    /**
     * Display tag page with posts
     */
    public function show()
    {
        $slug = $this->getParam('slug');
        
        if (empty($slug)) {
            $this->show404();
            return;
        }
        
        // Get tag by slug
        $tag = $this->tagModel->getBySlug($slug);
        
        if (!$tag) {
            $this->show404();
            return;
        }
        
        // Get pagination parameters
        $page = (int)$this->getGet('page', 1);
        $perPage = defined('POSTS_PER_PAGE') ? POSTS_PER_PAGE : 10;
        
        // Get posts with this tag
        $posts = $this->postModel->getByTag($tag['id'], $page, $perPage);
        
        // Get total posts count for pagination
        $totalPosts = $this->postModel->countByTag($tag['id']);
        $totalPages = ceil($totalPosts / $perPage);
        
        // Get related tags
        $relatedTags = $this->tagModel->getRelated($tag['id'], 10);
        
        // Get popular posts with this tag
        $popularPosts = $this->postModel->getPopularByTag($tag['id'], 5);
        
        $this->data = [
            'title' => $tag['name'] . ' - 标签',
            'description' => '查看标签"' . $tag['name'] . '"下的所有文章',
            'keywords' => $tag['name'] . ',标签,文章',
            'tag' => $tag,
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'relatedTags' => $relatedTags,
            'popularPosts' => $popularPosts,
            'currentTag' => 'tag'
        ];
        
        $this->render('tag/show', $this->data);
    }
    
    /**
     * Display all tags
     */
    public function index()
    {
        // Get all tags with post counts
        $tags = $this->tagModel->getAllWithPostCount();
        
        // Sort tags by popularity and name
        usort($tags, function($a, $b) {
            if ($a['post_count'] == $b['post_count']) {
                return strcmp($a['name'], $b['name']);
            }
            return $b['post_count'] - $a['post_count'];
        });
        
        // Get tag statistics
        $stats = [
            'total_tags' => count($tags),
            'total_posts' => array_sum(array_column($tags, 'post_count')),
            'most_popular' => !empty($tags) ? max(array_column($tags, 'post_count')) : 0
        ];
        
        // Group tags by first letter for better display
        $groupedTags = [];
        foreach ($tags as $tag) {
            $firstLetter = strtoupper(mb_substr($tag['name'], 0, 1));
            if (!isset($groupedTags[$firstLetter])) {
                $groupedTags[$firstLetter] = [];
            }
            $groupedTags[$firstLetter][] = $tag;
        }
        
        $this->data = [
            'title' => '所有标签',
            'description' => '浏览所有文章标签，发现感兴趣的内容',
            'keywords' => '标签,文章标签,博客标签',
            'tags' => $tags,
            'groupedTags' => $groupedTags,
            'stats' => $stats,
            'currentPage' => 'tags'
        ];
        
        $this->render('tag/index', $this->data);
    }
    
    /**
     * Get tag posts via AJAX
     */
    public function posts()
    {
        if (!$this->isAjax()) {
            $this->jsonError('Invalid request', 400);
            return;
        }
        
        $tagId = (int)$this->getGet('tag_id');
        $page = (int)$this->getGet('page', 1);
        $perPage = (int)$this->getGet('per_page', 10);
        
        if (!$tagId) {
            $this->jsonError('Tag ID is required', 400);
            return;
        }
        
        try {
            $posts = $this->postModel->getByTag($tagId, $page, $perPage);
            $totalPosts = $this->postModel->countByTag($tagId);
            
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
     * Get popular tags via AJAX
     */
    public function popular()
    {
        if (!$this->isAjax()) {
            $this->jsonError('Invalid request', 400);
            return;
        }
        
        $limit = (int)$this->getGet('limit', 20);
        
        try {
            $tags = $this->tagModel->getPopular($limit);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $tags
            ]);
        } catch (Exception $e) {
            $this->jsonError('Failed to load tags', 500);
        }
    }
    
    /**
     * Search tags via AJAX
     */
    public function search()
    {
        if (!$this->isAjax()) {
            $this->jsonError('Invalid request', 400);
            return;
        }
        
        $query = trim($this->getGet('q', ''));
        $limit = (int)$this->getGet('limit', 10);
        
        if (empty($query)) {
            $this->jsonError('Search query is required', 400);
            return;
        }
        
        try {
            $tags = $this->tagModel->search($query, $limit);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $tags
            ]);
        } catch (Exception $e) {
            $this->jsonError('Failed to search tags', 500);
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
