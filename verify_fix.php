<?php
/**
 * 验证修复脚本
 * 检查关键修复是否已应用
 */

echo "=== Koi Blog 修复验证脚本 ===\n\n";

$errors = [];
$warnings = [];

// 检查1：BaseController构造函数
echo "1. 检查BaseController构造函数...\n";
$baseControllerFile = __DIR__ . '/app/core/BaseController.php';
if (file_exists($baseControllerFile)) {
    $content = file_get_contents($baseControllerFile);
    if (strpos($content, 'public function __construct()') !== false) {
        echo "   ✅ BaseController构造函数存在\n";
    } else {
        $errors[] = "BaseController缺少构造函数";
        echo "   ❌ BaseController缺少构造函数\n";
    }
} else {
    $errors[] = "BaseController文件不存在";
    echo "   ❌ BaseController文件不存在\n";
}

// 检查2：HomeController分页修复
echo "\n2. 检查HomeController分页修复...\n";
$homeControllerFile = __DIR__ . '/app/controllers/HomeController.php';
if (file_exists($homeControllerFile)) {
    $content = file_get_contents($homeControllerFile);
    if (strpos($content, 'max(1, intval($this->getGet(\'page\'') !== false) {
        echo "   ✅ HomeController分页参数已修复\n";
    } else {
        $errors[] = "HomeController分页参数未修复";
        echo "   ❌ HomeController分页参数未修复\n";
    }
} else {
    $errors[] = "HomeController文件不存在";
    echo "   ❌ HomeController文件不存在\n";
}

// 检查3：WebsiteController分页修复
echo "\n3. 检查WebsiteController分页修复...\n";
$websiteControllerFile = __DIR__ . '/app/controllers/WebsiteController.php';
if (file_exists($websiteControllerFile)) {
    $content = file_get_contents($websiteControllerFile);
    if (strpos($content, 'max(1, intval($this->getGet(\'page\'') !== false) {
        echo "   ✅ WebsiteController分页参数已修复\n";
    } else {
        $errors[] = "WebsiteController分页参数未修复";
        echo "   ❌ WebsiteController分页参数未修复\n";
    }
} else {
    $errors[] = "WebsiteController文件不存在";
    echo "   ❌ WebsiteController文件不存在\n";
}

// 检查4：CategoryController分页修复
echo "\n4. 检查CategoryController分页修复...\n";
$categoryControllerFile = __DIR__ . '/app/controllers/CategoryController.php';
if (file_exists($categoryControllerFile)) {
    $content = file_get_contents($categoryControllerFile);
    $maxCount = substr_count($content, 'max(1, intval($this->getGet(\'page\'');
    if ($maxCount >= 2) {
        echo "   ✅ CategoryController分页参数已修复 (找到{$maxCount}处)\n";
    } else {
        $errors[] = "CategoryController分页参数修复不完整";
        echo "   ❌ CategoryController分页参数修复不完整 (只找到{$maxCount}处，应该有2处)\n";
    }
} else {
    $errors[] = "CategoryController文件不存在";
    echo "   ❌ CategoryController文件不存在\n";
}

// 检查5：TagController分页修复
echo "\n5. 检查TagController分页修复...\n";
$tagControllerFile = __DIR__ . '/app/controllers/TagController.php';
if (file_exists($tagControllerFile)) {
    $content = file_get_contents($tagControllerFile);
    $maxCount = substr_count($content, 'max(1, intval($this->getGet(\'page\'');
    if ($maxCount >= 2) {
        echo "   ✅ TagController分页参数已修复 (找到{$maxCount}处)\n";
    } else {
        $errors[] = "TagController分页参数修复不完整";
        echo "   ❌ TagController分页参数修复不完整 (只找到{$maxCount}处，应该有2处)\n";
    }
} else {
    $errors[] = "TagController文件不存在";
    echo "   ❌ TagController文件不存在\n";
}

// 检查6：SearchController构造函数调用
echo "\n6. 检查SearchController构造函数调用...\n";
$searchControllerFile = __DIR__ . '/app/controllers/SearchController.php';
if (file_exists($searchControllerFile)) {
    $content = file_get_contents($searchControllerFile);
    if (strpos($content, 'parent::__construct()') !== false) {
        echo "   ✅ SearchController调用parent::__construct()\n";
    } else {
        $warnings[] = "SearchController没有调用parent::__construct()";
        echo "   ⚠️  SearchController没有调用parent::__construct()\n";
    }
} else {
    $errors[] = "SearchController文件不存在";
    echo "   ❌ SearchController文件不存在\n";
}

// 总结
echo "\n=== 验证结果 ===\n";
if (empty($errors)) {
    echo "🎉 所有关键修复都已应用！\n";
    echo "✅ BaseController构造函数问题已解决\n";
    echo "✅ 分页参数SQL错误问题已解决\n";
    echo "\n现在可以安全访问网站了！\n";
} else {
    echo "❌ 发现 " . count($errors) . " 个错误需要修复：\n";
    foreach ($errors as $error) {
        echo "   - {$error}\n";
    }
    echo "\n请按照DEPLOYMENT_FIX.md中的指南进行修复！\n";
}

if (!empty($warnings)) {
    echo "\n⚠️  发现 " . count($warnings) . " 个警告：\n";
    foreach ($warnings as $warning) {
        echo "   - {$warning}\n";
    }
}

echo "\n=== 测试建议 ===\n";
echo "修复完成后，请测试以下URL：\n";
echo "- 首页：/?page=0 (应该正常显示，不报错)\n";
echo "- 搜索：/search?q=test (应该正常显示)\n";
echo "- 分类：/category/tech?page=-1 (应该正常显示)\n";
echo "- 标签：/tag/php?page=0 (应该正常显示)\n";
echo "- API：/api/posts?page=0 (应该返回JSON)\n";

echo "\n脚本执行完成。\n";
?>
