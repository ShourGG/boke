<?php
/**
 * Test Editor.md Fix
 * 测试Editor.md修复效果
 */

// Load configuration
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor.md Fix Test - <?= SITE_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-bug text-danger"></i> Editor.md Fix Test
                </h1>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>测试目标：</strong>验证jQuery依赖冲突修复、Bootstrap组件正常工作、Editor.md流程图支持
                </div>
                
                <!-- Bootstrap Components Test -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Bootstrap Components Test</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="This is a tooltip">
                            <i class="fas fa-info"></i> Tooltip Test
                        </button>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#testModal">
                            <i class="fas fa-window-maximize"></i> Modal Test
                        </button>
                        
                        <div class="dropdown d-inline">
                            <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-caret-down"></i> Dropdown Test
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Editor.md Test -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>
                            <i class="fab fa-markdown"></i> Editor.md Test
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="editormd">
                            <textarea id="content" name="content">### Editor.md Test

This is a test for Editor.md with flowchart support.

#### Flowchart Test

```flow
st=>start: 用户登陆
op=>operation: 登陆操作
cond=>condition: 登陆成功 Yes or No?
e=>end: 进入后台

st->op->cond
cond(yes)->e
cond(no)->op
```

#### Sequence Diagram Test

```sequence
Alice->Bob: Hello Bob, how are you?
Note right of Bob: Bob thinks
Bob-->Alice: I am good thanks!
```

#### Math Formula Test

$$E=mc^2$$

#### Task List Test

- [x] Fix jQuery conflicts
- [x] Fix Bootstrap components
- [ ] Test flowchart rendering
- [ ] Test sequence diagram rendering
</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Console Output -->
                <div class="card">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-terminal"></i> Console Output
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="consoleOutput" class="bg-dark text-light p-3 rounded" style="font-family: monospace; height: 200px; overflow-y: auto;">
                            <div class="text-success">Console output will appear here...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Test Modal -->
    <div class="modal fade" id="testModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bootstrap Modal Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>If you can see this modal, Bootstrap JavaScript is working correctly!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap冲突修复 (必须在Bootstrap之前加载) -->
    <script src="<?= SITE_URL ?>/public/js/bootstrap-fix.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Editor.md Resources -->
    <link rel="stylesheet" href="/public/editor.md/css/editormd.min.css">
    
    <!-- jQuery and Editor.md Dependencies -->
    <script>
    /**
     * Editor.md Dependency Manager for Test Page
     */
    (function() {
        'use strict';
        
        const consoleOutput = document.getElementById('consoleOutput');
        
        function logToConsole(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const colorClass = type === 'error' ? 'text-danger' : type === 'warn' ? 'text-warning' : 'text-success';
            consoleOutput.innerHTML += `<div class="${colorClass}">[${timestamp}] ${message}</div>`;
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
            console[type](message);
        }
        
        // Store Bootstrap reference to prevent conflicts
        const originalBootstrap = window.bootstrap;
        
        // Load jQuery if not already loaded
        if (typeof window.jQuery === 'undefined') {
            logToConsole('Loading jQuery for Editor.md...');
            
            const jqueryScript = document.createElement('script');
            jqueryScript.src = '/public/editor.md/lib/jquery.min.js';
            jqueryScript.onload = function() {
                logToConsole('jQuery loaded successfully');
                loadEditorMdDependencies();
            };
            jqueryScript.onerror = function() {
                logToConsole('Failed to load jQuery', 'error');
            };
            document.head.appendChild(jqueryScript);
        } else {
            logToConsole('jQuery already available');
            loadEditorMdDependencies();
        }
        
        function loadEditorMdDependencies() {
            // Restore Bootstrap reference
            if (originalBootstrap) {
                window.bootstrap = originalBootstrap;
                logToConsole('Bootstrap reference restored');
            }
            
            // Store jQuery reference for Editor.md
            window.editormdJQuery = window.jQuery;
            
            // Load Editor.md core
            const editormdScript = document.createElement('script');
            editormdScript.src = '/public/editor.md/editormd.min.js';
            editormdScript.onload = function() {
                logToConsole('Editor.md core loaded');
                loadFlowchartDependencies();
            };
            editormdScript.onerror = function() {
                logToConsole('Failed to load Editor.md core', 'error');
            };
            document.head.appendChild(editormdScript);
        }
        
        function loadFlowchartDependencies() {
            const dependencies = [
                '/public/editor.md/lib/raphael.min.js',
                '/public/editor.md/lib/underscore.min.js',
                '/public/editor.md/lib/flowchart.min.js',
                '/public/editor.md/lib/jquery.flowchart.min.js',
                '/public/editor.md/lib/sequence-diagram.min.js'
            ];
            
            let loadedCount = 0;
            
            dependencies.forEach(function(src) {
                const script = document.createElement('script');
                script.src = src;
                script.onload = function() {
                    loadedCount++;
                    logToConsole('Loaded dependency: ' + src.split('/').pop());
                    
                    if (loadedCount === dependencies.length) {
                        logToConsole('All Editor.md dependencies loaded successfully');
                        initializeEditor();
                    }
                };
                script.onerror = function() {
                    logToConsole('Failed to load dependency: ' + src, 'error');
                };
                document.head.appendChild(script);
            });
        }
        
        function initializeEditor() {
            try {
                logToConsole('Initializing Editor.md...');
                
                const editor = editormd("editormd", {
                    width: "100%",
                    height: 500,
                    syncScrolling: "single",
                    path: "/public/editor.md/lib/",
                    saveHTMLToTextarea: true,
                    searchReplace: true,
                    watch: true,
                    toolbar: true,
                    previewCodeHighlight: true,
                    emoji: true,
                    taskList: true,
                    tocm: true,
                    tex: true,
                    flowChart: true,
                    sequenceDiagram: true,
                    onload: function() {
                        logToConsole('Editor.md initialized successfully!');
                        
                        // Test flowchart functionality
                        setTimeout(function() {
                            testFlowchartSupport();
                        }, 1000);
                    }
                });
                
            } catch (error) {
                logToConsole('Failed to initialize Editor.md: ' + error.message, 'error');
            }
        }
        
        function testFlowchartSupport() {
            try {
                logToConsole('Testing flowchart support...');
                
                if (typeof window.flowchart !== 'undefined') {
                    logToConsole('✓ Flowchart.js is available');
                } else {
                    logToConsole('✗ Flowchart.js is not available', 'warn');
                }
                
                if (typeof window.Diagram !== 'undefined') {
                    logToConsole('✓ Sequence diagram is available');
                } else {
                    logToConsole('✗ Sequence diagram is not available', 'warn');
                }
                
                logToConsole('Test completed! Check the preview pane for rendered diagrams.');
                
            } catch (error) {
                logToConsole('Error testing flowchart support: ' + error.message, 'error');
            }
        }
        
    })();
    </script>
</body>
</html>
