<?php
/**
 * Website Controller
 * Handle website directory functionality
 */
class WebsiteController extends BaseController
{
    private $websiteModel;
    private $websiteCategoryModel;
    
    public function __construct()
    {
        $this->websiteModel = new Website();
        $this->websiteCategoryModel = new WebsiteCategory();
    }
    
    /**
     * Display website directory homepage
     */
    public function index()
    {
        $page = (int)$this->getGet('page', 1);
        $perPage = defined('WEBSITES_PER_PAGE') ? WEBSITES_PER_PAGE : 20;
        $categoryId = $this->getGet('category');
        $search = $this->getGet('search');
        
        // Handle search
        if ($search) {
            $websites = $this->websiteModel->search($search, $page, $perPage);
            $pageTitle = '搜索结果：' . htmlspecialchars($search);
        } else {
            $websites = $this->websiteModel->getWithCategory($page, $perPage, $categoryId);
            $pageTitle = '网站收录';
        }
        
        // Get categories for navigation
        $categories = $this->websiteCategoryModel->getAllWithWebsiteCount();
        
        // Get featured websites
        $featuredWebsites = $this->websiteModel->getFeatured(6);
        
        // Get current category info if filtering
        $currentCategory = null;
        if ($categoryId) {
            $currentCategory = $this->websiteCategoryModel->find($categoryId);
            if ($currentCategory) {
                $pageTitle = $currentCategory['name'] . ' - 网站收录';
            }
        }
        
        $this->data = [
            'title' => $pageTitle,
            'description' => '精选优质网站收录目录，发现有趣实用的网站资源',
            'websites' => $websites,
            'categories' => $categories,
            'featuredWebsites' => $featuredWebsites,
            'currentCategory' => $currentCategory,
            'searchQuery' => $search,
            'currentPage' => 'websites'
        ];
        
        $this->render('website/index', $this->data);
    }
    
    /**
     * Display single website details
     */
    public function show()
    {
        $id = (int)$this->getParam('id');
        
        if (!$id) {
            $this->redirect('websites');
            return;
        }
        
        // Get website with category info
        $website = $this->websiteModel->getWithCategoryById($id);
        
        if (!$website || $website['status'] !== 'approved') {
            $this->redirect('websites');
            return;
        }
        
        // Increment click count
        $this->websiteModel->incrementClicks($id);
        $website['click_count']++;
        
        // Get related websites from same category
        $relatedWebsites = [];
        if ($website['category_id']) {
            $relatedWebsites = $this->websiteModel->getApproved(1, 6, $website['category_id']);
            // Remove current website from related list
            $relatedWebsites['data'] = array_filter($relatedWebsites['data'], function($w) use ($id) {
                return $w['id'] != $id;
            });
            $relatedWebsites['data'] = array_slice($relatedWebsites['data'], 0, 5);
        }
        
        // Get recent websites
        $recentWebsites = $this->websiteModel->getRecent(5);
        
        $this->data = [
            'title' => $website['meta_title'] ?: $website['title'],
            'description' => $website['meta_description'] ?: $website['description'],
            'canonical' => SITE_URL . '/website/' . $website['id'],
            'website' => $website,
            'relatedWebsites' => $relatedWebsites['data'] ?? [],
            'recentWebsites' => $recentWebsites,
            'currentPage' => 'website'
        ];
        
        $this->render('website/show', $this->data);
    }
    
    /**
     * Handle website click redirect
     */
    public function click()
    {
        $id = (int)$this->getGet('id');
        
        if (!$id) {
            $this->redirect('websites');
            return;
        }
        
        $website = $this->websiteModel->find($id);
        
        if (!$website || $website['status'] !== 'approved') {
            $this->redirect('websites');
            return;
        }
        
        // Increment click count
        $this->websiteModel->incrementClicks($id);
        
        // Redirect to external website
        header('Location: ' . $website['url'], true, 302);
        exit;
    }
    
    /**
     * Display website submission form
     */
    public function submit()
    {
        if ($this->isPost()) {
            $this->handleSubmission();
            return;
        }
        
        // Get categories for form
        $categories = $this->websiteCategoryModel->findAll([], 'sort_order ASC, name ASC');
        
        $this->data = [
            'title' => '提交网站',
            'description' => '提交您的网站到我们的收录目录',
            'categories' => $categories,
            'currentPage' => 'submit'
        ];
        
        $this->render('website/submit', $this->data);
    }
    
    /**
     * Handle website submission
     */
    private function handleSubmission()
    {
        $data = [
            'title' => trim($this->getPost('title')),
            'url' => trim($this->getPost('url')),
            'description' => trim($this->getPost('description')),
            'category_id' => (int)$this->getPost('category_id'),
            'tags' => trim($this->getPost('tags')),
            'submitter_name' => trim($this->getPost('submitter_name')),
            'submitter_email' => trim($this->getPost('submitter_email'))
        ];
        
        // Basic validation
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = '网站标题不能为空';
        }
        
        if (empty($data['url']) || !filter_var($data['url'], FILTER_VALIDATE_URL)) {
            $errors[] = '请输入有效的网站URL';
        }
        
        if (empty($data['description'])) {
            $errors[] = '网站描述不能为空';
        }
        
        if (empty($data['submitter_name'])) {
            $errors[] = '提交者姓名不能为空';
        }
        
        if (empty($data['submitter_email']) || !filter_var($data['submitter_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '请输入有效的邮箱地址';
        }
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('websites/submit');
            return;
        }
        
        try {
            $this->websiteModel->submit($data);
            $this->setFlash('success', '网站提交成功！我们会尽快审核您的提交。');
            $this->redirect('websites');
        } catch (Exception $e) {
            $this->setFlash('error', '提交失败，请稍后重试。');
            $this->redirect('websites/submit');
        }
    }
}
