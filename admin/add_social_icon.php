<?php
/**
 * 添加/编辑社交图标页面
 * 用于添加新的社交图标或编辑现有社交图标
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 处理社交图标ID
$icon_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$is_edit = $icon_id > 0;

// 获取当前社交图标信息（如果是编辑模式）
$icon = null;
if ($is_edit) {
    $icon = get_social_icon_by_id($icon_id);
    
    // 如果社交图标不存在，跳转回社交图标管理页面
    if (!$icon) {
        header('Location: social_icons.php');
        exit();
    }
}

// 处理添加/编辑社交图标请求
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = $_POST['platform'] ?? '';
    $icon_class = $_POST['icon_class'] ?? '';
    $url = $_POST['url'] ?? '';
    $sort_order = $_POST['sort_order'] ?? 0;
    $status = $_POST['status'] ?? 'active';
    
    // 验证输入
    if (empty($platform) || empty($icon_class) || empty($url)) {
        $error = '平台名称、图标类名和URL不能为空';
    } else {
        // 准备社交图标数据
        $icon_data = array(
            'platform' => $platform,
            'icon_class' => $icon_class,
            'url' => $url,
            'sort_order' => intval($sort_order),
            'status' => $status
        );
        
        if ($is_edit) {
            // 更新社交图标
            if (update_social_icon($icon_id, $icon_data)) {
                $success = '社交图标更新成功';
                // 更新社交图标变量
                $icon = get_social_icon_by_id($icon_id);
            } else {
                $error = '社交图标更新失败';
            }
        } else {
            // 添加社交图标
            if (add_social_icon($icon_data)) {
                $success = '社交图标添加成功';
                // 清空表单
                $platform = $icon_class = $url = '';
                $sort_order = 0;
                $status = 'active';
            } else {
                $error = '社交图标添加失败';
            }
        }
    }
}

// 设置表单默认值
$platform = $is_edit ? $icon['platform'] : '';
$icon_class = $is_edit ? $icon['icon_class'] : '';
$url = $is_edit ? $icon['url'] : '';
$sort_order = $is_edit ? $icon['sort_order'] : 0;
$status = $is_edit ? $icon['status'] : 'active';
?>

<?php include 'includes/header.php'; ?>

<section class="add-social-icon">
    <h1><?php echo $is_edit ? '编辑社交图标' : '添加社交图标'; ?></h1>
    
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
    
    <form class="social-icon-form" method="POST" action="add_social_icon.php<?php echo $is_edit ? '?id=' . $icon_id : ''; ?>">
        <div class="form-group">
            <label for="platform">平台名称</label>
            <input type="text" id="platform" name="platform" value="<?php echo $platform; ?>" placeholder="请输入平台名称" required>
        </div>
        
        <div class="form-group">
            <label for="icon_class">图标类名</label>
            <input type="text" id="icon_class" name="icon_class" value="<?php echo $icon_class; ?>" placeholder="请输入Font Awesome图标类名（如：fab fa-weixin）" required>
        </div>
        
        <div class="form-group">
            <label for="url">链接URL</label>
            <input type="url" id="url" name="url" value="<?php echo $url; ?>" placeholder="请输入链接URL" required>
        </div>
        
        <div class="form-group">
            <label for="sort_order">排序</label>
            <input type="number" id="sort_order" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="请输入排序值" min="0">
        </div>
        
        <div class="form-group">
            <label for="status">状态</label>
            <select id="status" name="status">
                <option value="active" <?php echo ($status === 'active') ? 'selected' : ''; ?>>激活</option>
                <option value="inactive" <?php echo ($status === 'inactive') ? 'selected' : ''; ?>>禁用</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit ? '更新社交图标' : '添加社交图标'; ?>
            </button>
            <a href="social_icons.php" class="btn btn-secondary">取消</a>
        </div>
    </form>
</section>

<?php include 'includes/footer.php';