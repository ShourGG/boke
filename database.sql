-- Koi Blog Database Schema
-- MySQL database structure for personal blog with website directory

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: koi_blog

-- --------------------------------------------------------

-- Table structure for table `admins`
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `categories`
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text,
  `color` varchar(7) DEFAULT '#3498db',
  `sort_order` int(11) DEFAULT 0,
  `post_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `tags`
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `color` varchar(7) DEFAULT '#6c757d',
  `post_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `posts`
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('draft','published','private') DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `comment_count` int(11) DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `published_at` (`published_at`),
  KEY `is_featured` (`is_featured`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `post_tags`
CREATE TABLE `post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `comments`
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(100) NOT NULL,
  `author_url` varchar(255) DEFAULT NULL,
  `author_ip` varchar(45) DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('pending','approved','spam','trash') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `parent_id` (`parent_id`),
  KEY `status` (`status`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `website_categories`
CREATE TABLE `website_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text,
  `icon` varchar(50) DEFAULT 'fas fa-globe',
  `color` varchar(7) DEFAULT '#3498db',
  `sort_order` int(11) DEFAULT 0,
  `website_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `websites`
CREATE TABLE `websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL,
  `description` text,
  `screenshot` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `is_featured` tinyint(1) DEFAULT 0,
  `click_count` int(11) DEFAULT 0,
  `submitter_name` varchar(100) DEFAULT NULL,
  `submitter_email` varchar(100) DEFAULT NULL,
  `submitter_ip` varchar(45) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `is_featured` (`is_featured`),
  CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `website_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `settings`
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` longtext,
  `type` enum('string','text','number','boolean','json') DEFAULT 'string',
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Insert default data

-- Default admin user (username: admin, password: admin123)
INSERT INTO `admins` (`username`, `email`, `password`, `display_name`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

-- Default categories
INSERT INTO `categories` (`name`, `slug`, `description`, `color`) VALUES
('技术分享', 'tech', '分享编程技术和开发经验', '#3498db'),
('生活随笔', 'life', '记录生活点滴和个人感悟', '#e74c3c'),
('学习笔记', 'study', '学习过程中的笔记和总结', '#f39c12');

-- Default tags
INSERT INTO `tags` (`name`, `slug`, `color`) VALUES
('PHP', 'php', '#8892bf'),
('JavaScript', 'javascript', '#f7df1e'),
('MySQL', 'mysql', '#4479a1'),
('CSS', 'css', '#1572b6'),
('HTML', 'html', '#e34f26');

-- Default website categories
INSERT INTO `website_categories` (`name`, `slug`, `description`, `icon`, `color`) VALUES
('开发工具', 'dev-tools', '编程开发相关的工具和资源', 'fas fa-code', '#3498db'),
('设计资源', 'design', '设计相关的网站和资源', 'fas fa-palette', '#e74c3c'),
('学习教程', 'tutorials', '在线学习和教程网站', 'fas fa-graduation-cap', '#f39c12'),
('实用工具', 'utilities', '日常使用的实用工具网站', 'fas fa-tools', '#27ae60');

-- --------------------------------------------------------

-- Table structure for table `comments`
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT 0,
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(100) NOT NULL,
  `author_url` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','spam') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `parent_id` (`parent_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `performance_metrics`
CREATE TABLE `performance_metrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metric_type` varchar(50) NOT NULL,
  `metric_name` varchar(100) NOT NULL,
  `metric_value` decimal(10,3) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `metric_type` (`metric_type`),
  KEY `metric_name` (`metric_name`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Insert default admin user (password: admin123)
INSERT INTO `admins` (`username`, `email`, `password`, `display_name`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

-- Insert sample categories
INSERT INTO `categories` (`name`, `slug`, `description`, `color`, `sort_order`) VALUES
('技术分享', 'tech', '技术相关的文章和教程', '#3498db', 1),
('生活随笔', 'life', '生活感悟和随笔', '#e74c3c', 2),
('学习笔记', 'study', '学习过程中的笔记和总结', '#2ecc71', 3),
('项目经验', 'project', '项目开发经验分享', '#f39c12', 4);

-- Insert sample website categories
INSERT INTO `website_categories` (`name`, `slug`, `description`, `color`, `sort_order`) VALUES
('开发工具', 'dev-tools', '开发相关的工具和资源', '#3498db', 1),
('设计资源', 'design', '设计相关的网站和资源', '#e74c3c', 2),
('学习平台', 'learning', '在线学习和教育平台', '#2ecc71', 3),
('技术博客', 'tech-blog', '优秀的技术博客和文档', '#f39c12', 4),
('实用工具', 'utilities', '日常使用的实用工具', '#9b59b6', 5);

-- Insert sample tags
INSERT INTO `tags` (`name`, `slug`, `color`) VALUES
('PHP', 'php', '#777bb4'),
('JavaScript', 'javascript', '#f7df1e'),
('MySQL', 'mysql', '#4479a1'),
('Vue.js', 'vuejs', '#4fc08d'),
('Laravel', 'laravel', '#ff2d20'),
('Bootstrap', 'bootstrap', '#7952b3'),
('前端开发', 'frontend', '#61dafb'),
('后端开发', 'backend', '#68217a'),
('数据库', 'database', '#336791'),
('性能优化', 'performance', '#ff6b6b');

-- Default settings
INSERT INTO `settings` (`key`, `value`, `type`, `description`) VALUES
('site_name', 'Koi Blog', 'string', '网站名称'),
('site_description', 'A personal blog with website directory', 'string', '网站描述'),
('site_keywords', 'blog, personal, website directory', 'string', '网站关键词'),
('posts_per_page', '10', 'number', '每页文章数量'),
('websites_per_page', '20', 'number', '每页网站数量'),
('comment_moderation', '1', 'boolean', '评论审核'),
('allow_comments', '1', 'boolean', '允许评论');

COMMIT;
