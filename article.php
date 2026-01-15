<?php
/**
 * 文章详情页面
 * 展示单篇文章的详细内容
 */

// 引入函数库
require_once __DIR__ . '/includes/functions.php';

// 获取文章ID
$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 根据ID获取文章
$article = get_article_by_id($article_id);

// 检查文章是否存在且已发布
if (!$article || $article['status'] !== 'published') {
    header('Location: articles.php');
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<section class="article-detail">
    <article class="article">
        <header class="article-header">
            <h1><?php echo $article['title']; ?></h1>
            <div class="article-meta">
                <span><i class="far fa-calendar"></i> <?php echo format_datetime($article['created_at'], 'Y-m-d H:i'); ?></span>
                <?php if (!empty($article['category'])): ?>
                    <span><i class="fas fa-tag"></i> <?php echo $article['category']; ?></span>
                <?php endif; ?>
            </div>
        </header>
        
        <?php if (isset($article['image']) && !empty($article['image'])): ?>
            <div class="article-featured-image">
                <img src="<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>">
            </div>
        <?php endif; ?>
        
        <div class="article-body">
            <?php echo $article['content']; ?>
        </div>
        
        <footer class="article-footer">
            <div class="article-actions">
                <a href="articles.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> 返回文章列表</a>
            </div>
        </footer>
    </article>
</section>

<?php include 'includes/footer.php'; ?>