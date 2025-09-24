-- Banner设置表
CREATE TABLE IF NOT EXISTS banner_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    banner_image VARCHAR(500) DEFAULT 'https://rmt.ladydaily.com/fetch/fluid/storage/banner.png',
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
    'https://rmt.ladydaily.com/fetch/fluid/storage/banner.png',
    '',
    'A personal blog with website directory123',
    1
) ON DUPLICATE KEY UPDATE id=id;
