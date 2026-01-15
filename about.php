<?php
/**
 * 个人介绍页面
 * 展示详细的个人信息
 */

// 引入函数库
require_once __DIR__ . '/includes/functions.php';

// 获取个人信息
$personal_info = get_personal_info();
?>

<?php include 'includes/header.php'; ?>

<?php
    // 获取系统设置
    $settings = get_system_settings();
?>
<section class="about">
    <div class="about-header">
        <div class="about-avatar">
            <?php if (isset($personal_info['avatar']) && !empty($personal_info['avatar'])): ?>
                <img src="<?php echo $personal_info['avatar']; ?>" alt="个人头像" class="avatar-lg">
            <?php else: ?>
                <div class="avatar-placeholder-lg">
                    <i class="fas fa-user-circle"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="about-title">
            <h1><?php echo isset($personal_info['name']) ? $personal_info['name'] : '姓名'; ?></h1>
            <p><?php echo isset($personal_info['bio']) ? $personal_info['bio'] : '个人简介'; ?></p>
            <?php if (isset($settings['site_subtitle']) && !empty($settings['site_subtitle'])): ?>
                <p class="site-subtitle"><?php echo $settings['site_subtitle']; ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="about-content">
        <div class="about-section">
            <h2><i class="fas fa-user-graduate"></i> 教育背景</h2>
            <div class="content-box">
                <?php if (isset($personal_info['education']) && !empty($personal_info['education'])): ?>
                    <p><?php echo $personal_info['education']; ?></p>
                <?php else: ?>
                    <p>暂无教育背景信息</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="about-section">
            <h2><i class="fas fa-briefcase"></i> 工作经验</h2>
            <div class="content-box">
                <?php if (isset($personal_info['experience']) && !empty($personal_info['experience'])): ?>
                    <p><?php echo $personal_info['experience']; ?></p>
                <?php else: ?>
                    <p>暂无工作经验信息</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="about-section">
            <h2><i class="fas fa-tools"></i> 技能专长</h2>
            <div class="skills-container">
                <?php if (isset($personal_info['skills']) && !empty($personal_info['skills'])): ?>
                    <?php $skills = explode(',', $personal_info['skills']); ?>
                    <?php foreach ($skills as $skill): ?>
                        <div class="skill-tag">
                            <?php echo trim($skill); ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>暂无技能信息</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>