<?php
/**
 * Database Connection Class
 * PDO-based database wrapper with connection pooling and error handling
 */
class Database
{
    private static $instance = null;
    private $connection = null;
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset;
    private $lastAffectedRows = 0;
    
    /**
     * Private constructor for singleton pattern
     */
    private function __construct()
    {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->charset = DB_CHARSET ?? 'utf8mb4';
        
        $this->connect();
    }
    
    /**
     * Get database instance (singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establish database connection
     */
    private function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get PDO connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * Execute a query and return results
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query execution failed: " . $e->getMessage());
        }
    }
    
    /**
     * Fetch all results
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Fetch single result
     */
    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Execute insert/update/delete and return affected rows
     */
    public function execute($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        $this->lastAffectedRows = $stmt->rowCount();
        return $this->lastAffectedRows;
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->connection->rollback();
    }

    /**
     * Get affected rows from last operation
     */
    public function getAffectedRows()
    {
        // For PDO, we need to store the last statement's rowCount
        // This is a simple implementation that works with the execute method
        return $this->lastAffectedRows ?? 0;
    }

    /**
     * Check if table exists
     */
    public function tableExists($tableName)
    {
        $sql = "SHOW TABLES LIKE ?";
        $result = $this->fetch($sql, [$tableName]);
        return !empty($result);
    }
    
    /**
     * Get table columns
     */
    public function getTableColumns($tableName)
    {
        $sql = "DESCRIBE `{$tableName}`";
        return $this->fetchAll($sql);
    }
    
    /**
     * Escape string for safe SQL usage
     */
    public function escape($string)
    {
        return $this->connection->quote($string);
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup() {}
}
