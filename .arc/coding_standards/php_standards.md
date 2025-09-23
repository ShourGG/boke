# Koi Blog - PHP Coding Standards

## 🎯 Overview
This document defines the PHP coding standards for the Koi Blog project, ensuring consistency, readability, and maintainability across the codebase.

## 📝 General Principles
- **Clarity over Cleverness**: Write code that is easy to understand
- **Consistency**: Follow established patterns throughout the project
- **Security First**: Always consider security implications
- **Performance Awareness**: Write efficient code without premature optimization

## 🔧 Code Formatting

### File Structure
```php
<?php
/**
 * Brief description of the file's purpose
 * 
 * @author Your Name
 * @version 1.0
 * @since 2025-01-23
 */

// Class definition or procedural code
```

### Naming Conventions
- **Classes**: PascalCase (`BlogPost`, `DatabaseConnection`)
- **Methods**: camelCase (`getUserById`, `validateInput`)
- **Variables**: camelCase (`$userName`, `$postContent`)
- **Constants**: UPPER_SNAKE_CASE (`DB_HOST`, `MAX_FILE_SIZE`)
- **Files**: PascalCase for classes (`BlogPost.php`), lowercase for others (`config.php`)

### Indentation and Spacing
- **Indentation**: 4 spaces (no tabs)
- **Line Length**: Maximum 120 characters
- **Blank Lines**: One blank line between methods, two between classes
- **Operators**: Spaces around operators (`$a = $b + $c`)

## 🏗️ Class Structure

### Class Template
```php
<?php
/**
 * Brief class description
 * 
 * Detailed description of the class purpose and usage
 */
class ExampleClass extends BaseClass implements InterfaceExample
{
    // Constants first
    private const DEFAULT_LIMIT = 10;
    
    // Properties (visibility order: public, protected, private)
    public $publicProperty;
    protected $protectedProperty;
    private $privateProperty;
    
    /**
     * Constructor with parameter documentation
     * 
     * @param string $param1 Description of parameter
     * @param int $param2 Description of parameter
     */
    public function __construct($param1, $param2 = null)
    {
        $this->privateProperty = $param1;
        parent::__construct();
    }
    
    /**
     * Public methods first
     * 
     * @param array $data Input data
     * @return bool Success status
     * @throws InvalidArgumentException When data is invalid
     */
    public function publicMethod(array $data): bool
    {
        // Method implementation
        return true;
    }
    
    /**
     * Protected methods
     */
    protected function protectedMethod()
    {
        // Implementation
    }
    
    /**
     * Private methods last
     */
    private function privateMethod()
    {
        // Implementation
    }
}
```

## 🔒 Security Standards

### Input Validation
```php
// Always validate and sanitize input
public function processUserInput($input)
{
    // Validate
    if (empty($input) || !is_string($input)) {
        throw new InvalidArgumentException('Invalid input provided');
    }
    
    // Sanitize
    $cleanInput = filter_var($input, FILTER_SANITIZE_STRING);
    $cleanInput = trim($cleanInput);
    
    return $cleanInput;
}
```

### Database Queries
```php
// Always use prepared statements
public function getUserById($userId)
{
    $sql = "SELECT * FROM users WHERE id = ? AND status = ?";
    $params = [$userId, 'active'];
    
    return $this->database->fetch($sql, $params);
}

// NEVER do this
// $sql = "SELECT * FROM users WHERE id = " . $userId; // SQL Injection risk!
```

### Output Escaping
```php
// Escape output for HTML
echo htmlspecialchars($userContent, ENT_QUOTES, 'UTF-8');

// For URLs
echo urlencode($userInput);

// For JavaScript (use JSON)
echo json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP);
```

## 📊 Error Handling

### Exception Handling
```php
public function riskyOperation($data)
{
    try {
        // Risky code here
        $result = $this->processData($data);
        return $result;
    } catch (DatabaseException $e) {
        // Log the error
        error_log("Database error: " . $e->getMessage());
        
        // Return user-friendly message
        throw new UserException('Unable to process request. Please try again.');
    } catch (Exception $e) {
        // Log unexpected errors
        error_log("Unexpected error: " . $e->getMessage());
        
        // Don't expose internal details
        throw new UserException('An error occurred. Please contact support.');
    }
}
```

## 📝 Documentation Standards

### Method Documentation
```php
/**
 * Retrieves blog posts with pagination and filtering
 * 
 * This method fetches blog posts from the database with support for
 * pagination, category filtering, and search functionality.
 * 
 * @param int $page Page number (1-based)
 * @param int $limit Posts per page
 * @param string|null $category Category filter (optional)
 * @param string|null $search Search term (optional)
 * 
 * @return array Array of post objects
 * @throws InvalidArgumentException When page or limit is invalid
 * @throws DatabaseException When database query fails
 * 
 * @example
 * $posts = $blog->getPosts(1, 10, 'technology', 'php');
 * 
 * @since 1.0.0
 */
public function getPosts($page, $limit, $category = null, $search = null)
{
    // Implementation
}
```

## 🧪 Testing Standards

### Unit Test Example
```php
<?php
/**
 * Test class for BlogPost model
 */
class BlogPostTest extends PHPUnit\Framework\TestCase
{
    private $blogPost;
    
    protected function setUp(): void
    {
        $this->blogPost = new BlogPost();
    }
    
    /**
     * Test post validation with valid data
     */
    public function testValidatePostWithValidData()
    {
        $data = [
            'title' => 'Test Post',
            'content' => 'This is test content',
            'category_id' => 1
        ];
        
        $result = $this->blogPost->validate($data);
        $this->assertTrue($result);
    }
    
    /**
     * Test post validation with invalid data
     */
    public function testValidatePostWithInvalidData()
    {
        $this->expectException(InvalidArgumentException::class);
        
        $data = [
            'title' => '', // Invalid: empty title
            'content' => 'Content'
        ];
        
        $this->blogPost->validate($data);
    }
}
```

## 🚫 Code Smells to Avoid

### Bad Practices
```php
// DON'T: Long parameter lists
public function createPost($title, $content, $author, $category, $tags, $status, $publishDate, $seoTitle, $seoDescription) {}

// DO: Use objects or arrays
public function createPost(PostData $postData) {}

// DON'T: Deep nesting
if ($user) {
    if ($user->isActive()) {
        if ($user->hasPermission('write')) {
            // Deep nesting is hard to read
        }
    }
}

// DO: Early returns
if (!$user || !$user->isActive() || !$user->hasPermission('write')) {
    return false;
}
// Continue with main logic

// DON'T: Magic numbers
if ($user->getAge() > 18) {} // What's special about 18?

// DO: Named constants
if ($user->getAge() > self::MINIMUM_AGE) {}
```

## ✅ Code Review Checklist
- [ ] All inputs validated and sanitized
- [ ] Database queries use prepared statements
- [ ] Output properly escaped
- [ ] Error handling implemented
- [ ] Methods documented with PHPDoc
- [ ] Naming conventions followed
- [ ] No code duplication
- [ ] Security considerations addressed
- [ ] Performance implications considered
- [ ] Tests written for new functionality
