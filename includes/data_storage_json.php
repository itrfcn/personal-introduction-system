<?php
/**
 * JSON文件存储适配器
 */

/**
 * JSON数据存储适配器类
 */
class JSONStorageAdapter {
    
    /**
     * 数据目录
     */
    private $data_dir;
    
    /**
     * 构造函数
     */
    public function __construct() {
        $this->data_dir = __DIR__ . '/../data/';
        
        // 确保数据目录存在
        if (!is_dir($this->data_dir)) {
            mkdir($this->data_dir, 0755, true);
        }
    }
    
    /**
     * 读取JSON文件
     * @param string $filename 文件名
     * @return array 数据
     */
    private function read_json_file($filename) {
        $file_path = $this->data_dir . $filename;
        
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            return json_decode($content, true) ?: [];
        }
        
        return [];
    }
    
    /**
     * 写入JSON文件
     * @param string $filename 文件名
     * @param array $data 数据
     * @return bool 是否成功
     */
    private function write_json_file($filename, $data) {
        $file_path = $this->data_dir . $filename;
        return file_put_contents($file_path, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) !== false;
    }
    
    /**
     * 获取下一个ID
     * @param array $data 数据数组
     * @return int 下一个ID
     */
    private function get_next_id($data) {
        if (empty($data)) {
            return 1;
        }
        
        $max_id = 0;
        foreach ($data as $item) {
            if (isset($item['id']) && $item['id'] > $max_id) {
                $max_id = $item['id'];
            }
        }
        
        return $max_id + 1;
    }
    
    /**
     * 初始化默认数据
     */
    public function init_default_data() {
        // 只创建必要的空文件，不添加任何默认数据
        
        // 创建空的用户文件
        $users = $this->read_json_file('users.json');
        if (empty($users)) {
            $this->write_json_file('users.json', []);
        }
        
        // 创建空的个人信息文件
        $personal_info = $this->read_json_file('personal_info.json');
        if (empty($personal_info)) {
            $this->write_json_file('personal_info.json', []);
        }
        
        // 创建空的文章文件
        $articles = $this->read_json_file('articles.json');
        if (empty($articles)) {
            $this->write_json_file('articles.json', []);
        }
        
        // 创建空的站点文件
        $sites = $this->read_json_file('sites.json');
        if (empty($sites)) {
            $this->write_json_file('sites.json', []);
        }
        
        // 创建空的社交图标文件
        $social_icons = $this->read_json_file('social_icons.json');
        if (empty($social_icons)) {
            $this->write_json_file('social_icons.json', []);
        }
        
        // 创建空的系统设置文件
        $system_settings = $this->read_json_file('system_settings.json');
        if (empty($system_settings)) {
            $this->write_json_file('system_settings.json', []);
        }
        
        // 创建空的页脚链接文件
        $footer_links = $this->read_json_file('footer_links.json');
        if (empty($footer_links)) {
            $this->write_json_file('footer_links.json', []);
        }
    }
    
    /**
     * 获取所有用户
     * @return array 用户列表
     */
    public function get_all_users() {
        return $this->read_json_file('users.json');
    }
    
    /**
     * 根据用户名获取用户
     * @param string $username 用户名
     * @return array|null 用户信息
     */
    public function get_user_by_username($username) {
        $users = $this->read_json_file('users.json');
        
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }
        
        return null;
    }
    
    /**
     * 更新用户密码
     * @param int $id 用户ID
     * @param string $password 新密码
     * @return bool 是否成功
     */
    public function update_user_password($id, $password) {
        $users = $this->read_json_file('users.json');
        
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                // 对新密码进行哈希处理
                $user['password'] = password_hash($password, PASSWORD_DEFAULT);
                
                // 更新修改时间
                if (!isset($user['updated_at'])) {
                    $user['created_at'] = date('Y-m-d H:i:s');
                }
                $user['updated_at'] = date('Y-m-d H:i:s');
                
                return $this->write_json_file('users.json', $users);
            }
        }
        
        return false;
    }
    
    /**
     * 添加用户
     * @param array $user 用户信息
     * @return int|bool 成功返回用户ID，失败返回false
     */
    public function add_user($user) {
        $users = $this->read_json_file('users.json');
        
        // 检查用户名是否已存在
        foreach ($users as $existing_user) {
            if ($existing_user['username'] === $user['username']) {
                return false;
            }
        }
        
        // 生成新用户ID
        $user['id'] = $this->get_next_id($users);
        
        // 设置创建时间
        $user['created_at'] = date('Y-m-d H:i:s');
        
        // 如果没有密码哈希，对密码进行哈希处理
        if (isset($user['password']) && !empty($user['password']) && !password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }
        
        // 添加新用户
        $users[] = $user;
        
        // 保存到文件
        if ($this->write_json_file('users.json', $users)) {
            return $user['id'];
        }
        
        return false;
    }
    
    /**
     * 获取个人信息
     * @return array|null 个人信息
     */
    public function get_personal_info() {
        $personal_info = $this->read_json_file('personal_info.json');
        return !empty($personal_info) ? $personal_info[0] : null;
    }
    
    /**
     * 更新个人信息
     * @param array $data 个人信息数据
     * @return bool 是否成功
     */
    public function update_personal_info($data) {
        $personal_info = $this->read_json_file('personal_info.json');
        
        $info = !empty($personal_info) ? $personal_info[0] : [];
        
        $info['name'] = $data['name'] ?? '';
        $info['avatar'] = $data['avatar'] ?? '';
        $info['bio'] = $data['bio'] ?? '';
        $info['skills'] = $data['skills'] ?? '';
        $info['education'] = $data['education'] ?? '';
        $info['experience'] = $data['experience'] ?? '';
        $info['updated_at'] = date('Y-m-d H:i:s');
        
        if (empty($info['id'])) {
            $info['id'] = 1;
        }
        
        $personal_info[0] = $info;
        return $this->write_json_file('personal_info.json', $personal_info);
    }
    
    /**
     * 获取所有文章
     * @param string $status 文章状态 (published/draft/all)
     * @param int $limit 限制数量
     * @return array 文章列表
     */
    public function get_articles($status = 'published', $limit = 100) {
        $articles = $this->read_json_file('articles.json');
        
        // 按创建时间倒序排序
        usort($articles, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // 过滤状态
        if ($status !== 'all') {
            $articles = array_filter($articles, function($article) use ($status) {
                return $article['status'] === $status;
            });
        }
        
        // 限制数量
        return array_slice($articles, 0, $limit);
    }
    
    /**
     * 根据ID获取文章
     * @param int $id 文章ID
     * @return array|null 文章信息
     */
    public function get_article_by_id($id) {
        $articles = $this->read_json_file('articles.json');
        
        foreach ($articles as $article) {
            if ($article['id'] == $id) {
                return $article;
            }
        }
        
        return null;
    }
    
    /**
     * 添加文章
     * @param array $data 文章数据
     * @return int|bool 成功返回文章ID，失败返回false
     */
    public function add_article($data) {
        $articles = $this->read_json_file('articles.json');
        
        $article = [
            'id' => $this->get_next_id($articles),
            'title' => $data['title'] ?? '',
            'content' => $data['content'] ?? '',
            'excerpt' => $data['excerpt'] ?? '',
            'image' => $data['image'] ?? '',
            'category' => $data['category'] ?? '',
            'status' => $data['status'] ?? 'draft',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $articles[] = $article;
        
        if ($this->write_json_file('articles.json', $articles)) {
            return $article['id'];
        }
        
        return false;
    }
    
    /**
     * 更新文章
     * @param int $id 文章ID
     * @param array $data 文章数据
     * @return bool 是否成功
     */
    public function update_article($id, $data) {
        $articles = $this->read_json_file('articles.json');
        
        foreach ($articles as &$article) {
            if ($article['id'] == $id) {
                $article['title'] = $data['title'] ?? $article['title'];
                $article['content'] = $data['content'] ?? $article['content'];
                $article['excerpt'] = $data['excerpt'] ?? $article['excerpt'];
                $article['image'] = $data['image'] ?? $article['image'];
                $article['category'] = $data['category'] ?? $article['category'];
                $article['status'] = $data['status'] ?? $article['status'];
                $article['updated_at'] = date('Y-m-d H:i:s');
                
                return $this->write_json_file('articles.json', $articles);
            }
        }
        
        return false;
    }
    
    /**
     * 删除文章
     * @param int $id 文章ID
     * @return bool 是否成功
     */
    public function delete_article($id) {
        $articles = $this->read_json_file('articles.json');
        
        $new_articles = array_filter($articles, function($article) use ($id) {
            return $article['id'] != $id;
        });
        
        if (count($new_articles) !== count($articles)) {
            return $this->write_json_file('articles.json', $new_articles);
        }
        
        return false;
    }
    
    /**
     * 获取所有站点
     * @return array 站点列表
     */
    public function get_sites() {
        $sites = $this->read_json_file('sites.json');
        
        // 按创建时间倒序排序
        usort($sites, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $sites;
    }
    
    /**
     * 根据ID获取站点
     * @param int $id 站点ID
     * @return array|null 站点信息
     */
    public function get_site_by_id($id) {
        $sites = $this->read_json_file('sites.json');
        
        foreach ($sites as $site) {
            if ($site['id'] == $id) {
                return $site;
            }
        }
        
        return null;
    }
    
    /**
     * 添加站点
     * @param array $data 站点数据
     * @return int|bool 成功返回站点ID，失败返回false
     */
    public function add_site($data) {
        $sites = $this->read_json_file('sites.json');
        
        $site = [
            'id' => $this->get_next_id($sites),
            'name' => $data['name'] ?? '',
            'url' => $data['url'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $sites[] = $site;
        
        if ($this->write_json_file('sites.json', $sites)) {
            return $site['id'];
        }
        
        return false;
    }
    
    /**
     * 更新站点
     * @param int $id 站点ID
     * @param array $data 站点数据
     * @return bool 是否成功
     */
    public function update_site($id, $data) {
        $sites = $this->read_json_file('sites.json');
        
        foreach ($sites as &$site) {
            if ($site['id'] == $id) {
                $site['name'] = $data['name'] ?? $site['name'];
                $site['url'] = $data['url'] ?? $site['url'];
                $site['description'] = $data['description'] ?? $site['description'];
                $site['image'] = $data['image'] ?? $site['image'];
                
                return $this->write_json_file('sites.json', $sites);
            }
        }
        
        return false;
    }
    
    /**
     * 删除站点
     * @param int $id 站点ID
     * @return bool 是否成功
     */
    public function delete_site($id) {
        $sites = $this->read_json_file('sites.json');
        
        $new_sites = array_filter($sites, function($site) use ($id) {
            return $site['id'] != $id;
        });
        
        if (count($new_sites) !== count($sites)) {
            return $this->write_json_file('sites.json', $new_sites);
        }
        
        return false;
    }
    
    /**
     * 获取所有社交图标
     * @return array 社交图标列表
     */
    public function get_social_icons() {
        $social_icons = $this->read_json_file('social_icons.json');
        
        // 过滤激活状态
        $social_icons = array_filter($social_icons, function($icon) {
            return $icon['status'] === 'active';
        });
        
        // 按排序顺序排序
        usort($social_icons, function($a, $b) {
            return $a['sort_order'] - $b['sort_order'];
        });
        
        return $social_icons;
    }
    
    /**
     * 获取所有社交图标（包括非激活状态）
     * @return array 社交图标列表
     */
    public function get_all_social_icons() {
        $social_icons = $this->read_json_file('social_icons.json');
        
        // 按排序顺序排序
        usort($social_icons, function($a, $b) {
            return $a['sort_order'] - $b['sort_order'];
        });
        
        return $social_icons;
    }
    
    /**
     * 根据ID获取社交图标
     * @param int $id 社交图标ID
     * @return array|null 社交图标信息
     */
    public function get_social_icon_by_id($id) {
        $social_icons = $this->read_json_file('social_icons.json');
        
        foreach ($social_icons as $icon) {
            if ($icon['id'] == $id) {
                return $icon;
            }
        }
        
        return null;
    }
    
    /**
     * 添加社交图标
     * @param array $data 社交图标数据
     * @return int|bool 成功返回图标ID，失败返回false
     */
    public function add_social_icon($data) {
        $social_icons = $this->read_json_file('social_icons.json');
        
        $icon = [
            'id' => $this->get_next_id($social_icons),
            'platform' => $data['platform'] ?? '',
            'icon_class' => $data['icon_class'] ?? '',
            'url' => $data['url'] ?? '',
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $data['status'] ?? 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $social_icons[] = $icon;
        
        if ($this->write_json_file('social_icons.json', $social_icons)) {
            return $icon['id'];
        }
        
        return false;
    }
    
    /**
     * 更新社交图标
     * @param int $id 社交图标ID
     * @param array $data 社交图标数据
     * @return bool 是否成功
     */
    public function update_social_icon($id, $data) {
        $social_icons = $this->read_json_file('social_icons.json');
        
        foreach ($social_icons as &$icon) {
            if ($icon['id'] == $id) {
                $icon['platform'] = $data['platform'] ?? $icon['platform'];
                $icon['icon_class'] = $data['icon_class'] ?? $icon['icon_class'];
                $icon['url'] = $data['url'] ?? $icon['url'];
                $icon['sort_order'] = $data['sort_order'] ?? $icon['sort_order'];
                $icon['status'] = $data['status'] ?? $icon['status'];
                $icon['updated_at'] = date('Y-m-d H:i:s');
                
                return $this->write_json_file('social_icons.json', $social_icons);
            }
        }
        
        return false;
    }
    
    /**
     * 删除社交图标
     * @param int $id 社交图标ID
     * @return bool 是否成功
     */
    public function delete_social_icon($id) {
        $social_icons = $this->read_json_file('social_icons.json');
        
        $new_icons = array_filter($social_icons, function($icon) use ($id) {
            return $icon['id'] != $id;
        });
        
        if (count($new_icons) !== count($social_icons)) {
            return $this->write_json_file('social_icons.json', $new_icons);
        }
        
        return false;
    }
    
    /**
     * 获取系统设置
     * @return array 系统设置
     */
    public function get_system_settings() {
        $system_settings = $this->read_json_file('system_settings.json');
        
        // 检查是否是索引数组（包含多个设置项）还是关联数组（单个设置项）
        if (is_array($system_settings) && !empty($system_settings)) {
            if (isset($system_settings[0]) && is_array($system_settings[0])) {
                // 是索引数组，返回第一个设置项
                return $system_settings[0];
            } else {
                // 是关联数组，直接返回
                return $system_settings;
            }
        }
        
        return null;
    }
    
    /**
     * 更新系统设置
     * @param array $data 系统设置数据
     * @return bool 是否成功
     */
    public function update_system_settings($data) {
        $existing_settings = $this->read_json_file('system_settings.json');
        
        // 确定当前文件格式
        if (is_array($existing_settings) && isset($existing_settings[0]) && is_array($existing_settings[0])) {
            // 文件格式是索引数组（包含多个设置项）
            $settings = $existing_settings[0];
            
            $settings['site_title'] = $data['site_title'] ?? $settings['site_title'] ?? '';
            $settings['site_subtitle'] = $data['site_subtitle'] ?? $settings['site_subtitle'] ?? '';
            $settings['site_url'] = $data['site_url'] ?? $settings['site_url'] ?? '';
            $settings['site_description'] = $data['site_description'] ?? $settings['site_description'] ?? '';
            $settings['copyright_text'] = $data['copyright_text'] ?? $settings['copyright_text'] ?? '';
            $settings['logo_text'] = $data['logo_text'] ?? $settings['logo_text'] ?? '';
            $settings['logo_icon'] = $data['logo_icon'] ?? $settings['logo_icon'] ?? '';
            $settings['updated_at'] = date('Y-m-d H:i:s');
            
            if (empty($settings['id'])) {
                $settings['id'] = 1;
                $settings['created_at'] = date('Y-m-d H:i:s');
            }
            
            $existing_settings[0] = $settings;
            return $this->write_json_file('system_settings.json', $existing_settings);
        } else {
            // 文件格式是关联数组（单个设置项）
            $settings = is_array($existing_settings) ? $existing_settings : [];
            
            $settings['site_title'] = $data['site_title'] ?? $settings['site_title'] ?? '';
            $settings['site_subtitle'] = $data['site_subtitle'] ?? $settings['site_subtitle'] ?? '';
            $settings['site_url'] = $data['site_url'] ?? $settings['site_url'] ?? '';
            $settings['site_description'] = $data['site_description'] ?? $settings['site_description'] ?? '';
            $settings['copyright_text'] = $data['copyright_text'] ?? $settings['copyright_text'] ?? '';
            $settings['logo_text'] = $data['logo_text'] ?? $settings['logo_text'] ?? '';
            $settings['logo_icon'] = $data['logo_icon'] ?? $settings['logo_icon'] ?? '';
            $settings['updated_at'] = date('Y-m-d H:i:s');
            
            if (empty($settings['id'])) {
                $settings['id'] = 1;
                $settings['created_at'] = date('Y-m-d H:i:s');
            }
            
            return $this->write_json_file('system_settings.json', $settings);
        }
    }
    
    /**
     * 获取页脚链接
     * @return array 页脚链接列表
     */
    public function get_footer_links() {
        $footer_links = $this->read_json_file('footer_links.json');
        
        // 按排序顺序排序
        usort($footer_links, function($a, $b) {
            return $a['sort_order'] - $b['sort_order'];
        });
        
        return $footer_links;
    }
}

// 创建JSON存储适配器实例
$storage_adapter = new JSONStorageAdapter();