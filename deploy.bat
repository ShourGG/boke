@echo off
echo ========================================
echo    Koi Blog - Quick Deploy Script
echo    老王的快速部署工具
echo ========================================
echo.

echo [1/4] 添加所有文件到Git...
git add .

echo [2/4] 提交更改...
set /p commit_msg="请输入提交信息: "
if "%commit_msg%"=="" set commit_msg=Update: 快速更新
git commit -m "%commit_msg%"

echo [3/4] 推送到GitHub...
git push origin master

echo [4/4] 部署完成！
echo.
echo ✅ 代码已推送到GitHub
echo 📝 现在可以在宝塔面板点击"拉取"按钮更新服务器
echo 🌐 GitHub仓库: https://github.com/ShourGG/boke
echo.
pause
