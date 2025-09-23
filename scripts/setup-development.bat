@echo off
echo ========================================
echo    Koi Blog - Development Setup
echo    老王的开发环境配置工具
echo ========================================
echo.

echo [1/5] 创建必要的目录结构...
if not exist "uploads" mkdir uploads
if not exist "logs" mkdir logs
if not exist "cache" mkdir cache
if not exist "backups" mkdir backups
echo ✅ 目录结构创建完成

echo.
echo [2/5] 设置配置文件...
if not exist "config\config.php" (
    if exist "config\config.sample.php" (
        copy "config\config.sample.php" "config\config.php"
        echo ✅ 配置文件已从模板创建
        echo ⚠️  请编辑 config\config.php 设置数据库连接
    ) else (
        echo ❌ 配置模板文件不存在
    )
) else (
    echo ✅ 配置文件已存在
)

echo.
echo [3/5] 检查PHP环境...
php --version >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ PHP 环境可用
    php --version | findstr "PHP"
) else (
    echo ❌ PHP 环境不可用，请安装PHP 7.4+
)

echo.
echo [4/5] 初始化Git仓库（如果需要）...
if not exist ".git" (
    git --version >nul 2>&1
    if %errorlevel% equ 0 (
        git init
        echo ✅ Git仓库初始化完成
        
        echo # Koi Blog > README.md
        echo Personal blog system with website directory >> README.md
        
        echo node_modules/ > .gitignore
        echo config/config.php >> .gitignore
        echo uploads/* >> .gitignore
        echo logs/* >> .gitignore
        echo cache/* >> .gitignore
        echo backups/* >> .gitignore
        echo .DS_Store >> .gitignore
        echo Thumbs.db >> .gitignore
        echo ✅ .gitignore 文件创建完成
    ) else (
        echo ⚠️  Git 不可用，跳过仓库初始化
    )
) else (
    echo ✅ Git仓库已存在
)

echo.
echo [5/5] 创建开发辅助脚本...

echo @echo off > start-dev.bat
echo echo 启动开发服务器... >> start-dev.bat
echo php -S localhost:8000 -t . >> start-dev.bat
echo pause >> start-dev.bat

echo ✅ 开发服务器脚本创建完成 (start-dev.bat)

echo.
echo ========================================
echo 🎉 开发环境设置完成！
echo.
echo 📋 下一步操作：
echo 1. 编辑 config\config.php 设置数据库连接
echo 2. 运行 start-dev.bat 启动开发服务器
echo 3. 访问 http://localhost:8000 查看项目
echo 4. 使用 deploy.bat 部署到生产环境
echo.
echo 🔧 可用的脚本：
echo - validate-project.bat : 项目结构验证
echo - start-dev.bat        : 启动开发服务器
echo - deploy.bat           : 部署到生产环境
echo ========================================
echo.
pause
