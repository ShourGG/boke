<?php
/**
 * Admin Tag Controller
 * 管理后台标签控制器 - 处理标签管理功能
 */
class AdminTagController extends BaseController
{
    private $tagModel;
    
    public function __construct()
    {
        $this->tagModel = new Tag();
    }
    
    /**
     * 标签列表页面
     */
    public function index()
    {
        $this->requireAuth();
        
        $page = max(1, intval($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');
        $perPage = 20;
        
        $offset = ($page - 1) * $perPage;
        
        // 获取标签列表
        if ($search) {
            $tags = $this->tagModel->search($search, $perPage, $offset);
            $totalCount = $this->tagModel->getSearchCount($search);
        } else {
            $tags = $this->tagModel->getAllWithPostCount($perPage, $offset);
            $totalCount = $this->tagModel->getTotalCount();
        }
        
        $totalPages = ceil($totalCount / $perPage);
        
        // 获取统计数据
        $stats = [
            'total' => $this->tagModel->getTotalCount(),
            'used' => $this->tagModel->getUsedCount(),
            'unused' => $this->tagModel->getUnusedCount(),
            'most_popular' => $this->tagModel->getMostPopular()
        ];
        
        $this->render('admin/tags/index', [
            'title' => '标签管理',
            'tags' => $tags,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'search' => $search
        ]);
    }
    
    /**
     * 添加标签
     */
    public function create()
    {
        $this->requireAuth();
        
        if ($this->isPost()) {
            $this->handleCreate();
            return;
        }
        
        $this->render('admin/tags/create', [
            'title' => '添加标签'
        ]);
    }
    
    /**
     * 处理添加标签
     */
    private function handleCreate()
    {
        $data = [
            'name' => trim($this->getPost('name')),
            'slug' => $this->generateSlug($this->getPost('name')),
            'description' => trim($this->getPost('description')),
            'color' => $this->getPost('color', '#3498db')
        ];
        
        // 验证数据
        $errors = $this->validateTagData($data);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/tags/create');
            return;
        }
        
        try {
            $this->tagModel->create($data);
            $this->setFlash('success', '标签添加成功！');
            $this->redirect('admin/tags');
        } catch (Exception $e) {
            $this->setFlash('error', '添加失败：' . $e->getMessage());
            $this->redirect('admin/tags/create');
        }
    }
    
    /**
     * 编辑标签
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $tag = $this->tagModel->getById($id);
        if (!$tag) {
            $this->setFlash('error', '标签不存在');
            $this->redirect('admin/tags');
            return;
        }
        
        if ($this->isPost()) {
            $this->handleEdit($id);
            return;
        }
        
        $this->render('admin/tags/edit', [
            'title' => '编辑标签',
            'tag' => $tag
        ]);
    }
    
    /**
     * 处理编辑标签
     */
    private function handleEdit($id)
    {
        $data = [
            'name' => trim($this->getPost('name')),
            'slug' => $this->generateSlug($this->getPost('name'), $id),
            'description' => trim($this->getPost('description')),
            'color' => $this->getPost('color')
        ];
        
        // 验证数据
        $errors = $this->validateTagData($data, $id);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/tags/edit/' . $id);
            return;
        }
        
        try {
            $this->tagModel->update($id, $data);
            $this->setFlash('success', '标签更新成功！');
            $this->redirect('admin/tags');
        } catch (Exception $e) {
            $this->setFlash('error', '更新失败：' . $e->getMessage());
            $this->redirect('admin/tags/edit/' . $id);
        }
    }
    
    /**
     * 删除标签
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        try {
            $tag = $this->tagModel->getById($id);
            if (!$tag) {
                $this->jsonError('标签不存在', 404);
                return;
            }
            
            // 检查是否有文章使用此标签
            $postCount = $this->tagModel->getPostCount($id);
            if ($postCount > 0) {
                $this->jsonError("该标签下还有 {$postCount} 篇文章，无法删除", 400);
                return;
            }
            
            $this->tagModel->delete($id);
            $this->jsonResponse(['success' => true, 'message' => '标签删除成功']);
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
            $this->jsonError('请选择要操作的标签', 400);
            return;
        }
        
        try {
            $count = 0;
            
            switch ($action) {
                case 'delete':
                    // 检查是否有文章使用这些标签
                    $usedTags = $this->tagModel->getUsedTags($ids);
                    if (!empty($usedTags)) {
                        $tagNames = array_column($usedTags, 'name');
                        $this->jsonError('以下标签还有文章使用，无法删除：' . implode(', ', $tagNames), 400);
                        return;
                    }
                    
                    $count = $this->tagModel->batchDelete($ids);
                    $message = "成功删除 {$count} 个标签";
                    break;
                    
                case 'merge':
                    $targetId = (int)$this->getPost('target_id');
                    if (!$targetId || in_array($targetId, $ids)) {
                        $this->jsonError('请选择有效的目标标签', 400);
                        return;
                    }
                    
                    $count = $this->tagModel->mergeTags($ids, $targetId);
                    $message = "成功合并 {$count} 个标签";
                    break;
                    
                default:
                    $this->jsonError('无效的操作', 400);
                    return;
            }
            
            $this->jsonResponse(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            $this->jsonError('操作失败：' . $e->getMessage(), 500);
        }
    }
    
    /**
     * 清理未使用的标签
     */
    public function cleanup()
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        try {
            $count = $this->tagModel->deleteUnused();
            $this->jsonResponse([
                'success' => true,
                'message' => "成功清理 {$count} 个未使用的标签"
            ]);
        } catch (Exception $e) {
            $this->jsonError('清理失败：' . $e->getMessage(), 500);
        }
    }
    
    /**
     * 生成唯一slug
     */
    private function generateSlug($name, $excludeId = null)
    {
        return $this->tagModel->generateSlug($name, $excludeId);
    }
    
    /**
     * 验证标签数据
     */
    private function validateTagData($data, $excludeId = null)
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = '标签名称不能为空';
        } elseif (strlen($data['name']) > 50) {
            $errors[] = '标签名称不能超过50个字符';
        } elseif ($this->tagModel->nameExists($data['name'], $excludeId)) {
            $errors[] = '标签名称已存在';
        }
        
        if (!empty($data['description']) && strlen($data['description']) > 200) {
            $errors[] = '标签描述不能超过200个字符';
        }
        
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $data['color'])) {
            $errors[] = '请输入有效的颜色值';
        }
        
        return $errors;
    }
}
