<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui.css" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        /* Custom styling for orange theme */
        .swagger-ui .topbar {
            background-color: #ff5722;
            padding: 10px 0;
        }
        
        .swagger-ui .topbar .download-url-wrapper {
            display: none;
        }
        
        .swagger-ui .info .title {
            color: #ff5722;
            font-size: 36px;
        }
        
        .swagger-ui .btn.authorize {
            background-color: #ff5722;
            border-color: #ff5722;
        }
        
        .swagger-ui .btn.authorize:hover {
            background-color: #e64a19;
            border-color: #e64a19;
        }
        
        .swagger-ui .scheme-container {
            background: #fff;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.15);
        }
        
        .swagger-ui .opblock.opblock-post {
            border-color: #ff5722;
            background: rgba(255,87,34,.1);
        }
        
        .swagger-ui .opblock.opblock-post .opblock-summary-method {
            background: #ff5722;
        }
        
        .swagger-ui .opblock.opblock-get {
            border-color: #61affe;
            background: rgba(97,175,254,.1);
        }
        
        .swagger-ui .opblock.opblock-put {
            border-color: #fca130;
            background: rgba(252,161,48,.1);
        }
        
        .swagger-ui .opblock.opblock-delete {
            border-color: #f93e3e;
            background: rgba(249,62,62,.1);
        }
        
        /* Header customization */
        .api-header {
            background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
            color: white;
            padding: 20px 0;
            margin-bottom: 0;
        }
        
        .api-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .api-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        
        .api-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        
        .api-links {
            background: white;
            border-bottom: 1px solid #e5e5e5;
            padding: 15px 0;
        }
        
        .api-links .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .api-links a {
            color: #ff5722;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            border: 1px solid #ff5722;
            transition: all 0.2s;
        }
        
        .api-links a:hover {
            background: #ff5722;
            color: white;
        }
        
        .endpoint-count {
            background: #f8f9fa;
            color: #666;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
        }

        .error-message {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
        }

        .error-message h3 {
            color: #ff5722;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="api-header">
        <div class="container">
            <h1>📚 Content Management System API</h1>
            <p>Comprehensive REST API Documentation - v1.0.0</p>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="api-links">
        <div class="container">
            <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
            <a href="{{ route('api.swagger') }}" target="_blank">Download OpenAPI Spec</a>
            <div class="endpoint-count">
                <strong>Available Endpoints:</strong> 15+ REST endpoints
            </div>
        </div>
    </div>
    
    <!-- Error Message (initially hidden) -->
    <div id="error-message" class="error-message" style="display: none;">
        <h3>⚠️ Loading API Documentation...</h3>
        <p>If this message persists, there may be an issue with the API specification.</p>
        <p><strong>Base API URL:</strong> <code>{{ url('/api/v1') }}</code></p>
        <p><strong>Available Endpoints:</strong></p>
        <ul style="text-align: left; display: inline-block;">
            <li><code>GET /api/v1/posts</code> - Get all posts</li>
            <li><code>POST /api/v1/posts</code> - Create post</li>
            <li><code>GET /api/v1/posts/{slug}</code> - Get single post</li>
            <li><code>GET /api/v1/categories</code> - Get all categories</li>
            <li><code>POST /api/v1/categories</code> - Create category</li>
            <li><code>GET /api/v1/pages</code> - Get all pages</li>
            <li><code>POST /api/v1/pages</code> - Create page</li>
        </ul>
    </div>
    
    <!-- Swagger UI Container -->
    <div id="swagger-ui"></div>
    
    <!-- Swagger UI Scripts -->
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Show error message after 5 seconds if Swagger UI hasn't loaded
            setTimeout(function() {
                const swaggerContainer = document.getElementById('swagger-ui');
                if (!swaggerContainer || !swaggerContainer.children.length) {
                    document.getElementById('error-message').style.display = 'block';
                }
            }, 5000);

            // Simple OpenAPI spec embedded in the page (fallback)
            const fallbackSpec = {
                "openapi": "3.0.0",
                "info": {
                    "title": "Content Management System API",
                    "version": "1.0.0",
                    "description": "Comprehensive REST API for managing posts, categories, and pages"
                },
                "servers": [
                    {
                        "url": "{{ url('/api/v1') }}",
                        "description": "API Server"
                    }
                ],
                "paths": {
                    "/posts": {
                        "get": {
                            "tags": ["Posts"],
                            "summary": "Get all posts",
                            "description": "Retrieve a paginated list of posts",
                            "parameters": [
                                {
                                    "name": "search",
                                    "in": "query",
                                    "required": false,
                                    "schema": {
                                        "type": "string"
                                    }
                                }
                            ],
                            "responses": {
                                "200": {
                                    "description": "Posts retrieved successfully"
                                }
                            }
                        },
                        "post": {
                            "tags": ["Posts"],
                            "summary": "Create a new post",
                            "responses": {
                                "201": {
                                    "description": "Post created successfully"
                                }
                            }
                        }
                    },
                    "/categories": {
                        "get": {
                            "tags": ["Categories"],
                            "summary": "Get all categories",
                            "responses": {
                                "200": {
                                    "description": "Categories retrieved successfully"
                                }
                            }
                        }
                    },
                    "/pages": {
                        "get": {
                            "tags": ["Pages"],
                            "summary": "Get all pages",
                            "responses": {
                                "200": {
                                    "description": "Pages retrieved successfully"
                                }
                            }
                        }
                    }
                }
            };

            // Try to initialize Swagger UI with the dynamic endpoint first
            try {
                const ui = SwaggerUIBundle({
                    url: '{{ route("api.swagger") }}',
                    dom_id: '#swagger-ui',
                    deepLinking: true,
                    presets: [
                        SwaggerUIBundle.presets.apis,
                        SwaggerUIStandalonePreset
                    ],
                    plugins: [
                        SwaggerUIBundle.plugins.DownloadUrl
                    ],
                    layout: "StandaloneLayout",
                    tryItOutEnabled: true,
                    filter: true,
                    supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                    requestInterceptor: function(request) {
                        try {
                            // Add base URL if not already present
                            if (request.url && !request.url.startsWith('http')) {
                                request.url = '{{ url('/') }}' + request.url;
                            }
                            
                            // Add CSRF token for write operations - safely check method
                            const method = request.method ? String(request.method).toUpperCase() : 'GET';
                            if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                                // Ensure headers object exists
                                if (!request.headers) {
                                    request.headers = {};
                                }
                                
                                if (!request.headers['X-CSRF-TOKEN']) {
                                    request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                                }
                                request.headers['Accept'] = 'application/json';
                            }
                        } catch (error) {
                            console.warn('Request interceptor error:', error);
                        }
                        
                        return request;
                    },
                    onComplete: function() {
                        console.log("✅ Swagger UI loaded successfully from dynamic endpoint");
                        document.getElementById('error-message').style.display = 'none';
                    },
                    onFailure: function(data) {
                        console.error("❌ Failed to load from dynamic endpoint, trying fallback...");
                        
                        // Try fallback with embedded spec
                        const fallbackUi = SwaggerUIBundle({
                            spec: fallbackSpec,
                            dom_id: '#swagger-ui',
                            deepLinking: true,
                            presets: [
                                SwaggerUIBundle.presets.apis,
                                SwaggerUIStandalonePreset
                            ],
                            layout: "StandaloneLayout",
                            tryItOutEnabled: true,
                            filter: true,
                            onComplete: function() {
                                console.log("✅ Swagger UI loaded with fallback spec");
                                document.getElementById('error-message').style.display = 'none';
                            },
                            onFailure: function() {
                                console.error("❌ Both dynamic and fallback specs failed");
                                document.getElementById('error-message').style.display = 'block';
                            }
                        });
                    }
                });
            } catch (error) {
                console.error("❌ Error initializing Swagger UI:", error);
                
                // Use fallback spec directly
                const fallbackUi = SwaggerUIBundle({
                    spec: fallbackSpec,
                    dom_id: '#swagger-ui',
                    deepLinking: true,
                    presets: [
                        SwaggerUIBundle.presets.apis,
                        SwaggerUIStandalonePreset
                    ],
                    layout: "StandaloneLayout",
                    tryItOutEnabled: true,
                    filter: true,
                    onComplete: function() {
                        console.log("✅ Swagger UI loaded with embedded fallback spec");
                        document.getElementById('error-message').style.display = 'none';
                    }
                });
            }
        };
    </script>
</body>
</html> 