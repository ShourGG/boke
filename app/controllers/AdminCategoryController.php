<?php
/**
 * Admin Category Controller
 * 管理后台分类控制器 - 处理分类管理功能
 */
class AdminCategoryController extends BaseController
{
    private $categoryModel;
    
    public function __construct()
    {
        $this->categoryModel = new Category();
    }
    
    /**
     * 分类列表页面
     */
    public function index()
    {
        $this->requireAuth();
        
        $categories = $this->categoryModel->getAllWithStats();
        
        $this->render('admin/categories/index', [
            'title' => '分类管理',
            'categories' => $categories
        ]);
    }
    
    /**
     * 添加分类
     */
    public function create()
    {
        $this->requireAuth();
        
        if ($this->isPost()) {
            $this->handleCreate();
            return;
        }
        
        $this->render('admin/categories/create', [
            'title' => '添加分类'
        ]);
    }
    
    /**
     * 处理添加分类
     */
    private function handleCreate()
    {
        $data = [
            'name' => trim($this->getPost('name')),
            'slug' => $this->generateSlug($this->getPost('name')),
            'description' => trim($this->getPost('description')),
            'color' => $this->getPost('color', '#3498db'),
            'sort_order' => intval($this->getPost('sort_order', 0))
        ];
        
        // 验证数据
        $errors = $this->validateCategoryData($data);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/categories/create');
            return;
        }
        
        try {
            $this->categoryModel->create($data);
            $this->setFlash('success', '分类添加成功！');
            $this->redirect('admin/categories');
        } catch (Exception $e) {
            $this->setFlash('error', '添加失败：' . $e->getMessage());
            $this->redirect('admin/categories/create');
        }
    }
    
    /**
     * 编辑分类
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $category = $this->categoryModel->getById($id);
        if (!$category) {
            $this->setFlash('error', '分类不存在');
            $this->redirect('admin/categories');
            return;
        }
        
        if ($this->isPost()) {
            $this->handleEdit($id);
            return;
        }
        
        $this->render('admin/categories/edit', [
            'title' => '编辑分类',
            'category' => $category
        ]);
    }
    
    /**
     * 处理编辑分类
     */
    private function handleEdit($id)
    {
        $data = [
            'name' => trim($this->getPost('name')),
            'slug' => $this->generateSlug($this->getPost('name'), $id),
            'description' => trim($this->getPost('description')),
            'color' => $this->getPost('color'),
            'sort_order' => intval($this->getPost('sort_order'))
        ];
        
        // 验证数据
        $errors = $this->validateCategoryData($data, $id);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/categories/edit/' . $id);
            return;
        }
        
        try {
            $this->categoryModel->update($id, $data);
            $this->setFlash('success', '分类更新成功！');
            $this->redirect('admin/categories');
        } catch (Exception $e) {
            $this->setFlash('error', '更新失败：' . $e->getMessage());
            $this->redirect('admin/categories/edit/' . $id);
        }
    }
    
    /**
     * 删除分类
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        try {
            $category = $this->categoryModel->getById($id);
            if (!$category) {
                $this->jsonError('分类不存在', 404);
                return;
            }
            
            // 检查是否有文章使用此分类
            if ($category['post_count'] > 0) {
                $this->jsonError('该分类下还有文章，无法删除', 400);
                return;
            }
            
            $this->categoryModel->delete($id);
            $this->jsonResponse(['success' => true, 'message' => '分类删除成功']);
        } catch (Exception $e) {
            $this->jsonError('删除失败：' . $e->getMessage(), 500);
        }
    }
    
    /**
     * 更新排序
     */
    public function updateSort()
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        $sortData = $this->getPost('sort', []);
        
        if (empty($sortData)) {
            $this->jsonError('排序数据不能为空', 400);
            return;
        }
        
        try {
            foreach ($sortData as $item) {
                $id = intval($item['id']);
                $sortOrder = intval($item['sort_order']);
                
                $this->categoryModel->update($id, ['sort_order' => $sortOrder]);
            }
            
            $this->jsonResponse(['success' => true, 'message' => '排序更新成功']);
        } catch (Exception $e) {
            $this->jsonError('排序更新失败：' . $e->getMessage(), 500);
        }
    }
    
    /**
     * 生成分类别名
     */
    private function generateSlug($name, $excludeId = null)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $slug = trim($slug, '-');
        
        if (empty($slug)) {
            $slug = 'category-' . time();
        }
        
        // 检查别名是否已存在
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->categoryModel->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * 验证分类数据
     */
    private function validateCategoryData($data, $excludeId = null)
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = '分类名称不能为空';
        } elseif (strlen($data['name']) > 100) {
            $errors[] = '分类名称不能超过100个字符';
        } elseif ($this->categoryModel->nameExists($data['name'], $excludeId)) {
            $errors[] = '分类名称已存在';
        }
        
        if (!empty($data['description']) && strlen($data['description']) > 500) {
            $errors[] = '分类描述不能超过500个字符';
        }
        
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $data['color'])) {
            $errors[] = '请输入有效的颜色值';
        }
        
        if ($data['sort_order'] < 0) {
            $errors[] = '排序值不能为负数';
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
