# Posts CRUD Setup Instructions

## Required Setup Steps

### 1. Run Database Migration
```bash
php artisan migrate
```

### 2. Create Storage Link (Required for Image Upload)
```bash
php artisan storage:link
```

### 3. Optional: Seed Sample Data
```bash
php artisan db:seed --class=PostSeeder
```

## Features

### ✅ Complete CRUD Operations
- **Create**: Add new posts with title, slug, content, description, and image
- **Read**: List all posts with search, filter, and pagination
- **Update**: Edit existing posts
- **Delete**: Remove posts (with image cleanup)

### ✅ Advanced Features
- **Auto-slug generation** from title
- **Image upload** with preview and removal
- **Status management** (Draft/Published) with one-click toggle
- **Real-time validation** with custom error messages
- **Bulk operations** (select all, bulk delete)
- **User permissions** (users can only edit/delete their own posts)
- **Post statistics** dashboard
- **Enhanced search** (title, content, description)

### ✅ UI/UX Features
- **Responsive design** for all screen sizes
- **Loading states** and smooth animations
- **Error handling** with user-friendly messages
- **Orange theme** consistent with dashboard
- **Clean, modern interface**

## File Structure

```
app/
├── Http/Controllers/PostController.php
├── Livewire/Posts/
│   ├── Index.php
│   └── CreateEdit.php
├── Models/Post.php
database/
├── migrations/2024_01_01_000000_create_posts_table.php
├── factories/PostFactory.php
└── seeders/PostSeeder.php
resources/views/
├── posts/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── livewire/posts/
    ├── index.blade.php
    └── create-edit.blade.php
routes/web.php
storage/app/public/posts/ (for uploaded images)
```

## Usage

1. Navigate to `/posts` to view all posts
2. Click "New Post" to create a new post
3. Use search and filters to find specific posts
4. Click status badges to toggle between Draft/Published
5. Use bulk selection for mass operations
6. Images are automatically resized and optimized

## Security Features

- User authentication required
- Users can only modify their own posts
- File upload validation (type, size)
- SQL injection protection
- XSS protection with proper escaping 