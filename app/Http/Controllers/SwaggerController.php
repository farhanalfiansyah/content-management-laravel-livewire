<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SwaggerController extends Controller
{
    public function json(): JsonResponse
    {
        $spec = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Content Management System API',
                'version' => '1.0.0',
                'description' => 'REST API for managing posts, categories, and pages'
            ],
            'servers' => [
                [
                    'url' => url('/api/v1'),
                    'description' => 'API Server'
                ]
            ],
            'tags' => [
                ['name' => 'Posts', 'description' => 'Blog posts management'],
                ['name' => 'Categories', 'description' => 'Categories management'],
                ['name' => 'Pages', 'description' => 'Static pages management']
            ],
            'paths' => [
                '/posts' => [
                    'get' => [
                        'tags' => ['Posts'],
                        'summary' => 'Get all posts',
                        'description' => 'Retrieve paginated list of posts',
                        'parameters' => [
                            [
                                'name' => 'search',
                                'in' => 'query',
                                'required' => false,
                                'description' => 'Search term',
                                'schema' => ['type' => 'string']
                            ],
                            [
                                'name' => 'status',
                                'in' => 'query',
                                'required' => false,
                                'description' => 'Filter by status',
                                'schema' => [
                                    'type' => 'string',
                                    'enum' => ['draft', 'published']
                                ]
                            ],
                            [
                                'name' => 'per_page',
                                'in' => 'query',
                                'required' => false,
                                'description' => 'Items per page',
                                'schema' => [
                                    'type' => 'integer',
                                    'minimum' => 1,
                                    'maximum' => 50
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Success',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean'],
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Post']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'post' => [
                        'tags' => ['Posts'],
                        'summary' => 'Create post',
                        'description' => 'Create a new blog post',
                        'responses' => [
                            '201' => ['description' => 'Post created successfully']
                        ]
                    ]
                ],
                '/posts/{slug}' => [
                    'get' => [
                        'tags' => ['Posts'],
                        'summary' => 'Get single post',
                        'parameters' => [
                            [
                                'name' => 'slug',
                                'in' => 'path',
                                'required' => true,
                                'schema' => ['type' => 'string']
                            ]
                        ],
                        'responses' => [
                            '200' => ['description' => 'Post found'],
                            '404' => ['description' => 'Post not found']
                        ]
                    ]
                ],
                '/categories' => [
                    'get' => [
                        'tags' => ['Categories'],
                        'summary' => 'Get all categories',
                        'responses' => [
                            '200' => ['description' => 'Categories retrieved']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Categories'],
                        'summary' => 'Create category',
                        'responses' => [
                            '201' => ['description' => 'Category created']
                        ]
                    ]
                ],
                '/pages' => [
                    'get' => [
                        'tags' => ['Pages'],
                        'summary' => 'Get all pages',
                        'responses' => [
                            '200' => ['description' => 'Pages retrieved']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Pages'],
                        'summary' => 'Create page',
                        'responses' => [
                            '201' => ['description' => 'Page created']
                        ]
                    ]
                ]
            ],
            'components' => [
                'schemas' => [
                    'Post' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'title' => ['type' => 'string', 'example' => 'My Post'],
                            'slug' => ['type' => 'string', 'example' => 'my-post'],
                            'content' => ['type' => 'string', 'example' => '<p>Content</p>'],
                            'status' => ['type' => 'string', 'enum' => ['draft', 'published']],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'Category' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'name' => ['type' => 'string', 'example' => 'Technology'],
                            'slug' => ['type' => 'string', 'example' => 'technology'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'Page' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'title' => ['type' => 'string', 'example' => 'About Us'],
                            'slug' => ['type' => 'string', 'example' => 'about-us'],
                            'body' => ['type' => 'string', 'example' => '<p>Page content</p>'],
                            'status' => ['type' => 'string', 'enum' => ['draft', 'published']],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($spec, 200, [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
} 