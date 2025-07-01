# Laravel Content Management System - Deployment Guide

## 🚀 Deployment Options

### 1. Shared Hosting (cPanel/DirectAdmin)

#### Requirements:
- PHP 8.2+
- MySQL 5.7+ or SQLite support
- Composer access (or ability to upload vendor folder)
- Node.js for building assets (can be done locally)

#### Steps:
1. **Prepare files locally:**
   ```bash
   # Install dependencies
   composer install --optimize-autoloader --no-dev
   npm install
   npm run build
   
   # Create .env file
   cp .env.example .env
   php artisan key:generate
   ```

2. **Configure .env for production:**
   ```env
   APP_NAME="Content Management System"
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:your-generated-key-here
   
   # Database (MySQL for shared hosting)
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   
   # Or use SQLite (simpler)
   # DB_CONNECTION=sqlite
   # DB_DATABASE=/home/yourusername/database.sqlite
   
   SESSION_DRIVER=file
   CACHE_DRIVER=file
   QUEUE_CONNECTION=sync
   ```

3. **Upload files:**
   - Upload all files to your domain's root directory
   - Move contents of `public` folder to `public_html` or `www`
   - Update `public/index.php` to point to correct Laravel paths

4. **Set up database:**
   ```bash
   php artisan migrate
   php artisan storage:link
   php artisan db:seed (optional)
   ```

---

### 2. VPS/Cloud Server (Ubuntu/CentOS)

#### Requirements:
- Ubuntu 20.04+ or CentOS 8+
- Root or sudo access

#### Automated Setup Script:
```bash
#!/bin/bash
# Laravel deployment script for Ubuntu

# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 and extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath

# Install Nginx
sudo apt install -y nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL (optional)
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

#### Manual VPS Deployment:
1. **Clone and setup:**
   ```bash
   cd /var/www
   git clone your-repository html
   cd html
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

2. **Configure Nginx:**
   ```nginx
   server {
       listen 80;
       server_name your-domain.com;
       root /var/www/html/public;
       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```

3. **Set permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/html
   sudo chmod -R 755 /var/www/html
   sudo chmod -R 775 /var/www/html/storage
   sudo chmod -R 775 /var/www/html/bootstrap/cache
   ```

---

### 3. Docker Deployment

#### Create Dockerfile:
```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www && \
    chmod -R 775 /var/www/storage && \
    chmod -R 775 /var/www/bootstrap/cache

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

#### Docker Compose:
```yaml
version: '3.8'
services:
  app:
    build: .
    ports:
      - "80:80"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    volumes:
      - ./storage:/var/www/storage
      - ./public/images:/var/www/public/images
    depends_on:
      - mysql

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: laravel
      MYSQL_USER: laravel
      MYSQL_PASSWORD: password
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"

volumes:
  mysql_data:
```

---

### 4. Cloud Platform Deployment

#### 4A. Laravel Forge (Recommended)
1. Connect your server (DigitalOcean, AWS, etc.)
2. Create new site pointing to your repository
3. Configure environment variables
4. Deploy automatically

#### 4B. Heroku
1. **Create Procfile:**
   ```
   web: vendor/bin/heroku-php-apache2 public/
   ```

2. **Deploy:**
   ```bash
   heroku create your-app-name
   heroku config:set APP_KEY=$(php artisan --no-ansi key:generate --show)
   heroku addons:create heroku-postgresql:hobby-dev
   git push heroku main
   heroku run php artisan migrate
   ```

#### 4C. DigitalOcean App Platform
1. Connect your GitHub repository
2. Configure build and run commands:
   ```yaml
   # .do/app.yaml
   name: content-management
   services:
   - name: web
     source_dir: /
     github:
       repo: your-username/your-repo
       branch: main
     run_command: |
       php artisan config:cache
       php artisan route:cache
       php artisan view:cache
       vendor/bin/heroku-php-apache2 public/
     build_command: |
       composer install --optimize-autoloader --no-dev
       npm install
       npm run build
     environment_slug: php
   ```

---

## 🔧 Environment Configuration

### Production .env Template:
```env
APP_NAME="Content Management System"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-app-key-here
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cache & Session (for production)
BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls

# L5 Swagger Configuration
L5_SWAGGER_GENERATE_ALWAYS=false
L5_SWAGGER_CONST_HOST=https://yourdomain.com
```

---

## 📋 Post-Deployment Checklist

### Essential Commands:
```bash
# Run these after deployment
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link
php artisan l5-swagger:generate

# Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Security Checklist:
- [ ] Set `APP_DEBUG=false`
- [ ] Use strong `APP_KEY`
- [ ] Configure HTTPS/SSL certificate
- [ ] Set up firewall rules
- [ ] Configure backup strategy
- [ ] Set up monitoring (optional)
- [ ] Hide `.env` file from web access

### Performance Optimization:
```bash
# Enable OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=512
opcache.max_accelerated_files=65536

# Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

---

## 🔍 Troubleshooting

### Common Issues:

1. **Permission Errors:**
   ```bash
   sudo chown -R www-data:www-data /path/to/laravel
   sudo chmod -R 775 storage bootstrap/cache
   ```

2. **500 Error:**
   - Check Laravel logs: `storage/logs/laravel.log`
   - Verify `.env` configuration
   - Ensure `APP_KEY` is set

3. **Asset Loading Issues:**
   ```bash
   npm run build
   php artisan storage:link
   ```

4. **Database Connection:**
   - Verify database credentials
   - Check database server status
   - Test connection: `php artisan tinker` then `DB::connection()->getPdo()`

---

Choose the deployment method that best fits your needs and technical expertise. For beginners, I recommend starting with shared hosting or Laravel Forge for simplicity. 