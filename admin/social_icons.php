<?php
/**
 * 社交图标管理页面
 * 显示所有社交图标并支持编辑和删除操作
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 处理删除社交图标请求
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (delete_social_icon($id)) {
        $success = '社交图标删除成功';
    } else {
        $error = '社交图标删除失败';
    }
}

// 获取所有社交图标
$social_icons = get_all_social_icons();
?>

<?php include 'includes/header.php'; ?>

<section class="social-icons-admin">
    <h1>社交图标管理</h1>
    
    <div class="admin-actions">
        <a href="add_social_icon.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> 添加社交图标
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
    
    <div class="social-icons-table-container">
        <?php if (count($social_icons) > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>平台名称</th>
                        <th>图标</th>
                        <th>图标类名</th>
                        <th>链接URL</th>
                        <th>排序</th>
                        <th class="status-column">状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($social_icons as $icon): ?>
                        <tr>
                            <td><?php echo $icon['id']; ?></td>
                            <td><?php echo $icon['platform']; ?></td>
                            <td>
                                <i class="<?php echo $icon['icon_class']; ?>" style="font-size: 1.5rem;"></i>
                            </td>
                            <td><?php echo $icon['icon_class']; ?></td>
                            <td><a href="<?php echo $icon['url']; ?>" target="_blank"><?php echo $icon['url']; ?></a></td>
                            <td><?php echo $icon['sort_order']; ?></td>
                            <td>
                                <span class="badge <?php echo $icon['status'] === 'active' ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $icon['status'] === 'active' ? '已激活' : '已禁用'; ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="add_social_icon.php?id=<?php echo $icon['id']; ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i> 编辑
                                </a>
                                <a href="social_icons.php?delete=<?php echo $icon['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('确定要删除这个社交图标吗？');">
                                    <i class="fas fa-trash"></i> 删除
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-share-alt"></i>
                <p>暂无社交图标</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>