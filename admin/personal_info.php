<?php
/**
 * 个人信息管理页面
 * 用于编辑个人信息
 */

// 引入函数库
require_once __DIR__ . '/../includes/functions.php';

// 获取当前个人信息
$personal_info = get_personal_info();

// 处理更新个人信息请求
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $avatar = $_POST['avatar'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $education = $_POST['education'] ?? '';
    $experience = $_POST['experience'] ?? '';
    
    // 验证输入
    if (empty($name)) {
        $error = '姓名不能为空';
    } else {
        // 准备个人信息数据
        $info_data = array(
            'name' => $name,
            'avatar' => $avatar,
            'bio' => $bio,
            'skills' => $skills,
            'education' => $education,
            'experience' => $experience
        );
        
        // 更新个人信息
        if (update_personal_info($info_data)) {
            $success = '个人信息更新成功';
            // 更新个人信息变量
            $personal_info = get_personal_info();
        } else {
            $error = '个人信息更新失败';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="personal-info-admin">
    <h1>个人信息管理</h1>
    
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
    
    <form class="personal-info-form" method="POST" action="personal_info.php">
        <div class="form-group">
            <label for="name">姓名</label>
            <input type="text" id="name" name="name" value="<?php echo $personal_info['name'] ?? ''; ?>" placeholder="请输入姓名" required>
        </div>
        
        <div class="form-group">
            <label for="avatar">头像URL</label>
            <input type="text" id="avatar" name="avatar" value="<?php echo $personal_info['avatar'] ?? ''; ?>" placeholder="请输入头像图片URL">
        </div>
        
        <div class="form-group">
            <label for="bio">个人简介</label>
            <textarea id="bio" name="bio" rows="3" placeholder="请输入个人简介"><?php echo $personal_info['bio'] ?? ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="skills">技能专长</label>
            <textarea id="skills" name="skills" rows="3" placeholder="请输入技能专长，用逗号分隔"><?php echo $personal_info['skills'] ?? ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="education">教育背景</label>
            <textarea id="education" name="education" rows="3" placeholder="请输入教育背景"><?php echo $personal_info['education'] ?? ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="experience">工作经验</label>
            <textarea id="experience" name="experience" rows="3" placeholder="请输入工作经验"><?php echo $personal_info['experience'] ?? ''; ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新个人信息</button>
        </div>
    </form>
</section>

<?php include 'includes/footer.php'; ?>