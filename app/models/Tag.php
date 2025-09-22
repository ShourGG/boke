<?php
/**
 * Tag Model
 * Handle blog tags data operations
 */
class Tag extends BaseModel
{
    protected $table = 'tags';
    protected $fillable = ['name', 'slug', 'color'];
    
    /**
     * Get all tags with post count
     */
    public function getAllWithPostCount()
    {
        $sql = "SELECT t.*, COUNT(pt.post_id) as post_count
                FROM `{$this->table}` t
                LEFT JOIN `post_tags` pt ON t.id = pt.tag_id
                LEFT JOIN `posts` p ON pt.post_id = p.id AND p.status = 'published'
                GROUP BY t.id
                ORDER BY t.name ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get tag by slug
     */
    public function getBySlug($slug)
    {
        return $this->db->fetch("SELECT * FROM `{$this->table}` WHERE `slug` = ? LIMIT 1", [$slug]);
    }
    
    /**
     * Get popular tags for tag cloud
     */
    public function getPopular($limit = 20)
    {
        $sql = "SELECT t.*, COUNT(pt.post_id) as post_count
                FROM `{$this->table}` t
                INNER JOIN `post_tags` pt ON t.id = pt.tag_id
                INNER JOIN `posts` p ON pt.post_id = p.id AND p.status = 'published'
                GROUP BY t.id
                HAVING post_count > 0
                ORDER BY post_count DESC, t.name ASC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get tags by post ID
     */
    public function getByPostId($postId)
    {
        $sql = "SELECT t.* FROM `{$this->table}` t
                INNER JOIN `post_tags` pt ON t.id = pt.tag_id
                WHERE pt.post_id = ?
                ORDER BY t.name ASC";
        
        return $this->db->fetchAll($sql, [$postId]);
    }
    
    /**
     * Create or get existing tag
     */
    public function createOrGet($name)
    {
        $slug = $this->generateSlug($name);
        
        // Check if tag exists
        $existing = $this->getBySlug($slug);
        if ($existing) {
            return $existing['id'];
        }
        
        // Create new tag
        return $this->create([
            'name' => $name,
            'slug' => $slug,
            'color' => $this->getRandomColor()
        ]);
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
            $text = 'tag-' . uniqid();
        }
        
        return $text;
    }
    
    /**
     * Get random color for new tags
     */
    private function getRandomColor()
    {
        $colors = [
            '#3498db', '#e74c3c', '#f39c12', '#27ae60', '#9b59b6',
            '#1abc9c', '#34495e', '#e67e22', '#2ecc71', '#8e44ad'
        ];
        
        return $colors[array_rand($colors)];
    }
    
    /**
     * Update post count for tag
     */
    public function updatePostCount($tagId)
    {
        $sql = "UPDATE `{$this->table}` 
                SET `post_count` = (
                    SELECT COUNT(*) FROM `post_tags` pt
                    INNER JOIN `posts` p ON pt.post_id = p.id
                    WHERE pt.tag_id = ? AND p.status = 'published'
                ) 
                WHERE `id` = ?";
        
        return $this->db->execute($sql, [$tagId, $tagId]);
    }
    
    /**
     * Parse tags from string
     */
    public function parseTagString($tagString)
    {
        if (empty($tagString)) {
            return [];
        }
        
        $tags = array_map('trim', explode(',', $tagString));
        $tags = array_filter($tags); // Remove empty values
        $tags = array_unique($tags); // Remove duplicates
        
        $tagIds = [];
        foreach ($tags as $tagName) {
            if (!empty($tagName)) {
                $tagIds[] = $this->createOrGet($tagName);
            }
        }
        
        return $tagIds;
    }
    
    /**
     * Get tag string from post ID
     */
    public function getTagStringByPostId($postId)
    {
        $tags = $this->getByPostId($postId);
        return implode(', ', array_column($tags, 'name'));
    }
}
