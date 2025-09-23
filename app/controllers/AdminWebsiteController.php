<?php
/**
 * Admin Website Controller
 * 管理后台网站控制器 - 处理网站目录管理功能
 */
class AdminWebsiteController extends BaseController
{
    private $websiteModel;
    private $categoryModel;
    
    public function __construct()
    {
        $this->websiteModel = new Website();
        $this->categoryModel = new WebsiteCategory();
    }
    
    /**
     * 网站列表页面
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
        
        // 获取网站列表
        $websites = $this->websiteModel->getAdminList($offset, $perPage, $status, $category, $search);
        $totalCount = $this->websiteModel->getAdminCount($status, $category, $search);
        $totalPages = ceil($totalCount / $perPage);
        
        // 获取分类列表
        $categories = $this->categoryModel->findAll([], 'sort_order ASC, name ASC');
        
        // 获取统计数据
        $stats = [
            'total' => $this->websiteModel->getTotalCount(),
            'pending' => $this->websiteModel->getPendingCount(),
            'approved' => $this->websiteModel->getApprovedCount(),
            'rejected' => $this->websiteModel->getRejectedCount()
        ];
        
        $this->render('admin/websites/index', [
            'title' => '网站管理',
            'websites' => $websites,
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
     * 添加网站页面
     */
    public function create()
    {
        $this->requireAuth();
        
        if ($this->isPost()) {
            $this->handleCreate();
            return;
        }
        
        $categories = $this->categoryModel->findAll([], 'sort_order ASC, name ASC');
        
        $this->render('admin/websites/create', [
            'title' => '添加网站',
            'categories' => $categories
        ]);
    }
    
    /**
     * 处理添加网站
     */
    private function handleCreate()
    {
        $data = [
            'title' => trim($this->getPost('name')),
            'url' => trim($this->getPost('url')),
            'description' => trim($this->getPost('description')),
            'category_id' => intval($this->getPost('category_id')),
            'tags' => trim($this->getPost('tags')),
            'status' => $this->getPost('status', 'pending'),
            'is_featured' => $this->getPost('featured') ? 1 : 0,
            'submitter_name' => trim($this->getPost('submitter_name', '')),
            'submitter_email' => trim($this->getPost('submitter_email', '')),
            'submitter_ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'meta_title' => trim($this->getPost('meta_title', '')),
            'meta_description' => trim($this->getPost('meta_description', '')),
            'meta_keywords' => trim($this->getPost('meta_keywords', ''))
        ];
        
        // 验证数据
        $errors = $this->validateWebsiteData($data);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/websites/create');
            return;
        }
        
        try {
            $websiteId = $this->websiteModel->create($data);
            $this->setFlash('success', '网站添加成功！');
            $this->redirect('admin/websites');
        } catch (Exception $e) {
            $this->setFlash('error', '添加失败：' . $e->getMessage());
            $this->redirect('admin/websites/create');
        }
    }
    
    /**
     * 编辑网站页面
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $website = $this->websiteModel->find($id);
        if (!$website) {
            $this->setFlash('error', '网站不存在');
            $this->redirect('admin/websites');
            return;
        }
        
        if ($this->isPost()) {
            $this->handleEdit($id);
            return;
        }
        
        $categories = $this->categoryModel->findAll([], 'sort_order ASC, name ASC');
        
        $this->render('admin/websites/edit', [
            'title' => '编辑网站',
            'website' => $website,
            'categories' => $categories
        ]);
    }
    
    /**
     * 处理编辑网站
     */
    private function handleEdit($id)
    {
        $data = [
            'title' => trim($this->getPost('name')),
            'url' => trim($this->getPost('url')),
            'description' => trim($this->getPost('description')),
            'category_id' => intval($this->getPost('category_id')),
            'tags' => trim($this->getPost('tags')),
            'status' => $this->getPost('status'),
            'is_featured' => $this->getPost('featured') ? 1 : 0,
            'submitter_name' => trim($this->getPost('submitter_name', '')),
            'submitter_email' => trim($this->getPost('submitter_email', '')),
            'meta_title' => trim($this->getPost('meta_title', '')),
            'meta_description' => trim($this->getPost('meta_description', '')),
            'meta_keywords' => trim($this->getPost('meta_keywords', ''))
        ];
        
        // 验证数据
        $errors = $this->validateWebsiteData($data, $id);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/websites/edit/' . $id);
            return;
        }
        
        try {
            $this->websiteModel->update($id, $data);
            $this->setFlash('success', '网站更新成功！');
            $this->redirect('admin/websites');
        } catch (Exception $e) {
            $this->setFlash('error', '更新失败：' . $e->getMessage());
            $this->redirect('admin/websites/edit/' . $id);
        }
    }
    
    /**
     * 删除网站
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        try {
            $website = $this->websiteModel->find($id);
            if (!$website) {
                $this->jsonError('网站不存在', 404);
                return;
            }
            
            $this->websiteModel->delete($id);
            $this->jsonResponse(['success' => true, 'message' => '网站删除成功']);
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
            $this->jsonError('请选择要操作的网站', 400);
            return;
        }
        
        try {
            $count = 0;
            
            switch ($action) {
                case 'approve':
                    $count = $this->websiteModel->batchUpdateStatus($ids, 'approved');
                    $message = "成功审核通过 {$count} 个网站";
                    break;
                    
                case 'reject':
                    $count = $this->websiteModel->batchUpdateStatus($ids, 'rejected');
                    $message = "成功拒绝 {$count} 个网站";
                    break;
                    
                case 'feature':
                    $count = $this->websiteModel->batchUpdateFeatured($ids, 1);
                    $message = "成功设置 {$count} 个网站为推荐";
                    break;
                    
                case 'unfeature':
                    $count = $this->websiteModel->batchUpdateFeatured($ids, 0);
                    $message = "成功取消 {$count} 个网站的推荐";
                    break;
                    
                case 'delete':
                    $count = $this->websiteModel->batchDelete($ids);
                    $message = "成功删除 {$count} 个网站";
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
     * 验证网站数据
     */
    private function validateWebsiteData($data, $excludeId = null)
    {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = '网站名称不能为空';
        } elseif (strlen($data['title']) > 255) {
            $errors[] = '网站名称不能超过255个字符';
        }
        
        if (empty($data['url'])) {
            $errors[] = '网站URL不能为空';
        } elseif (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            $errors[] = '请输入有效的网站URL';
        } elseif ($this->websiteModel->urlExists($data['url'], $excludeId)) {
            $errors[] = '该网站URL已存在';
        }
        
        if (empty($data['description'])) {
            $errors[] = '网站描述不能为空';
        } elseif (strlen($data['description']) > 500) {
            $errors[] = '网站描述不能超过500个字符';
        }
        
        if (empty($data['category_id'])) {
            $errors[] = '请选择网站分类';
        } elseif (!$this->categoryModel->exists($data['category_id'])) {
            $errors[] = '选择的分类不存在';
        }
        
        if (!in_array($data['status'], ['pending', 'approved', 'rejected'])) {
            $errors[] = '无效的网站状态';
        }
        
        if ($data['rating'] < 0 || $data['rating'] > 5) {
            $errors[] = '评分必须在0-5之间';
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
