<?php
/**
 * Base Model Class
 * All models extend this class for common database operations
 */
class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $timestamps = true;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find record by ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Find all records
     */
    public function findAll($conditions = [], $orderBy = null, $limit = null)
    {
        $sql = "SELECT * FROM `{$this->table}`";
        $params = [];
        
        // Add WHERE conditions
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "`{$field}` = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        // Add ORDER BY
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // Add LIMIT
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Find records with pagination
     */
    public function paginate($page = 1, $perPage = 10, $conditions = [], $orderBy = null)
    {
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "`{$field}` = ?";
                $params[] = $value;
            }
            $countSql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        
        // Get records
        $sql = "SELECT * FROM `{$this->table}`";
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "`{$field}` = ?";
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
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
     * Create new record
     */
    public function create($data)
    {
        // Filter data by fillable fields
        $filteredData = $this->filterFillable($data);
        
        // Add timestamps
        if ($this->timestamps) {
            $filteredData['created_at'] = date('Y-m-d H:i:s');
            $filteredData['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $fields = array_keys($filteredData);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO `{$this->table}` (`" . implode('`, `', $fields) . "`) VALUES (" . implode(', ', $placeholders) . ")";
        
        $this->db->execute($sql, array_values($filteredData));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record
     */
    public function update($id, $data)
    {
        // Filter data by fillable fields
        $filteredData = $this->filterFillable($data);
        
        // Add updated timestamp
        if ($this->timestamps) {
            $filteredData['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $fields = array_keys($filteredData);
        $setClause = [];
        
        foreach ($fields as $field) {
            $setClause[] = "`{$field}` = ?";
        }
        
        $sql = "UPDATE `{$this->table}` SET " . implode(', ', $setClause) . " WHERE `{$this->primaryKey}` = ?";
        
        $params = array_values($filteredData);
        $params[] = $id;
        
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete record
     */
    public function delete($id)
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Count records
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "`{$field}` = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    /**
     * Filter data by fillable fields
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Execute raw SQL query
     */
    public function raw($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }
}
