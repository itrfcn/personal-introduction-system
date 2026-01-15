<?php
/**
 * 后台仪表盘
 * 显示系统概览
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 获取文章统计
$total_articles = count(get_articles('all'));
$published_articles = count(get_articles('published'));
$draft_articles = count(get_articles('draft'));

// 获取站点统计
$total_sites = count(get_sites());
?>

<?php include 'includes/header.php'; ?>

<section class="dashboard">
    <h1>仪表盘</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-content">
                <h3>总文章数</h3>
                <p><?php echo $total_articles; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>已发布</h3>
                <p><?php echo $published_articles; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-edit"></i>
            </div>
            <div class="stat-content">
                <h3>草稿</h3>
                <p><?php echo $draft_articles; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-globe"></i>
            </div>
            <div class="stat-content">
                <h3>旗下站点</h3>
                <p><?php echo $total_sites; ?></p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-actions">
        <h2>快速操作</h2>
        <div class="actions-grid">
            <a href="add_article.php" class="action-card">
                <i class="fas fa-plus-circle"></i>
                <h3>添加文章</h3>
                <p>发布新的文章内容</p>
            </a>
            
            <a href="articles.php" class="action-card">
                <i class="fas fa-edit"></i>
                <h3>管理文章</h3>
                <p>编辑或删除现有文章</p>
            </a>
            
            <a href="personal_info.php" class="action-card">
                <i class="fas fa-user-edit"></i>
                <h3>个人信息</h3>
                <p>更新个人简介和资料</p>
            </a>
            
            <a href="sites.php" class="action-card">
                <i class="fas fa-cog"></i>
                <h3>站点管理</h3>
                <p>管理旗下站点信息</p>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>