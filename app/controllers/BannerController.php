<?php

require_once 'app/models/BannerSettings.php';

/**
 * Banner Management Controller
 * Handles banner configuration in admin panel
 */
class BannerController
{
    private $bannerSettings;
    
    public function __construct()
    {
        $this->bannerSettings = new BannerSettings();
    }
    
    /**
     * Display banner settings page
     */
    public function index()
    {
        // Check admin authentication
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $settings = $this->bannerSettings->getSettings();
        
        $data = [
            'title' => 'Banner设置 - ' . SITE_NAME,
            'settings' => $settings
        ];
        
        include 'app/views/admin/banner/index.php';
    }
    
    /**
     * Update banner settings
     */
    public function update()
    {
        // Check admin authentication
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = $_POST;
                
                // Handle file upload if provided
                if (isset($_FILES['banner_image_file']) && $_FILES['banner_image_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadedUrl = $this->bannerSettings->uploadBannerImage($_FILES['banner_image_file']);
                    if ($uploadedUrl) {
                        $data['banner_image'] = $uploadedUrl;
                    } else {
                        throw new Exception('Failed to upload banner image.');
                    }
                }
                
                $result = $this->bannerSettings->updateSettings($data);
                
                if ($result) {
                    $_SESSION['flash']['success'] = 'Banner设置已成功更新！';
                } else {
                    $_SESSION['flash']['error'] = 'Banner设置更新失败，请重试。';
                }
            } catch (Exception $e) {
                $_SESSION['flash']['error'] = 'Error: ' . $e->getMessage();
            }
        }
        
        header('Location: ' . SITE_URL . '/admin/banner');
        exit;
    }
    
    /**
     * Get banner settings as JSON (for AJAX)
     */
    public function getSettings()
    {
        header('Content-Type: application/json');
        echo json_encode($this->bannerSettings->getSettings());
        exit;
    }
    
    /**
     * Preview banner with new settings
     */
    public function preview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = $_POST;
            
            // Sanitize settings for preview
            $settings['banner_image'] = filter_var($settings['banner_image'] ?? '', FILTER_SANITIZE_URL);
            $settings['banner_title'] = htmlspecialchars($settings['banner_title'] ?? '', ENT_QUOTES, 'UTF-8');
            $settings['banner_subtitle'] = htmlspecialchars($settings['banner_subtitle'] ?? '', ENT_QUOTES, 'UTF-8');
            $settings['overlay_opacity'] = floatval($settings['overlay_opacity'] ?? 0.30);
            
            $data = [
                'title' => 'Banner预览 - ' . SITE_NAME,
                'settings' => $settings,
                'preview_mode' => true
            ];
            
            include 'app/views/admin/banner/preview.php';
        } else {
            header('Location: ' . SITE_URL . '/admin/banner');
            exit;
        }
    }
}
