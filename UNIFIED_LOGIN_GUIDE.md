# 🔐 Unified Login System Guide

## Overview

AgriConnect uses a **unified login system** where Admin, Farmer, and Buyer can all log in through the **same login page** (`/login`). The system automatically detects the user's role and redirects them to the appropriate dashboard.

---

## 🎯 How It Works

### **Single Login Page for All Roles**

```
URL: http://localhost:8000/login

┌─────────────────────────────────────┐
│        Argi-Connect Login           │
├─────────────────────────────────────┤
│  [Admin] [Farmer] [Buyer]           │
│                                     │
│  Email:    [________________]       │
│  Password: [________________]       │
│                                     │
│  [ ] Remember me   Forgot password? │
│                                     │
│  [        Sign in        ]          │
│                                     │
│  Don't have an account? Register    │
└─────────────────────────────────────┘
```

### **Automatic Role Detection**

When a user logs in, the system:

1. ✅ **Validates credentials** (email + password)
2. ✅ **Checks user's role** in the database
3. ✅ **Redirects to appropriate dashboard**

```php
// AuthController.php - Line 45-50
return match ($user->role) {
    'farmer' => redirect()->route('farmer.dashboard'),
    'buyer' => redirect()->route('buyer.dashboard'),
    'admin' => redirect()->route('admin.dashboard'),
    default => redirect()->route('home'),
};
```

---

## 👥 User Roles & Redirects

| Role | Email Example | Redirects To | Dashboard URL |
|------|---------------|--------------|---------------|
| **Admin** | `admin@agriconnect.com` | Admin Dashboard | `/admin/dashboard` |
| **Farmer** | `jstincid01@gmail.com` | Farmer Dashboard | `/farmer/dashboard` |
| **Buyer** | `buyer@example.com` | Buyer Dashboard | `/buyer/dashboard` |

---

## 🔑 Test Accounts

### **Admin Account**
```
Email: admin@agriconnect.com
Password: [your_admin_password]
Access: Full system control
```

### **Farmer Account**
```
Email: jstincid01@gmail.com
Password: [your_farmer_password]
Access: Product management, orders, earnings, reviews
```

### **Buyer Account**
```
Email: buyer@example.com
Password: [your_buyer_password]
Access: Marketplace, orders, messages, reviews
```

---

## 📊 Database Structure

### **users Table**

```sql
users
├── id
├── email           ← Used for login
├── password        ← Hashed password
├── role            ← 'admin', 'farmer', or 'buyer'
├── first_name
├── last_name
└── timestamps
```

### **Role Assignment**

```sql
-- Check user's role
SELECT id, email, role FROM users WHERE email = 'user@example.com';

-- Example results:
| id   | email                    | role   |
|------|--------------------------|--------|
| 1    | admin@agriconnect.com    | admin  |
| 1023 | jstincid01@gmail.com     | farmer |
| 2047 | buyer@example.com        | buyer  |
```

---

## 🔄 Login Flow Diagram

```
┌─────────────┐
│   USER      │
│ Visits      │
│ /login      │
└──────┬──────┘
       │
       ↓
┌─────────────────────────┐
│   LOGIN PAGE            │
│ • Email input           │
│ • Password input        │
│ • Shows all role badges │
└──────┬──────────────────┘
       │
       │ Submits credentials
       ↓
┌─────────────────────────┐
│   AuthController        │
│ • Validates email/pass  │
│ • Checks user->role     │
└──────┬──────────────────┘
       │
       ├─── role = 'admin' ───→ /admin/dashboard
       │
       ├─── role = 'farmer' ──→ /farmer/dashboard
       │
       └─── role = 'buyer' ───→ /buyer/dashboard
```

---

## 🛠️ Implementation Details

### **1. Routes** (`routes/web.php`)

```php
// Single login route for all roles
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

### **2. Controller** (`app/Http/Controllers/AuthController.php`)

```php
public function login(Request $request)
{
    // Validate credentials
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Attempt login
    if (!Auth::attempt($credentials)) {
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    // Regenerate session
    $request->session()->regenerate();

    // Get authenticated user
    $user = Auth::user();

    // Redirect based on role
    return match ($user->role) {
        'farmer' => redirect()->route('farmer.dashboard'),
        'buyer' => redirect()->route('buyer.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => redirect()->route('home'),
    };
}
```

### **3. View** (`resources/views/auth/login.blade.php`)

```blade
<!-- Role indicators showing all supported roles -->
<div class="flex items-center justify-center gap-2 mb-4">
    <span class="badge bg-blue-100 text-blue-800">
        <i class="fas fa-user-shield"></i> Admin
    </span>
    <span class="badge bg-green-100 text-green-800">
        <i class="fas fa-tractor"></i> Farmer
    </span>
    <span class="badge bg-purple-100 text-purple-800">
        <i class="fas fa-shopping-cart"></i> Buyer
    </span>
</div>

<!-- Single login form for all roles -->
<form action="{{ route('login.perform') }}" method="POST">
    @csrf
    <input type="email" name="email" required />
    <input type="password" name="password" required />
    <button type="submit">Sign in</button>
</form>
```

---

## 🔒 Security Features

### **1. Password Hashing**
```php
// Passwords are hashed using bcrypt
$user->password = Hash::make('plain_password');
```

### **2. Session Management**
```php
// Session regeneration on login
$request->session()->regenerate();

// Session invalidation on logout
$request->session()->invalidate();
$request->session()->regenerateToken();
```

### **3. CSRF Protection**
```blade
<!-- All forms include CSRF token -->
@csrf
```

### **4. Authentication Middleware**
```php
// Protect routes by role
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer/dashboard', ...);
});
```

---

## 🎨 UI Features

### **Role Badges**

The login page displays three colored badges:

```
[🛡️ Admin]  [🚜 Farmer]  [🛒 Buyer]
  Blue        Green       Purple
```

This visually indicates that all three roles can use this login page.

### **Responsive Design**

- ✅ Mobile-friendly
- ✅ Tablet-optimized
- ✅ Desktop-ready

---

## 📝 Creating New Users

### **Method 1: Database Seeder**

```php
// database/seeders/UserSeeder.php
User::create([
    'email' => 'newfarmer@example.com',
    'password' => Hash::make('password123'),
    'role' => 'farmer',
    'first_name' => 'John',
    'last_name' => 'Doe',
]);
```

### **Method 2: Registration Page**

```
URL: http://localhost:8000/register

Users can register and select their role:
- Farmer (sell products)
- Buyer (purchase products)
```

### **Method 3: Admin Panel**

Admin can create users through the admin dashboard.

---

## 🧪 Testing the Unified Login

### **Test Case 1: Admin Login**

1. Go to `/login`
2. Enter admin credentials
3. Click "Sign in"
4. ✅ Should redirect to `/admin/dashboard`

### **Test Case 2: Farmer Login**

1. Go to `/login`
2. Enter farmer credentials
3. Click "Sign in"
4. ✅ Should redirect to `/farmer/dashboard`

### **Test Case 3: Buyer Login**

1. Go to `/login`
2. Enter buyer credentials
3. Click "Sign in"
4. ✅ Should redirect to `/buyer/dashboard`

### **Test Case 4: Invalid Credentials**

1. Go to `/login`
2. Enter wrong email/password
3. Click "Sign in"
4. ✅ Should show error message
5. ✅ Should stay on login page

---

## 🔧 Troubleshooting

### **Problem: User redirected to wrong dashboard**

**Solution:** Check the user's role in the database:

```sql
SELECT id, email, role FROM users WHERE email = 'user@example.com';
```

Update if incorrect:

```sql
UPDATE users SET role = 'farmer' WHERE email = 'user@example.com';
```

### **Problem: "These credentials do not match our records"**

**Causes:**
1. Wrong email or password
2. User doesn't exist in database
3. Password not hashed correctly

**Solution:**
```sql
-- Check if user exists
SELECT * FROM users WHERE email = 'user@example.com';

-- Reset password if needed
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'user@example.com';
-- This sets password to 'password'
```

### **Problem: Session expires too quickly**

**Solution:** Update `config/session.php`:

```php
'lifetime' => 120, // Session lifetime in minutes
'expire_on_close' => false, // Keep session after browser close
```

---

## 🎯 Benefits of Unified Login

### **1. User-Friendly**
- ✅ Single URL to remember (`/login`)
- ✅ No confusion about which login page to use
- ✅ Clear role indicators

### **2. Maintainable**
- ✅ One login controller to maintain
- ✅ One login view to update
- ✅ Centralized authentication logic

### **3. Secure**
- ✅ Consistent security measures
- ✅ Single point of authentication
- ✅ Easier to audit and monitor

### **4. Scalable**
- ✅ Easy to add new roles
- ✅ Simple to modify redirect logic
- ✅ Flexible for future enhancements

---

## 📚 Related Files

| File | Purpose |
|------|---------|
| `app/Http/Controllers/AuthController.php` | Handles login logic |
| `resources/views/auth/login.blade.php` | Login page UI |
| `routes/web.php` | Login routes |
| `app/Models/User.php` | User model with role |
| `database/migrations/*_create_users_table.php` | Users table schema |

---

## 🚀 Quick Start

### **For Developers:**

1. **Clone the repository**
2. **Run migrations:**
   ```bash
   php artisan migrate
   ```
3. **Seed test users:**
   ```bash
   php artisan db:seed --class=UserSeeder
   ```
4. **Start server:**
   ```bash
   php artisan serve
   ```
5. **Visit:** `http://localhost:8000/login`

### **For Users:**

1. **Go to:** `http://localhost:8000/login`
2. **Enter your email and password**
3. **Click "Sign in"**
4. **You'll be automatically redirected to your dashboard based on your role**

---

## 📞 Support

If you have questions about the unified login system:

1. Check this guide first
2. Review the code in `AuthController.php`
3. Check the database for user roles
4. Test with the provided test accounts

---

## ✅ Summary

**AgriConnect's unified login system:**

- ✅ **One login page** for all roles (`/login`)
- ✅ **Automatic role detection** from database
- ✅ **Smart redirects** to appropriate dashboards
- ✅ **Secure authentication** with Laravel's built-in features
- ✅ **User-friendly UI** with role indicators
- ✅ **Easy to maintain** and extend

**All users (Admin, Farmer, Buyer) use the same login page!** 🎉
