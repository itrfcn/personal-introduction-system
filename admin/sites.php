<?php
/**
 * 旗下站点管理页面
 * 显示所有站点并支持编辑和删除操作
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 处理删除站点请求
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (delete_site($id)) {
        $success = '站点删除成功';
    } else {
        $error = '站点删除失败';
    }
}

// 获取所有站点
$sites = get_sites();
?>

<?php include 'includes/header.php'; ?>

<section class="sites-admin">
    <h1>旗下站点管理</h1>
    
    <div class="admin-actions">
        <a href="add_site.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> 添加站点
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
    
    <div class="sites-table-container">
        <?php if (count($sites) > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>站点名称</th>
                        <th>URL</th>
                        <th>描述</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sites as $site): ?>
                        <tr>
                            <td><?php echo $site['id']; ?></td>
                            <td><?php echo $site['name']; ?></td>
                            <td><a href="<?php echo $site['url']; ?>" target="_blank"><?php echo $site['url']; ?></a></td>
                            <td><?php echo $site['description']; ?></td>
                            <td><?php echo format_datetime($site['created_at']); ?></td>
                            <td class="actions">
                                <a href="add_site.php?id=<?php echo $site['id']; ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i> 编辑
                                </a>
                                <a href="sites.php?delete=<?php echo $site['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('确定要删除这个站点吗？');">
                                    <i class="fas fa-trash"></i> 删除
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-globe"></i>
                <p>暂无旗下站点</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>