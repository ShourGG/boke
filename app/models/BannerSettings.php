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
                    'banner_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
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
                'banner_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
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
            // Debug: Log received data
            error_log("BannerSettings::updateSettings - Received data: " . print_r($data, true));

            // Validate data
            $bannerImage = filter_var($data['banner_image'] ?? '', FILTER_SANITIZE_URL);
            $bannerTitle = htmlspecialchars($data['banner_title'] ?? '', ENT_QUOTES, 'UTF-8');
            $bannerSubtitle = htmlspecialchars($data['banner_subtitle'] ?? '', ENT_QUOTES, 'UTF-8');
            $bannerEnabled = isset($data['banner_enabled']) ? 1 : 0;
            $parallaxEnabled = isset($data['parallax_enabled']) ? 1 : 0;
            $overlayOpacity = floatval($data['overlay_opacity'] ?? 0.30);

            // Ensure opacity is within valid range
            $overlayOpacity = max(0, min(1, $overlayOpacity));

            // Debug: Log processed data
            error_log("BannerSettings::updateSettings - Processed data: " . json_encode([
                'banner_image' => $bannerImage,
                'banner_title' => $bannerTitle,
                'banner_subtitle' => $bannerSubtitle,
                'banner_enabled' => $bannerEnabled,
                'parallax_enabled' => $parallaxEnabled,
                'overlay_opacity' => $overlayOpacity
            ]));

            // Check if settings exist (use same logic as getSettings - get latest record)
            $stmt = $this->db->prepare("SELECT id FROM banner_settings ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $exists = $stmt->fetch();

            if ($exists) {
                // Update existing settings
                error_log("BannerSettings::updateSettings - Updating existing record with ID: " . $exists['id']);
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
                error_log("BannerSettings::updateSettings - Update result: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                // Insert new settings
                error_log("BannerSettings::updateSettings - Inserting new record");
                $stmt = $this->db->prepare("
                    INSERT INTO banner_settings
                    (banner_image, banner_title, banner_subtitle, banner_enabled, parallax_enabled, overlay_opacity)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $result = $stmt->execute([
                    $bannerImage, $bannerTitle, $bannerSubtitle,
                    $bannerEnabled, $parallaxEnabled, $overlayOpacity
                ]);
                error_log("BannerSettings::updateSettings - Insert result: " . ($result ? 'SUCCESS' : 'FAILED'));
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
