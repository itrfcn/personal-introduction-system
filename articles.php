<?php
/**
 * 文章列表页面
 * 展示所有文章
 */

// 引入函数库
require_once __DIR__ . '/includes/functions.php';

// 获取所有已发布的文章
$articles = get_articles('published');
?>

<?php include 'includes/header.php'; ?>

<section class="articles">
    <h1>文章列表</h1>
    <div class="articles-list">
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $article): ?>
                <article class="article-item">
                    <div class="article-item-content">
                        <h2><a href="article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></h2>
                        <div class="article-meta">
                            <span><i class="far fa-calendar"></i> <?php echo format_datetime($article['created_at'], 'Y-m-d'); ?></span>
                            <?php if (!empty($article['category'])): ?>
                                <span><i class="fas fa-tag"></i> <?php echo $article['category']; ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="article-excerpt">
                            <?php echo isset($article['excerpt']) ? $article['excerpt'] : generate_excerpt($article['content']); ?>
                        </p>
                        <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-secondary">阅读全文</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-articles">
                <i class="fas fa-newspaper"></i>
                <p>暂无文章</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>