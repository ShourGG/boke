# Koi Blog - Testing Strategy and Standards

## 🎯 Testing Philosophy
Our testing strategy follows the **Testing Pyramid** principle: many unit tests, some integration tests, and few end-to-end tests. We prioritize **fast feedback**, **reliability**, and **maintainability**.

## 🏗️ Testing Pyramid

### 1. Unit Tests (70% of tests)
**Purpose**: Test individual functions and methods in isolation  
**Tools**: PHPUnit  
**Coverage Target**: >90% for business logic  

### 2. Integration Tests (20% of tests)
**Purpose**: Test component interactions and database operations  
**Tools**: PHPUnit with database fixtures  
**Coverage Target**: All critical user journeys  

### 3. End-to-End Tests (10% of tests)
**Purpose**: Test complete user workflows through the browser  
**Tools**: Manual testing + Selenium (if needed)  
**Coverage Target**: Core user scenarios  

## 🧪 Unit Testing Standards

### Test Structure (AAA Pattern)
```php
<?php
/**
 * Test class for Post model
 */
class PostTest extends PHPUnit\Framework\TestCase
{
    private $post;
    private $mockDatabase;
    
    protected function setUp(): void
    {
        // Arrange: Set up test dependencies
        $this->mockDatabase = $this->createMock(Database::class);
        $this->post = new Post($this->mockDatabase);
    }
    
    protected function tearDown(): void
    {
        // Clean up after each test
        $this->post = null;
        $this->mockDatabase = null;
    }
    
    /**
     * Test post validation with valid data
     */
    public function testValidatePostWithValidData()
    {
        // Arrange
        $validData = [
            'title' => 'Test Post Title',
            'content' => 'This is test content for the post.',
            'category_id' => 1,
            'status' => 'draft'
        ];
        
        // Act
        $result = $this->post->validate($validData);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test post validation with invalid data
     */
    public function testValidatePostWithInvalidData()
    {
        // Arrange
        $invalidData = [
            'title' => '', // Invalid: empty title
            'content' => 'Content',
            'category_id' => 'invalid' // Invalid: non-numeric
        ];
        
        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Title is required');
        
        $this->post->validate($invalidData);
    }
    
    /**
     * Test post creation with database interaction
     */
    public function testCreatePostSuccessfully()
    {
        // Arrange
        $postData = [
            'title' => 'New Post',
            'content' => 'Post content',
            'category_id' => 1
        ];
        
        $this->mockDatabase
            ->expects($this->once())
            ->method('execute')
            ->with($this->stringContains('INSERT INTO posts'))
            ->willReturn(1);
            
        $this->mockDatabase
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(123);
        
        // Act
        $postId = $this->post->create($postData);
        
        // Assert
        $this->assertEquals(123, $postId);
    }
}
```

### Test Naming Conventions
```php
// Method naming: test + WhatIsBeingTested + ExpectedBehavior
public function testValidatePostWithValidData()           // ✅ Clear
public function testCreatePostWhenDatabaseFails()         // ✅ Clear
public function testGetPublishedPostsReturnsArray()      // ✅ Clear

// Avoid
public function testPost()                               // ❌ Too vague
public function testValidation()                        // ❌ Not specific
public function test1()                                  // ❌ Meaningless
```

### Mock and Stub Guidelines
```php
// Mock external dependencies
public function testPostCreationWithDatabaseMock()
{
    // Create mock
    $mockDb = $this->createMock(Database::class);
    
    // Set expectations
    $mockDb->expects($this->once())
           ->method('execute')
           ->with($this->stringContains('INSERT'))
           ->willReturn(1);
    
    // Use mock in test
    $post = new Post($mockDb);
    $result = $post->create(['title' => 'Test']);
    
    $this->assertTrue($result);
}

// Stub for simple return values
public function testGetCategoryName()
{
    $stubCategory = $this->createStub(Category::class);
    $stubCategory->method('getName')->willReturn('Technology');
    
    $post = new Post();
    $post->setCategory($stubCategory);
    
    $this->assertEquals('Technology', $post->getCategoryName());
}
```

## 🔗 Integration Testing

### Database Integration Tests
```php
<?php
/**
 * Integration tests for Post model with real database
 */
class PostIntegrationTest extends PHPUnit\Framework\TestCase
{
    private static $database;
    private $post;
    
    public static function setUpBeforeClass(): void
    {
        // Set up test database
        self::$database = new Database();
        self::createTestTables();
        self::seedTestData();
    }
    
    public static function tearDownAfterClass(): void
    {
        // Clean up test database
        self::dropTestTables();
    }
    
    protected function setUp(): void
    {
        $this->post = new Post(self::$database);
        
        // Start transaction for test isolation
        self::$database->beginTransaction();
    }
    
    protected function tearDown(): void
    {
        // Rollback transaction to clean state
        self::$database->rollback();
    }
    
    /**
     * Test complete post creation workflow
     */
    public function testCreatePostWorkflow()
    {
        // Create category first
        $categoryId = $this->createTestCategory();
        
        // Create post
        $postData = [
            'title' => 'Integration Test Post',
            'content' => 'This is integration test content.',
            'category_id' => $categoryId,
            'status' => 'published'
        ];
        
        $postId = $this->post->create($postData);
        
        // Verify post was created
        $this->assertIsInt($postId);
        $this->assertGreaterThan(0, $postId);
        
        // Verify post can be retrieved
        $retrievedPost = $this->post->findById($postId);
        $this->assertNotNull($retrievedPost);
        $this->assertEquals('Integration Test Post', $retrievedPost['title']);
    }
    
    private function createTestCategory()
    {
        $category = new Category(self::$database);
        return $category->create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);
    }
    
    private static function createTestTables()
    {
        // Create test database schema
        $sql = file_get_contents(__DIR__ . '/fixtures/test_schema.sql');
        self::$database->execute($sql);
    }
}
```

### API Integration Tests
```php
/**
 * Test API endpoints with HTTP requests
 */
class ApiIntegrationTest extends PHPUnit\Framework\TestCase
{
    private $baseUrl = 'http://localhost:8000';
    
    /**
     * Test blog post API endpoint
     */
    public function testGetBlogPostsApi()
    {
        // Make HTTP request
        $response = $this->makeRequest('GET', '/api/posts');
        
        // Assert response
        $this->assertEquals(200, $response['status']);
        $this->assertArrayHasKey('data', $response['body']);
        $this->assertIsArray($response['body']['data']);
    }
    
    /**
     * Test post creation via API
     */
    public function testCreatePostViaApi()
    {
        $postData = [
            'title' => 'API Test Post',
            'content' => 'Content via API',
            'category_id' => 1
        ];
        
        $response = $this->makeRequest('POST', '/api/posts', $postData);
        
        $this->assertEquals(201, $response['status']);
        $this->assertArrayHasKey('id', $response['body']);
    }
    
    private function makeRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $options = [
            'http' => [
                'method' => $method,
                'header' => 'Content-Type: application/json',
                'content' => $data ? json_encode($data) : null
            ]
        ];
        
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        return [
            'status' => $this->getHttpResponseCode($http_response_header),
            'body' => json_decode($response, true)
        ];
    }
}
```

## 🌐 End-to-End Testing

### Manual Testing Checklist
```markdown
## Blog Functionality
- [ ] Homepage loads and displays recent posts
- [ ] Post detail page shows full content
- [ ] Category filtering works correctly
- [ ] Search functionality returns relevant results
- [ ] Pagination works on post listings

## Website Directory
- [ ] Website directory page loads
- [ ] Website categories filter correctly
- [ ] Website detail modal/page displays information
- [ ] Featured websites are highlighted

## Admin Panel
- [ ] Admin login with valid credentials
- [ ] Admin login rejects invalid credentials
- [ ] Post creation form validation
- [ ] Post editing saves changes
- [ ] Post deletion works with confirmation
- [ ] Website management functions work

## Responsive Design
- [ ] Mobile layout displays correctly
- [ ] Tablet layout displays correctly
- [ ] Desktop layout displays correctly
- [ ] Navigation menu works on all devices

## Performance
- [ ] Page load times under 2 seconds
- [ ] Images load properly
- [ ] No JavaScript errors in console
- [ ] Forms submit without errors
```

### Automated E2E Tests (Optional)
```php
// Using Selenium WebDriver (if implemented)
class EndToEndTest extends PHPUnit\Framework\TestCase
{
    private $driver;
    
    protected function setUp(): void
    {
        $this->driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            DesiredCapabilities::chrome()
        );
    }
    
    protected function tearDown(): void
    {
        $this->driver->quit();
    }
    
    public function testCompleteUserJourney()
    {
        // Navigate to homepage
        $this->driver->get('http://localhost:8000');
        
        // Verify homepage elements
        $this->assertStringContains('Koi Blog', $this->driver->getTitle());
        
        // Click on a blog post
        $firstPost = $this->driver->findElement(WebDriverBy::className('blog-post-link'));
        $firstPost->click();
        
        // Verify post detail page
        $postTitle = $this->driver->findElement(WebDriverBy::tagName('h1'));
        $this->assertNotEmpty($postTitle->getText());
        
        // Navigate back and test search
        $this->driver->navigate()->back();
        $searchBox = $this->driver->findElement(WebDriverBy::id('search'));
        $searchBox->sendKeys('test');
        $searchBox->submit();
        
        // Verify search results
        $results = $this->driver->findElements(WebDriverBy::className('search-result'));
        $this->assertGreaterThan(0, count($results));
    }
}
```

## 📊 Test Data Management

### Test Fixtures
```php
// tests/fixtures/PostFixture.php
class PostFixture
{
    public static function validPostData()
    {
        return [
            'title' => 'Test Post Title',
            'slug' => 'test-post-title',
            'content' => 'This is test content for the blog post.',
            'excerpt' => 'Test excerpt',
            'status' => 'published',
            'category_id' => 1,
            'author_id' => 1
        ];
    }
    
    public static function invalidPostData()
    {
        return [
            'title' => '', // Invalid: empty
            'content' => 'Content',
            'category_id' => 'invalid' // Invalid: non-numeric
        ];
    }
    
    public static function createTestPost($overrides = [])
    {
        $data = array_merge(self::validPostData(), $overrides);
        $post = new Post();
        return $post->create($data);
    }
}
```

### Database Seeders
```php
// tests/seeders/TestSeeder.php
class TestSeeder
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    
    public function seedTestData()
    {
        $this->seedCategories();
        $this->seedAdmins();
        $this->seedPosts();
    }
    
    private function seedCategories()
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle'],
            ['name' => 'Travel', 'slug' => 'travel']
        ];
        
        foreach ($categories as $category) {
            $this->database->execute(
                "INSERT INTO categories (name, slug) VALUES (?, ?)",
                [$category['name'], $category['slug']]
            );
        }
    }
}
```

## 🚀 Test Automation

### PHPUnit Configuration
```xml
<!-- phpunit.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/bootstrap.php"
         colors="true"
         verbose="true"
         stopOnFailure="false">
    
    <testsuites>
        <testsuite name="Unit Tests">
            <directory>tests/unit</directory>
        </testsuite>
        <testsuite name="Integration Tests">
            <directory>tests/integration</directory>
        </testsuite>
    </testsuites>
    
    <filter>
        <whitelist>
            <directory suffix=".php">app/</directory>
            <exclude>
                <directory>app/views/</directory>
                <file>app/config.php</file>
            </exclude>
        </whitelist>
    </filter>
    
    <logging>
        <log type="coverage-html" target="tests/coverage"/>
        <log type="coverage-text" target="php://stdout"/>
    </logging>
</phpunit>
```

### Test Commands
```bash
# Run all tests
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit --testsuite="Unit Tests"

# Run with coverage
vendor/bin/phpunit --coverage-html tests/coverage

# Run specific test file
vendor/bin/phpunit tests/unit/PostTest.php

# Run specific test method
vendor/bin/phpunit --filter testValidatePostWithValidData
```

## ✅ Quality Gates

### Pre-Commit Checks
- [ ] All tests pass
- [ ] Code coverage >80%
- [ ] No syntax errors
- [ ] PHPStan analysis passes
- [ ] Code style follows standards

### Continuous Integration
```yaml
# .github/workflows/tests.yml (if using GitHub Actions)
name: Tests
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: vendor/bin/phpunit --coverage-text
```

### Coverage Targets
- **Unit Tests**: >90% line coverage
- **Integration Tests**: All critical paths covered
- **Overall**: >80% total coverage
- **Business Logic**: 100% coverage for core models
