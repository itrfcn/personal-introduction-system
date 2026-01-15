<?php
/**
 * MySQL数据库存储适配器
 */

// 建立数据库连接
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// 检查连接是否成功
if (!$conn) {
    die("数据库连接失败: " . mysqli_connect_error());
}

// 设置字符集
mysqli_set_charset($conn, "utf8mb4");

/**
 * 执行SQL查询（INSERT/UPDATE/DELETE）
 * @param string $sql SQL查询语句
 * @return int|bool 成功返回插入ID或受影响行数，失败返回false
 */
function execute($sql) {
    global $conn;
    if (mysqli_query($conn, $sql)) {
        return mysqli_insert_id($conn) > 0 ? mysqli_insert_id($conn) : mysqli_affected_rows($conn);
    }
    return false;
}

/**
 * 获取所有查询结果
 * @param string $sql SQL查询语句
 * @return array 查询结果数组
 */
function fetch_all($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return [];
    }
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_free_result($result);
    return $rows;
}

/**
 * 获取单行查询结果
 * @param string $sql SQL查询语句
 * @return array|null 查询结果
 */
function fetch_one($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return null;
    }
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $row;
}

/**
 * 转义字符串
 * @param string $str 要转义的字符串
 * @return string 转义后的字符串
 */
function escape_string($str) {
    global $conn;
    return mysqli_real_escape_string($conn, $str);
}

/**
 * MySQL数据存储适配器类
 */
class MySQLStorageAdapter {
    
    /**
     * 初始化默认数据
     */
    public function init_default_data() {
        // 创建用户表
        execute("CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // 创建个人信息表
        execute("CREATE TABLE IF NOT EXISTS personal_info (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            avatar VARCHAR(255) DEFAULT '',
            bio TEXT,
            skills VARCHAR(255) DEFAULT '',
            education TEXT,
            experience TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // 创建文章表
        execute("CREATE TABLE IF NOT EXISTS articles (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            content LONGTEXT NOT NULL,
            excerpt VARCHAR(255) DEFAULT '',
            image VARCHAR(255) DEFAULT '',
            category VARCHAR(50) DEFAULT '',
            status ENUM('published', 'draft', 'archived') DEFAULT 'published',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // 创建旗下站点表
        execute("CREATE TABLE IF NOT EXISTS sites (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            url VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255) DEFAULT '',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // 创建社交图标表
        execute("CREATE TABLE IF NOT EXISTS social_icons (
            id INT PRIMARY KEY AUTO_INCREMENT,
            platform VARCHAR(50) NOT NULL,
            icon_class VARCHAR(50) NOT NULL,
            url VARCHAR(255) NOT NULL,
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // 创建系统设置表
        execute("CREATE TABLE IF NOT EXISTS system_settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            site_title VARCHAR(100) DEFAULT '个人网站',
            site_subtitle VARCHAR(255) DEFAULT '',
            site_url VARCHAR(255) DEFAULT '',
            site_description TEXT,
            copyright_text VARCHAR(255) DEFAULT '',
            logo_text VARCHAR(100) DEFAULT '个人网站',
            logo_icon VARCHAR(50) DEFAULT 'fas fa-user-circle',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // 创建页脚链接表
        execute("CREATE TABLE IF NOT EXISTS footer_links (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL,
            url VARCHAR(255) NOT NULL,
            icon VARCHAR(50) NOT NULL,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // 不添加任何默认数据，只确保表结构存在
        // 表结构已经在前面创建完成，这里不需要添加任何数据
    }
    
    /**
     * 获取所有用户
     * @return array 用户列表
     */
    public function get_all_users() {
        return fetch_all("SELECT * FROM users");
    }
    
    /**
     * 根据用户名获取用户
     * @param string $username 用户名
     * @return array|null 用户信息
     */
    public function get_user_by_username($username) {
        $username = escape_string($username);
        return fetch_one("SELECT * FROM users WHERE username = '$username'");
    }
    
    /**
     * 更新用户密码
     * @param int $id 用户ID
     * @param string $password 新密码
     * @return bool 是否成功
     */
    public function update_user_password($id, $password) {
        $id = intval($id);
        
        // 对新密码进行哈希处理
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // 更新密码
        $result = execute("UPDATE users SET password = '$hashedPassword', updated_at = NOW() WHERE id = $id");
        
        return $result !== false;
    }
    
    /**
     * 添加用户
     * @param array $user 用户信息
     * @return int|bool 成功返回用户ID，失败返回false
     */
    public function add_user($user) {
        // 转义特殊字符
        $username = escape_string($user['username']);
        
        // 检查用户名是否已存在
        $existing_user = fetch_one("SELECT * FROM users WHERE username = '$username'");
        if ($existing_user) {
            return false;
        }
        
        // 对密码进行哈希处理
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        
        // 插入用户
        $result = execute("INSERT INTO users (username, password, created_at) VALUES ('$username', '$password', NOW())");
        
        return $result !== false ? $result : false;
    }
    
    /**
     * 获取个人信息
     * @return array|null 个人信息
     */
    public function get_personal_info() {
        return fetch_one("SELECT * FROM personal_info");
    }
    
    /**
     * 更新个人信息
     * @param array $data 个人信息数据
     * @return bool 是否成功
     */
    public function update_personal_info($data) {
        // 转义特殊字符
        $name = escape_string($data['name']);
        $avatar = escape_string($data['avatar']);
        $bio = escape_string($data['bio']);
        $skills = escape_string($data['skills']);
        $education = escape_string($data['education']);
        $experience = escape_string($data['experience']);
        
        // 检查个人信息是否存在
        $info = fetch_one("SELECT * FROM personal_info");
        
        if (empty($info)) {
            // 添加新信息
            $result = execute("INSERT INTO personal_info (name, avatar, bio, skills, education, experience, updated_at) VALUES ('$name', '$avatar', '$bio', '$skills', '$education', '$experience', NOW())");
        } else {
            // 更新现有信息
            $result = execute("UPDATE personal_info SET name = '$name', avatar = '$avatar', bio = '$bio', skills = '$skills', education = '$education', experience = '$experience', updated_at = NOW()");
        }
        
        return $result !== false;
    }
    
    /**
     * 获取所有文章
     * @param string $status 文章状态 (published/draft/all)
     * @param int $limit 限制数量
     * @return array 文章列表
     */
    public function get_articles($status = 'published', $limit = 100) {
        if ($status !== 'all') {
            $status = escape_string($status);
            return fetch_all("SELECT * FROM articles WHERE status = '$status' ORDER BY created_at DESC LIMIT $limit");
        } else {
            return fetch_all("SELECT * FROM articles ORDER BY created_at DESC LIMIT $limit");
        }
    }
    
    /**
     * 根据ID获取文章
     * @param int $id 文章ID
     * @return array|null 文章信息
     */
    public function get_article_by_id($id) {
        $id = intval($id);
        return fetch_one("SELECT * FROM articles WHERE id = $id");
    }
    
    /**
     * 添加文章
     * @param array $data 文章数据
     * @return int|bool 成功返回文章ID，失败返回false
     */
    public function add_article($data) {
        // 转义特殊字符
        $title = escape_string($data['title']);
        $content = escape_string($data['content']);
        $excerpt = escape_string($data['excerpt']);
        $image = escape_string($data['image']);
        $category = escape_string($data['category']);
        $status = escape_string($data['status']);
        
        // 插入文章
        $result = execute("INSERT INTO articles (title, content, excerpt, image, category, status, created_at, updated_at) VALUES ('$title', '$content', '$excerpt', '$image', '$category', '$status', NOW(), NOW())");
        
        return $result !== false ? $result : false;
    }
    
    /**
     * 更新文章
     * @param int $id 文章ID
     * @param array $data 文章数据
     * @return bool 是否成功
     */
    public function update_article($id, $data) {
        $id = intval($id);
        
        // 转义特殊字符
        $title = escape_string($data['title']);
        $content = escape_string($data['content']);
        $excerpt = escape_string($data['excerpt']);
        $image = escape_string($data['image']);
        $category = escape_string($data['category']);
        $status = escape_string($data['status']);
        
        // 更新文章
        $result = execute("UPDATE articles SET title = '$title', content = '$content', excerpt = '$excerpt', image = '$image', category = '$category', status = '$status', updated_at = NOW() WHERE id = $id");
        
        return $result !== false;
    }
    
    /**
     * 删除文章
     * @param int $id 文章ID
     * @return bool 是否成功
     */
    public function delete_article($id) {
        $id = intval($id);
        $result = execute("DELETE FROM articles WHERE id = $id");
        return $result !== false;
    }
    
    /**
     * 获取所有站点
     * @return array 站点列表
     */
    public function get_sites() {
        return fetch_all("SELECT * FROM sites ORDER BY created_at DESC");
    }
    
    /**
     * 根据ID获取站点
     * @param int $id 站点ID
     * @return array|null 站点信息
     */
    public function get_site_by_id($id) {
        $id = intval($id);
        return fetch_one("SELECT * FROM sites WHERE id = $id");
    }
    
    /**
     * 添加站点
     * @param array $data 站点数据
     * @return int|bool 成功返回站点ID，失败返回false
     */
    public function add_site($data) {
        // 转义特殊字符
        $name = escape_string($data['name']);
        $url = escape_string($data['url']);
        $description = escape_string($data['description']);
        $image = escape_string($data['image']);
        
        // 插入站点
        $result = execute("INSERT INTO sites (name, url, description, image, created_at) VALUES ('$name', '$url', '$description', '$image', NOW())");
        
        return $result !== false ? $result : false;
    }
    
    /**
     * 更新站点
     * @param int $id 站点ID
     * @param array $data 站点数据
     * @return bool 是否成功
     */
    public function update_site($id, $data) {
        $id = intval($id);
        
        // 转义特殊字符
        $name = escape_string($data['name']);
        $url = escape_string($data['url']);
        $description = escape_string($data['description']);
        $image = escape_string($data['image']);
        
        // 更新站点
        $result = execute("UPDATE sites SET name = '$name', url = '$url', description = '$description', image = '$image' WHERE id = $id");
        
        return $result !== false;
    }
    
    /**
     * 删除站点
     * @param int $id 站点ID
     * @return bool 是否成功
     */
    public function delete_site($id) {
        $id = intval($id);
        $result = execute("DELETE FROM sites WHERE id = $id");
        return $result !== false;
    }
    
    /**
     * 获取所有社交图标
     * @return array 社交图标列表
     */
    public function get_social_icons() {
        return fetch_all("SELECT * FROM social_icons WHERE status = 'active' ORDER BY sort_order");
    }
    
    /**
     * 获取所有社交图标（包括非激活状态）
     * @return array 社交图标列表
     */
    public function get_all_social_icons() {
        return fetch_all("SELECT * FROM social_icons ORDER BY sort_order");
    }
    
    /**
     * 根据ID获取社交图标
     * @param int $id 社交图标ID
     * @return array|null 社交图标信息
     */
    public function get_social_icon_by_id($id) {
        $id = intval($id);
        return fetch_one("SELECT * FROM social_icons WHERE id = $id");
    }
    
    /**
     * 添加社交图标
     * @param array $data 社交图标数据
     * @return int|bool 成功返回图标ID，失败返回false
     */
    public function add_social_icon($data) {
        // 转义特殊字符
        $platform = escape_string($data['platform']);
        $icon_class = escape_string($data['icon_class']);
        $url = escape_string($data['url']);
        $sort_order = intval($data['sort_order'] ?? 0);
        $status = escape_string($data['status'] ?? 'active');
        
        // 插入社交图标
        $result = execute("INSERT INTO social_icons (platform, icon_class, url, sort_order, status, created_at, updated_at) VALUES ('$platform', '$icon_class', '$url', $sort_order, '$status', NOW(), NOW())");
        
        return $result !== false ? $result : false;
    }
    
    /**
     * 更新社交图标
     * @param int $id 社交图标ID
     * @param array $data 社交图标数据
     * @return bool 是否成功
     */
    public function update_social_icon($id, $data) {
        $id = intval($id);
        
        // 转义特殊字符
        $platform = escape_string($data['platform']);
        $icon_class = escape_string($data['icon_class']);
        $url = escape_string($data['url']);
        $sort_order = intval($data['sort_order']);
        $status = escape_string($data['status']);
        
        // 更新社交图标
        $result = execute("UPDATE social_icons SET platform = '$platform', icon_class = '$icon_class', url = '$url', sort_order = $sort_order, status = '$status', updated_at = NOW() WHERE id = $id");
        
        return $result !== false;
    }
    
    /**
     * 删除社交图标
     * @param int $id 社交图标ID
     * @return bool 是否成功
     */
    public function delete_social_icon($id) {
        $id = intval($id);
        $result = execute("DELETE FROM social_icons WHERE id = $id");
        return $result !== false;
    }
    
    /**
     * 获取系统设置
     * @return array 系统设置
     */
    public function get_system_settings() {
        return fetch_one("SELECT * FROM system_settings");
    }
    
    /**
     * 更新系统设置
     * @param array $data 系统设置数据
     * @return bool 是否成功
     */
    public function update_system_settings($data) {
        // 检查系统设置是否存在
        $settings = fetch_one("SELECT * FROM system_settings");
        
        // 转义特殊字符
        $site_title = escape_string($data['site_title'] ?? '');
        $site_subtitle = escape_string($data['site_subtitle'] ?? '');
        $site_url = escape_string($data['site_url'] ?? '');
        $site_description = escape_string($data['site_description'] ?? '');
        $copyright_text = escape_string($data['copyright_text'] ?? '');
        $logo_text = escape_string($data['logo_text'] ?? '');
        $logo_icon = escape_string($data['logo_icon'] ?? '');
        
        if (empty($settings)) {
            // 添加新设置
            $result = execute("INSERT INTO system_settings (site_title, site_subtitle, site_url, site_description, copyright_text, logo_text, logo_icon, created_at, updated_at) VALUES ('$site_title', '$site_subtitle', '$site_url', '$site_description', '$copyright_text', '$logo_text', '$logo_icon', NOW(), NOW())");
        } else {
            // 更新现有设置
            $result = execute("UPDATE system_settings SET site_title = '$site_title', site_subtitle = '$site_subtitle', site_url = '$site_url', site_description = '$site_description', copyright_text = '$copyright_text', logo_text = '$logo_text', logo_icon = '$logo_icon', updated_at = NOW()");
        }
        
        return $result !== false;
    }
    
    /**
     * 获取页脚链接
     * @return array 页脚链接列表
     */
    public function get_footer_links() {
        return fetch_all("SELECT * FROM footer_links ORDER BY sort_order");
    }
}

// 创建MySQL存储适配器实例
$storage_adapter = new MySQLStorageAdapter();