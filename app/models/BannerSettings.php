<?php

// 确保Database类被加载
require_once __DIR__ . '/../core/Database.php';

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
                    'overlay_opacity' => 0.30,
                    // 副标题样式默认值
                    'subtitle_color' => '#ffffff',
                    'subtitle_font_family' => 'inherit',
                    'subtitle_font_size' => '3rem',
                    'subtitle_font_weight' => '300',
                    'subtitle_text_shadow' => '0 2px 4px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6)',
                    'subtitle_animation' => 'typewriter',
                    'subtitle_gradient_enabled' => 0,
                    'subtitle_gradient_start' => '#ffffff',
                    'subtitle_gradient_end' => '#f8f9fa',
                    'subtitle_gradient_direction' => '135deg'
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
                'overlay_opacity' => 0.30,
                // 副标题样式默认值
                'subtitle_color' => '#ffffff',
                'subtitle_font_family' => 'inherit',
                'subtitle_font_size' => '3rem',
                'subtitle_font_weight' => '300',
                'subtitle_text_shadow' => '0 2px 4px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6)',
                'subtitle_animation' => 'typewriter',
                'subtitle_gradient_enabled' => 0,
                'subtitle_gradient_start' => '#ffffff',
                'subtitle_gradient_end' => '#f8f9fa',
                'subtitle_gradient_direction' => '135deg'
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

            // 副标题样式数据验证
            $subtitleColor = htmlspecialchars($data['subtitle_color'] ?? '#ffffff', ENT_QUOTES, 'UTF-8');
            $subtitleFontFamily = htmlspecialchars($data['subtitle_font_family'] ?? 'inherit', ENT_QUOTES, 'UTF-8');
            $subtitleFontSize = htmlspecialchars($data['subtitle_font_size'] ?? '3rem', ENT_QUOTES, 'UTF-8');
            $subtitleFontWeight = htmlspecialchars($data['subtitle_font_weight'] ?? '300', ENT_QUOTES, 'UTF-8');
            $subtitleTextShadow = htmlspecialchars($data['subtitle_text_shadow'] ?? '0 2px 4px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6)', ENT_QUOTES, 'UTF-8');
            $subtitleAnimation = htmlspecialchars($data['subtitle_animation'] ?? 'typewriter', ENT_QUOTES, 'UTF-8');
            $subtitleGradientEnabled = isset($data['subtitle_gradient_enabled']) ? 1 : 0;
            $subtitleGradientStart = htmlspecialchars($data['subtitle_gradient_start'] ?? '#ffffff', ENT_QUOTES, 'UTF-8');
            $subtitleGradientEnd = htmlspecialchars($data['subtitle_gradient_end'] ?? '#f8f9fa', ENT_QUOTES, 'UTF-8');
            $subtitleGradientDirection = htmlspecialchars($data['subtitle_gradient_direction'] ?? '135deg', ENT_QUOTES, 'UTF-8');

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
                        subtitle_color = ?, subtitle_font_family = ?, subtitle_font_size = ?,
                        subtitle_font_weight = ?, subtitle_text_shadow = ?, subtitle_animation = ?,
                        subtitle_gradient_enabled = ?, subtitle_gradient_start = ?, subtitle_gradient_end = ?,
                        subtitle_gradient_direction = ?, updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                $result = $stmt->execute([
                    $bannerImage, $bannerTitle, $bannerSubtitle,
                    $bannerEnabled, $parallaxEnabled, $overlayOpacity,
                    $subtitleColor, $subtitleFontFamily, $subtitleFontSize,
                    $subtitleFontWeight, $subtitleTextShadow, $subtitleAnimation,
                    $subtitleGradientEnabled, $subtitleGradientStart, $subtitleGradientEnd,
                    $subtitleGradientDirection, $exists['id']
                ]);
                error_log("BannerSettings::updateSettings - Update result: " . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                // Insert new settings
                error_log("BannerSettings::updateSettings - Inserting new record");
                $stmt = $this->db->prepare("
                    INSERT INTO banner_settings
                    (banner_image, banner_title, banner_subtitle, banner_enabled, parallax_enabled, overlay_opacity,
                     subtitle_color, subtitle_font_family, subtitle_font_size, subtitle_font_weight,
                     subtitle_text_shadow, subtitle_animation, subtitle_gradient_enabled,
                     subtitle_gradient_start, subtitle_gradient_end, subtitle_gradient_direction)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $result = $stmt->execute([
                    $bannerImage, $bannerTitle, $bannerSubtitle,
                    $bannerEnabled, $parallaxEnabled, $overlayOpacity,
                    $subtitleColor, $subtitleFontFamily, $subtitleFontSize,
                    $subtitleFontWeight, $subtitleTextShadow, $subtitleAnimation,
                    $subtitleGradientEnabled, $subtitleGradientStart, $subtitleGradientEnd,
                    $subtitleGradientDirection
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
