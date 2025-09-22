<?php
/**
 * Admin Model
 * Handle admin user authentication and management
 */
class Admin extends BaseModel
{
    protected $table = 'admins';
    protected $fillable = [
        'username', 'email', 'password', 'display_name', 'avatar', 'status'
    ];
    
    /**
     * Authenticate admin user
     */
    public function authenticate($username, $password)
    {
        $sql = "SELECT * FROM `{$this->table}` 
                WHERE (`username` = ? OR `email` = ?) AND `status` = 'active' 
                LIMIT 1";
        
        $admin = $this->db->fetch($sql, [$username, $username]);
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Update last login
            $this->updateLastLogin($admin['id']);
            
            // Remove password from returned data
            unset($admin['password']);
            
            return $admin;
        }
        
        return false;
    }
    
    /**
     * Update last login timestamp
     */
    public function updateLastLogin($adminId)
    {
        $sql = "UPDATE `{$this->table}` SET `last_login` = NOW() WHERE `id` = ?";
        return $this->db->execute($sql, [$adminId]);
    }
    
    /**
     * Create admin user with hashed password
     */
    public function createAdmin($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->create($data);
    }
    
    /**
     * Update admin password
     */
    public function updatePassword($adminId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE `{$this->table}` SET `password` = ?, `updated_at` = NOW() WHERE `id` = ?";
        return $this->db->execute($sql, [$hashedPassword, $adminId]);
    }
    
    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM `{$this->table}` WHERE `username` = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM `{$this->table}` WHERE `email` = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Get admin profile
     */
    public function getProfile($adminId)
    {
        $sql = "SELECT `id`, `username`, `email`, `display_name`, `avatar`, 
                       `status`, `last_login`, `created_at`, `updated_at`
                FROM `{$this->table}` 
                WHERE `id` = ? 
                LIMIT 1";
        
        return $this->db->fetch($sql, [$adminId]);
    }
    
    /**
     * Update admin profile
     */
    public function updateProfile($adminId, $data)
    {
        // Remove password from profile update data
        unset($data['password']);
        
        return $this->update($adminId, $data);
    }
    
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $stats = [];
        
        // Total posts
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `posts`");
        $stats['total_posts'] = $result['count'];
        
        // Published posts
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `posts` WHERE `status` = 'published'");
        $stats['published_posts'] = $result['count'];
        
        // Draft posts
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `posts` WHERE `status` = 'draft'");
        $stats['draft_posts'] = $result['count'];
        
        // Total websites
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `websites`");
        $stats['total_websites'] = $result['count'];
        
        // Approved websites
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `websites` WHERE `status` = 'approved'");
        $stats['approved_websites'] = $result['count'];
        
        // Pending websites
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `websites` WHERE `status` = 'pending'");
        $stats['pending_websites'] = $result['count'];
        
        // Total comments
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `comments`");
        $stats['total_comments'] = $result['count'];
        
        // Pending comments
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `comments` WHERE `status` = 'pending'");
        $stats['pending_comments'] = $result['count'];
        
        // Categories count
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `categories`");
        $stats['categories_count'] = $result['count'];
        
        // Tags count
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM `tags`");
        $stats['tags_count'] = $result['count'];
        
        return $stats;
    }
    
    /**
     * Get recent activities for dashboard
     */
    public function getRecentActivities($limit = 10)
    {
        $activities = [];
        
        // Recent posts
        $recentPosts = $this->db->fetchAll(
            "SELECT 'post' as type, id, title as name, created_at FROM `posts` 
             ORDER BY created_at DESC LIMIT ?", 
            [$limit]
        );
        
        // Recent websites
        $recentWebsites = $this->db->fetchAll(
            "SELECT 'website' as type, id, title as name, created_at FROM `websites` 
             ORDER BY created_at DESC LIMIT ?", 
            [$limit]
        );
        
        // Recent comments
        $recentComments = $this->db->fetchAll(
            "SELECT 'comment' as type, id, author_name as name, created_at FROM `comments` 
             ORDER BY created_at DESC LIMIT ?", 
            [$limit]
        );
        
        // Merge and sort activities
        $activities = array_merge($recentPosts, $recentWebsites, $recentComments);
        
        // Sort by created_at desc
        usort($activities, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($activities, 0, $limit);
    }
}
