<?php
/**
 * 旗下站点页面
 * 展示所有旗下站点信息
 */

// 引入函数库
require_once __DIR__ . '/includes/functions.php';

// 获取所有旗下站点
$sites = get_sites();
?>

<?php include 'includes/header.php'; ?>

<section class="sites">
    <h1>旗下站点</h1>
    <div class="sites-grid">
        <?php if (count($sites) > 0): ?>
            <?php foreach ($sites as $site): ?>
                <div class="site-card">
                    <div class="site-header">
                        <?php if (isset($site['image']) && !empty($site['image'])): ?>
                            <div class="site-image">
                                <img src="<?php echo $site['image']; ?>" alt="<?php echo $site['name']; ?>">
                            </div>
                        <?php else: ?>
                            <div class="site-image-placeholder">
                                <i class="fas fa-globe"></i>
                            </div>
                        <?php endif; ?>
                        <h2><?php echo $site['name']; ?></h2>
                    </div>
                    <div class="site-content">
                        <p><?php echo $site['description']; ?></p>
                    </div>
                    <div class="site-footer">
                        <a href="<?php echo $site['url']; ?>" target="_blank" class="btn btn-primary">访问站点</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-sites">
                <i class="fas fa-globe"></i>
                <p>暂无旗下站点</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>