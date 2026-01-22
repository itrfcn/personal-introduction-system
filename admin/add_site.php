<?php
/**
 * 添加/编辑旗下站点页面
 * 用于添加新的站点或编辑现有站点
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 处理站点ID
$site_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$is_edit = $site_id > 0;

// 获取当前站点信息（如果是编辑模式）
$site = null;
if ($is_edit) {
    $sites = get_sites();
    foreach ($sites as $s) {
        if ($s['id'] === $site_id) {
            $site = $s;
            break;
        }
    }
    
    // 如果站点不存在，跳转回站点管理页面
    if (!$site) {
        header('Location: sites.php');
        exit();
    }
}

// 处理添加/编辑站点请求
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $url = $_POST['url'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_POST['image'] ?? '';
    
    // 验证输入
    if (empty($name) || empty($url)) {
        $error = '站点名称和URL不能为空';
    } else {
        // 准备站点数据
        $site_data = array(
            'name' => $name,
            'url' => $url,
            'description' => $description,
            'image' => $image
        );
        
        if ($is_edit) {
            // 更新站点
            if (update_site($site_id, $site_data)) {
                $success = '站点更新成功';
                // 更新站点变量
                $site = get_site_by_id($site_id);
            } else {
                $error = '站点更新失败';
            }
        } else {
            // 添加站点
            if (add_site($site_data)) {
                $success = '站点添加成功';
                // 清空表单
                $name = $url = $description = $image = '';
            } else {
                $error = '站点添加失败';
            }
        }
    }
}

// 设置表单默认值
$name = $is_edit ? $site['name'] : '';
$url = $is_edit ? $site['url'] : '';
$description = $is_edit ? $site['description'] : '';
$image = $is_edit ? $site['image'] : '';
?>

<?php include 'includes/header.php'; ?>

<section class="add-site">
    <h1><?php echo $is_edit ? '编辑站点' : '添加站点'; ?></h1>
    
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
    
    <form class="site-form" method="POST" action="add_site.php<?php echo $is_edit ? '?id=' . $site_id : ''; ?>">
        <div class="form-group">
            <label for="name">站点名称</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="请输入站点名称" required>
        </div>
        
        <div class="form-group">
            <label for="url">站点URL</label>
            <input type="url" id="url" name="url" value="<?php echo htmlspecialchars($url); ?>" placeholder="请输入站点URL" required>
        </div>
        
        <div class="form-group">
            <label for="image">站点图片URL</label>
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>" placeholder="请输入站点图片URL">
        </div>
        
        <div class="form-group">
            <label for="description">站点描述</label>
            <textarea id="description" name="description" rows="3" placeholder="请输入站点描述"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit ? '更新站点' : '添加站点'; ?>
            </button>
            <a href="sites.php" class="btn btn-secondary">取消</a>
        </div>
    </form>
</section>

<?php include 'includes/footer.php'; ?>