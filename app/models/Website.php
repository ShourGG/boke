<?php
/**
 * Website Model
 * Handle website directory data operations
 */
class Website extends BaseModel
{
    protected $table = 'websites';
    protected $fillable = [
        'title', 'url', 'description', 'screenshot', 'favicon',
        'category_id', 'tags', 'status', 'is_featured',
        'submitter_name', 'submitter_email', 'meta_title', 'meta_description'
    ];
    
    /**
     * Get approved websites with pagination
     */
    public function getApproved($page = 1, $perPage = 20, $categoryId = null)
    {
        $conditions = ['status' => 'approved'];
        
        if ($categoryId) {
            $conditions['category_id'] = $categoryId;
        }
        
        return $this->paginate($page, $perPage, $conditions, 'is_featured DESC, created_at DESC');
    }
    
    /**
     * Get featured websites
     */
    public function getFeatured($limit = 10)
    {
        return $this->findAll([
            'status' => 'approved',
            'is_featured' => 1
        ], 'created_at DESC', $limit);
    }
    
    /**
     * Get websites with category info
     */
    public function getWithCategory($page = 1, $perPage = 20, $categoryId = null)
    {
        $offset = ($page - 1) * $perPage;
        
        // Build WHERE clause
        $whereClause = "w.status = 'approved'";
        $params = [];
        
        if ($categoryId) {
            $whereClause .= " AND w.category_id = ?";
            $params[] = $categoryId;
        }
        
        // Count total
        $countSql = "SELECT COUNT(*) as total FROM `{$this->table}` w WHERE {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        
        // Get records
        $sql = "SELECT w.*, wc.name as category_name, wc.slug as category_slug, 
                       wc.icon as category_icon, wc.color as category_color
                FROM `{$this->table}` w
                LEFT JOIN `website_categories` wc ON w.category_id = wc.id
                WHERE {$whereClause}
                ORDER BY w.is_featured DESC, w.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $records = $this->db->fetchAll($sql, $params);
        
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
     * Search websites
     */
    public function search($query, $page = 1, $perPage = 20)
    {
        $searchTerm = '%' . $query . '%';
        $offset = ($page - 1) * $perPage;
        
        // Count total
        $countSql = "SELECT COUNT(*) as total FROM `{$this->table}`
                     WHERE `status` = 'approved'
                     AND (`title` LIKE ? OR `description` LIKE ? OR `tags` LIKE ?)";
        
        $totalResult = $this->db->fetch($countSql, [$searchTerm, $searchTerm, $searchTerm]);
        $total = $totalResult['total'];
        
        // Get records
        $sql = "SELECT w.*, wc.name as category_name, wc.slug as category_slug,
                       wc.icon as category_icon, wc.color as category_color
                FROM `{$this->table}` w
                LEFT JOIN `website_categories` wc ON w.category_id = wc.id
                WHERE w.status = 'approved'
                AND (w.title LIKE ? OR w.description LIKE ? OR w.tags LIKE ?)
                ORDER BY w.is_featured DESC, w.created_at DESC
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
     * Get recent websites
     */
    public function getRecent($limit = 10)
    {
        $sql = "SELECT w.*, wc.name as category_name, wc.color as category_color
                FROM `{$this->table}` w
                LEFT JOIN `website_categories` wc ON w.category_id = wc.id
                WHERE w.status = 'approved'
                ORDER BY w.created_at DESC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Increment click count
     */
    public function incrementClicks($id)
    {
        $sql = "UPDATE `{$this->table}` SET `click_count` = `click_count` + 1 WHERE `id` = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Get website by ID with category
     */
    public function getWithCategoryById($id)
    {
        $sql = "SELECT w.*, wc.name as category_name, wc.slug as category_slug,
                       wc.icon as category_icon, wc.color as category_color
                FROM `{$this->table}` w
                LEFT JOIN `website_categories` wc ON w.category_id = wc.id
                WHERE w.id = ?
                LIMIT 1";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Get websites by category slug
     */
    public function getByCategorySlug($categorySlug, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        
        // Count total
        $countSql = "SELECT COUNT(*) as total
                     FROM `{$this->table}` w
                     INNER JOIN `website_categories` wc ON w.category_id = wc.id
                     WHERE w.status = 'approved' AND wc.slug = ?";
        
        $totalResult = $this->db->fetch($countSql, [$categorySlug]);
        $total = $totalResult['total'];
        
        // Get records
        $sql = "SELECT w.*, wc.name as category_name, wc.slug as category_slug,
                       wc.icon as category_icon, wc.color as category_color
                FROM `{$this->table}` w
                INNER JOIN `website_categories` wc ON w.category_id = wc.id
                WHERE w.status = 'approved' AND wc.slug = ?
                ORDER BY w.is_featured DESC, w.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $records = $this->db->fetchAll($sql, [$categorySlug]);
        
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
     * Submit new website
     */
    public function submit($data)
    {
        // Add submission metadata
        $data['status'] = 'pending';
        $data['submitter_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        
        // Auto-generate meta fields if not provided
        if (empty($data['meta_title'])) {
            $data['meta_title'] = $data['title'];
        }
        
        if (empty($data['meta_description'])) {
            $data['meta_description'] = substr($data['description'], 0, 160);
        }
        
        return $this->create($data);
    }
    
    /**
     * Get pending websites for admin review
     */
    public function getPending($page = 1, $perPage = 20)
    {
        return $this->paginate($page, $perPage, ['status' => 'pending'], 'created_at DESC');
    }
    
    /**
     * Approve website
     */
    public function approve($id)
    {
        return $this->update($id, ['status' => 'approved']);
    }
    
    /**
     * Reject website
     */
    public function reject($id)
    {
        return $this->update($id, ['status' => 'rejected']);
    }

    /**
     * Get total count of approved websites
     */
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}` WHERE status = 'approved'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }
}
