<?php
/**
 * Admin Post Controller
 * 管理后台文章控制器 - 处理文章管理功能
 */
class AdminPostController extends BaseController
{
    private $postModel;
    private $categoryModel;
    private $tagModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
    }
    
    /**
     * 文章列表页面
     */
    public function index()
    {
        $this->requireAuth();
        
        $page = max(1, intval($_GET['page'] ?? 1));
        $status = $_GET['status'] ?? 'all';
        $category = intval($_GET['category'] ?? 0);
        $search = trim($_GET['search'] ?? '');
        $perPage = 20;
        
        $offset = ($page - 1) * $perPage;
        
        // 获取文章列表
        $posts = $this->postModel->getAdminList($offset, $perPage, $status, $category, $search);
        $totalCount = $this->postModel->getAdminCount($status, $category, $search);
        $totalPages = ceil($totalCount / $perPage);
        
        // 获取分类列表
        $categories = $this->categoryModel->getAll();
        
        // 获取统计数据
        $stats = [
            'total' => $this->postModel->getTotalCount(),
            'published' => $this->postModel->getPublishedCount(),
            'draft' => $this->postModel->getDraftCount(),
            'total_views' => $this->postModel->getTotalViews()
        ];
        
        $this->render('admin/posts/index', [
            'title' => '文章管理',
            'posts' => $posts,
            'categories' => $categories,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'filters' => [
                'status' => $status,
                'category' => $category,
                'search' => $search
            ]
        ]);
    }
    
    /**
     * 添加文章页面
     */
    public function create()
    {
        $this->requireAuth();
        
        if ($this->isPost()) {
            $this->handleCreate();
            return;
        }
        
        $categories = $this->categoryModel->getAll();
        $tags = $this->tagModel->getAll();
        
        $this->render('admin/posts/create', [
            'title' => '添加文章',
            'categories' => $categories,
            'tags' => $tags
        ]);
    }
    
    /**
     * 处理添加文章
     */
    private function handleCreate()
    {
        $data = [
            'title' => trim($this->getPost('title')),
            'slug' => $this->generateSlug($this->getPost('title')),
            'excerpt' => trim($this->getPost('excerpt')),
            'content' => $this->getPost('content'),
            'category_id' => intval($this->getPost('category_id')),
            'status' => $this->getPost('status', 'draft'),
            'featured' => $this->getPost('featured') ? 1 : 0,
            'allow_comments' => $this->getPost('allow_comments') ? 1 : 0,
            'meta_title' => trim($this->getPost('meta_title')),
            'meta_description' => trim($this->getPost('meta_description')),
            'meta_keywords' => trim($this->getPost('meta_keywords'))
        ];
        
        // 验证数据
        $errors = $this->validatePostData($data);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/posts/create');
            return;
        }
        
        try {
            $postId = $this->postModel->create($data);
            
            // 处理标签
            $tags = $this->getPost('tags', []);
            if (!empty($tags)) {
                $this->postModel->syncTags($postId, $tags);
            }
            
            $this->setFlash('success', '文章添加成功！');
            $this->redirect('admin/posts');
        } catch (Exception $e) {
            $this->setFlash('error', '添加失败：' . $e->getMessage());
            $this->redirect('admin/posts/create');
        }
    }
    
    /**
     * 编辑文章页面
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $post = $this->postModel->getById($id);
        if (!$post) {
            $this->setFlash('error', '文章不存在');
            $this->redirect('admin/posts');
            return;
        }
        
        if ($this->isPost()) {
            $this->handleEdit($id);
            return;
        }
        
        $categories = $this->categoryModel->getAll();
        $tags = $this->tagModel->getAll();
        $postTags = $this->postModel->getTags($id);
        
        $this->render('admin/posts/edit', [
            'title' => '编辑文章',
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
            'postTags' => array_column($postTags, 'id')
        ]);
    }
    
    /**
     * 处理编辑文章
     */
    private function handleEdit($id)
    {
        $data = [
            'title' => trim($this->getPost('title')),
            'slug' => $this->generateSlug($this->getPost('title'), $id),
            'excerpt' => trim($this->getPost('excerpt')),
            'content' => $this->getPost('content'),
            'category_id' => intval($this->getPost('category_id')),
            'status' => $this->getPost('status'),
            'featured' => $this->getPost('featured') ? 1 : 0,
            'allow_comments' => $this->getPost('allow_comments') ? 1 : 0,
            'meta_title' => trim($this->getPost('meta_title')),
            'meta_description' => trim($this->getPost('meta_description')),
            'meta_keywords' => trim($this->getPost('meta_keywords'))
        ];
        
        // 验证数据
        $errors = $this->validatePostData($data, $id);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/posts/edit/' . $id);
            return;
        }
        
        try {
            $this->postModel->update($id, $data);
            
            // 处理标签
            $tags = $this->getPost('tags', []);
            $this->postModel->syncTags($id, $tags);
            
            $this->setFlash('success', '文章更新成功！');
            $this->redirect('admin/posts');
        } catch (Exception $e) {
            $this->setFlash('error', '更新失败：' . $e->getMessage());
            $this->redirect('admin/posts/edit/' . $id);
        }
    }
    
    /**
     * 删除文章
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        try {
            $post = $this->postModel->getById($id);
            if (!$post) {
                $this->jsonError('文章不存在', 404);
                return;
            }
            
            $this->postModel->delete($id);
            $this->jsonResponse(['success' => true, 'message' => '文章删除成功']);
        } catch (Exception $e) {
            $this->jsonError('删除失败：' . $e->getMessage(), 500);
        }
    }
    
    /**
     * 批量操作
     */
    public function batchAction()
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        $action = $this->getPost('action');
        $ids = $this->getPost('ids', []);
        
        if (empty($ids) || !is_array($ids)) {
            $this->jsonError('请选择要操作的文章', 400);
            return;
        }
        
        try {
            $count = 0;
            
            switch ($action) {
                case 'publish':
                    $count = $this->postModel->batchUpdateStatus($ids, 'published');
                    $message = "成功发布 {$count} 篇文章";
                    break;
                    
                case 'draft':
                    $count = $this->postModel->batchUpdateStatus($ids, 'draft');
                    $message = "成功设置 {$count} 篇文章为草稿";
                    break;
                    
                case 'feature':
                    $count = $this->postModel->batchUpdateFeatured($ids, 1);
                    $message = "成功设置 {$count} 篇文章为推荐";
                    break;
                    
                case 'unfeature':
                    $count = $this->postModel->batchUpdateFeatured($ids, 0);
                    $message = "成功取消 {$count} 篇文章的推荐";
                    break;
                    
                case 'delete':
                    $count = $this->postModel->batchDelete($ids);
                    $message = "成功删除 {$count} 篇文章";
                    break;
                    
                default:
                    $this->jsonError('无效的操作', 400);
                    return;
            }
            
            $this->jsonResponse(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            $this->jsonError('批量操作失败：' . $e->getMessage(), 500);
        }
    }
    
    /**
     * 生成文章别名
     */
    private function generateSlug($title, $excludeId = null)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug = trim($slug, '-');
        
        if (empty($slug)) {
            $slug = 'post-' . time();
        }
        
        // 检查别名是否已存在
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->postModel->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * 验证文章数据
     */
    private function validatePostData($data, $excludeId = null)
    {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = '文章标题不能为空';
        } elseif (strlen($data['title']) > 200) {
            $errors[] = '文章标题不能超过200个字符';
        }
        
        if (empty($data['content'])) {
            $errors[] = '文章内容不能为空';
        }
        
        if (empty($data['category_id'])) {
            $errors[] = '请选择文章分类';
        } elseif (!$this->categoryModel->exists($data['category_id'])) {
            $errors[] = '选择的分类不存在';
        }
        
        if (!in_array($data['status'], ['draft', 'published', 'private'])) {
            $errors[] = '无效的文章状态';
        }
        
        if (!empty($data['meta_title']) && strlen($data['meta_title']) > 200) {
            $errors[] = 'SEO标题不能超过200个字符';
        }
        
        if (!empty($data['meta_description']) && strlen($data['meta_description']) > 300) {
            $errors[] = 'SEO描述不能超过300个字符';
        }
        
        return $errors;
    }
    
    /**
     * 返回JSON响应
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * 返回JSON错误响应
     */
    private function jsonError($message, $statusCode = 400)
    {
        $this->jsonResponse(['success' => false, 'error' => $message], $statusCode);
    }
}
