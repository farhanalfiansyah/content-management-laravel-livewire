# 🚀 REST API Best Practices Implementation

## Overview
This document outlines the REST API best practices implemented in the Content Management System API.

## 📋 **API Structure & Versioning**

### Base URL Structure
```
http://domain.com/api/v1/{resource}
```

### API Versioning
- **Current Version**: v1
- **Future-proof**: New versions (v2, v3) can be added without breaking existing clients
- **Backward Compatibility**: Old versions remain supported during transition periods

## 🔒 **Security Best Practices**

### 1. Rate Limiting
- **Default**: 60 requests per minute per IP
- **Headers Included**: 
  - `X-RateLimit-Limit`
  - `X-RateLimit-Remaining`
  - `Retry-After` (when limit exceeded)

### 2. CORS Configuration
- **Headers**: Proper CORS headers for cross-origin requests
- **Methods**: GET, POST, PUT, PATCH, DELETE, OPTIONS
- **Preflight**: OPTIONS requests handled correctly

### 3. Input Validation
- **Sanitization**: All inputs sanitized and validated
- **Type Checking**: Strict type validation
- **Length Limits**: Maximum field lengths enforced

## 📊 **Response Standards**

### Consistent Response Format
```json
{
  "success": true|false,
  "message": "Human readable message",
  "data": {...},
  "meta": {...},
  "links": {...}
}
```

### HTTP Status Codes
- `200 OK`: Successful GET, PUT, PATCH
- `201 Created`: Successful POST
- `204 No Content`: Successful DELETE
- `400 Bad Request`: Invalid request
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Access denied
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation errors
- `429 Too Many Requests`: Rate limit exceeded
- `500 Internal Server Error`: Server error

### Error Response Format
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

## 🔍 **Query Parameters & Filtering**

### Pagination
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 50)

### Sorting
- `sort_by`: Field to sort by
- `sort_order`: `asc` or `desc` (default: `desc`)

### Filtering
- `search`: Search across multiple fields
- `status`: Filter by status
- `category`: Filter by category ID
- `author`: Filter by author ID

### Example Request
```
GET /api/v1/posts?search=laravel&status=published&sort_by=created_at&sort_order=desc&per_page=20&page=1
```

## 📚 **API Endpoints**

### Posts API
```
GET    /api/v1/posts                   # List posts
POST   /api/v1/posts                   # Create post
GET    /api/v1/posts/{slug}            # Get post by slug
PUT    /api/v1/posts/{id}              # Update post
DELETE /api/v1/posts/{id}              # Delete post
GET    /api/v1/categories/{slug}/posts # Get posts by category
```

### Categories API
```
GET    /api/v1/categories              # List categories
POST   /api/v1/categories              # Create category
GET    /api/v1/categories/{slug}       # Get category by slug
PUT    /api/v1/categories/{id}         # Update category
DELETE /api/v1/categories/{id}         # Delete category
```

### Pages API
```
GET    /api/v1/pages                   # List pages
POST   /api/v1/pages                   # Create page
GET    /api/v1/pages/{slug}            # Get page by slug
PUT    /api/v1/pages/{id}              # Update page
DELETE /api/v1/pages/{id}              # Delete page
```

### Utility Endpoints
```
GET    /api/test                       # API test endpoint
GET    /api/health                     # Health check
```

## 🛠️ **Implementation Details**

### Response Trait
All controllers use `ApiResponseTrait` for consistent responses:
- `successResponse()`
- `errorResponse()`
- `validationErrorResponse()`
- `paginatedResponse()`
- `createdResponse()`
- `updatedResponse()`
- `deletedResponse()`

### Base Controller
`ApiController` provides common functionality:
- Pagination parameters
- Search parameters
- Sort validation
- Common utilities

### Middleware Stack
1. **CORS**: Cross-origin resource sharing
2. **Rate Limiting**: 60 requests per minute
3. **Throttling**: API-specific rate limits
4. **Validation**: Input validation and sanitization

## 🔄 **Request/Response Examples**

### Create Post Request
```javascript
const formData = new FormData();
formData.append('title', 'My New Post');
formData.append('content', '<p>Rich content here</p>');
formData.append('status', 'published');
formData.append('category_ids[]', '1');
formData.append('category_ids[]', '2');
formData.append('image', imageFile);

const response = await fetch('/api/v1/posts', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
  },
  body: formData
});

const result = await response.json();
```

### Success Response
```json
{
  "success": true,
  "message": "Post created successfully",
  "data": {
    "id": 123,
    "title": "My New Post",
    "slug": "my-new-post",
    "content": "<p>Rich content here</p>",
    "status": "published",
    "image_url": "http://domain.com/images/posts/image.jpg",
    "categories": [
      {"id": 1, "name": "Technology", "slug": "technology"},
      {"id": 2, "name": "Laravel", "slug": "laravel"}
    ],
    "author": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### Validation Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "content": ["The content field is required."],
    "status": ["The selected status is invalid."]
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "message": "",
  "data": [...],
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

## 🎯 **Frontend Integration**

### Next.js Examples

#### Custom Hook for API Calls
```javascript
// hooks/useApi.js
import { useState, useEffect } from 'react';

export function useApi(endpoint, options = {}) {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const response = await fetch(`/api/v1${endpoint}`, {
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...options.headers
          },
          ...options
        });
        
        const result = await response.json();
        
        if (result.success) {
          setData(result.data);
        } else {
          setError(result.message);
        }
      } catch (err) {
        setError('Network error');
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [endpoint]);

  return { data, loading, error };
}
```

#### API Service Class
```javascript
// services/api.js
class ApiService {
  constructor(baseURL = '/api/v1') {
    this.baseURL = baseURL;
  }

  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    
    const response = await fetch(url, {
      headers: {
        'Accept': 'application/json',
        ...options.headers
      },
      ...options
    });

    const result = await response.json();
    
    if (!result.success) {
      throw new Error(result.message);
    }
    
    return result;
  }

  // Posts
  async getPosts(params = {}) {
    const query = new URLSearchParams(params);
    return this.request(`/posts?${query}`);
  }

  async getPost(slug) {
    return this.request(`/posts/${slug}`);
  }

  async createPost(data) {
    return this.request('/posts', {
      method: 'POST',
      body: data
    });
  }

  // Categories
  async getCategories(params = {}) {
    const query = new URLSearchParams(params);
    return this.request(`/categories?${query}`);
  }

  // Pages
  async getPages(params = {}) {
    const query = new URLSearchParams(params);
    return this.request(`/pages?${query}`);
  }
}

export default new ApiService();
```

## 🚀 **Performance Optimizations**

### Caching Strategy
- **Response Caching**: Cache frequently accessed data
- **ETag Headers**: Conditional requests for unchanged resources
- **CDN Integration**: Static assets served via CDN

### Database Optimizations
- **Eager Loading**: Relationships loaded efficiently
- **Select Optimization**: Only required fields selected
- **Indexing**: Proper database indexes on searchable fields

### Response Optimization
- **Data Transformation**: Consistent data formatting
- **Relationship Loading**: Minimal N+1 queries
- **Pagination**: Efficient large dataset handling

## 📈 **Monitoring & Analytics**

### API Metrics
- Request count per endpoint
- Response times
- Error rates
- Rate limit violations

### Logging
- Request/response logging
- Error tracking
- Performance monitoring
- Security events

## 🔧 **Development Tools**

### Testing
- Unit tests for all endpoints
- Integration tests for workflows
- Performance testing
- Security testing

### Documentation
- Auto-generated API documentation
- Interactive API explorer
- Code examples for popular frameworks

This API implementation follows industry best practices and is production-ready! 🎉 