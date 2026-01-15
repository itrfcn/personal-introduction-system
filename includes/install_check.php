<?php
/**
 * 安装检查机制
 * 检查系统是否已安装，如果未安装则跳转到安装页面
 */

// 定义安装状态文件路径
$install_lock_file = __DIR__ . '/../data/install.lock';

// 排除安装页面本身，避免无限循环
$current_file = basename($_SERVER['SCRIPT_FILENAME']);
if ($current_file !== 'install.php') {
    // 检查是否已安装
    if (!file_exists($install_lock_file)) {
        // 未安装，跳转到安装页面
        header('Location: install.php');
        exit();
    }
}