# Content Management System API Documentation

## 📋 Overview

This is a comprehensive REST API for managing posts, categories, and pages in a content management system. Built with Laravel and following REST best practices.

**Base URL:** `http://localhost:8000/api/v1`  
**Version:** 1.0.0  
**Authentication:** CSRF Token (for write operations)  
**Rate Limiting:** 60 requests per minute  

## 🎯 Quick Links

- [Interactive Documentation (Swagger UI)](/api/documentation)
- [OpenAPI Specification (JSON)](/api/swagger.json)
- [Postman Collection](#postman-collection)

---

## 🔐 Authentication

For write operations (POST, PUT, DELETE), include the CSRF token in headers:

```http
X-CSRF-TOKEN: your-csrf-token
Content-Type: application/json
Accept: application/json
```

---

## 📝 Posts API

### Get All Posts

```http
GET /api/v1/posts
```

**Query Parameters:**
- `search` (string): Search in title, content, description
- `status` (string): Filter by status (`draft`, `published`)
- `category` (integer): Filter by category ID
- `author` (integer): Filter by author ID
- `include_drafts` (boolean): Include draft posts (default: false)
- `sort_by` (string): Sort field (`title`, `created_at`, `updated_at`, `published_at`)
- `sort_order` (string): Sort direction (`asc`, `desc`)
- `per_page` (integer): Items per page (max 50, default 15)
- `page` (integer): Page number

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/posts?search=laravel&status=published&per_page=10"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Getting Started with Laravel",
      "slug": "getting-started-with-laravel",
      "short_description": "Learn the basics of Laravel framework",
      "image_url": "http://localhost:8000/images/posts/image.jpg",
      "status": "published",
      "published_at": "2024-01-15T10:30:00.000000Z",
      "created_at": "2024-01-15T10:25:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z",
      "author": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "categories": [
        {
          "id": 1,
          "name": "Technology",
          "slug": "technology"
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75,
    "from": 1,
    "to": 15
  },
  "links": {
    "first": "http://localhost:8000/api/v1/posts?page=1",
    "last": "http://localhost:8000/api/v1/posts?page=5",
    "prev": null,
    "next": "http://localhost:8000/api/v1/posts?page=2"
  }
}
```

### Create Post

```http
POST /api/v1/posts
```

**Request Body (multipart/form-data):**
```json
{
  "title": "My New Post",
  "slug": "my-new-post",
  "short_description": "Brief description of the post",
  "content": "<p>Full post content with HTML...</p>",
  "status": "published",
  "published_at": "2024-01-15",
  "category_ids": [1, 2, 3],
  "image": "file upload"
}
```

**Example cURL:**
```bash
curl -X POST "http://localhost:8000/api/v1/posts" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -H "Accept: application/json" \
  -F "title=My New Post" \
  -F "content=<p>Post content here</p>" \
  -F "status=published" \
  -F "category_ids[]=1" \
  -F "category_ids[]=2" \
  -F "image=@/path/to/image.jpg"
```

### Get Single Post

```http
GET /api/v1/posts/{slug}
```

**Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/posts/getting-started-with-laravel"
```

### Update Post

```http
PUT /api/v1/posts/{id}
```

**Request Body (multipart/form-data):**
```json
{
  "title": "Updated Post Title",
  "content": "<p>Updated content...</p>",
  "status": "published",
  "category_ids": [1, 2]
}
```

### Delete Post

```http
DELETE /api/v1/posts/{id}
```

**Example:**
```bash
curl -X DELETE "http://localhost:8000/api/v1/posts/1" \
  -H "X-CSRF-TOKEN: your-csrf-token"
```

### Get Posts by Category

```http
GET /api/v1/categories/{slug}/posts
```

**Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/categories/technology/posts"
```

---

## 🏷️ Categories API

### Get All Categories

```http
GET /api/v1/categories
```

**Query Parameters:**
- `search` (string): Search by name
- `with_posts` (boolean): Include posts relationship
- `sort_by` (string): Sort field (`name`, `created_at`, `updated_at`)
- `sort_order` (string): Sort direction (`asc`, `desc`)
- `per_page` (integer): Items per page (max 50, default 15)

**Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/categories?with_posts=true"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Technology",
      "slug": "technology",
      "posts_count": 15,
      "created_at": "2024-01-15T10:25:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z",
      "posts": [
        {
          "id": 1,
          "title": "Getting Started with Laravel",
          "slug": "getting-started-with-laravel",
          "short_description": "Learn the basics of Laravel framework",
          "image_url": "http://localhost:8000/images/posts/image.jpg",
          "status": "published",
          "published_at": "2024-01-15T10:30:00.000000Z",
          "author": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
          }
        }
      ]
    }
  ]
}
```

### Create Category

```http
POST /api/v1/categories
```

**Request Body:**
```json
{
  "name": "Web Development",
  "slug": "web-development"
}
```

**Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/categories" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -H "Content-Type: application/json" \
  -d '{"name": "Web Development"}'
```

### Get Single Category

```http
GET /api/v1/categories/{slug}
```

**Query Parameters:**
- `with_posts` (boolean): Include posts relationship

### Update Category

```http
PUT /api/v1/categories/{id}
```

### Delete Category

```http
DELETE /api/v1/categories/{id}
```

---

## 📄 Pages API

### Get All Pages

```http
GET /api/v1/pages
```

**Query Parameters:**
- `search` (string): Search in title and body
- `status` (string): Filter by status (`draft`, `published`)
- `include_drafts` (boolean): Include draft pages
- `sort_by` (string): Sort field (`title`, `created_at`, `updated_at`)
- `sort_order` (string): Sort direction (`asc`, `desc`)
- `per_page` (integer): Items per page (max 50, default 15)

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "About Us",
      "slug": "about-us",
      "status": "published",
      "created_at": "2024-01-15T10:25:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z",
      "author": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      }
    }
  ]
}
```

### Create Page

```http
POST /api/v1/pages
```

**Request Body:**
```json
{
  "title": "Privacy Policy",
  "slug": "privacy-policy",
  "body": "<p>Full page content with HTML...</p>",
  "status": "published"
}
```

### Get Single Page

```http
GET /api/v1/pages/{slug}
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "About Us",
    "slug": "about-us",
    "body": "<p>Full page content with HTML...</p>",
    "status": "published",
    "created_at": "2024-01-15T10:25:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z",
    "author": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

### Update Page

```http
PUT /api/v1/pages/{id}
```

### Delete Page

```http
DELETE /api/v1/pages/{id}
```

---

## 📊 Response Format

All API responses follow a consistent format:

### Success Response

```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": { ... }
}
```

### Error Response

```json
{
  "success": false,
  "message": "An error occurred",
  "errors": { ... }
}
```

### Validation Error Response

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": [
      "The field is required."
    ]
  }
}
```

### Paginated Response

```json
{
  "success": true,
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75,
    "from": 1,
    "to": 15
  },
  "links": {
    "first": "http://domain.com/api/v1/posts?page=1",
    "last": "http://domain.com/api/v1/posts?page=5",
    "prev": null,
    "next": "http://domain.com/api/v1/posts?page=2"
  }
}
```

---

## 🚨 Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Internal Server Error |

---

## 📦 Postman Collection

You can import our Postman collection for easy testing:

```json
{
  "info": {
    "name": "Content Management System API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000/api/v1"
    }
  ],
  "item": [
    {
      "name": "Posts",
      "item": [
        {
          "name": "Get All Posts",
          "request": {
            "method": "GET",
            "url": "{{base_url}}/posts"
          }
        },
        {
          "name": "Create Post",
          "request": {
            "method": "POST",
            "url": "{{base_url}}/posts",
            "header": [
              {
                "key": "X-CSRF-TOKEN",
                "value": "{{csrf_token}}"
              }
            ]
          }
        }
      ]
    }
  ]
}
```

---

## 🔧 Rate Limiting

The API implements rate limiting to ensure fair usage:

- **60 requests per minute** per IP address
- Rate limit headers are included in responses:
  - `X-RateLimit-Limit`: 60
  - `X-RateLimit-Remaining`: 59
  - `X-RateLimit-Reset`: 1640995200

---

## 📝 Examples

### Search Posts with Multiple Filters

```bash
curl -X GET "http://localhost:8000/api/v1/posts?search=laravel&status=published&category=1&sort_by=published_at&sort_order=desc&per_page=5"
```

### Create Post with Categories and Image

```bash
curl -X POST "http://localhost:8000/api/v1/posts" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -H "Accept: application/json" \
  -F "title=Advanced Laravel Tutorial" \
  -F "short_description=Learn advanced Laravel concepts" \
  -F "content=<h1>Advanced Laravel</h1><p>This tutorial covers...</p>" \
  -F "status=published" \
  -F "category_ids[]=1" \
  -F "category_ids[]=2" \
  -F "image=@/Users/john/image.jpg"
```

### Update Category

```bash
curl -X PUT "http://localhost:8000/api/v1/categories/1" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -H "Content-Type: application/json" \
  -d '{"name": "Advanced Technology", "slug": "advanced-technology"}'
```

---

## 🎉 Conclusion

This API provides a complete solution for content management with:

- ✅ **Full CRUD operations** for Posts, Categories, and Pages
- ✅ **Advanced filtering and search** capabilities
- ✅ **File upload support** for post images
- ✅ **Relationship management** between posts and categories
- ✅ **Consistent response format** with proper error handling
- ✅ **Rate limiting** for API protection
- ✅ **Comprehensive documentation** with examples

For more information or support, please contact: **api@example.com** 