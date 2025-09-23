# Koi Blog - Architecture Diagrams

## 🎯 Overview
This document contains visual representations of the Koi Blog system architecture, including system overview, data flow, and component relationships.

## 🏗️ System Architecture Overview

```mermaid
graph TB
    subgraph "Client Layer"
        Browser[Web Browser]
        Mobile[Mobile Browser]
    end
    
    subgraph "Web Server Layer"
        Nginx[Nginx Web Server]
        PHP[PHP-FPM]
    end
    
    subgraph "Application Layer"
        Router[Router]
        Controllers[Controllers]
        Models[Models]
        Views[Views]
    end
    
    subgraph "Data Layer"
        MySQL[(MySQL Database)]
        Files[File System]
    end
    
    subgraph "External Services"
        CDN[CDN/Static Assets]
        Backup[Backup Storage]
    end
    
    Browser --> Nginx
    Mobile --> Nginx
    Nginx --> PHP
    PHP --> Router
    Router --> Controllers
    Controllers --> Models
    Controllers --> Views
    Models --> MySQL
    Models --> Files
    Views --> Browser
    CDN --> Browser
    MySQL --> Backup
```

## 🔄 Request Flow Diagram

```mermaid
sequenceDiagram
    participant User
    participant Nginx
    participant PHP
    participant Router
    participant Controller
    participant Model
    participant Database
    participant View
    
    User->>Nginx: HTTP Request
    Nginx->>PHP: Forward Request
    PHP->>Router: Parse URL
    Router->>Controller: Route to Handler
    Controller->>Model: Get Data
    Model->>Database: Execute Query
    Database-->>Model: Return Data
    Model-->>Controller: Return Results
    Controller->>View: Render Template
    View-->>Controller: HTML Response
    Controller-->>PHP: Response
    PHP-->>Nginx: Response
    Nginx-->>User: HTTP Response
```

## 📊 Domain Model Diagram

```mermaid
erDiagram
    POSTS ||--o{ POST_TAGS : has
    POSTS }o--|| CATEGORIES : belongs_to
    POSTS }o--|| ADMINS : authored_by
    TAGS ||--o{ POST_TAGS : tagged_in
    CATEGORIES ||--o{ CATEGORIES : parent_child
    
    WEBSITES }o--|| WEBSITE_CATEGORIES : belongs_to
    WEBSITES ||--o{ WEBSITE_TAGS : has
    WEBSITE_CATEGORIES ||--o{ WEBSITE_CATEGORIES : parent_child
    
    ADMINS ||--o{ SESSIONS : has
    ADMINS ||--o{ POSTS : creates
    ADMINS ||--o{ WEBSITES : moderates
    
    POSTS {
        int id PK
        string title
        string slug UK
        text content
        text excerpt
        enum status
        boolean featured
        int view_count
        int category_id FK
        int author_id FK
        timestamp published_at
        timestamp created_at
        timestamp updated_at
    }
    
    CATEGORIES {
        int id PK
        string name
        string slug UK
        text description
        int parent_id FK
        int sort_order
        timestamp created_at
        timestamp updated_at
    }
    
    TAGS {
        int id PK
        string name
        string slug UK
        text description
        int usage_count
        timestamp created_at
        timestamp updated_at
    }
    
    POST_TAGS {
        int post_id FK
        int tag_id FK
        timestamp created_at
    }
    
    WEBSITES {
        int id PK
        string name
        string url
        text description
        string screenshot_url
        int category_id FK
        enum status
        boolean featured
        decimal rating
        int view_count
        int submitted_by FK
        int reviewed_by FK
        timestamp reviewed_at
        timestamp created_at
        timestamp updated_at
    }
    
    WEBSITE_CATEGORIES {
        int id PK
        string name
        string slug UK
        text description
        int parent_id FK
        int sort_order
        timestamp created_at
        timestamp updated_at
    }
    
    ADMINS {
        int id PK
        string username UK
        string email UK
        string password_hash
        string full_name
        enum role
        enum status
        timestamp last_login_at
        string last_login_ip
        int failed_login_attempts
        timestamp locked_until
        timestamp created_at
        timestamp updated_at
    }
    
    SESSIONS {
        string id PK
        int admin_id FK
        string ip_address
        text user_agent
        text payload
        timestamp last_activity
        timestamp expires_at
        timestamp created_at
    }
```

## 🏛️ MVC Architecture Diagram

```mermaid
graph TB
    subgraph "View Layer"
        V1[layouts/base.php]
        V2[home/index.php]
        V3[post/detail.php]
        V4[admin/dashboard.php]
    end
    
    subgraph "Controller Layer"
        C1[HomeController]
        C2[PostController]
        C3[AdminController]
        C4[WebsiteController]
    end
    
    subgraph "Model Layer"
        M1[Post Model]
        M2[Category Model]
        M3[Website Model]
        M4[Admin Model]
    end
    
    subgraph "Core Layer"
        Router[Router]
        Database[Database]
        BaseController[BaseController]
        BaseModel[BaseModel]
    end
    
    Router --> C1
    Router --> C2
    Router --> C3
    Router --> C4
    
    C1 --> M1
    C1 --> M2
    C2 --> M1
    C2 --> M2
    C3 --> M4
    C4 --> M3
    
    C1 --> V1
    C1 --> V2
    C2 --> V3
    C3 --> V4
    
    M1 --> Database
    M2 --> Database
    M3 --> Database
    M4 --> Database
    
    C1 -.-> BaseController
    C2 -.-> BaseController
    C3 -.-> BaseController
    C4 -.-> BaseController
    
    M1 -.-> BaseModel
    M2 -.-> BaseModel
    M3 -.-> BaseModel
    M4 -.-> BaseModel
```

## 🔐 Security Architecture

```mermaid
graph TB
    subgraph "Input Layer"
        UserInput[User Input]
        FileUpload[File Upload]
        FormData[Form Data]
    end
    
    subgraph "Validation Layer"
        InputValidation[Input Validation]
        FileValidation[File Type Validation]
        CSRFProtection[CSRF Token Validation]
    end
    
    subgraph "Processing Layer"
        Sanitization[Data Sanitization]
        Authentication[User Authentication]
        Authorization[Permission Check]
    end
    
    subgraph "Data Layer"
        PreparedStatements[Prepared Statements]
        EncryptedStorage[Encrypted Storage]
        AuditLog[Audit Logging]
    end
    
    subgraph "Output Layer"
        OutputEscaping[Output Escaping]
        SecurityHeaders[Security Headers]
        ResponseFiltering[Response Filtering]
    end
    
    UserInput --> InputValidation
    FileUpload --> FileValidation
    FormData --> CSRFProtection
    
    InputValidation --> Sanitization
    FileValidation --> Sanitization
    CSRFProtection --> Authentication
    
    Sanitization --> Authorization
    Authentication --> Authorization
    
    Authorization --> PreparedStatements
    Authorization --> EncryptedStorage
    Authorization --> AuditLog
    
    PreparedStatements --> OutputEscaping
    EncryptedStorage --> SecurityHeaders
    AuditLog --> ResponseFiltering
```

## 🚀 Deployment Architecture

```mermaid
graph TB
    subgraph "Development Environment"
        DevCode[Local Code]
        DevDB[(Local MySQL)]
        DevServer[PHP Dev Server]
    end
    
    subgraph "Version Control"
        GitHub[GitHub Repository]
        GitActions[Git Actions/Hooks]
    end
    
    subgraph "Production Environment"
        BaotaPanel[Baota Panel]
        NginxProd[Nginx]
        PHPProd[PHP-FPM]
        MySQLProd[(MySQL)]
        FileSystem[File System]
    end
    
    subgraph "Monitoring"
        Logs[Error Logs]
        Metrics[Performance Metrics]
        Alerts[Alert System]
    end
    
    DevCode --> GitHub
    GitHub --> GitActions
    GitActions --> BaotaPanel
    BaotaPanel --> NginxProd
    BaotaPanel --> PHPProd
    BaotaPanel --> MySQLProd
    
    NginxProd --> Logs
    PHPProd --> Metrics
    MySQLProd --> Alerts
    
    DevServer -.-> DevDB
    PHPProd --> MySQLProd
    PHPProd --> FileSystem
```

## 📱 Responsive Design Architecture

```mermaid
graph TB
    subgraph "Breakpoints"
        Mobile[Mobile: <768px]
        Tablet[Tablet: 768px-1024px]
        Desktop[Desktop: >1024px]
    end
    
    subgraph "CSS Architecture"
        Base[Base Styles]
        Components[Component Styles]
        Utilities[Utility Classes]
        MediaQueries[Media Queries]
    end
    
    subgraph "Layout System"
        Grid[CSS Grid]
        Flexbox[Flexbox]
        Bootstrap[Bootstrap Grid]
    end
    
    subgraph "Performance"
        CriticalCSS[Critical CSS]
        LazyLoading[Lazy Loading]
        ImageOptimization[Image Optimization]
    end
    
    Mobile --> MediaQueries
    Tablet --> MediaQueries
    Desktop --> MediaQueries
    
    MediaQueries --> Components
    Base --> Components
    Components --> Utilities
    
    Components --> Grid
    Components --> Flexbox
    Components --> Bootstrap
    
    Grid --> CriticalCSS
    Flexbox --> LazyLoading
    Bootstrap --> ImageOptimization
```

## 🔄 Data Flow Diagram

```mermaid
graph LR
    subgraph "User Actions"
        A1[View Homepage]
        A2[Read Post]
        A3[Browse Directory]
        A4[Admin Login]
        A5[Create Content]
    end
    
    subgraph "System Processing"
        P1[Route Request]
        P2[Authenticate User]
        P3[Fetch Data]
        P4[Process Business Logic]
        P5[Render Response]
    end
    
    subgraph "Data Sources"
        D1[(Posts Table)]
        D2[(Categories Table)]
        D3[(Websites Table)]
        D4[(Admin Sessions)]
        D5[File System]
    end
    
    A1 --> P1
    A2 --> P1
    A3 --> P1
    A4 --> P2
    A5 --> P2
    
    P1 --> P3
    P2 --> P3
    P3 --> P4
    P4 --> P5
    
    P3 --> D1
    P3 --> D2
    P3 --> D3
    P2 --> D4
    P4 --> D5
```

## 🧪 Testing Architecture

```mermaid
graph TB
    subgraph "Test Types"
        Unit[Unit Tests]
        Integration[Integration Tests]
        E2E[End-to-End Tests]
    end
    
    subgraph "Test Tools"
        PHPUnit[PHPUnit]
        MockObjects[Mock Objects]
        TestDatabase[Test Database]
        Selenium[Selenium WebDriver]
    end
    
    subgraph "Test Environment"
        TestData[Test Fixtures]
        TestConfig[Test Configuration]
        TestServer[Test Server]
    end
    
    subgraph "CI/CD Pipeline"
        GitHooks[Git Hooks]
        AutomatedTests[Automated Testing]
        QualityGates[Quality Gates]
    end
    
    Unit --> PHPUnit
    Unit --> MockObjects
    Integration --> TestDatabase
    E2E --> Selenium
    
    PHPUnit --> TestData
    MockObjects --> TestConfig
    TestDatabase --> TestServer
    
    TestServer --> GitHooks
    TestData --> AutomatedTests
    TestConfig --> QualityGates
```

## 📊 Performance Monitoring Architecture

```mermaid
graph TB
    subgraph "Frontend Monitoring"
        WebVitals[Core Web Vitals]
        UserTiming[User Timing API]
        ResourceTiming[Resource Timing]
    end
    
    subgraph "Backend Monitoring"
        ExecutionTime[Execution Time]
        MemoryUsage[Memory Usage]
        QueryPerformance[Query Performance]
    end
    
    subgraph "Data Collection"
        MetricsAPI[Metrics API]
        LogFiles[Log Files]
        Database[(Metrics Database)]
    end
    
    subgraph "Analysis & Alerts"
        Dashboard[Performance Dashboard]
        Alerts[Alert System]
        Reports[Performance Reports]
    end
    
    WebVitals --> MetricsAPI
    UserTiming --> MetricsAPI
    ResourceTiming --> MetricsAPI
    
    ExecutionTime --> LogFiles
    MemoryUsage --> LogFiles
    QueryPerformance --> Database
    
    MetricsAPI --> Dashboard
    LogFiles --> Alerts
    Database --> Reports
```

## 🔧 Component Interaction Diagram

```mermaid
graph TB
    subgraph "Frontend Components"
        Header[Header Component]
        Navigation[Navigation Component]
        PostCard[Post Card Component]
        Sidebar[Sidebar Component]
        Footer[Footer Component]
    end
    
    subgraph "Backend Components"
        Router[Router Component]
        Auth[Authentication Component]
        Cache[Cache Component]
        Logger[Logger Component]
        Validator[Validator Component]
    end
    
    subgraph "Data Components"
        PostModel[Post Model]
        CategoryModel[Category Model]
        UserModel[User Model]
        DatabaseConnection[Database Connection]
    end
    
    Header --> Navigation
    Navigation --> PostCard
    PostCard --> Sidebar
    Sidebar --> Footer
    
    Router --> Auth
    Auth --> Cache
    Cache --> Logger
    Logger --> Validator
    
    PostModel --> DatabaseConnection
    CategoryModel --> DatabaseConnection
    UserModel --> DatabaseConnection
    
    Router -.-> PostModel
    Auth -.-> UserModel
    Validator -.-> CategoryModel
```

---

## 📝 Diagram Usage Guidelines

### When to Update Diagrams
- **Architecture Changes**: Update when system architecture evolves
- **New Components**: Add new components to relevant diagrams
- **Data Model Changes**: Update ERD when database schema changes
- **Security Updates**: Modify security diagrams when security measures change

### Diagram Maintenance
- **Monthly Review**: Check diagrams for accuracy
- **Version Control**: Keep diagrams in sync with code changes
- **Documentation**: Update related documentation when diagrams change
- **Stakeholder Review**: Share updated diagrams with team members

### Tools for Diagram Creation
- **Mermaid**: For text-based diagrams (recommended)
- **Draw.io**: For complex visual diagrams
- **PlantUML**: For UML diagrams
- **Lucidchart**: For collaborative diagramming
