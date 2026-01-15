<?php
/**
 * 首页
 * 展示最新文章和个人信息摘要
 */

// 引入函数库
require_once __DIR__ . '/includes/functions.php';

// 获取最新文章（最多5篇）
$latest_articles = get_articles('published', 5);

// 获取个人信息
$personal_info = get_personal_info();
?>

<?php include 'includes/header.php'; ?>

<?php
    // 获取系统设置
    $settings = get_system_settings();
?>
<section class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1><?php echo isset($settings['site_title']) ? $settings['site_title'] : '欢迎来到我的个人网站'; ?></h1>
            <?php if (isset($settings['site_subtitle']) && !empty($settings['site_subtitle'])): ?>
                <p class="site-subtitle"><?php echo $settings['site_subtitle']; ?></p>
            <?php endif; ?>
            <p><?php echo isset($personal_info['bio']) ? $personal_info['bio'] : '这是一个个人介绍网站'; ?></p>
            <a href="about.php" class="btn btn-primary btn-lg">了解更多</a>
        </div>
        <div class="hero-image">
            <?php if (isset($personal_info['avatar']) && !empty($personal_info['avatar'])): ?>
                <img src="<?php echo $personal_info['avatar']; ?>" alt="个人头像" class="avatar">
            <?php else: ?>
                <div class="avatar-placeholder">
                    <i class="fas fa-user-circle"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="latest-articles">
    <h2>最新文章</h2>
    <div class="articles-grid">
        <?php if (count($latest_articles) > 0): ?>
            <?php foreach ($latest_articles as $article): ?>
                <article class="article-card">
                    <?php if (isset($article['image']) && !empty($article['image'])): ?>
                        <div class="article-image">
                            <img src="<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>">
                        </div>
                    <?php endif; ?>
                    <div class="article-content">
                        <h3><a href="article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></h3>
                        <p class="article-excerpt">
                            <?php echo isset($article['excerpt']) ? $article['excerpt'] : generate_excerpt($article['content']); ?>
                        </p>
                        <div class="article-meta">
                            <span><i class="far fa-calendar"></i> <?php echo format_datetime($article['created_at'], 'Y-m-d'); ?></span>
                            <span><i class="far fa-eye"></i> 阅读</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-articles">暂无文章</p>
        <?php endif; ?>
    </div>
    <div class="view-more">
        <a href="articles.php" class="btn btn-secondary">查看更多文章</a>
    </div>
</section>

<section class="skills">
    <h2>技能专长</h2>
    <div class="skills-grid">
        <?php if (isset($personal_info['skills']) && !empty($personal_info['skills'])): ?>
            <?php $skills = explode(',', $personal_info['skills']); ?>
            <?php foreach ($skills as $skill): ?>
                <div class="skill-item">
                    <span><?php echo trim($skill); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>暂无技能信息</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>