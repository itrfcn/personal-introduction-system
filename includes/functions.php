<?php
/**
 * 通用函数库（JSON数据存储版本）
 * 包含用户验证、文章操作等功能
 */

// 引入安装检查机制
require_once __DIR__ . '/install_check.php';

// 引入JSON数据存储系统
require_once __DIR__ . '/data_storage.php';

/**
 * 用户登录验证
 * @param string $username 用户名
 * @param string $password 密码
 * @return bool|array 登录成功返回用户信息，失败返回false
 */
function login($username, $password) {
    try {
        // 根据用户名获取用户
        $user = get_user_by_username($username);
        
        if ($user) {
            // 验证密码
            if (password_verify($password, $user['password'])) {
                // 登录成功，设置session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                return $user;
            }
        }
        
        return false;
    } catch (Exception $e) {
        die("登录失败: " . $e->getMessage());
    }
}

/**
 * 检查用户是否已登录
 * @return bool 是否已登录
 */
function is_logged_in() {
    session_start();
    return isset($_SESSION['user_id']);
}

/**
 * 用户登出
 */
function logout() {
    session_start();
    session_destroy();
    header("Location: login.php");
    exit();
}

/**
 * 格式化日期时间
 * @param string $datetime 日期时间字符串
 * @param string $format 格式化模板
 * @return string 格式化后的日期时间
 */
function format_datetime($datetime, $format = 'Y-m-d H:i:s') {
    $date = new DateTime($datetime);
    return $date->format($format);
}

/**
 * 生成文章摘要
 * @param string $content 文章内容
 * @param int $length 摘要长度
 * @return string 文章摘要
 */
function generate_excerpt($content, $length = 150) {
    // 移除HTML标签
    $content = strip_tags($content);
    // 截取指定长度
    if (strlen($content) > $length) {
        $content = substr($content, 0, $length) . '...';
    }
    return $content;
}
?>