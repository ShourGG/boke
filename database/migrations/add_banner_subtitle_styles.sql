-- Banner副标题样式配置扩展
-- 添加副标题样式相关字段

ALTER TABLE banner_settings 
ADD COLUMN subtitle_color VARCHAR(20) DEFAULT '#ffffff' COMMENT '副标题文字颜色',
ADD COLUMN subtitle_font_family VARCHAR(100) DEFAULT 'inherit' COMMENT '副标题字体家族',
ADD COLUMN subtitle_font_size VARCHAR(20) DEFAULT '3rem' COMMENT '副标题字体大小',
ADD COLUMN subtitle_font_weight VARCHAR(20) DEFAULT '300' COMMENT '副标题字体粗细',
ADD COLUMN subtitle_text_shadow VARCHAR(200) DEFAULT '0 2px 4px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6)' COMMENT '副标题文字阴影',
ADD COLUMN subtitle_animation VARCHAR(50) DEFAULT 'typewriter' COMMENT '副标题动画效果',
ADD COLUMN subtitle_gradient_enabled TINYINT(1) DEFAULT 0 COMMENT '是否启用渐变色',
ADD COLUMN subtitle_gradient_start VARCHAR(20) DEFAULT '#ffffff' COMMENT '渐变起始颜色',
ADD COLUMN subtitle_gradient_end VARCHAR(20) DEFAULT '#f8f9fa' COMMENT '渐变结束颜色',
ADD COLUMN subtitle_gradient_direction VARCHAR(20) DEFAULT '135deg' COMMENT '渐变方向';

-- 更新现有记录的默认值
UPDATE banner_settings SET 
    subtitle_color = '#ffffff',
    subtitle_font_family = 'inherit',
    subtitle_font_size = '3rem',
    subtitle_font_weight = '300',
    subtitle_text_shadow = '0 2px 4px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6)',
    subtitle_animation = 'typewriter',
    subtitle_gradient_enabled = 0,
    subtitle_gradient_start = '#ffffff',
    subtitle_gradient_end = '#f8f9fa',
    subtitle_gradient_direction = '135deg'
WHERE subtitle_color IS NULL;
