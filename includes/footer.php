    </main>
    
    <!-- 页脚 -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <h3>网站介绍</h3>
                    <p><?php 
                    // 确保能获取系统设置，并添加错误处理
                    $copyright_text = '© 2026 个人网站. All rights reserved.';
                    try {
                        require_once __DIR__ . '/functions.php';
                        $settings = get_system_settings();
                        if (isset($settings['copyright_text'])) {
                            $copyright_text = $settings['copyright_text'];
                        }
                    } catch (Exception $e) {
                        // 数据库连接失败时使用默认值
                    }
                    echo $copyright_text;
                    ?></p>
                </div>
                <div class="footer-links">
                    <h4>快速链接</h4>
                    <ul>
                        <?php 
                        // 获取所有页脚链接，并添加错误处理
                        $default_links = [
                            ['name' => '首页', 'url' => 'index.php', 'icon' => 'fas fa-home'],
                            ['name' => '个人介绍', 'url' => 'about.php', 'icon' => 'fas fa-user'],
                            ['name' => '文章', 'url' => 'articles.php', 'icon' => 'fas fa-newspaper'],
                            ['name' => '旗下站点', 'url' => 'sites.php', 'icon' => 'fas fa-globe']
                        ];
                        
                        try {
                            require_once __DIR__ . '/functions.php';
                            $footer_links = get_footer_links();
                            if (empty($footer_links)) {
                                $footer_links = $default_links;
                            }
                        } catch (Exception $e) {
                            // 数据库连接失败时使用默认链接
                            $footer_links = $default_links;
                        }
                        
                        foreach ($footer_links as $link): 
                        ?>
                            <li><a href="<?php echo $link['url']; ?>"><i class="<?php echo $link['icon']; ?>"></i> <?php echo $link['name']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="footer-social">
                    <h4>社交媒体</h4>
                    <ul class="social-icons">
                        <?php 
                        // 获取所有激活的社交图标，并添加错误处理
                        $default_icons = [
                            ['url' => 'https://weixin.qq.com', 'icon_class' => 'fab fa-weixin'],
                            ['url' => 'https://weibo.com', 'icon_class' => 'fab fa-weibo'],
                            ['url' => 'https://github.com', 'icon_class' => 'fab fa-github'],
                            ['url' => 'https://linkedin.com', 'icon_class' => 'fab fa-linkedin']
                        ];
                        
                        try {
                            require_once __DIR__ . '/functions.php';
                            $social_icons = get_social_icons();
                            if (empty($social_icons)) {
                                $social_icons = $default_icons;
                            }
                        } catch (Exception $e) {
                            // 数据库连接失败时使用默认图标
                            $social_icons = $default_icons;
                        }
                        
                        // 循环输出社交图标
                        foreach ($social_icons as $icon): 
                        ?>
                            <li><a href="<?php echo $icon['url']; ?>" target="_blank"><i class="<?php echo $icon['icon_class']; ?>"></i></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- 引入JavaScript文件 -->
    <script src="js/main.js"></script>
</body>
</html>