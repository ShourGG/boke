<?php
/**
 * Admin Settings Controller
 * 管理后台设置控制器 - 处理系统设置功能
 */
class AdminSettingsController extends BaseController
{
    private $settingsModel;
    
    public function __construct()
    {
        // Settings will be stored in a simple file or database
        // For now, we'll use a simple approach
    }
    
    /**
     * 设置首页
     */
    public function index()
    {
        $this->requireAuth();
        
        // Get current settings
        $settings = $this->getCurrentSettings();
        
        if ($this->isPost()) {
            $this->handleUpdate();
            return;
        }
        
        $this->render('admin/settings/index', [
            'title' => '系统设置',
            'settings' => $settings
        ]);
    }
    
    /**
     * 处理设置更新
     */
    private function handleUpdate()
    {
        $settings = [
            'site_name' => trim($this->getPost('site_name')),
            'site_description' => trim($this->getPost('site_description')),
            'site_keywords' => trim($this->getPost('site_keywords')),
            'site_url' => trim($this->getPost('site_url')),
            'admin_email' => trim($this->getPost('admin_email')),
            'posts_per_page' => (int)$this->getPost('posts_per_page', 10),
            'websites_per_page' => (int)$this->getPost('websites_per_page', 20),
            'allow_comments' => $this->getPost('allow_comments') ? 1 : 0,
            'comment_moderation' => $this->getPost('comment_moderation') ? 1 : 0,
            'enable_registration' => $this->getPost('enable_registration') ? 1 : 0,
            'maintenance_mode' => $this->getPost('maintenance_mode') ? 1 : 0,
            'admin_theme_preference' => trim($this->getPost('admin_theme_preference', 'system')),
            'google_analytics' => trim($this->getPost('google_analytics')),
            'footer_text' => trim($this->getPost('footer_text'))
        ];
        
        // 验证设置
        $errors = $this->validateSettings($settings);
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/settings');
            return;
        }
        
        try {
            $this->saveSettings($settings);
            $this->setFlash('success', '设置保存成功！');
            $this->redirect('admin/settings');
        } catch (Exception $e) {
            $this->setFlash('error', '保存失败：' . $e->getMessage());
            $this->redirect('admin/settings');
        }
    }
    
    /**
     * 获取当前设置
     */
    private function getCurrentSettings()
    {
        $defaultSettings = [
            'site_name' => defined('SITE_NAME') ? SITE_NAME : 'Koi Blog',
            'site_description' => defined('SITE_DESCRIPTION') ? SITE_DESCRIPTION : '个人博客系统',
            'site_keywords' => defined('SITE_KEYWORDS') ? SITE_KEYWORDS : '博客,个人网站',
            'site_url' => defined('SITE_URL') ? SITE_URL : '',
            'admin_email' => defined('ADMIN_EMAIL') ? ADMIN_EMAIL : '',
            'posts_per_page' => defined('POSTS_PER_PAGE') ? POSTS_PER_PAGE : 10,
            'websites_per_page' => defined('WEBSITES_PER_PAGE') ? WEBSITES_PER_PAGE : 20,
            'allow_comments' => 1,
            'comment_moderation' => 1,
            'enable_registration' => 0,
            'maintenance_mode' => 0,
            'admin_theme_preference' => 'system',
            'google_analytics' => '',
            'footer_text' => '© 2024 Koi Blog. All rights reserved.'
        ];
        
        // Try to load from settings file
        $settingsFile = CONFIG_PATH . '/settings.json';
        if (file_exists($settingsFile)) {
            $savedSettings = json_decode(file_get_contents($settingsFile), true);
            if ($savedSettings) {
                return array_merge($defaultSettings, $savedSettings);
            }
        }
        
        return $defaultSettings;
    }
    
    /**
     * 保存设置
     */
    private function saveSettings($settings)
    {
        $settingsFile = CONFIG_PATH . '/settings.json';
        
        // Ensure config directory exists
        if (!is_dir(CONFIG_PATH)) {
            mkdir(CONFIG_PATH, 0755, true);
        }
        
        $result = file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        if ($result === false) {
            throw new Exception('无法保存设置文件');
        }
        
        // Also update config.php if it exists
        $this->updateConfigFile($settings);
    }
    
    /**
     * 更新配置文件
     */
    private function updateConfigFile($settings)
    {
        $configFile = CONFIG_PATH . '/config.php';
        if (!file_exists($configFile)) {
            return;
        }
        
        $configContent = file_get_contents($configFile);
        
        // Update constants
        $updates = [
            'SITE_NAME' => $settings['site_name'],
            'SITE_DESCRIPTION' => $settings['site_description'],
            'SITE_KEYWORDS' => $settings['site_keywords'],
            'SITE_URL' => $settings['site_url'],
            'ADMIN_EMAIL' => $settings['admin_email'],
            'POSTS_PER_PAGE' => $settings['posts_per_page'],
            'WEBSITES_PER_PAGE' => $settings['websites_per_page']
        ];
        
        foreach ($updates as $constant => $value) {
            $pattern = "/define\s*\(\s*['\"]" . $constant . "['\"]\s*,\s*[^)]+\)/";
            $replacement = "define('{$constant}', " . (is_numeric($value) ? $value : "'" . addslashes($value) . "'") . ")";
            $configContent = preg_replace($pattern, $replacement, $configContent);
        }
        
        file_put_contents($configFile, $configContent);
    }
    
    /**
     * 验证设置
     */
    private function validateSettings($settings)
    {
        $errors = [];
        
        if (empty($settings['site_name'])) {
            $errors[] = '网站名称不能为空';
        }
        
        if (!empty($settings['site_url']) && !filter_var($settings['site_url'], FILTER_VALIDATE_URL)) {
            $errors[] = '网站URL格式不正确';
        }
        
        if (!empty($settings['admin_email']) && !filter_var($settings['admin_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '管理员邮箱格式不正确';
        }
        
        if ($settings['posts_per_page'] < 1 || $settings['posts_per_page'] > 100) {
            $errors[] = '每页文章数量必须在1-100之间';
        }
        
        if ($settings['websites_per_page'] < 1 || $settings['websites_per_page'] > 100) {
            $errors[] = '每页网站数量必须在1-100之间';
        }
        
        return $errors;
    }
    
    /**
     * 清除缓存
     */
    public function clearCache()
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        try {
            // Clear various cache directories
            $cacheDirectories = [
                ROOT_PATH . '/cache',
                ROOT_PATH . '/tmp',
                PUBLIC_PATH . '/cache'
            ];
            
            $clearedFiles = 0;
            foreach ($cacheDirectories as $dir) {
                if (is_dir($dir)) {
                    $files = glob($dir . '/*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            unlink($file);
                            $clearedFiles++;
                        }
                    }
                }
            }
            
            $this->jsonResponse([
                'success' => true,
                'message' => "成功清除 {$clearedFiles} 个缓存文件"
            ]);
        } catch (Exception $e) {
            $this->jsonError('清除缓存失败：' . $e->getMessage(), 500);
        }
    }
    
    /**
     * 备份数据库
     */
    public function backup()
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->jsonError('Invalid request method', 405);
            return;
        }
        
        try {
            $backupDir = ROOT_PATH . '/backups';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . '/' . $filename;
            
            // Simple backup using mysqldump (if available)
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                $filepath
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($filepath)) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => '数据库备份成功',
                    'filename' => $filename,
                    'size' => filesize($filepath)
                ]);
            } else {
                throw new Exception('备份命令执行失败');
            }
        } catch (Exception $e) {
            $this->jsonError('备份失败：' . $e->getMessage(), 500);
        }
    }
}
