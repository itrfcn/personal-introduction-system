-- 完整的个人网站数据库文件
-- 创建时间：2026-01-13

-- 创建数据库
CREATE DATABASE IF NOT EXISTS personal_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 使用数据库
USE personal_website;

-- 用户表
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 插入默认管理员用户
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$hh994uYkUO5pA9O5RXRTyuPvTNCN8JN2n432diPusLwXuUJ0RKA4u') ON DUPLICATE KEY UPDATE username=username;

-- 个人信息表
CREATE TABLE IF NOT EXISTS personal_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT '',
    bio TEXT,
    skills VARCHAR(255) DEFAULT '',
    education TEXT,
    experience TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 文章表
CREATE TABLE IF NOT EXISTS articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt VARCHAR(255) DEFAULT '',
    image VARCHAR(255) DEFAULT '',
    category VARCHAR(50) DEFAULT '',
    status ENUM('published', 'draft', 'archived') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 旗下站点表
CREATE TABLE IF NOT EXISTS sites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 社交图标表
CREATE TABLE IF NOT EXISTS social_icons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    platform VARCHAR(50) NOT NULL,
    icon_class VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 系统设置表
CREATE TABLE IF NOT EXISTS system_settings (
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
);

-- 页脚链接表
CREATE TABLE IF NOT EXISTS footer_links (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(50) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 显示数据库中的所有表
SHOW TABLES;

-- 显示表的记录数
SELECT 'users' AS table_name, COUNT(*) AS record_count FROM users UNION ALL
SELECT 'personal_info' AS table_name, COUNT(*) AS record_count FROM personal_info UNION ALL
SELECT 'articles' AS table_name, COUNT(*) AS record_count FROM articles UNION ALL
SELECT 'sites' AS table_name, COUNT(*) AS record_count FROM sites UNION ALL
SELECT 'social_icons' AS table_name, COUNT(*) AS record_count FROM social_icons UNION ALL
SELECT 'system_settings' AS table_name, COUNT(*) AS record_count FROM system_settings UNION ALL
SELECT 'footer_links' AS table_name, COUNT(*) AS record_count FROM footer_links;