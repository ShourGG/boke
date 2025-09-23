@echo off
echo ========================================
echo    Koi Blog - Project Validation
echo    老王的代码质量检查工具
echo ========================================
echo.

set "error_count=0"

echo [1/6] 检查.arc目录结构...
if not exist ".arc\README.md" (
    echo ❌ 缺少 .arc\README.md
    set /a error_count+=1
) else (
    echo ✅ .arc\README.md 存在
)

if not exist ".arc\domain_model\README.md" (
    echo ❌ 缺少 .arc\domain_model\README.md
    set /a error_count+=1
) else (
    echo ✅ 领域模型文档存在
)

if not exist ".arc\architecture\principles.md" (
    echo ❌ 缺少 .arc\architecture\principles.md
    set /a error_count+=1
) else (
    echo ✅ 架构原则文档存在
)

echo.
echo [2/6] 检查核心配置文件...
if not exist "config\config.sample.php" (
    echo ❌ 缺少 config\config.sample.php
    set /a error_count+=1
) else (
    echo ✅ 配置模板存在
)

if exist "config\config.php" (
    echo ✅ 配置文件存在
) else (
    echo ⚠️  配置文件不存在（开发环境正常）
)

echo.
echo [3/6] 检查MVC结构...
if not exist "app\core\Database.php" (
    echo ❌ 缺少核心数据库类
    set /a error_count+=1
) else (
    echo ✅ 数据库核心类存在
)

if not exist "app\controllers" (
    echo ❌ 缺少控制器目录
    set /a error_count+=1
) else (
    echo ✅ 控制器目录存在
)

if not exist "app\models" (
    echo ❌ 缺少模型目录
    set /a error_count+=1
) else (
    echo ✅ 模型目录存在
)

if not exist "app\views" (
    echo ❌ 缺少视图目录
    set /a error_count+=1
) else (
    echo ✅ 视图目录存在
)

echo.
echo [4/6] 检查安全配置...
findstr /C:"define('SECRET_KEY'" config\config.sample.php >nul
if %errorlevel% equ 0 (
    echo ✅ 安全密钥配置存在
) else (
    echo ❌ 缺少安全密钥配置
    set /a error_count+=1
)

echo.
echo [5/6] 检查文件权限和安全...
if exist ".git" (
    echo ✅ Git仓库存在
) else (
    echo ⚠️  Git仓库不存在
)

if exist "uploads" (
    echo ✅ 上传目录存在
) else (
    echo ⚠️  上传目录不存在
)

echo.
echo [6/6] 检查部署脚本...
if exist "deploy.bat" (
    echo ✅ 部署脚本存在
) else (
    echo ❌ 缺少部署脚本
    set /a error_count+=1
)

echo.
echo ========================================
if %error_count% equ 0 (
    echo ✅ 项目验证通过！所有检查项目都正常
    echo 🚀 可以安全进行开发和部署
) else (
    echo ❌ 发现 %error_count% 个问题需要修复
    echo 🔧 请根据上述提示修复问题后重新验证
)
echo ========================================
echo.
pause
