<?php
/**
 * Banner副标题样式配置测试页面
 * @author Old Wang
 * @version 1.0
 */

require_once 'config/database.php';

echo "<h2>🎨 Banner副标题样式配置测试</h2>";

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "<p>✅ 数据库连接成功</p>";
    
    // 检查表结构
    echo "<h3>📊 数据库表结构检查</h3>";
    $stmt = $pdo->query("SHOW COLUMNS FROM banner_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $requiredFields = [
        'subtitle_color', 'subtitle_font_family', 'subtitle_font_size', 
        'subtitle_font_weight', 'subtitle_text_shadow', 'subtitle_animation',
        'subtitle_gradient_enabled', 'subtitle_gradient_start', 
        'subtitle_gradient_end', 'subtitle_gradient_direction'
    ];
    
    $existingFields = array_column($columns, 'Field');
    
    echo "<div style='display: flex; gap: 20px;'>";
    echo "<div>";
    echo "<h4>✅ 已存在字段：</h4>";
    echo "<ul>";
    foreach ($existingFields as $field) {
        echo "<li>$field</li>";
    }
    echo "</ul>";
    echo "</div>";
    
    echo "<div>";
    echo "<h4>🔍 样式字段检查：</h4>";
    echo "<ul>";
    foreach ($requiredFields as $field) {
        if (in_array($field, $existingFields)) {
            echo "<li style='color: green;'>✅ $field - 已存在</li>";
        } else {
            echo "<li style='color: red;'>❌ $field - 缺失</li>";
        }
    }
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    
    // 检查是否需要迁移
    $needsMigration = false;
    foreach ($requiredFields as $field) {
        if (!in_array($field, $existingFields)) {
            $needsMigration = true;
            break;
        }
    }
    
    if ($needsMigration) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>⚠️ 需要执行数据库迁移</h4>";
        echo "<p>检测到缺失的样式配置字段，需要执行迁移脚本。</p>";
        echo "<p><a href='migrate_banner_styles.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>执行数据库迁移</a></p>";
        echo "</div>";
    } else {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>✅ 数据库结构完整</h4>";
        echo "<p>所有样式配置字段都已存在，可以正常使用Banner样式配置功能。</p>";
        echo "</div>";
        
        // 显示当前设置
        $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($settings) {
            echo "<h3>🎯 当前Banner样式设置：</h3>";
            echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
            echo "<table style='width: 100%; border-collapse: collapse;'>";
            echo "<tr><th style='text-align: left; padding: 8px; border-bottom: 1px solid #ddd;'>配置项</th><th style='text-align: left; padding: 8px; border-bottom: 1px solid #ddd;'>当前值</th></tr>";
            
            $styleFields = [
                'subtitle_color' => '文字颜色',
                'subtitle_font_family' => '字体家族',
                'subtitle_font_size' => '字体大小',
                'subtitle_font_weight' => '字体粗细',
                'subtitle_text_shadow' => '文字阴影',
                'subtitle_animation' => '动画效果',
                'subtitle_gradient_enabled' => '渐变启用',
                'subtitle_gradient_start' => '渐变起始色',
                'subtitle_gradient_end' => '渐变结束色',
                'subtitle_gradient_direction' => '渐变方向'
            ];
            
            foreach ($styleFields as $field => $label) {
                $value = $settings[$field] ?? '未设置';
                if ($field === 'subtitle_gradient_enabled') {
                    $value = $value ? '是' : '否';
                }
                echo "<tr><td style='padding: 8px; border-bottom: 1px solid #eee;'>$label</td><td style='padding: 8px; border-bottom: 1px solid #eee;'>$value</td></tr>";
            }
            echo "</table>";
            echo "</div>";
            
            // 样式预览
            echo "<h3>🎨 样式预览：</h3>";
            echo "<div style='background: #333; padding: 30px; text-align: center; border-radius: 8px; margin: 20px 0;'>";
            
            $previewStyle = "
                color: " . ($settings['subtitle_color'] ?? '#ffffff') . ";
                font-family: " . ($settings['subtitle_font_family'] ?? 'inherit') . ";
                font-size: " . ($settings['subtitle_font_size'] ?? '2rem') . ";
                font-weight: " . ($settings['subtitle_font_weight'] ?? '300') . ";
                text-shadow: " . ($settings['subtitle_text_shadow'] ?? 'none') . ";
            ";
            
            if (($settings['subtitle_gradient_enabled'] ?? 0)) {
                $previewStyle .= "
                    background: linear-gradient(" . ($settings['subtitle_gradient_direction'] ?? '135deg') . ", " . ($settings['subtitle_gradient_start'] ?? '#ffffff') . " 0%, " . ($settings['subtitle_gradient_end'] ?? '#f8f9fa') . " 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                ";
            }
            
            echo "<div style='$previewStyle'>";
            echo htmlspecialchars($settings['banner_subtitle'] ?: 'A personal blog with website directory');
            echo "</div>";
            echo "<small style='color: #ccc; margin-top: 10px; display: block;'>这是副标题样式的预览效果</small>";
            echo "</div>";
        }
        
        echo "<p><a href='/admin/banner' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>前往Banner管理</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ 数据库错误: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ 测试错误: " . $e->getMessage() . "</p>";
}
?>
