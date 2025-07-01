#!/bin/bash

# Laravel Content Management System - Deployment Script
# This script helps automate the deployment process

echo "🚀 Laravel Content Management System - Deployment Script"
echo "========================================================"

# Function to display colored output
print_status() {
    echo -e "\033[1;32m✓ $1\033[0m"
}

print_warning() {
    echo -e "\033[1;33m⚠ $1\033[0m"
}

print_error() {
    echo -e "\033[1;31m✗ $1\033[0m"
}

# Check if .env file exists
if [ ! -f .env ]; then
    print_warning ".env file not found. Please create one from the template below:"
    echo "----------------------------------------"
    cat << 'EOF'
APP_NAME="Content Management System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

L5_SWAGGER_GENERATE_ALWAYS=false
L5_SWAGGER_CONST_HOST=https://yourdomain.com
EOF
    echo "----------------------------------------"
    echo "Please create .env file and run this script again."
    exit 1
fi

print_status ".env file found"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer first."
    echo "Visit: https://getcomposer.org/download/"
    exit 1
fi

print_status "Composer is installed"

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed. Please install Node.js first."
    echo "Visit: https://nodejs.org/"
    exit 1
fi

print_status "Node.js is installed"

# Install PHP dependencies
echo ""
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction
if [ $? -eq 0 ]; then
    print_status "PHP dependencies installed successfully"
else
    print_error "Failed to install PHP dependencies"
    exit 1
fi

# Install Node.js dependencies
echo ""
echo "📦 Installing Node.js dependencies..."
npm install
if [ $? -eq 0 ]; then
    print_status "Node.js dependencies installed successfully"
else
    print_error "Failed to install Node.js dependencies"
    exit 1
fi

# Build assets
echo ""
echo "🔨 Building frontend assets..."
npm run build
if [ $? -eq 0 ]; then
    print_status "Frontend assets built successfully"
else
    print_error "Failed to build frontend assets"
    exit 1
fi

# Generate application key if not set
echo ""
echo "🔑 Checking application key..."
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=\"\"" .env; then
    print_warning "APP_KEY is empty. Generating new key..."
    php artisan key:generate --force
    print_status "Application key generated"
else
    print_status "Application key is already set"
fi

# Cache configuration
echo ""
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_status "Application optimized"

# Run database migrations
echo ""
echo "💾 Running database migrations..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    print_status "Database migrations completed"
else
    print_warning "Database migrations failed. Please check your database connection."
fi

# Create storage link
echo ""
echo "🔗 Creating storage link..."
php artisan storage:link
if [ $? -eq 0 ]; then
    print_status "Storage link created"
else
    print_warning "Storage link creation failed (may already exist)"
fi

# Generate API documentation
echo ""
echo "📚 Generating API documentation..."
php artisan l5-swagger:generate
if [ $? -eq 0 ]; then
    print_status "API documentation generated"
else
    print_warning "API documentation generation failed"
fi

# Set proper permissions (for Linux/macOS)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo ""
    echo "🔒 Setting file permissions..."
    chmod -R 775 storage bootstrap/cache
    print_status "File permissions set"
fi

echo ""
echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Ensure your web server points to the 'public' directory"
echo "2. Configure your database connection in .env"
echo "3. Set up SSL certificate for HTTPS"
echo "4. Configure your domain DNS"
echo "5. Test your application"
echo ""
echo "📖 For detailed deployment instructions, see DEPLOYMENT_GUIDE.md"
echo ""
print_status "Your Laravel Content Management System is ready to go!" 