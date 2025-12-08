#!/bin/bash

echo "===================================="
echo "AgriConnect Setup Script"
echo "===================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running in the correct directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: This script must be run from the AgriConnect_Project directory${NC}"
    echo "Please navigate to the AgriConnect_Project directory and run again."
    exit 1
fi

echo -e "${GREEN}[1/10]${NC} Installing PHP dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Composer install failed${NC}"
    exit 1
fi
echo -e "${GREEN}PHP dependencies installed successfully!${NC}"
echo ""

echo -e "${GREEN}[2/10]${NC} Installing Node dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo -e "${RED}Error: NPM install failed${NC}"
    exit 1
fi
echo -e "${GREEN}Node dependencies installed successfully!${NC}"
echo ""

echo -e "${GREEN}[3/10]${NC} Creating .env file..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo ".env file created!"
else
    echo ".env file already exists, skipping..."
fi
echo ""

echo -e "${GREEN}[4/10]${NC} Generating application key..."
php artisan key:generate
echo "Application key generated!"
echo ""

echo -e "${GREEN}[5/10]${NC} Creating storage link..."
php artisan storage:link
echo "Storage link created!"
echo ""

echo -e "${GREEN}[6/10]${NC} Setting directory permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "Directory permissions set!"
echo ""

echo -e "${GREEN}[7/10]${NC} Running database migrations..."
echo -e "${YELLOW}Please make sure MySQL is running and database 'agriconnect_db' exists${NC}"
echo "Press Enter to continue or Ctrl+C to cancel..."
read
php artisan migrate
if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Database migration failed${NC}"
    echo "Please check your database connection in .env file"
    exit 1
fi
echo -e "${GREEN}Database migrations completed!${NC}"
echo ""

echo -e "${GREEN}[8/10]${NC} Seeding database with sample data..."
php artisan db:seed
echo -e "${GREEN}Database seeded successfully!${NC}"
echo ""

echo -e "${GREEN}[9/10]${NC} Building frontend assets..."
npm run build
echo -e "${GREEN}Frontend assets built successfully!${NC}"
echo ""

echo -e "${GREEN}[10/10]${NC} Optimizing application..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
echo -e "${GREEN}Application optimized!${NC}"
echo ""

echo "===================================="
echo -e "${GREEN}Setup Complete!${NC}"
echo "===================================="
echo ""
echo "Next steps:"
echo "1. Configure your database settings in .env file"
echo "2. Run 'php artisan serve' to start the development server"
echo "3. In another terminal, run 'npm run dev' for hot-reloading"
echo "4. Access the application at http://localhost:8000"
echo ""
echo "Default Admin Credentials:"
echo "Email: admin@agriconnect.com"
echo "Password: password123"
echo ""
