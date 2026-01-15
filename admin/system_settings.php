<?php
/**
 * 系统设置管理页面
 * 用于修改网站标题、副标题、页脚版权信息和网站链接等设置
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 处理更新系统设置请求
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_title = $_POST['site_title'] ?? '';
    $site_subtitle = $_POST['site_subtitle'] ?? '';
    $site_url = $_POST['site_url'] ?? '';
    $site_description = $_POST['site_description'] ?? '';
    $copyright_text = $_POST['copyright_text'] ?? '';
    $logo_text = $_POST['logo_text'] ?? '';
    $logo_icon = $_POST['logo_icon'] ?? '';
    
    // 验证输入
    if (empty($site_title)) {
        $error = '网站标题不能为空';
    } else {
        // 准备系统设置数据
        $settings_data = array(
            'site_title' => $site_title,
            'site_subtitle' => $site_subtitle,
            'site_url' => $site_url,
            'site_description' => $site_description,
            'copyright_text' => $copyright_text,
            'logo_text' => $logo_text,
            'logo_icon' => $logo_icon
        );
        
        // 更新系统设置
        if (update_system_settings($settings_data)) {
            $success = '系统设置更新成功';
        } else {
            $error = '系统设置更新失败';
        }
    }
}

// 获取当前系统设置
$settings = get_system_settings();
?>

<?php include 'includes/header.php'; ?>

<section class="system-settings">
    <h1>系统设置管理</h1>
    
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
    
    <div class="system-settings-container">
        <form class="system-settings-form" method="POST" action="system_settings.php">
            <div class="form-group">
                <label for="site_title">网站标题</label>
                <input type="text" id="site_title" name="site_title" value="<?php echo $settings['site_title']; ?>" placeholder="请输入网站标题" required>
            </div>
            
            <div class="form-group">
                <label for="site_subtitle">网站副标题</label>
                <input type="text" id="site_subtitle" name="site_subtitle" value="<?php echo $settings['site_subtitle']; ?>" placeholder="请输入网站副标题">
            </div>
            
            <div class="form-group">
                <label for="site_url">网站URL</label>
                <input type="url" id="site_url" name="site_url" value="<?php echo $settings['site_url']; ?>" placeholder="请输入网站URL">
            </div>
            
            <div class="form-group">
                <label for="site_description">网站描述</label>
                <textarea id="site_description" name="site_description" rows="3" placeholder="请输入网站描述"><?php echo $settings['site_description']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="copyright_text">版权信息</label>
                <input type="text" id="copyright_text" name="copyright_text" value="<?php echo $settings['copyright_text']; ?>" placeholder="请输入版权信息">
            </div>
            
            <div class="form-group">
                <label for="logo_text">Logo文本</label>
                <input type="text" id="logo_text" name="logo_text" value="<?php echo $settings['logo_text']; ?>" placeholder="请输入Logo文本">
            </div>
            
            <div class="form-group">
                <label for="logo_icon">Logo图标类名</label>
                <input type="text" id="logo_icon" name="logo_icon" value="<?php echo $settings['logo_icon']; ?>" placeholder="请输入Font Awesome图标类名（如：fas fa-user-circle）">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">保存设置</button>
            </div>
        </form>
    </div>
</section>

<?php include 'includes/footer.php';