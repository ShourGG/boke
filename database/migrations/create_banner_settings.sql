-- Banner设置表
CREATE TABLE IF NOT EXISTS banner_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    banner_image VARCHAR(500) DEFAULT 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
    banner_title VARCHAR(200) DEFAULT '',
    banner_subtitle VARCHAR(500) DEFAULT '',
    banner_enabled TINYINT(1) DEFAULT 1,
    parallax_enabled TINYINT(1) DEFAULT 0,
    overlay_opacity DECIMAL(3,2) DEFAULT 0.30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 插入默认设置
INSERT INTO banner_settings (banner_image, banner_title, banner_subtitle, banner_enabled)
VALUES (
    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
    '',
    'A personal blog with website directory123',
    1
) ON DUPLICATE KEY UPDATE id=id;
