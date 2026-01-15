<?php
/**
 * 统一数据存储接口
 * 支持MySQL数据库和JSON文件两种存储方式
 */

// 引入配置文件
require_once __DIR__ . '/config.php';

/**
 * 根据存储模式加载对应的数据存储实现
 */
if (STORAGE_MODE === 'mysql') {
    // 使用MySQL数据库存储模式
    require_once __DIR__ . '/data_storage_mysql.php';
} else {
    // 使用JSON文件存储模式
    require_once __DIR__ . '/data_storage_json.php';
}

/**
 * 获取所有用户
 * @return array 用户列表
 */
function get_all_users() {
    global $storage_adapter;
    return $storage_adapter->get_all_users();
}

/**
 * 根据用户名获取用户
 * @param string $username 用户名
 * @return array|null 用户信息
 */
function get_user_by_username($username) {
    global $storage_adapter;
    return $storage_adapter->get_user_by_username($username);
}

/**
 * 更新用户密码
 * @param int $id 用户ID
 * @param string $password 新密码
 * @return bool 是否成功
 */
function update_user_password($id, $password) {
    global $storage_adapter;
    return $storage_adapter->update_user_password($id, $password);
}

/**
 * 添加用户
 * @param array $user 用户信息
 * @return int|bool 成功返回用户ID，失败返回false
 */
function add_user($user) {
    global $storage_adapter;
    return $storage_adapter->add_user($user);
}

/**
 * 获取个人信息
 * @return array|null 个人信息
 */
function get_personal_info() {
    global $storage_adapter;
    return $storage_adapter->get_personal_info();
}

/**
 * 更新个人信息
 * @param array $data 个人信息数据
 * @return bool 是否成功
 */
function update_personal_info($data) {
    global $storage_adapter;
    return $storage_adapter->update_personal_info($data);
}

/**
 * 获取所有文章
 * @param string $status 文章状态 (published/draft/all)
 * @param int $limit 限制数量
 * @return array 文章列表
 */
function get_articles($status = 'published', $limit = 100) {
    global $storage_adapter;
    return $storage_adapter->get_articles($status, $limit);
}

/**
 * 根据ID获取文章
 * @param int $id 文章ID
 * @return array|null 文章信息
 */
function get_article_by_id($id) {
    global $storage_adapter;
    return $storage_adapter->get_article_by_id($id);
}

/**
 * 添加文章
 * @param array $data 文章数据
 * @return int|bool 成功返回文章ID，失败返回false
 */
function add_article($data) {
    global $storage_adapter;
    return $storage_adapter->add_article($data);
}

/**
 * 更新文章
 * @param int $id 文章ID
 * @param array $data 文章数据
 * @return bool 是否成功
 */
function update_article($id, $data) {
    global $storage_adapter;
    return $storage_adapter->update_article($id, $data);
}

/**
 * 删除文章
 * @param int $id 文章ID
 * @return bool 是否成功
 */
function delete_article($id) {
    global $storage_adapter;
    return $storage_adapter->delete_article($id);
}

/**
 * 获取所有站点
 * @return array 站点列表
 */
function get_sites() {
    global $storage_adapter;
    return $storage_adapter->get_sites();
}

/**
 * 根据ID获取站点
 * @param int $id 站点ID
 * @return array|null 站点信息
 */
function get_site_by_id($id) {
    global $storage_adapter;
    return $storage_adapter->get_site_by_id($id);
}

/**
 * 添加站点
 * @param array $data 站点数据
 * @return int|bool 成功返回站点ID，失败返回false
 */
function add_site($data) {
    global $storage_adapter;
    return $storage_adapter->add_site($data);
}

/**
 * 更新站点
 * @param int $id 站点ID
 * @param array $data 站点数据
 * @return bool 是否成功
 */
function update_site($id, $data) {
    global $storage_adapter;
    return $storage_adapter->update_site($id, $data);
}

/**
 * 删除站点
 * @param int $id 站点ID
 * @return bool 是否成功
 */
function delete_site($id) {
    global $storage_adapter;
    return $storage_adapter->delete_site($id);
}

/**
 * 获取所有社交图标
 * @return array 社交图标列表
 */
function get_social_icons() {
    global $storage_adapter;
    return $storage_adapter->get_social_icons();
}

/**
 * 获取所有社交图标（包括非激活状态）
 * @return array 社交图标列表
 */
function get_all_social_icons() {
    global $storage_adapter;
    return $storage_adapter->get_all_social_icons();
}

/**
 * 根据ID获取社交图标
 * @param int $id 社交图标ID
 * @return array|null 社交图标信息
 */
function get_social_icon_by_id($id) {
    global $storage_adapter;
    return $storage_adapter->get_social_icon_by_id($id);
}

/**
 * 添加社交图标
 * @param array $data 社交图标数据
 * @return int|bool 成功返回图标ID，失败返回false
 */
function add_social_icon($data) {
    global $storage_adapter;
    return $storage_adapter->add_social_icon($data);
}

/**
 * 更新社交图标
 * @param int $id 社交图标ID
 * @param array $data 社交图标数据
 * @return bool 是否成功
 */
function update_social_icon($id, $data) {
    global $storage_adapter;
    return $storage_adapter->update_social_icon($id, $data);
}

/**
 * 删除社交图标
 * @param int $id 社交图标ID
 * @return bool 是否成功
 */
function delete_social_icon($id) {
    global $storage_adapter;
    return $storage_adapter->delete_social_icon($id);
}

/**
 * 获取系统设置
 * @return array 系统设置
 */
function get_system_settings() {
    global $storage_adapter;
    return $storage_adapter->get_system_settings();
}

/**
 * 更新系统设置
 * @param array $data 系统设置数据
 * @return bool 是否成功
 */
function update_system_settings($data) {
    global $storage_adapter;
    return $storage_adapter->update_system_settings($data);
}

/**
 * 获取页脚链接
 * @return array 页脚链接列表
 */
function get_footer_links() {
    global $storage_adapter;
    return $storage_adapter->get_footer_links();
}

/**
 * 初始化默认数据
 */
function init_default_data() {
    global $storage_adapter;
    return $storage_adapter->init_default_data();
}

// 初始化默认数据
init_default_data();
?>