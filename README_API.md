# 📚 Content Management System API Documentation

> Comprehensive REST API built with Laravel, featuring Swagger/OpenAPI documentation for managing posts, categories, and pages.

## 🚀 Quick Start

### View API Documentation
- **Interactive Swagger UI:** `http://localhost:8000/api/documentation`
- **OpenAPI JSON Spec:** `http://localhost:8000/api/swagger.json`
- **Base API URL:** `http://localhost:8000/api/v1`

### Authentication
Include CSRF token for write operations:
```http
X-CSRF-TOKEN: your-csrf-token
Content-Type: application/json
Accept: application/json
```

## 📋 Available Endpoints

### 📝 Posts API
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/posts` | Get all posts with filtering |
| `POST` | `/api/v1/posts` | Create new post |
| `GET` | `/api/v1/posts/{slug}` | Get single post |
| `PUT` | `/api/v1/posts/{id}` | Update post |
| `DELETE` | `/api/v1/posts/{id}` | Delete post |
| `GET` | `/api/v1/categories/{slug}/posts` | Get posts by category |

### 🏷️ Categories API
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/categories` | Get all categories |
| `POST` | `/api/v1/categories` | Create new category |
| `GET` | `/api/v1/categories/{slug}` | Get single category |
| `PUT` | `/api/v1/categories/{id}` | Update category |
| `DELETE` | `/api/v1/categories/{id}` | Delete category |

### 📄 Pages API
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/pages` | Get all pages |
| `POST` | `/api/v1/pages` | Create new page |
| `GET` | `/api/v1/pages/{slug}` | Get single page |
| `PUT` | `/api/v1/pages/{id}` | Update page |
| `DELETE` | `/api/v1/pages/{id}` | Delete page |

## 🎯 Key Features

### ✅ **Complete CRUD Operations**
- Full Create, Read, Update, Delete functionality
- Proper HTTP status codes and error handling
- Consistent JSON response format

### 🔍 **Advanced Filtering & Search**
```bash
# Search posts
GET /api/v1/posts?search=laravel&status=published&category=1

# Pagination
GET /api/v1/posts?per_page=10&page=2

# Sorting
GET /api/v1/posts?sort_by=published_at&sort_order=desc
```

### 📁 **File Upload Support**
```bash
# Upload post image
curl -X POST "/api/v1/posts" \
  -F "title=My Post" \
  -F "content=<p>Content</p>" \
  -F "image=@image.jpg"
```

### 🔗 **Relationship Management**
```bash
# Attach categories to posts
POST /api/v1/posts
{
  "title": "My Post",
  "category_ids": [1, 2, 3]
}
```

## 📊 Response Examples

### Success Response
```json
{
  "success": true,
  "message": "Post created successfully",
  "data": {
    "id": 1,
    "title": "My Post",
    "slug": "my-post",
    "status": "published",
    "author": {
      "id": 1,
      "name": "John Doe"
    },
    "categories": [
      {"id": 1, "name": "Technology"}
    ]
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  },
  "links": {
    "first": "http://domain.com/api/v1/posts?page=1",
    "next": "http://domain.com/api/v1/posts?page=2"
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

## 🛡️ Security Features

### Rate Limiting
- **60 requests per minute** per IP
- Rate limit headers in responses
- JSON error responses for exceeded limits

### CORS Support
- Configurable origin whitelist
- Proper preflight handling
- Security headers included

### Input Validation
- Comprehensive validation rules
- HTML sanitization
- File upload security (type, size limits)

## 📖 Swagger/OpenAPI Documentation

### Full OpenAPI 3.0 Specification
Our API includes comprehensive Swagger documentation with:

- **Complete schema definitions** for all models
- **Detailed parameter descriptions** with examples
- **Request/response examples** for all endpoints
- **Interactive testing interface** via Swagger UI
- **Authentication documentation**
- **Error code explanations**

### Access Documentation
1. **Web Interface:** Visit `http://localhost:8000/api/documentation`
2. **JSON Spec:** Download from `http://localhost:8000/api/swagger.json`
3. **Import to Postman:** Use the OpenAPI spec URL

### Documentation Features
- 🎨 **Beautiful UI** with orange theme matching your CMS
- 🧪 **Interactive testing** - try endpoints directly in browser
- 📱 **Mobile responsive** design
- 🔍 **Search and filter** endpoints
- 📋 **Copy code examples** for multiple languages
- 🚀 **Quick start guide** integrated in docs

## 🧪 Testing Examples

### Test with cURL
```bash
# Get all posts
curl -X GET "http://localhost:8000/api/v1/posts"

# Create a post
curl -X POST "http://localhost:8000/api/v1/posts" \
  -H "X-CSRF-TOKEN: token" \
  -H "Content-Type: application/json" \
  -d '{"title":"Test Post","content":"Content","status":"published"}'

# Search posts
curl -X GET "http://localhost:8000/api/v1/posts?search=laravel&status=published"
```

### Test with JavaScript
```javascript
// Fetch posts
fetch('/api/v1/posts')
  .then(response => response.json())
  .then(data => console.log(data));

// Create post
fetch('/api/v1/posts', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    title: 'New Post',
    content: 'Post content',
    status: 'published'
  })
});
```

## 🏆 API Best Practices Implemented

### ✅ RESTful Design
- Proper HTTP methods and status codes
- Resource-based URLs
- Consistent naming conventions

### ✅ Error Handling
- Meaningful error messages
- Proper HTTP status codes
- Detailed validation errors

### ✅ Performance
- Efficient database queries with eager loading
- Pagination for large datasets
- Optimized response formats

### ✅ Security
- Input validation and sanitization
- Rate limiting protection
- CORS configuration
- File upload security

### ✅ Developer Experience
- Comprehensive documentation
- Interactive API explorer
- Clear examples and guides
- Consistent response format

## 🚀 Next.js Integration Ready

This API is designed specifically for Next.js frontends:

```javascript
// Example Next.js API integration
export async function getPosts(params = {}) {
  const url = new URL('/api/v1/posts', process.env.NEXT_PUBLIC_API_URL);
  Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
  
  const response = await fetch(url);
  return response.json();
}

// Usage in component
const { data: posts, meta } = await getPosts({
  search: 'laravel',
  status: 'published',
  per_page: 10
});
```

## 📞 Support

For API support or questions:
- 📧 **Email:** api@example.com
- 📚 **Documentation:** [Interactive Swagger UI](http://localhost:8000/api/documentation)
- 🐛 **Issues:** Report via GitHub Issues

---

**🎉 Ready to build amazing frontends with this powerful API!** 