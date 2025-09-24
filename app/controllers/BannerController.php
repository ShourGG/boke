<?php

require_once 'app/models/BannerSettings.php';

/**
 * Banner Management Controller
 * Handles banner configuration in admin panel
 */
class BannerController extends BaseController
{
    private $bannerSettings;

    public function __construct()
    {
        parent::__construct();
        $this->bannerSettings = new BannerSettings();
    }
    
    /**
     * Display banner settings page
     */
    public function index()
    {
        // Check admin authentication
        $this->requireAuth();

        $settings = $this->bannerSettings->getSettings();

        $this->data['title'] = 'Banner设置 - ' . SITE_NAME;
        $this->data['settings'] = $settings;

        $this->render('admin/banner/index', $this->data);
    }
    
    /**
     * Update banner settings
     */
    public function update()
    {
        // Check admin authentication
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = $_POST;

                // Debug: Log received POST data
                error_log("BannerController::update - Received POST data: " . print_r($data, true));
                error_log("BannerController::update - Received FILES data: " . print_r($_FILES, true));

                // Handle file upload if provided
                if (isset($_FILES['banner_image_file']) && $_FILES['banner_image_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadedUrl = $this->bannerSettings->uploadBannerImage($_FILES['banner_image_file']);
                    if ($uploadedUrl) {
                        $data['banner_image'] = $uploadedUrl;
                        error_log("BannerController::update - File uploaded successfully: " . $uploadedUrl);
                    } else {
                        throw new Exception('Failed to upload banner image.');
                    }
                }

                $result = $this->bannerSettings->updateSettings($data);

                error_log("BannerController::update - Update result: " . ($result ? 'SUCCESS' : 'FAILED'));

                if ($result) {
                    $this->setFlash('success', 'Banner设置已成功更新！');
                } else {
                    $this->setFlash('error', 'Banner设置更新失败，请重试。');
                }
            } catch (Exception $e) {
                error_log("BannerController::update - Exception: " . $e->getMessage());
                $this->setFlash('error', 'Error: ' . $e->getMessage());
            }
        }

        $this->redirect('/admin/banner');
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

            $this->data['title'] = 'Banner预览 - ' . SITE_NAME;
            $this->data['settings'] = $settings;
            $this->data['preview_mode'] = true;

            $this->render('admin/banner/preview', $this->data);
        } else {
            $this->redirect('/admin/banner');
        }
    }
}
