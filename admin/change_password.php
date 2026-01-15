<?php
/**
 * 后台修改密码页面
 */

// 检查用户是否已登录
require_once __DIR__ . '/../includes/functions.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// 处理修改密码请求
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取当前用户信息
    $currentUser = get_user_by_username($_SESSION['username']);
    
    if ($currentUser) {
        // 验证当前密码
        if (!password_verify($_POST['current_password'], $currentUser['password'])) {
            $error = '当前密码错误';
        } else if (strlen($_POST['new_password']) < 6) {
            $error = '新密码长度不能少于6个字符';
        } else if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = '两次输入的新密码不一致';
        } else {
            // 更新密码
            if (update_user_password($currentUser['id'], $_POST['new_password'])) {
                $success = '密码修改成功';
            } else {
                $error = '密码修改失败，请重试';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改密码 - 后台管理</title>
    <!-- 引入CSS文件 -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- 引入Font Awesome图标库 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- 引入头部 -->
    <?php include __DIR__ . '/includes/header.php'; ?>
    
            <div class="admin-content">
                <h1><i class="fas fa-lock"></i> 修改密码</h1>
                
                <div class="content-wrapper">
                    <div class="form-container">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form class="password-form" method="POST" action="change_password.php">
                            <div class="form-group">
                                <label for="current_password"><i class="fas fa-key"></i> 当前密码</label>
                                <input type="password" id="current_password" name="current_password" placeholder="请输入当前密码" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password"><i class="fas fa-lock-open"></i> 新密码</label>
                                <input type="password" id="new_password" name="new_password" placeholder="请输入新密码（至少6个字符）" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password"><i class="fas fa-lock"></i> 确认新密码</label>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="请再次输入新密码" required>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 保存修改</button>
                                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> 返回</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    
    <!-- 引入页脚 -->
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>