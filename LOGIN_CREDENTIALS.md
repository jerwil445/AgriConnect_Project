# AgriConnect Login Credentials

## All User Accounts

| Role | Name | Email | Password |
|------|------|-------|----------|
| **Admin** | Admin User | **admin@agriconnect.com** | **admin** |
| Farmer | farmer 1 | farmer1@gmail.com | farmer |
| Farmer | Dodo Acido | jstincid01@gmail.com | farmer |
| Buyer | buyer 1 | buyer1@gmail.com | buyer |
| Buyer | buyer 2 | buyer2@gmail.com | buyer |

## Quick Login Links

- **Admin Panel**: http://localhost/admin
- **Farmer Dashboard**: http://localhost/farmer  
- **Buyer Dashboard**: http://localhost/buyer

## Login Instructions

1. Go to: http://localhost/login
2. Enter email and password from the table above
3. Click "Login"
4. You will be redirected to the appropriate dashboard based on your role

## Password Reset

If you need to reset passwords again, run:
```bash
php artisan users:reset-by-role --create-admin --force
```

This will:
- Create admin account if missing
- Reset all passwords to match their roles (admin/farmer/buyer)

## Troubleshooting

If you can't login:

1. **Check if user exists:**
   ```bash
   php artisan test:admin-login
   ```

2. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   ```

3. **Check database connection:**
   ```bash
   php artisan tinker --execute="echo DB::connection()->getDatabaseName();"
   ```

---
*Last updated: December 10, 2025*
