<?php
/**
 * Banner副标题样式配置数据库迁移脚本
 * @author Old Wang
 * @version 1.0
 */

require_once 'config/database.php';

echo "<h2>🎨 Banner副标题样式配置迁移</h2>";

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "<p>✅ 数据库连接成功</p>";
    
    // 检查是否已经存在新字段
    $stmt = $pdo->query("SHOW COLUMNS FROM banner_settings LIKE 'subtitle_color'");
    if ($stmt->rowCount() > 0) {
        echo "<p>⚠️ 副标题样式字段已存在，跳过迁移</p>";
        exit;
    }
    
    echo "<p>🔧 开始添加副标题样式字段...</p>";
    
    // 执行迁移SQL
    $migrationSQL = file_get_contents('database/migrations/add_banner_subtitle_styles.sql');
    
    // 分割SQL语句（按分号分割）
    $statements = array_filter(array_map('trim', explode(';', $migrationSQL)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            echo "<p>执行: " . substr($statement, 0, 50) . "...</p>";
            $pdo->exec($statement);
        }
    }
    
    echo "<p>✅ 副标题样式字段添加成功！</p>";
    
    // 验证字段是否添加成功
    $stmt = $pdo->query("SHOW COLUMNS FROM banner_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $newFields = [
        'subtitle_color', 'subtitle_font_family', 'subtitle_font_size', 
        'subtitle_font_weight', 'subtitle_text_shadow', 'subtitle_animation',
        'subtitle_gradient_enabled', 'subtitle_gradient_start', 
        'subtitle_gradient_end', 'subtitle_gradient_direction'
    ];
    
    echo "<h3>📊 字段验证结果：</h3>";
    echo "<ul>";
    foreach ($newFields as $field) {
        if (in_array($field, $columns)) {
            echo "<li>✅ $field - 已添加</li>";
        } else {
            echo "<li>❌ $field - 添加失败</li>";
        }
    }
    echo "</ul>";
    
    // 显示当前Banner设置
    $stmt = $pdo->query("SELECT * FROM banner_settings ORDER BY id DESC LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        echo "<h3>🎯 当前Banner设置：</h3>";
        echo "<ul>";
        echo "<li>副标题颜色: " . ($settings['subtitle_color'] ?? '未设置') . "</li>";
        echo "<li>字体家族: " . ($settings['subtitle_font_family'] ?? '未设置') . "</li>";
        echo "<li>字体大小: " . ($settings['subtitle_font_size'] ?? '未设置') . "</li>";
        echo "<li>字体粗细: " . ($settings['subtitle_font_weight'] ?? '未设置') . "</li>";
        echo "<li>动画效果: " . ($settings['subtitle_animation'] ?? '未设置') . "</li>";
        echo "<li>渐变启用: " . (($settings['subtitle_gradient_enabled'] ?? 0) ? '是' : '否') . "</li>";
        echo "</ul>";
    }
    
    echo "<p>🎉 <strong>迁移完成！现在可以在Banner管理中配置副标题样式了！</strong></p>";
    echo "<p><a href='/admin/banner' class='btn btn-primary'>前往Banner管理</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ 数据库错误: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ 迁移错误: " . $e->getMessage() . "</p>";
}
?>
