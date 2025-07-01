<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateApiDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate-docs {--output=public/api-docs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Swagger API documentation from annotations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Generating Swagger API Documentation...');
        
        $outputPath = $this->option('output');
        
        // Create output directory if it doesn't exist
        if (!File::exists($outputPath)) {
            File::makeDirectory($outputPath, 0755, true);
            $this->info("📁 Created directory: {$outputPath}");
        }

        // Generate OpenAPI specification
        $openApiSpec = $this->generateOpenApiSpec();
        
        // Save JSON specification
        $jsonPath = $outputPath . '/swagger.json';
        File::put($jsonPath, json_encode($openApiSpec, JSON_PRETTY_PRINT));
        $this->info("📄 Generated: {$jsonPath}");

        // Generate HTML documentation
        $htmlPath = $outputPath . '/index.html';
        File::put($htmlPath, $this->generateHtmlDocs());
        $this->info("🌐 Generated: {$htmlPath}");

        // Generate README
        $readmePath = $outputPath . '/README.md';
        File::put($readmePath, $this->generateReadme());
        $this->info("📋 Generated: {$readmePath}");

        $this->newLine();
        $this->info('✅ API Documentation generated successfully!');
        $this->info("📖 View documentation at: " . url($outputPath . '/index.html'));
        $this->info("🔗 JSON spec available at: " . url($outputPath . '/swagger.json'));
    }

    /**
     * Generate OpenAPI specification
     */
    private function generateOpenApiSpec(): array
    {
        return [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Content Management System API',
                'version' => '1.0.0',
                'description' => 'Comprehensive REST API for managing posts, categories, and pages. Built with Laravel and following REST best practices.',
                'contact' => [
                    'email' => 'api@example.com',
                    'name' => 'API Support'
                ],
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://opensource.org/licenses/MIT'
                ]
            ],
            'servers' => [
                [
                    'url' => url('/api/v1'),
                    'description' => 'API Server'
                ]
            ],
            'tags' => [
                ['name' => 'Posts', 'description' => 'Operations related to blog posts'],
                ['name' => 'Categories', 'description' => 'Operations related to post categories'],
                ['name' => 'Pages', 'description' => 'Operations related to static pages']
            ],
            'paths' => $this->generatePaths(),
            'components' => [
                'schemas' => $this->generateSchemas(),
                'parameters' => $this->generateParameters(),
                'responses' => $this->generateResponses()
            ]
        ];
    }

    /**
     * Generate API paths
     */
    private function generatePaths(): array
    {
        return [
            '/posts' => [
                'get' => [
                    'tags' => ['Posts'],
                    'summary' => 'Get all posts',
                    'description' => 'Retrieve a paginated list of posts with filtering and search capabilities',
                    'parameters' => [
                        ['$ref' => '#/components/parameters/search'],
                        ['$ref' => '#/components/parameters/status'],
                        ['$ref' => '#/components/parameters/category'],
                        ['$ref' => '#/components/parameters/author'],
                        ['$ref' => '#/components/parameters/include_drafts'],
                        ['$ref' => '#/components/parameters/sort_by'],
                        ['$ref' => '#/components/parameters/sort_order'],
                        ['$ref' => '#/components/parameters/per_page'],
                        ['$ref' => '#/components/parameters/page']
                    ],
                    'responses' => [
                        '200' => ['$ref' => '#/components/responses/PostsListResponse'],
                        '500' => ['$ref' => '#/components/responses/ErrorResponse']
                    ]
                ],
                'post' => [
                    'tags' => ['Posts'],
                    'summary' => 'Create a new post',
                    'description' => 'Create a new blog post with categories and optional image upload',
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'multipart/form-data' => [
                                'schema' => ['$ref' => '#/components/schemas/PostCreateRequest']
                            ]
                        ]
                    ],
                    'responses' => [
                        '201' => ['$ref' => '#/components/responses/PostCreatedResponse'],
                        '422' => ['$ref' => '#/components/responses/ValidationErrorResponse'],
                        '500' => ['$ref' => '#/components/responses/ErrorResponse']
                    ]
                ]
            ],
            '/posts/{slug}' => [
                'get' => [
                    'tags' => ['Posts'],
                    'summary' => 'Get a specific post',
                    'description' => 'Retrieve a single post by its slug, including full content',
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'required' => true,
                            'description' => 'Post slug',
                            'schema' => ['type' => 'string', 'example' => 'getting-started-with-laravel']
                        ]
                    ],
                    'responses' => [
                        '200' => ['$ref' => '#/components/responses/PostDetailResponse'],
                        '404' => ['$ref' => '#/components/responses/NotFoundResponse'],
                        '500' => ['$ref' => '#/components/responses/ErrorResponse']
                    ]
                ]
            ],
            '/posts/{id}' => [
                'put' => [
                    'tags' => ['Posts'],
                    'summary' => 'Update a post',
                    'description' => 'Update an existing post by ID',
                    'parameters' => [
                        [
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'description' => 'Post ID',
                            'schema' => ['type' => 'integer', 'example' => 1]
                        ]
                    ],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'multipart/form-data' => [
                                'schema' => ['$ref' => '#/components/schemas/PostUpdateRequest']
                            ]
                        ]
                    ],
                    'responses' => [
                        '200' => ['$ref' => '#/components/responses/PostDetailResponse'],
                        '404' => ['$ref' => '#/components/responses/NotFoundResponse'],
                        '422' => ['$ref' => '#/components/responses/ValidationErrorResponse'],
                        '500' => ['$ref' => '#/components/responses/ErrorResponse']
                    ]
                ],
                'delete' => [
                    'tags' => ['Posts'],
                    'summary' => 'Delete a post',
                    'description' => 'Delete a post by ID, including associated image and category relationships',
                    'parameters' => [
                        [
                            'name' => 'id',
                            'in' => 'path',
                            'required' => true,
                            'description' => 'Post ID',
                            'schema' => ['type' => 'integer', 'example' => 1]
                        ]
                    ],
                    'responses' => [
                        '200' => ['$ref' => '#/components/responses/SuccessResponse'],
                        '404' => ['$ref' => '#/components/responses/NotFoundResponse'],
                        '500' => ['$ref' => '#/components/responses/ErrorResponse']
                    ]
                ]
            ],
            // Add similar definitions for Categories and Pages...
        ];
    }

    /**
     * Generate schema definitions
     */
    private function generateSchemas(): array
    {
        return [
            'Post' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer', 'example' => 1],
                    'title' => ['type' => 'string', 'example' => 'Getting Started with Laravel'],
                    'slug' => ['type' => 'string', 'example' => 'getting-started-with-laravel'],
                    'short_description' => ['type' => 'string', 'example' => 'Learn the basics of Laravel framework'],
                    'content' => ['type' => 'string', 'example' => '<p>Full post content with HTML...</p>'],
                    'image_url' => ['type' => 'string', 'example' => 'http://domain.com/images/posts/image.jpg'],
                    'status' => ['type' => 'string', 'enum' => ['draft', 'published'], 'example' => 'published'],
                    'published_at' => ['type' => 'string', 'format' => 'date-time', 'example' => '2024-01-15T10:30:00.000000Z'],
                    'created_at' => ['type' => 'string', 'format' => 'date-time', 'example' => '2024-01-15T10:25:00.000000Z'],
                    'updated_at' => ['type' => 'string', 'format' => 'date-time', 'example' => '2024-01-15T10:30:00.000000Z'],
                    'author' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'name' => ['type' => 'string', 'example' => 'John Doe'],
                            'email' => ['type' => 'string', 'example' => 'john@example.com']
                        ]
                    ],
                    'categories' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => ['type' => 'integer', 'example' => 1],
                                'name' => ['type' => 'string', 'example' => 'Technology'],
                                'slug' => ['type' => 'string', 'example' => 'technology']
                            ]
                        ]
                    ]
                ]
            ],
            'PostCreateRequest' => [
                'type' => 'object',
                'required' => ['title', 'content', 'status'],
                'properties' => [
                    'title' => ['type' => 'string', 'maxLength' => 255, 'example' => 'My New Post'],
                    'slug' => ['type' => 'string', 'maxLength' => 255, 'example' => 'my-new-post'],
                    'short_description' => ['type' => 'string', 'maxLength' => 500, 'example' => 'Brief description of the post'],
                    'content' => ['type' => 'string', 'example' => '<p>Full post content with HTML...</p>'],
                    'status' => ['type' => 'string', 'enum' => ['draft', 'published'], 'example' => 'published'],
                    'published_at' => ['type' => 'string', 'format' => 'date', 'example' => '2024-01-15'],
                    'category_ids' => [
                        'type' => 'array',
                        'items' => ['type' => 'integer'],
                        'example' => [1, 2, 3]
                    ],
                    'image' => ['type' => 'string', 'format' => 'binary', 'description' => 'Image file upload']
                ]
            ]
            // Add more schemas...
        ];
    }

    /**
     * Generate parameter definitions
     */
    private function generateParameters(): array
    {
        return [
            'search' => [
                'name' => 'search',
                'in' => 'query',
                'description' => 'Search term to filter results',
                'required' => false,
                'schema' => ['type' => 'string', 'example' => 'laravel']
            ],
            'status' => [
                'name' => 'status',
                'in' => 'query',
                'description' => 'Filter by status',
                'required' => false,
                'schema' => ['type' => 'string', 'enum' => ['draft', 'published'], 'example' => 'published']
            ],
            'category' => [
                'name' => 'category',
                'in' => 'query',
                'description' => 'Filter by category ID',
                'required' => false,
                'schema' => ['type' => 'integer', 'example' => 1]
            ],
            'author' => [
                'name' => 'author',
                'in' => 'query',
                'description' => 'Filter by author ID',
                'required' => false,
                'schema' => ['type' => 'integer', 'example' => 1]
            ],
            'include_drafts' => [
                'name' => 'include_drafts',
                'in' => 'query',
                'description' => 'Include draft posts',
                'required' => false,
                'schema' => ['type' => 'boolean', 'example' => false]
            ],
            'sort_by' => [
                'name' => 'sort_by',
                'in' => 'query',
                'description' => 'Field to sort by',
                'required' => false,
                'schema' => ['type' => 'string', 'enum' => ['title', 'created_at', 'updated_at', 'published_at'], 'example' => 'created_at']
            ],
            'sort_order' => [
                'name' => 'sort_order',
                'in' => 'query',
                'description' => 'Sort order',
                'required' => false,
                'schema' => ['type' => 'string', 'enum' => ['asc', 'desc'], 'example' => 'desc']
            ],
            'per_page' => [
                'name' => 'per_page',
                'in' => 'query',
                'description' => 'Number of items per page (max 50)',
                'required' => false,
                'schema' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'example' => 15]
            ],
            'page' => [
                'name' => 'page',
                'in' => 'query',
                'description' => 'Page number',
                'required' => false,
                'schema' => ['type' => 'integer', 'minimum' => 1, 'example' => 1]
            ]
        ];
    }

    /**
     * Generate response definitions
     */
    private function generateResponses(): array
    {
        return [
            'SuccessResponse' => [
                'description' => 'Success response',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean', 'example' => true],
                                'message' => ['type' => 'string', 'example' => 'Operation completed successfully'],
                                'data' => ['type' => 'object']
                            ]
                        ]
                    ]
                ]
            ],
            'ErrorResponse' => [
                'description' => 'Error response',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean', 'example' => false],
                                'message' => ['type' => 'string', 'example' => 'An error occurred'],
                                'errors' => ['type' => 'object']
                            ]
                        ]
                    ]
                ]
            ],
            'ValidationErrorResponse' => [
                'description' => 'Validation error response',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean', 'example' => false],
                                'message' => ['type' => 'string', 'example' => 'Validation failed'],
                                'errors' => [
                                    'type' => 'object',
                                    'additionalProperties' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'string']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'NotFoundResponse' => [
                'description' => 'Resource not found',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean', 'example' => false],
                                'message' => ['type' => 'string', 'example' => 'Resource not found']
                            ]
                        ]
                    ]
                ]
            ],
            'PostsListResponse' => [
                'description' => 'Posts list response with pagination',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean', 'example' => true],
                                'data' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/Post']
                                ],
                                'meta' => ['$ref' => '#/components/schemas/PaginationMeta'],
                                'links' => ['$ref' => '#/components/schemas/PaginationLinks']
                            ]
                        ]
                    ]
                ]
            ],
            'PostDetailResponse' => [
                'description' => 'Single post response',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean', 'example' => true],
                                'data' => ['$ref' => '#/components/schemas/Post']
                            ]
                        ]
                    ]
                ]
            ],
            'PostCreatedResponse' => [
                'description' => 'Post created response',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean', 'example' => true],
                                'message' => ['type' => 'string', 'example' => 'Post created successfully'],
                                'data' => ['$ref' => '#/components/schemas/Post']
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Generate HTML documentation
     */
    private function generateHtmlDocs(): string
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Content Management System</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui.css" />
    <style>
        .swagger-ui .topbar { background-color: #ff5722; }
        .swagger-ui .info .title { color: #ff5722; }
        .swagger-ui .btn.authorize { background-color: #ff5722; border-color: #ff5722; }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-standalone-preset.js"></script>
    <script>
        SwaggerUIBundle({
            url: "./swagger.json",
            dom_id: "#swagger-ui",
            presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
            layout: "StandaloneLayout",
            tryItOutEnabled: true,
            filter: true
        });
    </script>
</body>
</html>';
    }

    /**
     * Generate README
     */
    private function generateReadme(): string
    {
        return '# API Documentation

This directory contains the generated API documentation for the Content Management System.

## Files

- `index.html` - Interactive Swagger UI documentation
- `swagger.json` - OpenAPI 3.0 specification in JSON format
- `README.md` - This file

## Usage

1. Open `index.html` in a web browser to view the interactive documentation
2. Use `swagger.json` to import the API specification into tools like Postman or Insomnia
3. The API base URL is: ' . url('/api/v1') . '

## Regenerating Documentation

To regenerate this documentation, run:

```bash
php artisan api:generate-docs
```

Generated on: ' . now()->format('Y-m-d H:i:s') . '
';
    }
} 