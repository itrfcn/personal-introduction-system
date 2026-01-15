<?php
/**
 * 文章管理页面
 * 显示所有文章并支持编辑和删除操作
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 处理删除文章请求
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (delete_article($id)) {
        $success = '文章删除成功';
    } else {
        $error = '文章删除失败';
    }
}

// 获取所有文章
$articles = get_articles('all');
?>

<?php include 'includes/header.php'; ?>

<section class="articles-admin">
    <h1>文章管理</h1>
    
    <div class="admin-actions">
        <a href="add_article.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> 添加文章
        </a>
    </div>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="articles-table-container">
        <?php if (count($articles) > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>标题</th>
                        <th class="status-column">状态</th>
                        <th>分类</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td><?php echo $article['title']; ?></td>
                            <td>
                                <span class="badge <?php echo $article['status'] === 'published' ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $article['status'] === 'published' ? '已发布' : '草稿'; ?>
                                </span>
                            </td>
                            <td><?php echo $article['category'] ?? '未分类'; ?></td>
                            <td><?php echo format_datetime($article['created_at']); ?></td>
                            <td class="actions">
                                <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i> 编辑
                                </a>
                                <a href="articles.php?delete=<?php echo $article['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('确定要删除这篇文章吗？');">
                                    <i class="fas fa-trash"></i> 删除
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-newspaper"></i>
                <p>暂无文章</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>