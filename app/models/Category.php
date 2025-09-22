<?php
/**
 * Category Model
 * Handle blog categories data operations
 */
class Category extends BaseModel
{
    protected $table = 'categories';
    protected $fillable = [
        'name', 'slug', 'description', 'color', 'sort_order'
    ];
    
    /**
     * Get all categories with post count
     */
    public function getAllWithPostCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count
                FROM `{$this->table}` c
                LEFT JOIN `posts` p ON c.id = p.category_id AND p.status = 'published'
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get category by slug
     */
    public function getBySlug($slug)
    {
        return $this->db->fetch("SELECT * FROM `{$this->table}` WHERE `slug` = ? LIMIT 1", [$slug]);
    }
    
    /**
     * Get categories for navigation
     */
    public function getForNavigation($limit = 10)
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count
                FROM `{$this->table}` c
                LEFT JOIN `posts` p ON c.id = p.category_id AND p.status = 'published'
                GROUP BY c.id
                HAVING post_count > 0
                ORDER BY c.sort_order ASC, c.name ASC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Generate unique slug
     */
    public function generateSlug($name, $id = null)
    {
        $slug = $this->slugify($name);
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
    private function slugExists($slug, $excludeId = null)
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
        $text = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        
        if (empty($text) || strlen($text) < 2) {
            $text = 'category-' . uniqid();
        }
        
        return $text;
    }
    
    /**
     * Update post count for category
     */
    public function updatePostCount($categoryId)
    {
        $sql = "UPDATE `{$this->table}` 
                SET `post_count` = (
                    SELECT COUNT(*) FROM `posts` 
                    WHERE `category_id` = ? AND `status` = 'published'
                ) 
                WHERE `id` = ?";
        
        return $this->db->execute($sql, [$categoryId, $categoryId]);
    }
    
    /**
     * Get popular categories
     */
    public function getPopular($limit = 5)
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count
                FROM `{$this->table}` c
                LEFT JOIN `posts` p ON c.id = p.category_id AND p.status = 'published'
                GROUP BY c.id
                HAVING post_count > 0
                ORDER BY post_count DESC, c.name ASC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
}
