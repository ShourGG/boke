<?php
/**
 * Admin Controller
 * Handle admin authentication and dashboard
 */
class AdminController extends BaseController
{
    private $adminModel;
    
    public function __construct()
    {
        $this->adminModel = new Admin();
    }
    
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $this->requireAuth();
        
        // Get dashboard statistics
        $stats = $this->adminModel->getDashboardStats();
        
        // Get recent activities
        $activities = $this->adminModel->getRecentActivities(10);
        
        $this->data = [
            'title' => '管理后台',
            'stats' => $stats,
            'activities' => $activities,
            'currentPage' => 'dashboard'
        ];
        
        $this->render('admin/dashboard', $this->data);
    }
    
    /**
     * Admin login page
     */
    public function login()
    {
        // Redirect if already logged in
        if (isset($_SESSION['admin_id'])) {
            $this->redirect('admin');
            return;
        }
        
        if ($this->isPost()) {
            $this->handleLogin();
            return;
        }
        
        $this->data = [
            'title' => '管理员登录',
            'currentPage' => 'login'
        ];
        
        $this->render('admin/login', $this->data);
    }
    
    /**
     * Handle login form submission
     */
    private function handleLogin()
    {
        $username = trim($this->getPost('username'));
        $password = $this->getPost('password');
        $remember = $this->getPost('remember');
        
        if (empty($username) || empty($password)) {
            $this->setFlash('error', '请输入用户名和密码');
            $this->redirect('admin/login');
            return;
        }
        
        // Attempt authentication
        $admin = $this->adminModel->authenticate($username, $password);
        
        if ($admin) {
            // Set session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_display_name'] = $admin['display_name'] ?: $admin['username'];
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('admin_remember', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 days
                // In production, you should store this token in database
            }
            
            $this->setFlash('success', '登录成功！欢迎回来，' . $admin['display_name']);
            $this->redirect('admin');
        } else {
            $this->setFlash('error', '用户名或密码错误');
            $this->redirect('admin/login');
        }
    }
    
    /**
     * Admin logout
     */
    public function logout()
    {
        // Clear session
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_display_name']);
        
        // Clear remember me cookie
        if (isset($_COOKIE['admin_remember'])) {
            setcookie('admin_remember', '', time() - 3600, '/');
        }
        
        $this->setFlash('success', '已安全退出登录');
        $this->redirect('admin/login');
    }
    
    /**
     * Admin profile page
     */
    public function profile()
    {
        $this->requireAuth();
        
        if ($this->isPost()) {
            $this->handleProfileUpdate();
            return;
        }
        
        // Get admin profile
        $profile = $this->adminModel->getProfile($_SESSION['admin_id']);
        
        $this->data = [
            'title' => '个人资料',
            'profile' => $profile,
            'currentPage' => 'profile'
        ];
        
        $this->render('admin/profile', $this->data);
    }
    
    /**
     * Handle profile update
     */
    private function handleProfileUpdate()
    {
        $data = [
            'display_name' => trim($this->getPost('display_name')),
            'email' => trim($this->getPost('email'))
        ];
        
        $newPassword = $this->getPost('new_password');
        $confirmPassword = $this->getPost('confirm_password');
        
        // Validate data
        $errors = [];
        
        if (empty($data['display_name'])) {
            $errors[] = '显示名称不能为空';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '请输入有效的邮箱地址';
        }
        
        // Check if email exists for other admin
        if ($this->adminModel->emailExists($data['email'], $_SESSION['admin_id'])) {
            $errors[] = '该邮箱已被其他管理员使用';
        }
        
        // Password validation
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                $errors[] = '新密码至少需要6个字符';
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors[] = '两次输入的密码不一致';
            }
        }
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/profile');
            return;
        }
        
        try {
            // Update profile
            $this->adminModel->updateProfile($_SESSION['admin_id'], $data);
            
            // Update password if provided
            if (!empty($newPassword)) {
                $this->adminModel->updatePassword($_SESSION['admin_id'], $newPassword);
            }
            
            // Update session display name
            $_SESSION['admin_display_name'] = $data['display_name'];
            
            $this->setFlash('success', '个人资料更新成功');
            $this->redirect('admin/profile');
        } catch (Exception $e) {
            $this->setFlash('error', '更新失败，请稍后重试');
            $this->redirect('admin/profile');
        }
    }
}
