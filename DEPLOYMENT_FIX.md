# 🚨 紧急修复部署指南

## 问题描述
服务器出现致命错误：`Cannot call constructor in SearchController.php:13`

## 错误原因
- SearchController调用`parent::__construct()`
- 但服务器上的BaseController没有构造函数
- 本地代码已修复，但服务器代码未更新

## 🔧 立即修复步骤

### 方案1：服务器拉取最新代码（推荐）
```bash
# 在服务器上执行
cd /www/wwwroot/38.12.4.139/
git pull origin master
# 如果有冲突，强制更新
git reset --hard origin/master
```

### 🚨 如果git不可用，请手动修复以下文件：

#### 修复1：BaseController.php构造函数
**文件路径**：`/www/wwwroot/38.12.4.139/app/core/BaseController.php`
**在第10行后添加以下构造函数**：
```php
    /**
     * Base constructor
     * Initialize common functionality for all controllers
     */
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Set default timezone
        if (!ini_get('date.timezone')) {
            date_default_timezone_set('Asia/Shanghai');
        }

        // Initialize flash messages in data
        $this->data['flash'] = $this->getFlash();
    }
```

#### 修复2：分页参数验证
**文件1**：`/www/wwwroot/38.12.4.139/app/controllers/HomeController.php`
**修改第24行**：
```php
// 原来：$page = (int)$this->getGet('page', 1);
// 改为：
$page = max(1, intval($this->getGet('page', 1)));
```

**文件2**：`/www/wwwroot/38.12.4.139/app/controllers/WebsiteController.php`
**修改第22行**：
```php
// 原来：$page = (int)$this->getGet('page', 1);
// 改为：
$page = max(1, intval($this->getGet('page', 1)));
```

**文件3**：`/www/wwwroot/38.12.4.139/app/controllers/CategoryController.php`
**修改第38行和第110行**：
```php
// 第38行，原来：$page = (int)$this->getGet('page', 1);
// 改为：
$page = max(1, intval($this->getGet('page', 1)));

// 第110行，原来：$page = (int)$this->getGet('page', 1);
// 改为：
$page = max(1, intval($this->getGet('page', 1)));
```

**文件4**：`/www/wwwroot/38.12.4.139/app/controllers/TagController.php`
**修改第38行和第129行**：
```php
// 第38行，原来：$page = (int)$this->getGet('page', 1);
// 改为：
$page = max(1, intval($this->getGet('page', 1)));

// 第129行，原来：$page = (int)$this->getGet('page', 1);
// 改为：
$page = max(1, intval($this->getGet('page', 1)));
```

#### 方案3：临时快速修复（不推荐）
如果需要立即恢复服务，可以临时移除构造函数调用：

**文件**：`/www/wwwroot/38.12.4.139/app/controllers/SearchController.php`
**修改第13行**：
```php
// 临时注释掉这行
// parent::__construct();
```

**文件**：`/www/wwwroot/38.12.4.139/app/controllers/ApiController.php`
**修改第14行**：
```php
// 临时注释掉这行
// parent::__construct();
```

**⚠️ 注意**：方案3只能临时解决构造函数问题，但不能解决分页SQL错误！

## ✅ 验证修复
修复后访问以下URL验证：
- 搜索页面：`http://38.12.4.139/search`
- API接口：`http://38.12.4.139/api/posts`

## 📝 修复说明
- 本次修复为BaseController添加了构造函数
- 提供了会话管理、时区设置、Flash消息初始化
- 符合面向对象设计最佳实践
- 所有控制器现在都有统一的基础功能

## 🔄 后续建议
1. 建议在服务器上设置自动部署脚本
2. 配置Git钩子实现代码自动更新
3. 建立测试环境避免生产环境直接修复

---
**修复优先级**：🔴 紧急 - 影响网站搜索和API功能
**预计修复时间**：2-5分钟
**风险等级**：低 - 只是添加构造函数，不影响现有功能
