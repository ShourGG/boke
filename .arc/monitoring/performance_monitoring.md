# Koi Blog - Performance Monitoring Framework

## 🎯 Performance Philosophy
Performance is a feature, not an afterthought. We monitor, measure, and optimize continuously to ensure excellent user experience across all devices and network conditions.

## 📊 Key Performance Indicators (KPIs)

### Core Web Vitals
- **Largest Contentful Paint (LCP)**: ≤ 2.5 seconds
- **First Input Delay (FID)**: ≤ 100 milliseconds  
- **Cumulative Layout Shift (CLS)**: ≤ 0.1
- **First Contentful Paint (FCP)**: ≤ 1.8 seconds

### Custom Metrics
- **Time to Interactive (TTI)**: ≤ 3.5 seconds
- **Total Blocking Time (TBT)**: ≤ 200 milliseconds
- **Speed Index**: ≤ 3.0 seconds
- **Database Query Time**: ≤ 100ms average
- **Page Load Time**: ≤ 2.0 seconds (3G connection)

## 🔍 Monitoring Implementation

### Frontend Performance Monitoring
```javascript
// Performance monitoring script
(function() {
    'use strict';
    
    // Core Web Vitals measurement
    function measureCoreWebVitals() {
        // Largest Contentful Paint
        new PerformanceObserver((entryList) => {
            const entries = entryList.getEntries();
            const lastEntry = entries[entries.length - 1];
            
            sendMetric('lcp', lastEntry.startTime);
        }).observe({entryTypes: ['largest-contentful-paint']});
        
        // First Input Delay
        new PerformanceObserver((entryList) => {
            const firstInput = entryList.getEntries()[0];
            const fid = firstInput.processingStart - firstInput.startTime;
            
            sendMetric('fid', fid);
        }).observe({entryTypes: ['first-input']});
        
        // Cumulative Layout Shift
        let clsValue = 0;
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                }
            }
            sendMetric('cls', clsValue);
        }).observe({entryTypes: ['layout-shift']});
    }
    
    // Custom timing measurements
    function measureCustomMetrics() {
        // Time to Interactive
        const tti = performance.timing.domInteractive - performance.timing.navigationStart;
        sendMetric('tti', tti);
        
        // DOM Content Loaded
        const dcl = performance.timing.domContentLoadedEventEnd - performance.timing.navigationStart;
        sendMetric('dcl', dcl);
        
        // Full page load
        const load = performance.timing.loadEventEnd - performance.timing.navigationStart;
        sendMetric('load', load);
    }
    
    // Resource timing
    function measureResourcePerformance() {
        const resources = performance.getEntriesByType('resource');
        
        resources.forEach(resource => {
            if (resource.name.includes('.css')) {
                sendMetric('css_load_time', resource.duration, resource.name);
            } else if (resource.name.includes('.js')) {
                sendMetric('js_load_time', resource.duration, resource.name);
            } else if (resource.name.match(/\.(jpg|jpeg|png|gif|webp)$/)) {
                sendMetric('image_load_time', resource.duration, resource.name);
            }
        });
    }
    
    // Send metrics to server
    function sendMetric(name, value, resource = null) {
        const data = {
            metric: name,
            value: Math.round(value),
            url: window.location.pathname,
            timestamp: Date.now(),
            user_agent: navigator.userAgent,
            connection: navigator.connection ? navigator.connection.effectiveType : 'unknown'
        };
        
        if (resource) {
            data.resource = resource;
        }
        
        // Send via beacon API (non-blocking)
        if (navigator.sendBeacon) {
            navigator.sendBeacon('/api/metrics', JSON.stringify(data));
        } else {
            // Fallback for older browsers
            fetch('/api/metrics', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {'Content-Type': 'application/json'},
                keepalive: true
            }).catch(() => {}); // Ignore errors
        }
    }
    
    // Initialize monitoring when page loads
    if (document.readyState === 'complete') {
        measureCoreWebVitals();
        measureCustomMetrics();
        measureResourcePerformance();
    } else {
        window.addEventListener('load', () => {
            measureCoreWebVitals();
            measureCustomMetrics();
            measureResourcePerformance();
        });
    }
})();
```

### Backend Performance Monitoring
```php
<?php
/**
 * Performance monitoring middleware
 */
class PerformanceMonitor
{
    private $startTime;
    private $startMemory;
    private $queryCount = 0;
    private $queryTime = 0;
    
    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage(true);
    }
    
    /**
     * Track database query performance
     */
    public function trackQuery($sql, $params, $executionTime)
    {
        $this->queryCount++;
        $this->queryTime += $executionTime;
        
        // Log slow queries (>100ms)
        if ($executionTime > 0.1) {
            $this->logSlowQuery($sql, $params, $executionTime);
        }
    }
    
    /**
     * Generate performance report
     */
    public function generateReport()
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $report = [
            'execution_time' => round(($endTime - $this->startTime) * 1000, 2), // ms
            'memory_usage' => round(($endMemory - $this->startMemory) / 1024 / 1024, 2), // MB
            'peak_memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 2), // MB
            'query_count' => $this->queryCount,
            'query_time' => round($this->queryTime * 1000, 2), // ms
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'timestamp' => time(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
        
        // Send to monitoring system
        $this->sendMetrics($report);
        
        // Add to response headers (development only)
        if (defined('DEBUG') && DEBUG) {
            header("X-Execution-Time: {$report['execution_time']}ms");
            header("X-Memory-Usage: {$report['memory_usage']}MB");
            header("X-Query-Count: {$report['query_count']}");
        }
        
        return $report;
    }
    
    /**
     * Log slow queries for optimization
     */
    private function logSlowQuery($sql, $params, $time)
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'execution_time' => round($time * 1000, 2),
            'sql' => $sql,
            'params' => $params,
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ];
        
        error_log('SLOW_QUERY: ' . json_encode($logEntry));
    }
    
    /**
     * Send metrics to monitoring system
     */
    private function sendMetrics($metrics)
    {
        // Store in database for analysis
        try {
            $db = Database::getInstance();
            $db->execute(
                "INSERT INTO performance_metrics (url, method, execution_time, memory_usage, query_count, query_time, timestamp) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $metrics['url'],
                    $metrics['method'],
                    $metrics['execution_time'],
                    $metrics['memory_usage'],
                    $metrics['query_count'],
                    $metrics['query_time'],
                    $metrics['timestamp']
                ]
            );
        } catch (Exception $e) {
            // Don't let monitoring break the application
            error_log('Performance monitoring error: ' . $e->getMessage());
        }
    }
}

// Usage in application
$monitor = new PerformanceMonitor();

// At the end of request
register_shutdown_function(function() use ($monitor) {
    $monitor->generateReport();
});
```

### Database Performance Monitoring
```sql
-- Performance metrics table
CREATE TABLE performance_metrics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(500) NOT NULL,
    method VARCHAR(10) NOT NULL,
    execution_time DECIMAL(8,2) NOT NULL,  -- milliseconds
    memory_usage DECIMAL(8,2) NOT NULL,    -- MB
    query_count INT UNSIGNED NOT NULL,
    query_time DECIMAL(8,2) NOT NULL,      -- milliseconds
    timestamp INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    KEY idx_url (url(100)),
    KEY idx_timestamp (timestamp),
    KEY idx_execution_time (execution_time),
    KEY idx_created_at (created_at)
);

-- Slow query log table
CREATE TABLE slow_queries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sql_query TEXT NOT NULL,
    execution_time DECIMAL(8,2) NOT NULL,
    url VARCHAR(500),
    timestamp INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    KEY idx_execution_time (execution_time),
    KEY idx_timestamp (timestamp),
    KEY idx_url (url(100))
);
```

## 📈 Performance Analytics

### Daily Performance Report
```php
<?php
/**
 * Generate daily performance analytics
 */
class PerformanceAnalytics
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Generate daily performance summary
     */
    public function getDailyReport($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $startTimestamp = strtotime($date . ' 00:00:00');
        $endTimestamp = strtotime($date . ' 23:59:59');
        
        // Overall performance metrics
        $overall = $this->db->fetch("
            SELECT 
                COUNT(*) as total_requests,
                AVG(execution_time) as avg_execution_time,
                MAX(execution_time) as max_execution_time,
                AVG(memory_usage) as avg_memory_usage,
                MAX(memory_usage) as max_memory_usage,
                AVG(query_count) as avg_query_count,
                MAX(query_count) as max_query_count,
                AVG(query_time) as avg_query_time
            FROM performance_metrics 
            WHERE timestamp BETWEEN ? AND ?
        ", [$startTimestamp, $endTimestamp]);
        
        // Slowest pages
        $slowestPages = $this->db->fetchAll("
            SELECT 
                url,
                COUNT(*) as request_count,
                AVG(execution_time) as avg_time,
                MAX(execution_time) as max_time
            FROM performance_metrics 
            WHERE timestamp BETWEEN ? AND ?
            GROUP BY url
            ORDER BY avg_time DESC
            LIMIT 10
        ", [$startTimestamp, $endTimestamp]);
        
        // Performance by hour
        $hourlyPerformance = $this->db->fetchAll("
            SELECT 
                HOUR(FROM_UNIXTIME(timestamp)) as hour,
                COUNT(*) as requests,
                AVG(execution_time) as avg_time
            FROM performance_metrics 
            WHERE timestamp BETWEEN ? AND ?
            GROUP BY HOUR(FROM_UNIXTIME(timestamp))
            ORDER BY hour
        ", [$startTimestamp, $endTimestamp]);
        
        return [
            'date' => $date,
            'overall' => $overall,
            'slowest_pages' => $slowestPages,
            'hourly_performance' => $hourlyPerformance
        ];
    }
    
    /**
     * Get performance trends over time
     */
    public function getPerformanceTrends($days = 7)
    {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        return $this->db->fetchAll("
            SELECT 
                DATE(FROM_UNIXTIME(timestamp)) as date,
                COUNT(*) as requests,
                AVG(execution_time) as avg_execution_time,
                AVG(memory_usage) as avg_memory_usage,
                AVG(query_count) as avg_query_count
            FROM performance_metrics 
            WHERE DATE(FROM_UNIXTIME(timestamp)) BETWEEN ? AND ?
            GROUP BY DATE(FROM_UNIXTIME(timestamp))
            ORDER BY date
        ", [$startDate, $endDate]);
    }
}
```

## 🚨 Performance Alerts

### Alert Thresholds
```php
<?php
/**
 * Performance alert system
 */
class PerformanceAlerts
{
    private $thresholds = [
        'execution_time' => 2000,    // 2 seconds
        'memory_usage' => 64,        // 64 MB
        'query_count' => 20,         // 20 queries per request
        'query_time' => 500,         // 500ms total query time
    ];
    
    /**
     * Check if metrics exceed thresholds
     */
    public function checkThresholds($metrics)
    {
        $alerts = [];
        
        foreach ($this->thresholds as $metric => $threshold) {
            if (isset($metrics[$metric]) && $metrics[$metric] > $threshold) {
                $alerts[] = [
                    'metric' => $metric,
                    'value' => $metrics[$metric],
                    'threshold' => $threshold,
                    'severity' => $this->getSeverity($metric, $metrics[$metric], $threshold)
                ];
            }
        }
        
        if (!empty($alerts)) {
            $this->sendAlert($alerts, $metrics);
        }
        
        return $alerts;
    }
    
    /**
     * Determine alert severity
     */
    private function getSeverity($metric, $value, $threshold)
    {
        $ratio = $value / $threshold;
        
        if ($ratio >= 2.0) return 'critical';
        if ($ratio >= 1.5) return 'warning';
        return 'info';
    }
    
    /**
     * Send performance alert
     */
    private function sendAlert($alerts, $metrics)
    {
        $message = "Performance Alert - " . $metrics['url'] . "\n\n";
        
        foreach ($alerts as $alert) {
            $message .= sprintf(
                "%s: %s (threshold: %s) - %s\n",
                strtoupper($alert['metric']),
                $alert['value'],
                $alert['threshold'],
                strtoupper($alert['severity'])
            );
        }
        
        // Log alert
        error_log('PERFORMANCE_ALERT: ' . json_encode([
            'alerts' => $alerts,
            'metrics' => $metrics,
            'timestamp' => time()
        ]));
        
        // Send email/notification (implement as needed)
        // $this->sendNotification($message);
    }
}
```

## 🔧 Performance Optimization Tools

### Query Optimization
```php
<?php
/**
 * Database query analyzer
 */
class QueryAnalyzer
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Analyze query performance
     */
    public function analyzeQuery($sql, $params = [])
    {
        // Get query execution plan
        $explainSql = "EXPLAIN " . $sql;
        $plan = $this->db->fetchAll($explainSql, $params);
        
        // Check for performance issues
        $issues = [];
        
        foreach ($plan as $row) {
            // Check for full table scans
            if ($row['type'] === 'ALL') {
                $issues[] = "Full table scan on table: {$row['table']}";
            }
            
            // Check for missing indexes
            if ($row['key'] === null && $row['rows'] > 1000) {
                $issues[] = "No index used for table: {$row['table']} ({$row['rows']} rows examined)";
            }
            
            // Check for temporary tables
            if (isset($row['Extra']) && strpos($row['Extra'], 'Using temporary') !== false) {
                $issues[] = "Temporary table created for query";
            }
        }
        
        return [
            'execution_plan' => $plan,
            'issues' => $issues,
            'recommendations' => $this->getRecommendations($issues)
        ];
    }
    
    private function getRecommendations($issues)
    {
        $recommendations = [];
        
        foreach ($issues as $issue) {
            if (strpos($issue, 'Full table scan') !== false) {
                $recommendations[] = "Add appropriate indexes to avoid full table scans";
            }
            if (strpos($issue, 'No index used') !== false) {
                $recommendations[] = "Create indexes on frequently queried columns";
            }
            if (strpos($issue, 'Temporary table') !== false) {
                $recommendations[] = "Optimize query to avoid temporary table creation";
            }
        }
        
        return array_unique($recommendations);
    }
}
```

## 📊 Performance Dashboard

### Metrics Collection API
```php
<?php
/**
 * API endpoint for collecting performance metrics
 */
class MetricsController
{
    /**
     * Collect frontend performance metrics
     */
    public function collectMetrics()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['metric'])) {
            http_response_code(400);
            return;
        }
        
        try {
            $db = Database::getInstance();
            
            // Store frontend metrics
            $db->execute("
                INSERT INTO frontend_metrics (metric_name, value, url, timestamp, user_agent, connection_type)
                VALUES (?, ?, ?, ?, ?, ?)
            ", [
                $input['metric'],
                $input['value'],
                $input['url'] ?? '',
                $input['timestamp'] ?? time(),
                $input['user_agent'] ?? '',
                $input['connection'] ?? 'unknown'
            ]);
            
            http_response_code(204); // No content
        } catch (Exception $e) {
            error_log('Metrics collection error: ' . $e->getMessage());
            http_response_code(500);
        }
    }
}
```

## ✅ Performance Checklist

### Development Phase
- [ ] Database queries optimized with proper indexes
- [ ] Images compressed and optimized
- [ ] CSS and JavaScript minified
- [ ] Critical CSS inlined
- [ ] Lazy loading implemented for images
- [ ] Caching strategy implemented
- [ ] Performance monitoring code added

### Pre-Production
- [ ] Lighthouse audit score >90
- [ ] Core Web Vitals meet targets
- [ ] Load testing completed
- [ ] Database performance tested
- [ ] CDN configured (if applicable)
- [ ] Monitoring alerts configured

### Production Monitoring
- [ ] Daily performance reports reviewed
- [ ] Slow query log monitored
- [ ] Alert thresholds configured
- [ ] Performance trends tracked
- [ ] User experience metrics collected
