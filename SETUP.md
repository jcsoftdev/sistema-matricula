# 🎓 Sistema de Matrículas - Setup Guide

A comprehensive Laravel-based student enrollment management system with real-time WebSocket notifications.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [WebSocket Configuration](#websocket-configuration)
- [Asset Compilation](#asset-compilation)
- [Running the Application](#running-the-application)
- [Default Users](#default-users)
- [Testing Features](#testing-features)
- [Production Deployment](#production-deployment)
- [Troubleshooting](#troubleshooting)
- [API Endpoints](#api-endpoints)

## 🛠 Prerequisites

Before installing, ensure you have the following installed on your system:

- **PHP 8.1+** with extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - PostgreSQL/MySQL extension
- **Composer** (PHP dependency manager)
- **Node.js 16+** and **npm**
- **PostgreSQL 13+** or **MySQL 8.0+**
- **Git**

### Verify Prerequisites

```bash
# Check PHP version
php --version

# Check Composer
composer --version

# Check Node.js and npm
node --version
npm --version

# Check database (PostgreSQL example)
psql --version
```

## 🚀 Installation

### Step 1: Clone the Repository

```bash
# Clone the project
git clone <your-repository-url>
cd sismatricula

# Or if you already have the code
cd /path/to/sismatricula
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

## ⚙️ Configuration

### Step 3: Environment Setup

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables

Edit the `.env` file with your specific settings:

```env
# Application
APP_NAME="Sistema de Matrículas"
APP_ENV=local
APP_KEY=base64:your-generated-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (PostgreSQL example)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sismatricula
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# OR for MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sismatricula
# DB_USERNAME=your_db_username
# DB_PASSWORD=your_db_password

# Broadcasting & WebSocket
BROADCAST_DRIVER=reverb

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🗄️ Database Setup

### Step 5: Create Database

**For PostgreSQL:**
```sql
-- Connect to PostgreSQL as superuser
psql -U postgres

-- Create database
CREATE DATABASE sismatricula;

-- Create user (optional)
CREATE USER sismatricula_user WITH PASSWORD 'your_password';
GRANT ALL PRIVILEGES ON DATABASE sismatricula TO sismatricula_user;

-- Exit
\q
```

**For MySQL:**
```sql
-- Connect to MySQL as root
mysql -u root -p

-- Create database
CREATE DATABASE sismatricula CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (optional)
CREATE USER 'sismatricula_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON sismatricula.* TO 'sismatricula_user'@'localhost';
FLUSH PRIVILEGES;

-- Exit
EXIT;
```

### Step 6: Run Migrations

```bash
# Run database migrations
php artisan migrate

# Seed the database with sample data (optional)
php artisan db:seed
```

## 📡 WebSocket Configuration

### Step 7: Configure Laravel Reverb

Add the following to your `.env` file:

```env
# Broadcasting
BROADCAST_DRIVER=reverb

# Reverb Configuration
REVERB_APP_ID=416106
REVERB_APP_KEY=tfpylhqswaob1atg660b
REVERB_APP_SECRET=kvo34kswb5kzm8ptssov
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

# Vite Variables (if using Vite)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Mix Variables (for Laravel Mix)
MIX_REVERB_APP_KEY="${REVERB_APP_KEY}"
MIX_REVERB_HOST="${REVERB_HOST}"
MIX_REVERB_PORT="${REVERB_PORT}"
MIX_REVERB_SCHEME="${REVERB_SCHEME}"
```

## 🎨 Asset Compilation

### Step 8: Compile Frontend Assets

```bash
# For development
npm run dev

# For production
npm run production

# Using Laravel Mix directly
npx mix

# Watch for changes during development
npm run watch
# OR
npx mix watch
```

## 🏃‍♂️ Running the Application

### Step 9: Start Required Services

You need **3 terminal windows** running simultaneously:

#### Terminal 1: Laravel Development Server
```bash
php artisan serve
```
✅ **Application URL:** http://localhost:8000

#### Terminal 2: WebSocket Server
```bash
php artisan reverb:start
```
✅ **WebSocket Server:** localhost:8080

#### Terminal 3: Asset Watcher (Development Only)
```bash
npm run watch
```
✅ **Asset compilation:** Automatic on file changes

### Step 10: Verify Installation

Visit http://localhost:8000 - you should see the login page.

## 👥 Default Users

### Step 11: Create Initial Users

If you ran `php artisan db:seed`, check the DatabaseSeeder for default users. Otherwise, create users manually:

```bash
php artisan tinker
```

```php
// Create admin user
$admin = new App\Models\User();
$admin->name = 'Administrator';
$admin->email = 'admin@sismatricula.com';
$admin->password = bcrypt('password');
$admin->rol = 'admin';
$admin->save();

// Create secretario user
$secretario = new App\Models\User();
$secretario->name = 'Secretario';
$secretario->email = 'secretario@sismatricula.com';
$secretario->password = bcrypt('password');
$secretario->rol = 'secretario';
$secretario->save();

// Create padre user
$padre = new App\Models\User();
$padre->name = 'Padre de Familia';
$padre->email = 'padre@sismatricula.com';
$padre->password = bcrypt('password');
$padre->rol = 'padre';
$padre->save();

exit
```

## 🧪 Testing Features

### Step 12: Test Core Functionality

1. **Login Testing:**
   - Admin: `admin@sismatricula.com` / `password`
   - Secretario: `secretario@sismatricula.com` / `password`
   - Padre: `padre@sismatricula.com` / `password`

2. **Admin/Secretario Features:**
   - Navigate to `/matriculas` - View enrollments
   - Navigate to `/estudiantes` - Manage students
   - Navigate to `/apoderados` - Manage guardians
   - Navigate to `/pagos` - Payment management

3. **Parent Features:**
   - Navigate to `/prematricula` - Submit pre-enrollment

4. **Real-time Notifications:**
   - Login as admin/secretario in one browser
   - Login as padre in another browser/incognito
   - Submit a prematricula as padre
   - Check admin window for real-time notification

5. **Payment Integration:**
   - From matriculas list, click 💰 payment icon
   - Verify matricula code auto-fills in payment form

## 🚀 Production Deployment

### Step 13: Production Configuration

```bash
# Set production environment
cp .env .env.backup
```

Update `.env` for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use production database credentials
DB_HOST=your_production_host
DB_DATABASE=your_production_db
DB_USERNAME=your_production_user
DB_PASSWORD=your_production_password

# Configure mail for production
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
```

### Step 14: Optimize for Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Compile assets for production
npm run production

# Set proper permissions
chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

## 🐛 Troubleshooting

### Common Issues

#### WebSocket Connection Issues
```bash
# Check if port 8080 is in use
lsof -i :8080

# Kill process using port 8080
kill -9 $(lsof -ti:8080)

# Restart Reverb server
php artisan reverb:start
```

#### Asset Compilation Issues
```bash
# Clear compiled assets
rm -rf public/js public/css public/mix-manifest.json

# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install

# Rebuild assets
npx mix
```

#### Database Connection Issues
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
exit
```

#### Permission Issues
```bash
# Fix Laravel permissions
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### Clear All Caches
```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Clear compiled services
php artisan clear-compiled
```

## 📚 API Endpoints

### Main Routes

| Method | URL | Description | Auth Required |
|--------|-----|-------------|---------------|
| GET | `/` | Dashboard/Home | Yes |
| GET | `/login` | Login page | No |
| POST | `/login` | Login process | No |
| POST | `/logout` | Logout | Yes |
| GET | `/matriculas` | Enrollment list | Admin/Secretario |
| GET | `/estudiantes` | Students list | Admin/Secretario |
| GET | `/apoderados` | Guardians list | Admin/Secretario |
| GET | `/pagos` | Payments list | Admin/Secretario |
| GET | `/prematricula` | Pre-enrollment | Padre |
| POST | `/prematricula` | Submit pre-enrollment | Padre |

### Real-time Features

- **WebSocket Channel:** `admin-notifications`
- **Event:** `prematricula.submitted`
- **Port:** 8080 (configurable)

## 🔧 Development Commands

```bash
# Start development environment
php artisan serve & php artisan reverb:start & npm run watch

# Reset database
php artisan migrate:fresh --seed

# Generate IDE helper files (if using barryvdh/laravel-ide-helper)
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta

# Run tests (if configured)
php artisan test

# Check code style (if configured)
./vendor/bin/phpcs
./vendor/bin/phpcbf
```

## 📞 Support

If you encounter any issues:

1. Check the Laravel logs: `tail -f storage/logs/laravel.log`
2. Check browser console for JavaScript errors
3. Verify all services are running (Laravel, Reverb, asset watcher)
4. Ensure database connection is working
5. Check file permissions

---

## 🎉 Success!

Your Sistema de Matrículas application should now be fully functional with:

✅ Student and guardian management  
✅ Enrollment processing  
✅ Payment management  
✅ Real-time WebSocket notifications  
✅ Role-based access control  
✅ Responsive web interface  

**Happy coding!** 🚀
