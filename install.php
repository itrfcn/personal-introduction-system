<?php
/**
 * 系统安装页面
 */

// 检查是否已经安装
$install_lock_file = __DIR__ . '/data/install.lock';
if (file_exists($install_lock_file)) {
    die('系统已经安装过了，如果需要重新安装，请删除 data/install.lock 文件');
}

// 处理安装请求
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证表单数据
    if (empty($_POST['admin_username'])) {
        $errors[] = '管理员用户名不能为空';
    } elseif (strlen($_POST['admin_username']) < 3) {
        $errors[] = '管理员用户名长度不能少于3个字符';
    }
    
    if (empty($_POST['admin_password'])) {
        $errors[] = '管理员密码不能为空';
    } elseif (strlen($_POST['admin_password']) < 6) {
        $errors[] = '管理员密码长度不能少于6个字符';
    } elseif ($_POST['admin_password'] !== $_POST['admin_password_confirm']) {
        $errors[] = '两次输入的管理员密码不一致';
    }
    
    if (empty($_POST['site_title'])) {
        $errors[] = '网站标题不能为空';
    }
    
    // 如果没有错误，开始安装
    if (empty($errors)) {
        try {
            // 获取用户选择的存储模式
            $storage_mode = $_POST['storage_mode'] ?? 'data';
            
            // 更新配置文件
            $config_content = "<?php\n// 系统配置\ndefine('STORAGE_MODE', '$storage_mode');\n";
            
            // 如果选择MySQL模式，需要添加数据库配置
            if ($storage_mode === 'mysql') {
                $db_host = $_POST['db_host'] ?? 'localhost';
                $db_port = $_POST['db_port'] ?? '3306';
                $db_name = $_POST['db_name'] ?? 'personal_website';
                $db_username = $_POST['db_username'] ?? 'root';
                $db_password = $_POST['db_password'] ?? '';
                
                $config_content .= "\n// 数据库配置\ndefine('DB_HOST', '$db_host');\ndefine('DB_PORT', '$db_port');\ndefine('DB_NAME', '$db_name');\ndefine('DB_USER', '$db_username');\ndefine('DB_PASSWORD', '$db_password');\n";
            }
            
            // 写入配置文件
            file_put_contents(__DIR__ . '/includes/config.php', $config_content);
            
            // 引入数据存储系统
            require_once __DIR__ . '/includes/config.php';
            require_once __DIR__ . '/includes/data_storage.php';
            
            // 如果是文件存储模式，创建数据目录（如果不存在）
            if ($storage_mode === 'data' && !file_exists(__DIR__ . '/data')) {
                mkdir(__DIR__ . '/data', 0755, true);
            }
            
            // 初始化默认数据（只创建空结构，不添加任何默认数据）
            init_default_data();
            
            // 创建管理员用户（系统必需，否则无法登录）
            $admin = get_user_by_username('admin');
            if (!$admin) {
                // 创建新的管理员用户
                add_user([
                    'username' => 'admin',
                    'password' => $_POST['admin_password'] // 使用用户输入的密码
                ]);
            }
            // 更新管理员密码
            $admin = get_user_by_username('admin');
            if ($admin) {
                update_user_password($admin['id'], $_POST['admin_password']);
            }
            
            // 不自动添加网站设置和个人信息
            // 用户可以在登录后通过后台管理页面手动添加这些信息
            
            // 创建安装锁文件
            file_put_contents($install_lock_file, 'installed on ' . date('Y-m-d H:i:s'));
            
            // 安装成功
            $success = true;
        } catch (Exception $e) {
            $errors[] = '安装失败: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统安装 - 个人网站</title>
    <!-- 引入CSS文件 -->
    <link rel="stylesheet" href="css/style.css">
    <!-- 引入Font Awesome图标库 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .install-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            width: 100%;
            max-width: 600px;
            animation: slideInUp 0.5s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .install-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .install-header h1 {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .install-header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .errors {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .errors ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .errors li {
            color: #c0392b;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }
        
        .errors li::before {
            content: '\f06a';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            color: #e74c3c;
        }
        
        .success {
            background: #efe;
            border: 1px solid #cfc;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #27ae60;
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1c638c 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .btn-block {
            width: 100%;
        }
        
        .step-title {
            color: #3498db;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 0.5rem;
        }
        
        .install-success {
            text-align: center;
            padding: 2rem 0;
        }
        
        .install-success i {
            font-size: 4rem;
            color: #27ae60;
            margin-bottom: 1rem;
        }
        
        .install-success h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .install-success p {
            color: #7f8c8d;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <h1><i class="fas fa-cogs"></i> 系统安装向导</h1>
            <p>欢迎使用个人网站系统，请按照以下步骤完成安装</p>
        </div>
        
        <?php if ($success): ?>
            <div class="install-success">
                <i class="fas fa-check-circle"></i>
                <h2>安装成功！</h2>
                <p>系统已经成功安装完成，您现在可以：</p>
                <a href="admin/login.php" class="btn btn-primary">登录后台管理</a>
                <a href="index.php" class="btn btn-primary" style="margin-top: 1rem;">访问网站首页</a>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="install.php">
                <h3 class="step-title"><i class="fas fa-database"></i> 存储模式选择</h3>
                
                <div class="form-group">
                    <label for="storage_mode">选择存储模式</label>
                    <div style="display: flex; gap: 1rem;">
                        <label style="flex: 1; cursor: pointer; padding: 1rem; border: 2px solid #e1e8ed; border-radius: 10px; transition: all 0.3s ease;">
                            <input type="radio" name="storage_mode" value="data" checked style="margin-right: 0.5rem;">
                            <strong>文件存储 (data文件夹)</strong>
                            <p style="margin: 0.5rem 0 0 0; color: #7f8c8d; font-size: 0.9rem;">使用JSON文件存储数据，简单易用，适合小型网站</p>
                        </label>
                        <label style="flex: 1; cursor: pointer; padding: 1rem; border: 2px solid #e1e8ed; border-radius: 10px; transition: all 0.3s ease;">
                            <input type="radio" name="storage_mode" value="mysql" style="margin-right: 0.5rem;">
                            <strong>数据库存储 (MySQL)</strong>
                            <p style="margin: 0.5rem 0 0 0; color: #7f8c8d; font-size: 0.9rem;">使用MySQL数据库存储数据，性能更好，适合大型网站</p>
                            <p style="margin: 0.5rem 0 0 0; color: #f39c12; font-size: 0.9rem;"><i class="fas fa-info-circle"></i> 注意：MySQL模式需要确保MySQL服务器已安装并运行</p>
                        </label>
                    </div>
                </div>
                
                <div class="form-group" id="mysql_config" style="display: none;">
                    <h4 style="margin-bottom: 1rem; color: #2c3e50;">MySQL数据库配置</h4>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label for="db_host">数据库主机</label>
                            <input type="text" id="db_host" name="db_host" value="localhost">
                        </div>
                        <div>
                            <label for="db_port">数据库端口</label>
                            <input type="text" id="db_port" name="db_port" value="3306">
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label for="db_name">数据库名称</label>
                            <input type="text" id="db_name" name="db_name" value="personal_website">
                        </div>
                        <div>
                            <label for="db_username">数据库用户名</label>
                            <input type="text" id="db_username" name="db_username" value="root">
                        </div>
                    </div>
                    
                    <div>
                        <label for="db_password">数据库密码</label>
                        <input type="password" id="db_password" name="db_password">
                    </div>
                </div>
                
                <h3 class="step-title"><i class="fas fa-user-shield"></i> 管理员信息</h3>
                
                <div class="form-group">
                    <label for="admin_username">管理员用户名</label>
                    <input type="text" id="admin_username" name="admin_username" value="<?php echo $_POST['admin_username'] ?? 'admin'; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="admin_password">管理员密码</label>
                    <input type="password" id="admin_password" name="admin_password" required>
                </div>
                
                <div class="form-group">
                    <label for="admin_password_confirm">确认管理员密码</label>
                    <input type="password" id="admin_password_confirm" name="admin_password_confirm" required>
                </div>
                
                <h3 class="step-title"><i class="fas fa-globe"></i> 网站基本信息</h3>
                
                <div class="form-group">
                    <label for="site_title">网站标题</label>
                    <input type="text" id="site_title" name="site_title" value="<?php echo $_POST['site_title'] ?? '个人网站'; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="site_subtitle">网站副标题</label>
                    <input type="text" id="site_subtitle" name="site_subtitle" value="<?php echo $_POST['site_subtitle'] ?? '分享我的技术与生活'; ?>">
                </div>
                
                <div class="form-group">
                    <label for="site_url">网站URL</label>
                    <input type="text" id="site_url" name="site_url" value="<?php echo $_POST['site_url'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="site_description">网站描述</label>
                    <textarea id="site_description" name="site_description" placeholder="简单描述一下您的网站"><?php echo $_POST['site_description'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="copyright_text">版权信息</label>
                    <input type="text" id="copyright_text" name="copyright_text" value="<?php echo $_POST['copyright_text'] ?? '© ' . date('Y') . ' 个人网站. All rights reserved.'; ?>">
                </div>
                
                <div class="form-group">
                    <label for="logo_text">Logo文字</label>
                    <input type="text" id="logo_text" name="logo_text" value="<?php echo $_POST['logo_text'] ?? '个人网站'; ?>">
                </div>
                
                <div class="form-group">
                    <label for="logo_icon">Logo图标（Font Awesome）</label>
                    <input type="text" id="logo_icon" name="logo_icon" value="<?php echo $_POST['logo_icon'] ?? 'fas fa-user-circle'; ?>" placeholder="例如：fas fa-user-circle">
                </div>
                
                <div class="form-group">
                    <label for="bio">个人简介</label>
                    <textarea id="bio" name="bio" placeholder="简单介绍一下自己"><?php echo $_POST['bio'] ?? '这是一个个人介绍网站'; ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-download"></i> 完成安装</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        // 监听存储模式选择变化
        const storageModeRadios = document.querySelectorAll('input[name="storage_mode"]');
        const mysqlConfigDiv = document.getElementById('mysql_config');
        
        storageModeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.value === 'mysql') {
                    mysqlConfigDiv.style.display = 'block';
                    // 为数据库配置表单添加淡入动画
                    setTimeout(() => {
                        mysqlConfigDiv.style.opacity = '1';
                        mysqlConfigDiv.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    mysqlConfigDiv.style.opacity = '0';
                    mysqlConfigDiv.style.transform = 'translateY(-20px)';
                    // 为数据库配置表单添加淡出动画
                    setTimeout(() => {
                        mysqlConfigDiv.style.display = 'none';
                    }, 300);
                }
            });
        });
        
        // 初始化样式
        mysqlConfigDiv.style.opacity = '0';
        mysqlConfigDiv.style.transform = 'translateY(-20px)';
        mysqlConfigDiv.style.transition = 'opacity 0.3s ease, transform 0.3s ease, display 0.3s ease';
        
        // 表单提交前验证
        document.querySelector('form').addEventListener('submit', (e) => {
            const storageMode = document.querySelector('input[name="storage_mode"]:checked').value;
            if (storageMode === 'mysql') {
                const dbName = document.getElementById('db_name').value;
                const dbUsername = document.getElementById('db_username').value;
                
                if (!dbName) {
                    e.preventDefault();
                    alert('数据库名称不能为空');
                    return false;
                }
                
                if (!dbUsername) {
                    e.preventDefault();
                    alert('数据库用户名不能为空');
                    return false;
                }
            }
        });
    </script>
</body>
</html>