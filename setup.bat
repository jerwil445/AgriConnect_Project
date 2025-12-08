@echo off
echo ====================================
echo AgriConnect Setup Script
echo ====================================
echo.

REM Check if running in the correct directory
if not exist "artisan" (
    echo Error: This script must be run from the AgriConnect_Project directory
    echo Please navigate to c:\xampp\htdocs\AgriConnect_Project and run again.
    pause
    exit /b 1
)

echo [1/10] Installing PHP dependencies...
call composer install
if %errorlevel% neq 0 (
    echo Error: Composer install failed
    pause
    exit /b 1
)
echo PHP dependencies installed successfully!
echo.

echo [2/10] Installing Node dependencies...
call npm install
if %errorlevel% neq 0 (
    echo Error: NPM install failed
    pause
    exit /b 1
)
echo Node dependencies installed successfully!
echo.

echo [3/10] Creating .env file...
if not exist ".env" (
    copy .env.example .env
    echo .env file created!
) else (
    echo .env file already exists, skipping...
)
echo.

echo [4/10] Generating application key...
php artisan key:generate
echo Application key generated!
echo.

echo [5/10] Creating storage link...
php artisan storage:link
echo Storage link created!
echo.

echo [6/10] Running database migrations...
echo Please make sure MySQL is running and database 'agriconnect_db' exists
echo Press any key to continue or Ctrl+C to cancel...
pause > nul
php artisan migrate
if %errorlevel% neq 0 (
    echo Error: Database migration failed
    echo Please check your database connection in .env file
    pause
    exit /b 1
)
echo Database migrations completed!
echo.

echo [7/10] Seeding database with sample data...
php artisan db:seed
echo Database seeded successfully!
echo.

echo [8/10] Building frontend assets...
call npm run build
echo Frontend assets built successfully!
echo.

echo [9/10] Clearing application cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo Cache cleared!
echo.

echo [10/10] Optimizing application...
php artisan optimize
echo Application optimized!
echo.

echo ====================================
echo Setup Complete!
echo ====================================
echo.
echo Next steps:
echo 1. Configure your database settings in .env file
echo 2. Run 'php artisan serve' to start the development server
echo 3. In another terminal, run 'npm run dev' for hot-reloading
echo 4. Access the application at http://localhost:8000
echo.
echo Default Admin Credentials:
echo Email: admin@agriconnect.com
echo Password: password123
echo.
pause
