<?php
/**
 * 编辑文章页面
 * 用于编辑已有的文章内容
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 获取文章ID
$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 根据ID获取文章
$article = get_article_by_id($article_id);

// 检查文章是否存在
if (!$article) {
    header('Location: articles.php');
    exit();
}

// 处理编辑文章请求
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $image = $_POST['image'] ?? '';
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? 'draft';
    
    // 验证输入
    if (empty($title) || empty($content)) {
        $error = '文章标题和内容不能为空';
    } else {
        // 如果没有提供摘要，自动生成
        if (empty($excerpt)) {
            $excerpt = generate_excerpt($content);
        }
        
        // 准备文章数据
        $article_data = array(
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'image' => $image,
            'category' => $category,
            'status' => $status
        );
        
        // 更新文章
        if (update_article($article_id, $article_data)) {
            $success = '文章更新成功';
            // 更新文章变量
            $article = get_article_by_id($article_id);
        } else {
            $error = '文章更新失败';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="edit-article">
    <h1>编辑文章</h1>
    
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
    
    <form class="article-form" method="POST" action="edit_article.php?id=<?php echo $article_id; ?>">
        <div class="form-group">
            <label for="title">文章标题</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" placeholder="请输入文章标题" required>
        </div>
        
        <div class="form-group">
            <label for="category">文章分类</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($article['category'] ?? ''); ?>" placeholder="请输入文章分类">
        </div>
        
        <div class="form-group">
            <label for="image">文章图片URL</label>
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($article['image'] ?? ''); ?>" placeholder="请输入文章图片URL">
        </div>
        
        <div class="form-group">
            <label for="excerpt">文章摘要</label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="请输入文章摘要"><?php echo htmlspecialchars($article['excerpt'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="content">文章内容</label>
            <textarea id="content" name="content" rows="10" placeholder="请输入文章内容" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="status">文章状态</label>
            <select id="status" name="status">
                <option value="draft" <?php echo ($article['status'] === 'draft') ? 'selected' : ''; ?>>草稿</option>
                <option value="published" <?php echo ($article['status'] === 'published') ? 'selected' : ''; ?>>已发布</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新文章</button>
            <a href="articles.php" class="btn btn-secondary">取消</a>
        </div>
    </form>
</section>

<?php include 'includes/footer.php'; ?>