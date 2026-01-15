<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
    require_once __DIR__ . '/functions.php';
    $settings = get_system_settings();
    ?>
    <title><?php echo $settings['site_title']; ?></title>
    <!-- 引入CSS文件 -->
    <link rel="stylesheet" href="css/style.css">
    <!-- 引入Font Awesome图标库 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- 导航栏 -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <i class="<?php echo $settings['logo_icon']; ?>"></i> <?php echo $settings['logo_text']; ?>
                </a>
            </div>
            
            <!-- 移动端汉堡菜单按钮 -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php"><i class="fas fa-home"></i> 首页</a></li>
                <li><a href="about.php"><i class="fas fa-user"></i> 个人介绍</a></li>
                <li><a href="articles.php"><i class="fas fa-newspaper"></i> 文章</a></li>
                <li><a href="sites.php"><i class="fas fa-globe"></i> 旗下站点</a></li>
                <li><a href="../admin/login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> 后台管理</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- 主内容区 -->
    <main class="container">