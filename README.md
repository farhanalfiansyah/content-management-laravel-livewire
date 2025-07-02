# Laravel Content Management System

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

A modern content management system built with Laravel 12, featuring a clean admin dashboard and REST API.

## Features

- 📝 **Posts Management** - Create, edit, and manage blog posts
- 📄 **Pages Management** - Static pages management  
- 🏷️ **Categories** - Organize content with categories
- 🖼️ **Image Upload** - Upload and manage images
- 🌍 **Multi-language** - Support for multiple languages
- 🚀 **REST API** - Complete REST API endpoints
- 📱 **Responsive** - Mobile-friendly admin interface

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL or SQLite

## Quick Start

### 1. Clone and Install
```bash
git clone <your-repository-url>
cd content-management
composer install
npm install
```

### 2. Environment Setup
```bash
# Copy environment file (use either method)
cp .env.example .env
# OR if .env.example doesn't exist:
cp env.example.txt .env

# Generate application key
php artisan key:generate
```

### 3. Configure Database
Edit `.env` file:
```env
# For SQLite (easier for development)
DB_CONNECTION=sqlite

# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Setup Database
```bash
# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link
```

### 5. Build Assets and Run
```bash
# Build frontend assets
npm run build

# Start the application
php artisan serve
```

Visit `http://localhost:8000` and register a new account to get started!

## Development

For development with hot reload:
```bash
# Terminal 1
php artisan serve

# Terminal 2  
npm run dev
```

## API Documentation

The REST API is available at `/api/v1/`:
- **API Endpoints:** `http://localhost:8000/api/v1/posts`, `/api/v1/categories`, `/api/v1/pages`

## Basic Usage

1. **Register/Login** at `http://localhost:8000/register`
2. **Create Posts** - Go to Posts → New Post
3. **Manage Categories** - Organize your content
4. **Upload Images** - Add images to your posts
5. **API Access** - Use the REST API for frontend integration

## Production Deployment

For production deployment:
1. Run `npm run build` to build assets
2. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
3. Configure your web server to point to the `public` directory
4. Set proper file permissions for `storage` and `bootstrap/cache`

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
