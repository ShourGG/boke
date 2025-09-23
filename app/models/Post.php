<?php
/**
 * Post Model
 * Handle blog posts data operations
 */
class Post extends BaseModel
{
    protected $table = 'posts';
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'category_id', 'status', 'is_featured', 'meta_title',
        'meta_description', 'meta_keywords', 'published_at'
    ];
    
    /**
     * Get published posts with pagination
     */
    public function getPublished($page = 1, $perPage = 10, $categoryId = null)
    {
        $conditions = ['status' => 'published'];
        
        if ($categoryId) {
            $conditions['category_id'] = $categoryId;
        }
        
        return $this->paginate($page, $perPage, $conditions, 'published_at DESC');
    }
    
    /**
     * Get featured posts
     */
    public function getFeatured($limit = 5)
    {
        return $this->findAll([
            'status' => 'published',
            'is_featured' => 1
        ], 'published_at DESC', $limit);
    }
    
    /**
     * Get recent posts
     */
    public function getRecent($limit = 5, $excludeId = null)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `status` = 'published'";
        $params = [];
        
        if ($excludeId) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }
        
        $sql .= " ORDER BY `published_at` DESC LIMIT {$limit}";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get post by slug
     */
    public function getBySlug($slug)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, c.color as category_color
                FROM `{$this->table}` p
                LEFT JOIN `categories` c ON p.category_id = c.id
                WHERE p.slug = ? AND p.status = 'published'
                LIMIT 1";
        
        return $this->db->fetch($sql, [$slug]);
    }
    
    /**
     * Get post with category and tags
     */
    public function getWithDetails($id)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, c.color as category_color
                FROM `{$this->table}` p
                LEFT JOIN `categories` c ON p.category_id = c.id
                WHERE p.id = ?
                LIMIT 1";
        
        $post = $this->db->fetch($sql, [$id]);
        
        if ($post) {
            // Get tags
            $tagSql = "SELECT t.* FROM `tags` t
                       INNER JOIN `post_tags` pt ON t.id = pt.tag_id
                       WHERE pt.post_id = ?
                       ORDER BY t.name";
            
            $post['tags'] = $this->db->fetchAll($tagSql, [$id]);
        }
        
        return $post;
    }
    
    /**
     * Search posts
     */
    public function search($query, $page = 1, $perPage = 10)
    {
        $searchTerm = '%' . $query . '%';
        
        // Count total results
        $countSql = "SELECT COUNT(*) as total FROM `{$this->table}`
                     WHERE `status` = 'published'
                     AND (`title` LIKE ? OR `content` LIKE ? OR `excerpt` LIKE ?)";
        
        $totalResult = $this->db->fetch($countSql, [$searchTerm, $searchTerm, $searchTerm]);
        $total = $totalResult['total'];
        
        // Get results
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM `{$this->table}` p
                LEFT JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published'
                AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)
                ORDER BY p.published_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $records = $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
        
        return [
            'data' => $records,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total),
            'query' => $query
        ];
    }
    
    /**
     * Get related posts
     */
    public function getRelated($postId, $categoryId, $limit = 5)
    {
        $sql = "SELECT * FROM `{$this->table}`
                WHERE `id` != ? AND `category_id` = ? AND `status` = 'published'
                ORDER BY `published_at` DESC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql, [$postId, $categoryId]);
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($id)
    {
        $sql = "UPDATE `{$this->table}` SET `view_count` = `view_count` + 1 WHERE `id` = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Generate unique slug
     */
    public function generateSlug($title, $id = null)
    {
        $slug = $this->slugify($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     */
    public function slugExists($slug, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM `{$this->table}` WHERE `slug` = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Convert string to slug
     */
    private function slugify($text)
    {
        // Replace Chinese characters and special characters
        $text = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        
        // If result is empty or too short, generate random slug
        if (empty($text) || strlen($text) < 3) {
            $text = 'post-' . uniqid();
        }
        
        return $text;
    }
    
    /**
     * Get posts by tag
     */
    public function getByTag($tagSlug, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        // Count total
        $countSql = "SELECT COUNT(DISTINCT p.id) as total
                     FROM `{$this->table}` p
                     INNER JOIN `post_tags` pt ON p.id = pt.post_id
                     INNER JOIN `tags` t ON pt.tag_id = t.id
                     WHERE p.status = 'published' AND t.slug = ?";
        
        $totalResult = $this->db->fetch($countSql, [$tagSlug]);
        $total = $totalResult['total'];
        
        // Get posts
        $sql = "SELECT DISTINCT p.*, c.name as category_name, c.slug as category_slug
                FROM `{$this->table}` p
                LEFT JOIN `categories` c ON p.category_id = c.id
                INNER JOIN `post_tags` pt ON p.id = pt.post_id
                INNER JOIN `tags` t ON pt.tag_id = t.id
                WHERE p.status = 'published' AND t.slug = ?
                ORDER BY p.published_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $records = $this->db->fetchAll($sql, [$tagSlug]);
        
        return [
            'data' => $records,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
    
    /**
     * Update post tags
     */
    public function updateTags($postId, $tagIds)
    {
        // Remove existing tags
        $this->db->execute("DELETE FROM `post_tags` WHERE `post_id` = ?", [$postId]);

        // Add new tags
        if (!empty($tagIds)) {
            foreach ($tagIds as $tagId) {
                $this->db->execute(
                    "INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES (?, ?)",
                    [$postId, $tagId]
                );
            }
        }
    }

    /**
     * Get total views across all posts
     */
    public function getTotalViews()
    {
        $sql = "SELECT SUM(view_count) as total_views FROM `{$this->table}` WHERE status = 'published'";
        $result = $this->db->fetch($sql);
        return $result['total_views'] ?? 0;
    }

    /**
     * Get post statistics
     */
    public function getStats()
    {
        $sql = "SELECT
                    COUNT(*) as total_posts,
                    SUM(view_count) as total_views,
                    COUNT(CASE WHEN status = 'published' THEN 1 END) as published_posts,
                    COUNT(CASE WHEN status = 'draft' THEN 1 END) as draft_posts
                FROM `{$this->table}`";

        return $this->db->fetch($sql);
    }

    /**
     * Get admin list with filters
     */
    public function getAdminList($offset, $perPage, $status = 'all', $category = 0, $search = '')
    {
        $whereClause = "1=1";
        $params = [];

        if ($status !== 'all') {
            $whereClause .= " AND p.status = ?";
            $params[] = $status;
        }

        if ($category > 0) {
            $whereClause .= " AND p.category_id = ?";
            $params[] = $category;
        }

        if (!empty($search)) {
            $whereClause .= " AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }

        $sql = "SELECT p.*, c.name as category_name,
                       COALESCE(p.view_count, 0) as view_count
                FROM `{$this->table}` p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE {$whereClause}
                ORDER BY p.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get admin count with filters
     */
    public function getAdminCount($status = 'all', $category = 0, $search = '')
    {
        $whereClause = "1=1";
        $params = [];

        if ($status !== 'all') {
            $whereClause .= " AND status = ?";
            $params[] = $status;
        }

        if ($category > 0) {
            $whereClause .= " AND category_id = ?";
            $params[] = $category;
        }

        if (!empty($search)) {
            $whereClause .= " AND (title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }

        $sql = "SELECT COUNT(*) as total FROM `{$this->table}` WHERE {$whereClause}";
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Batch update status
     */
    public function batchUpdateStatus($ids, $status)
    {
        if (empty($ids) || !is_array($ids)) {
            return 0;
        }

        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "UPDATE `{$this->table}` SET status = ? WHERE id IN ({$placeholders})";

        $params = array_merge([$status], $ids);
        $this->db->execute($sql, $params);
        return $this->db->getAffectedRows();
    }

    /**
     * Batch update featured status
     */
    public function batchUpdateFeatured($ids, $featured)
    {
        if (empty($ids) || !is_array($ids)) {
            return 0;
        }

        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "UPDATE `{$this->table}` SET is_featured = ? WHERE id IN ({$placeholders})";

        $params = array_merge([$featured], $ids);
        $this->db->execute($sql, $params);
        return $this->db->getAffectedRows();
    }

    /**
     * Batch delete posts
     */
    public function batchDelete($ids)
    {
        if (empty($ids) || !is_array($ids)) {
            return 0;
        }

        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "DELETE FROM `{$this->table}` WHERE id IN ({$placeholders})";

        $this->db->execute($sql, $ids);
        return $this->db->getAffectedRows();
    }

    /**
     * Get total count of all posts
     */
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Get published posts count
     */
    public function getPublishedCount()
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}` WHERE status = 'published'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Get draft posts count
     */
    public function getDraftCount()
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}` WHERE status = 'draft'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Get pending posts count
     */
    public function getPendingCount()
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}` WHERE status = 'pending'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }
}
