<?php
/**
 * 后台管理页面通用头部
 */

// 检查用户是否已登录
require_once __DIR__ . '/../../includes/functions.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 个人介绍系统</title>
    <!-- 引入CSS文件 -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- 引入Font Awesome图标库 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- 后台导航栏 -->
    <nav class="admin-navbar">
        <div class="container">
            <!-- 移动端菜单按钮 -->
            <button class="mobile-menu-btn" aria-label="菜单">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <a href="index.php">
                    <i class="fas fa-tachometer-alt"></i> 后台管理
                </a>
            </div>
            <div class="user-info">
                <span><?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> 退出
                </a>
            </div>
        </div>
    </nav>
    
    <!-- 后台主内容区 -->
    <div class="admin-container">
        <!-- 侧边栏 -->
        <aside class="admin-sidebar">
            <ul class="sidebar-menu">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> 仪表盘</a></li>
                <li><a href="articles.php"><i class="fas fa-newspaper"></i> 文章管理</a></li>
                <li><a href="add_article.php"><i class="fas fa-plus"></i> 添加文章</a></li>
                <li><a href="personal_info.php"><i class="fas fa-user"></i> 个人信息管理</a></li>
                <li><a href="change_password.php"><i class="fas fa-lock"></i> 修改密码</a></li>
                <li><a href="sites.php"><i class="fas fa-globe"></i> 旗下站点管理</a></li>
                <li><a href="add_site.php"><i class="fas fa-plus"></i> 添加站点</a></li>
                <li><a href="social_icons.php"><i class="fas fa-share-alt"></i> 社交图标管理</a></li>
                <li><a href="add_social_icon.php"><i class="fas fa-plus"></i> 添加社交图标</a></li>
                <li><a href="system_settings.php"><i class="fas fa-cog"></i> 系统设置</a></li>
            </ul>
        </aside>
        
        <!-- 主内容 -->
        <main class="admin-main">