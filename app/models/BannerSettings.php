<?php

/**
 * Banner Settings Model
 * Manages banner configuration and display settings
 */
class BannerSettings
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get current banner settings
     */
    public function getSettings()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Return default settings if none exist
            if (!$result) {
                return [
                    'banner_image' => 'https://rmt.ladydaily.com/fetch/fluid/storage/banner.png',
                    'banner_title' => '',
                    'banner_subtitle' => SITE_DESCRIPTION,
                    'banner_enabled' => 1,
                    'parallax_enabled' => 0,
                    'overlay_opacity' => 0.30
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Banner settings fetch error: " . $e->getMessage());
            return [
                'banner_image' => 'https://rmt.ladydaily.com/fetch/fluid/storage/banner.png',
                'banner_title' => '',
                'banner_subtitle' => SITE_DESCRIPTION,
                'banner_enabled' => 1,
                'parallax_enabled' => 0,
                'overlay_opacity' => 0.30
            ];
        }
    }
    
    /**
     * Update banner settings
     */
    public function updateSettings($data)
    {
        try {
            // Validate data
            $bannerImage = filter_var($data['banner_image'] ?? '', FILTER_SANITIZE_URL);
            $bannerTitle = htmlspecialchars($data['banner_title'] ?? '', ENT_QUOTES, 'UTF-8');
            $bannerSubtitle = htmlspecialchars($data['banner_subtitle'] ?? '', ENT_QUOTES, 'UTF-8');
            $bannerEnabled = isset($data['banner_enabled']) ? 1 : 0;
            $parallaxEnabled = isset($data['parallax_enabled']) ? 1 : 0;
            $overlayOpacity = floatval($data['overlay_opacity'] ?? 0.30);
            
            // Ensure opacity is within valid range
            $overlayOpacity = max(0, min(1, $overlayOpacity));
            
            // Check if settings exist
            $stmt = $this->db->prepare("SELECT id FROM banner_settings LIMIT 1");
            $stmt->execute();
            $exists = $stmt->fetch();
            
            if ($exists) {
                // Update existing settings
                $stmt = $this->db->prepare("
                    UPDATE banner_settings 
                    SET banner_image = ?, banner_title = ?, banner_subtitle = ?, 
                        banner_enabled = ?, parallax_enabled = ?, overlay_opacity = ?,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                $result = $stmt->execute([
                    $bannerImage, $bannerTitle, $bannerSubtitle, 
                    $bannerEnabled, $parallaxEnabled, $overlayOpacity,
                    $exists['id']
                ]);
            } else {
                // Insert new settings
                $stmt = $this->db->prepare("
                    INSERT INTO banner_settings 
                    (banner_image, banner_title, banner_subtitle, banner_enabled, parallax_enabled, overlay_opacity)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $result = $stmt->execute([
                    $bannerImage, $bannerTitle, $bannerSubtitle, 
                    $bannerEnabled, $parallaxEnabled, $overlayOpacity
                ]);
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Banner settings update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Upload banner image
     */
    public function uploadBannerImage($file)
    {
        try {
            $uploadDir = 'public/uploads/banners/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Validate file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
            }
            
            // Check file size (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                throw new Exception('File size too large. Maximum 5MB allowed.');
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'banner_' . time() . '_' . uniqid() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                return SITE_URL . '/' . $filepath;
            } else {
                throw new Exception('Failed to upload file.');
            }
        } catch (Exception $e) {
            error_log("Banner image upload error: " . $e->getMessage());
            return false;
        }
    }
}
