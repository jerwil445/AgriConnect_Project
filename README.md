# 🌾 AgriConnect - Agricultural Marketplace Platform

## 📋 Table of Contents
- [System Overview](#system-overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation Guide](#installation-guide)
- [Database Structure](#database-structure)
- [User Roles & Functionality](#user-roles--functionality)
- [Implementation Guide](#implementation-guide)
- [API Documentation](#api-documentation)
- [Testing & Validation](#testing--validation)
- [Troubleshooting](#troubleshooting)
- [Best Practices](#best-practices)
- [License](#license)

## 🌟 System Overview

AgriConnect is a comprehensive agricultural marketplace platform that connects farmers with buyers, facilitating the trade of agricultural products with a focus on eggs and poultry products. The system provides a multi-role platform with distinct interfaces for farmers, buyers, and administrators.

### Key Objectives
- **Direct Farm-to-Consumer Connection**: Eliminate middlemen and connect farmers directly with buyers
- **Transparent Pricing**: Real-time market prices and transparent transaction history
- **Quality Assurance**: Certification tracking and product verification
- **Efficient Inventory Management**: Real-time stock tracking and automated alerts
- **Smart Matching System**: AI-powered buyer-seller matching based on preferences

## ✨ Features

### Core Features
- 🔐 **Multi-role Authentication System** (Farmer, Buyer, Admin)
- 📦 **Product Management** with image uploads
- 🛒 **Advanced Marketplace** with filtering and search
- 💬 **Real-time Messaging System**
- 📊 **Analytics Dashboard** for all roles
- 🔔 **Smart Notification System**
- 🤝 **Automated Matching Algorithm**
- 📱 **Responsive Design** for all devices
- 🔍 **Advanced Search & Filtering**
- 📈 **Price Tracking & History**

## 💻 Technology Stack

### Backend
- **Framework**: Laravel 10.x
- **Language**: PHP 8.1+
- **Database**: MySQL 8.0
- **Cache**: Redis (optional)
- **Queue**: Database Queue Driver

### Frontend
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Vanilla JS, Alpine.js
- **Build Tool**: Vite
- **Icons**: Font Awesome 6.x
- **Charts**: Chart.js

### Development Tools
- **Package Manager**: Composer, NPM
- **Version Control**: Git
- **Testing**: PHPUnit
- **Code Quality**: PHP CS Fixer

## 📋 System Requirements

### Minimum Requirements
- PHP >= 8.1
- MySQL >= 5.7 or MariaDB >= 10.3
- Node.js >= 16.x
- Composer >= 2.0
- 2GB RAM
- 1GB free disk space

### Recommended Requirements
- PHP 8.2+
- MySQL 8.0+
- Node.js 18.x
- 4GB RAM
- SSD storage
- Redis for caching

## 🚀 Installation Guide

### Step 1: Environment Setup

1. **Install XAMPP/WAMP/MAMP**
   ```bash
   # Download from official website
   # Start Apache and MySQL services
   ```

2. **Install Composer**
   ```bash
   # Download from https://getcomposer.org
   # Verify installation
   composer --version
   ```

3. **Install Node.js**
   ```bash
   # Download from https://nodejs.org
   # Verify installation
   node --version
   npm --version
   ```

### Step 2: Project Setup

1. **Clone/Extract Project**
   ```bash
   cd c:\xampp\htdocs
   # Place AgriConnect_Project folder here
   ```

2. **Install PHP Dependencies**
   ```bash
   cd AgriConnect_Project
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Generate application key
   php artisan key:generate
   ```

5. **Configure Database**
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=agriconnect_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Step 3: Database Setup

1. **Create Database**
   ```sql
   CREATE DATABASE agriconnect_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Run Migrations**
   ```bash
   php artisan migrate
   ```

3. **Seed Database**
   ```bash
   php artisan db:seed
   ```

4. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

### Step 4: Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 5: Start Application

```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite dev server
npm run dev
```

Access the application at: `http://localhost:8000`

## 🗄️ Database Structure

### Core Tables

#### 1. **users**
```sql
- id (PK)
- first_name
- last_name
- email (unique)
- password
- phone_number
- address
- state
- role (farmer/buyer/admin)
- email_verified_at
- profile_picture
- created_at
- updated_at
```

#### 2. **farmers**
```sql
- id (PK)
- user_id (FK -> users)
- farm_name
- farm_size
- product_type
- experience_years
- certification
- farm_address
- created_at
- updated_at
```

#### 3. **buyers**
```sql
- id (PK)
- user_id (FK -> users)
- company_name
- business_type
- preferred_products
- buyer_address
- created_at
- updated_at
```

#### 4. **products**
```sql
- id (PK)
- farmer_id (FK -> farmers)
- product_name
- egg_type
- description
- quantity
- unit
- price
- harvest_date
- address
- status (Available/Sold Out/Pending)
- image
- created_at
- updated_at
```

#### 5. **sizes**
```sql
- id (PK)
- farmer_id (FK -> farmers)
- product_id (FK -> products)
- size_name (small/medium/large/extra_large/jumbo)
- tray_count
- created_at
- updated_at
```

#### 6. **demands**
```sql
- id (PK)
- buyer_id (FK -> buyers)
- egg_type
- quantity_needed
- preferred_size
- preferred_location
- max_price
- status (Open/Matched/Closed)
- created_at
- updated_at
```

#### 7. **demand_matches**
```sql
- id (PK)
- demand_id (FK -> demands)
- product_id (FK -> products)
- match_score
- status (Pending/Accepted/Rejected)
- created_at
- updated_at
```

#### 8. **conversations**
```sql
- id (PK)
- sender_id (FK -> users)
- receiver_id (FK -> users)
- last_message_at
- created_at
- updated_at
```

#### 9. **messages**
```sql
- id (PK)
- conversation_id (FK -> conversations)
- sender_id (FK -> users)
- message
- is_read
- created_at
- updated_at
```

#### 10. **notifications**
```sql
- id (PK)
- notifiable_type
- notifiable_id
- type
- data (JSON)
- read_at
- created_at
- updated_at
```

### Database Relationships
```
users (1) ─── (1) farmers
users (1) ─── (1) buyers
farmers (1) ─── (n) products
products (1) ─── (n) sizes
buyers (1) ─── (n) demands
demands (1) ─── (n) demand_matches
products (1) ─── (n) demand_matches
users (1) ─── (n) conversations (as sender/receiver)
conversations (1) ─── (n) messages
```

## 👥 User Roles & Functionality

### 🌾 Farmer Role

#### Features
1. **Product Management**
   - Add new products with images
   - Edit product details
   - Manage inventory levels
   - Set product availability
   - Track egg sizes and quantities

2. **Dashboard Analytics**
   - Total products overview
   - Revenue tracking
   - Pending orders
   - Product performance metrics

3. **Messaging System**
   - Communicate with buyers
   - Respond to inquiries
   - Negotiate prices

4. **Profile Management**
   - Update farm information
   - Add certifications
   - Manage contact details

#### Implementation Flow
```php
// FarmerController.php
public function addProduct(Request $request) {
    $validated = $request->validate([
        'product_name' => 'required|string|max:255',
        'egg_type' => 'required|string',
        'quantity' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048'
    ]);
    
    $product = Product::create([
        'farmer_id' => auth()->user()->farmer->id,
        ...$validated
    ]);
    
    // Handle sizes
    foreach ($request->sizes as $size) {
        Size::create([
            'product_id' => $product->id,
            'size_name' => $size['name'],
            'tray_count' => $size['count']
        ]);
    }
    
    return redirect()->route('farmer.products');
}
```

### 🛒 Buyer Role

#### Features
1. **Marketplace Browsing**
   - Advanced search and filtering
   - Product comparison
   - Price tracking
   - Seller ratings

2. **Demand Management**
   - Post buying requirements
   - Receive matched products
   - Track demand status

3. **Communication**
   - Contact farmers directly
   - Negotiate bulk orders
   - Request custom quotes

4. **Order Management**
   - Track order history
   - Save favorite products
   - Manage shipping addresses

#### Implementation Flow
```php
// BuyerController.php
public function marketplace(Request $request) {
    $query = Product::with(['farmer.user', 'sizes'])
        ->whereIn('status', ['Available', 'Sold Out']);
    
    // Apply filters
    if ($request->search) {
        $query->where('product_name', 'like', "%{$request->search}%");
    }
    
    if ($request->category && $request->category != 'all') {
        $query->where('egg_type', $request->category);
    }
    
    if ($request->min_price) {
        $query->where('price', '>=', $request->min_price);
    }
    
    if ($request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }
    
    $products = $query->paginate(12);
    
    return view('buyers.dashboard', compact('products'));
}
```

### 👨‍💼 Admin Role

#### Features
1. **User Management**
   - Approve/reject registrations
   - Manage user roles
   - View user activities
   - Handle reports

2. **Product Moderation**
   - Review product listings
   - Remove inappropriate content
   - Verify certifications

3. **System Analytics**
   - Platform statistics
   - Transaction reports
   - User growth metrics
   - Revenue analytics

4. **System Configuration**
   - Platform settings
   - Commission rates
   - Notification templates
   - Category management

#### Implementation Flow
```php
// AdminController.php
public function dashboard() {
    $stats = [
        'total_users' => User::count(),
        'total_farmers' => Farmer::count(),
        'total_buyers' => Buyer::count(),
        'total_products' => Product::count(),
        'pending_approvals' => User::whereNull('email_verified_at')->count(),
        'revenue' => DB::table('transactions')->sum('amount')
    ];
    
    $recentActivities = Activity::latest()->take(10)->get();
    
    return view('admin.dashboard', compact('stats', 'recentActivities'));
}
```

## 📝 Implementation Guide

### Phase 1: Database Setup (Day 1-2)

1. **Create all migrations**
   ```bash
   php artisan make:migration create_farmers_table
   php artisan make:migration create_buyers_table
   php artisan make:migration create_products_table
   php artisan make:migration create_sizes_table
   php artisan make:migration create_demands_table
   php artisan make:migration create_demand_matches_table
   php artisan make:migration create_conversations_table
   php artisan make:migration create_messages_table
   ```

2. **Define relationships in models**
   ```php
   // User.php
   public function farmer() {
       return $this->hasOne(Farmer::class);
   }
   
   public function buyer() {
       return $this->hasOne(Buyer::class);
   }
   ```

3. **Create seeders for test data**
   ```bash
   php artisan make:seeder UserSeeder
   php artisan make:seeder ProductSeeder
   ```

### Phase 2: Authentication System (Day 3-4)

1. **Multi-role authentication setup**
   ```php
   // LoginController.php
   protected function authenticated(Request $request, $user) {
       if ($user->role == 'admin') {
           return redirect()->route('admin.dashboard');
       } elseif ($user->role == 'farmer') {
           return redirect()->route('farmer.dashboard');
       } elseif ($user->role == 'buyer') {
           return redirect()->route('buyer.dashboard');
       }
   }
   ```

2. **Role-based middleware**
   ```php
   // RoleMiddleware.php
   public function handle($request, Closure $next, ...$roles) {
       if (!in_array($request->user()->role, $roles)) {
           abort(403, 'Unauthorized access');
       }
       return $next($request);
   }
   ```

### Phase 3: Core Features (Day 5-10)

1. **Product Management System**
   - CRUD operations
   - Image upload handling
   - Inventory tracking
   - Price management

2. **Search & Filter System**
   - Elasticsearch integration (optional)
   - Advanced filtering
   - Sorting algorithms
   - Pagination

3. **Matching Algorithm**
   ```php
   // MatchingService.php
   public function matchProductsWithDemands(Product $product) {
       $demands = Demand::where('status', 'Open')
           ->where('egg_type', $product->egg_type)
           ->where('max_price', '>=', $product->price)
           ->get();
       
       foreach ($demands as $demand) {
           $score = $this->calculateMatchScore($product, $demand);
           
           DemandMatch::create([
               'demand_id' => $demand->id,
               'product_id' => $product->id,
               'match_score' => $score,
               'status' => 'Pending'
           ]);
       }
   }
   
   private function calculateMatchScore($product, $demand) {
       $score = 0;
       
       // Price match (40% weight)
       $priceRatio = $product->price / $demand->max_price;
       $score += (1 - $priceRatio) * 40;
       
       // Quantity match (30% weight)
       if ($product->quantity >= $demand->quantity_needed) {
           $score += 30;
       } else {
           $score += ($product->quantity / $demand->quantity_needed) * 30;
       }
       
       // Location match (30% weight)
       if ($this->calculateDistance($product->address, $demand->preferred_location) < 50) {
           $score += 30;
       }
       
       return $score;
   }
   ```

### Phase 4: Communication System (Day 11-12)

1. **Real-time messaging**
   ```javascript
   // messages.js
   function sendMessage() {
       const message = document.getElementById('messageInput').value;
       
       fetch('/api/messages', {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
           },
           body: JSON.stringify({
               conversation_id: conversationId,
               message: message
           })
       })
       .then(response => response.json())
       .then(data => {
           appendMessage(data.message);
           document.getElementById('messageInput').value = '';
       });
   }
   ```

2. **Notification system**
   ```php
   // NotificationService.php
   public function notifyMatch($buyer, $product) {
       $buyer->notify(new ProductMatchedNotification($product));
       
       // Send email
       Mail::to($buyer->email)->send(new ProductMatchedMail($product));
       
       // Send SMS (optional)
       // SMS::send($buyer->phone, "New product match: {$product->name}");
   }
   ```

### Phase 5: Testing & Optimization (Day 13-14)

1. **Unit Testing**
   ```php
   // ProductTest.php
   public function test_product_creation() {
       $farmer = Farmer::factory()->create();
       
       $response = $this->actingAs($farmer->user)
           ->post('/farmer/products', [
               'product_name' => 'Fresh Eggs',
               'egg_type' => 'chicken',
               'quantity' => 100,
               'price' => 5.99
           ]);
       
       $response->assertRedirect('/farmer/products');
       $this->assertDatabaseHas('products', [
           'product_name' => 'Fresh Eggs',
           'farmer_id' => $farmer->id
       ]);
   }
   ```

2. **Performance Optimization**
   - Query optimization
   - Caching strategies
   - Image optimization
   - Lazy loading

## 🔧 API Documentation

### Authentication Endpoints

```http
POST /api/register
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "buyer"
}
```

```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

### Product Endpoints

```http
GET /api/products
Authorization: Bearer {token}

Query Parameters:
- search: string
- category: string
- min_price: number
- max_price: number
- page: number
```

```http
POST /api/products
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
    "product_name": "Fresh Eggs",
    "egg_type": "chicken",
    "quantity": 100,
    "price": 5.99,
    "image": file
}
```

## 🧪 Testing & Validation

### 1. Database Connectivity Test

```php
// Run in tinker
php artisan tinker

>>> DB::connection()->getPdo();
>>> User::count();
>>> Product::with('farmer')->first();
```

### 2. Authentication Flow Test

1. Register new user
2. Verify email (if enabled)
3. Login with credentials
4. Check role-based redirect
5. Test middleware protection

### 3. Product Management Test

1. **Create Product**
   - Login as farmer
   - Add product with all details
   - Upload image
   - Verify in database

2. **Edit Product**
   - Modify product details
   - Update inventory
   - Change status

3. **Search & Filter**
   - Test all filter combinations
   - Verify pagination
   - Check sorting options

### 4. Messaging System Test

1. Send message between users
2. Verify notification delivery
3. Check read/unread status
4. Test conversation threading

### 5. Matching Algorithm Test

```php
// Test matching accuracy
$testDemand = Demand::create([
    'buyer_id' => 1,
    'egg_type' => 'chicken',
    'quantity_needed' => 50,
    'max_price' => 10.00,
    'preferred_location' => 'Manila'
]);

$testProduct = Product::create([
    'farmer_id' => 1,
    'egg_type' => 'chicken',
    'quantity' => 100,
    'price' => 8.00,
    'address' => 'Quezon City'
]);

// Run matching
app(MatchingService::class)->matchNewProductWithDemands($testProduct);

// Verify match created
$match = DemandMatch::where('demand_id', $testDemand->id)
    ->where('product_id', $testProduct->id)
    ->first();

assert($match !== null);
assert($match->match_score > 70);
```

### 6. Performance Testing

```bash
# Load testing with Apache Bench
ab -n 1000 -c 10 http://localhost:8000/api/products

# Database query monitoring
DB::enableQueryLog();
// Perform operations
dd(DB::getQueryLog());
```

## 🐛 Troubleshooting

### Common Issues & Solutions

#### 1. Database Connection Error
```bash
# Error: SQLSTATE[HY000] [1045] Access denied
Solution:
1. Check .env database credentials
2. Verify MySQL is running
3. Create database if not exists
4. Grant user privileges
```

#### 2. Storage Permission Issues
```bash
# Error: The stream or file could not be opened
Solution:
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan cache:clear
php artisan config:clear
```

#### 3. Image Upload Not Working
```bash
# Images not displaying
Solution:
php artisan storage:link
# Verify storage/app/public is linked to public/storage
```

#### 4. Session/CSRF Token Issues
```bash
Solution:
php artisan session:table
php artisan migrate
php artisan cache:clear
```

#### 5. Vite/NPM Build Errors
```bash
Solution:
rm -rf node_modules package-lock.json
npm install
npm run build
```

## 💡 Best Practices

### 1. Security
- Always validate and sanitize user input
- Use prepared statements for database queries
- Implement rate limiting for API endpoints
- Keep dependencies updated
- Use HTTPS in production
- Implement CSRF protection
- Hash sensitive data

### 2. Performance
- Use eager loading to prevent N+1 queries
- Implement caching for frequently accessed data
- Optimize images before upload
- Use pagination for large datasets
- Implement database indexing
- Use queue jobs for heavy operations

### 3. Code Quality
```php
// Good Practice Example
public function store(ProductRequest $request) {
    DB::beginTransaction();
    try {
        $product = $this->productService->create($request->validated());
        
        if ($request->hasFile('image')) {
            $product->image = $this->imageService->upload($request->file('image'));
            $product->save();
        }
        
        DB::commit();
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Product creation failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to create product');
    }
}
```

### 4. Database Optimization
```sql
-- Add indexes for frequently queried columns
ALTER TABLE products ADD INDEX idx_egg_type (egg_type);
ALTER TABLE products ADD INDEX idx_status (status);
ALTER TABLE products ADD INDEX idx_price (price);
ALTER TABLE products ADD INDEX idx_farmer_id (farmer_id);

-- Composite index for complex queries
ALTER TABLE products ADD INDEX idx_search (egg_type, status, price);
```

### 5. Maintenance
- Regular database backups
- Monitor error logs
- Update dependencies monthly
- Review and optimize queries
- Clean up old data
- Monitor disk usage

## 📊 Monitoring & Analytics

### Key Metrics to Track
1. **User Metrics**
   - Daily active users
   - User retention rate
   - Registration conversion

2. **Business Metrics**
   - Total transactions
   - Average order value
   - Product listing growth

3. **Performance Metrics**
   - Page load time
   - API response time
   - Database query time

### Logging Setup
```php
// config/logging.php
'channels' => [
    'custom' => [
        'driver' => 'daily',
        'path' => storage_path('logs/agriconnect.log'),
        'level' => 'info',
        'days' => 30,
    ],
],
```

## 🚀 Deployment Guide

### Production Deployment Checklist
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Generate new APP_KEY
- [ ] Configure production database
- [ ] Setup SSL certificate
- [ ] Configure mail service
- [ ] Setup queue workers
- [ ] Enable caching
- [ ] Configure backup system
- [ ] Setup monitoring tools

### Server Requirements
- Ubuntu 20.04 LTS or higher
- Nginx or Apache
- PHP-FPM
- MySQL/PostgreSQL
- Redis (optional)
- Supervisor for queues

## 📄 License

This project is proprietary software. All rights reserved.

---

## 📞 Support

For technical support and inquiries:
- Email: support@agriconnect.com
- Documentation: https://docs.agriconnect.com
- Issue Tracker: GitHub Issues

---

**Last Updated**: December 2024
**Version**: 1.0.0
