<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Buyer;
use Illuminate\Support\Facades\Hash;

class BulkBuyersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $philippineCities = [
            'Manila', 'Quezon City', 'Caloocan', 'Davao City', 'Cebu City', 'Zamboanga City',
            'Antipolo', 'Pasig', 'Taguig', 'Valenzuela', 'Dasmariñas', 'Calamba', 'Makati',
            'Marikina', 'Muntinlupa', 'Las Piñas', 'Parañaque', 'Bacoor', 'Imus', 'San José del Monte',
            'Bacolod', 'Iloilo City', 'General Santos', 'Baguio', 'Butuan', 'Cagayan de Oro',
            'Iligan', 'Laoag', 'Legazpi', 'Malolos', 'Naga', 'Olongapo', 'San Fernando',
            'Tacloban', 'Tuguegarao', 'Vigan', 'Puerto Princesa', 'Batangas City',
            'Cabanatuan', 'Dagupan', 'Lucena', 'San Carlos', 'Tarlac City', 'Urdaneta'
        ];

        $provinces = [
            'Metro Manila', 'Bataan', 'Bulacan', 'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales',
            'Rizal', 'Cavite', 'Laguna', 'Batangas', 'Quezon', 'Aurora',
            'Albay', 'Camarines Norte', 'Camarines Sur', 'Catanduanes', 'Masbate', 'Sorsogon',
            'Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental',
            'Bohol', 'Cebu', 'Negros Oriental', 'Siquijor', 'Leyte', 'Southern Leyte'
        ];

        $restaurantNames = [
            'Golden Spoon Restaurant', 'Tasty Bites Café', 'Sunrise Diner', 'Ocean View Restaurant',
            'Mountain Breeze Café', 'City Lights Restaurant', 'Garden Fresh Eatery', 'Tropical Paradise Restaurant',
            'Heritage Bistro', 'Modern Kitchen', 'Classic Café', 'Flavor Town Restaurant',
            'Seaside Grill', 'Urban Eats', 'Country Kitchen', 'Metro Bistro',
            'Fresh Garden Restaurant', 'Sunset Café', 'Diamond Restaurant', 'Pearl Dining',
            'Ruby\'s Kitchen', 'Emerald Café', 'Sapphire Restaurant', 'Crystal Palace Dining',
            'Royal Feast Restaurant', 'Emperor\'s Table', 'King\'s Banquet', 'Queen\'s Kitchen',
            'Prince Café', 'Noble Dining', 'Elite Restaurant', 'Premier Bistro',
            'Supreme Kitchen', 'Excellence Café', 'Quality Dining', 'Perfect Taste Restaurant'
        ];

        $retailStoreNames = [
            'Fresh Market Store', 'Golden Grocery', 'Prime Mart', 'City Center Store', 'Metro Supermarket',
            'Happy Shopping Store', 'Convenience Plus', 'Quick Stop Market', 'Daily Needs Store', 'Family Mart',
            'Super Value Store', 'Best Buy Grocery', 'Smart Shopper', 'Easy Shop Market', 'Corner Store Plus',
            'Neighborhood Market', 'Community Store', 'Local Grocery', 'Village Market', 'Town Center Store',
            'Express Mart', 'Rapid Grocery', 'Swift Shop', 'Speed Store', 'Flash Market',
            'Thunder Grocery', 'Lightning Store', 'Storm Market', 'Cyclone Shop', 'Tornado Mart',
            '7-Eleven Philippines', 'Mini Stop', 'Lawson', 'FamilyMart', 'Circle K',
            'AllDay Mart', 'Uncle John\'s', 'Ministop Express', 'Go! Mart', 'Robinsons Easymart'
        ];

        $wholesaleCompanies = [
            'Philippines Wholesale Trading Corp', 'Metro Manila Distribution Inc', 'Cebu Trading Company',
            'Davao Wholesale Center', 'Luzon Distribution Hub', 'Visayas Trading Corp', 'Mindanao Supply Chain',
            'National Food Distributors', 'Regional Supply Network', 'Island Trading Company',
            'Pacific Wholesale Corp', 'Asian Food Distributors', 'Philippine Supply Chain Inc',
            'Metro Distribution Center', 'Central Trading Hub', 'Northern Wholesale Corp',
            'Southern Distribution Inc', 'Eastern Trading Company', 'Western Supply Network',
            'Capital Wholesale Trading', 'Premier Distribution Corp', 'Elite Trading Company',
            'Supreme Wholesale Inc', 'Excellence Distribution', 'Quality Trading Corp',
            'Prime Supply Chain', 'Ultimate Wholesale Hub', 'Perfect Distribution Inc',
            'Golden Trading Company', 'Diamond Wholesale Corp', 'Platinum Distribution Inc'
        ];

        $supermarketChains = [
            'SM Supermarket', 'Robinson\'s Supermarket', 'Puregold', 'Shopwise', 'Hypermart',
            'SaveMore Market', 'Super8 Grocery', 'Gaisano Grand Mall', 'Metro Retail Stores',
            'Walter Mart', 'Landmark Supermarket', 'Rustan\'s Fresh', 'S&R Membership Shopping',
            'Landers Superstore', 'Ayala Malls Market', 'Vista Mall Supermarket', 'Ever Gotesco',
            'Prince Hypermart', 'AllHome', 'Wilcon Depot', 'Cash & Carry', 'Makro',
            'Costco Philippines', 'Duty Free Philippines', 'Rustans Supermarket'
        ];

        $businessTypes = [
            'Restaurant', 'Fast Food Chain', 'Café', 'Bakery', 'Catering Service', 'Food Truck',
            'Retail Store', 'Grocery Store', 'Convenience Store', 'Supermarket', 'Mini Mart',
            'Wholesale Trading', 'Food Distribution', 'Supply Chain', 'Import/Export',
            'Hotel', 'Resort', 'Pension House', 'Boarding House', 'Canteen',
            'School Cafeteria', 'Hospital Kitchen', 'Corporate Cafeteria', 'Food Manufacturer',
            'Food Processing Plant', 'Individual Buyer', 'Family Business', 'Franchise Owner'
        ];

        $preferredProducts = [
            'Fresh Chicken Eggs', 'Organic Eggs', 'Free Range Eggs', 'Brown Eggs', 'White Eggs',
            'Duck Eggs', 'Quail Eggs', 'Large Eggs', 'Medium Eggs', 'Small Eggs',
            'Extra Large Eggs', 'Jumbo Eggs', 'Farm Fresh Eggs', 'Pasture Raised Eggs',
            'Cage Free Eggs', 'Hormone Free Eggs', 'Antibiotic Free Eggs', 'Natural Eggs',
            'Premium Quality Eggs', 'Grade A Eggs', 'Grade AA Eggs', 'Fertile Eggs',
            'Omega-3 Enriched Eggs', 'Vitamin Enhanced Eggs', 'Protein Rich Eggs'
        ];

        echo "Creating 1000 buyers with realistic Philippine data...\n";

        for ($i = 1; $i <= 1000; $i++) {
            $firstName = $this->getRandomFilipinoFirstName();
            $lastName = $this->getRandomFilipinoLastName();
            $city = $philippineCities[array_rand($philippineCities)];
            $province = $provinces[array_rand($provinces)];
            $businessType = $businessTypes[array_rand($businessTypes)];
            
            // Generate company name based on business type
            $companyName = $this->generateCompanyName($businessType, $firstName, $lastName, $i);
            
            // Create user first
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i) . '@buyer.agriconnect.ph',
                'password' => Hash::make('password123'),
                'role' => 'buyer',
                'phone_number' => $this->generatePhilippinePhone(),
                'address' => $this->generateAddress($city, $province),
                'kyc_status' => fake()->randomElement(['verified', 'verified', 'verified', 'pending']), // 75% verified
            ]);

            // Create buyer profile
            Buyer::create([
                'user_id' => $user->id,
                'company_name' => $companyName,
                'business_type' => $businessType,
                'preferred_products' => $this->generatePreferredProducts(),
                'address' => $this->generateBusinessAddress($city, $province, $businessType),
                'verified' => fake()->boolean(85), // 85% verified
            ]);

            if ($i % 50 == 0) {
                echo "Created $i buyers...\n";
            }
        }

        echo "Successfully created 1000 buyers!\n";
    }

    private function generateCompanyName($businessType, $firstName, $lastName, $index): string
    {
        switch ($businessType) {
            case 'Restaurant':
            case 'Fast Food Chain':
            case 'Café':
            case 'Bakery':
            case 'Catering Service':
            case 'Food Truck':
                $restaurantNames = [
                    'Golden Spoon Restaurant', 'Tasty Bites Café', 'Sunrise Diner', 'Ocean View Restaurant',
                    'Mountain Breeze Café', 'City Lights Restaurant', 'Garden Fresh Eatery', 'Tropical Paradise Restaurant',
                    'Heritage Bistro', 'Modern Kitchen', 'Classic Café', 'Flavor Town Restaurant',
                    'Seaside Grill', 'Urban Eats', 'Country Kitchen', 'Metro Bistro',
                    "$firstName\'s Kitchen", "$lastName\'s Restaurant", "$firstName $lastName Café",
                    'Mama\'s Kitchen', 'Papa\'s Restaurant', 'Family Kitchen', 'Home Style Restaurant'
                ];
                return $restaurantNames[array_rand($restaurantNames)] . ($index > 500 ? " $index" : '');

            case 'Retail Store':
            case 'Grocery Store':
            case 'Convenience Store':
            case 'Mini Mart':
                $storeNames = [
                    'Fresh Market Store', 'Golden Grocery', 'Prime Mart', 'City Center Store', 'Metro Supermarket',
                    'Happy Shopping Store', 'Convenience Plus', 'Quick Stop Market', 'Daily Needs Store', 'Family Mart',
                    "$firstName\'s Store", "$lastName\'s Market", "$firstName $lastName Grocery",
                    'Neighborhood Store', 'Community Market', 'Corner Store', 'Local Grocery'
                ];
                return $storeNames[array_rand($storeNames)] . ($index > 500 ? " Branch $index" : '');

            case 'Supermarket':
                $supermarkets = [
                    'SM Supermarket', 'Robinson\'s Supermarket', 'Puregold', 'Shopwise', 'Hypermart',
                    'SaveMore Market', 'Super8 Grocery', 'Gaisano Grand Mall', 'Metro Retail Stores',
                    'Walter Mart', 'Landmark Supermarket'
                ];
                return $supermarkets[array_rand($supermarkets)] . ' Branch ' . $index;

            case 'Wholesale Trading':
            case 'Food Distribution':
            case 'Supply Chain':
            case 'Import/Export':
                $wholesaleNames = [
                    'Philippines Trading Corp', 'Metro Distribution Inc', 'Food Supply Network',
                    'National Distributors', 'Regional Trading Hub', 'Pacific Wholesale Corp',
                    "$lastName Trading Company", "$firstName Distribution Inc"
                ];
                return $wholesaleNames[array_rand($wholesaleNames)] . ($index > 500 ? " $index" : '');

            case 'Hotel':
            case 'Resort':
            case 'Pension House':
                $hotelNames = [
                    'Grand Hotel', 'Paradise Resort', 'Luxury Inn', 'Comfort Lodge', 'Seaside Resort',
                    'Mountain View Hotel', 'City Center Inn', 'Tropical Resort', 'Heritage Hotel',
                    "$firstName Hotel", "$lastName Resort"
                ];
                return $hotelNames[array_rand($hotelNames)] . ($index > 500 ? " $index" : '');

            case 'Individual Buyer':
            case 'Family Business':
                return "$firstName $lastName";

            default:
                return "$firstName $lastName Enterprise";
        }
    }

    private function generatePreferredProducts(): string
    {
        $products = [
            'Fresh Chicken Eggs', 'Organic Eggs', 'Free Range Eggs', 'Brown Eggs', 'White Eggs',
            'Duck Eggs', 'Quail Eggs', 'Large Eggs', 'Medium Eggs', 'Small Eggs',
            'Extra Large Eggs', 'Premium Quality Eggs', 'Grade A Eggs', 'Farm Fresh Eggs'
        ];

        // Select 2-4 random products
        $selectedProducts = fake()->randomElements($products, fake()->numberBetween(2, 4));
        return implode(', ', $selectedProducts);
    }

    private function getRandomFilipinoFirstName(): string
    {
        $maleNames = [
            'Jose', 'Juan', 'Antonio', 'Pedro', 'Miguel', 'Francisco', 'Carlos', 'Luis',
            'Rafael', 'Manuel', 'Ricardo', 'Roberto', 'Eduardo', 'Fernando', 'Jorge',
            'Alberto', 'Daniel', 'Gabriel', 'Mario', 'Angel', 'Alejandro', 'Diego',
            'Ryan', 'Mark', 'John', 'Michael', 'James', 'Christopher', 'Joshua',
            'Matthew', 'Anthony', 'Paul', 'Kenneth', 'Kevin', 'Jason', 'Robert',
            'Emmanuel', 'Jonathan', 'Arnel', 'Reynaldo', 'Ferdinand', 'Benjamin'
        ];

        $femaleNames = [
            'Maria', 'Ana', 'Carmen', 'Josefa', 'Isabel', 'Teresa', 'Rosa', 'Francisca',
            'Antonia', 'Dolores', 'Luz', 'Esperanza', 'Concepcion', 'Pilar', 'Socorro',
            'Gloria', 'Remedios', 'Corazon', 'Fe', 'Natividad', 'Virginia', 'Leonor',
            'Mary', 'Elizabeth', 'Jennifer', 'Linda', 'Barbara', 'Susan', 'Jessica',
            'Sarah', 'Karen', 'Nancy', 'Betty', 'Helen', 'Sandra', 'Donna', 'Carol',
            'Marilyn', 'Michelle', 'Emily', 'Amanda', 'Melissa', 'Deborah', 'Stephanie'
        ];

        $names = array_merge($maleNames, $femaleNames);
        return $names[array_rand($names)];
    }

    private function getRandomFilipinoLastName(): string
    {
        $surnames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres',
            'Tomas', 'Andres', 'Marquez', 'Romualdez', 'Mercado', 'Aguilar', 'Castillo',
            'Francisco', 'Rivera', 'Ramos', 'Valdez', 'Villanueva', 'Aquino', 'Flores',
            'Gonzales', 'Sanchez', 'dela Cruz', 'Martinez', 'Lopez', 'Hernandez', 'Perez',
            'Gomez', 'Martin', 'Jimenez', 'Ruiz', 'Fernandez', 'Morales', 'Alvarez',
            'Romero', 'Alonso', 'Gutierrez', 'Navarro', 'Montes', 'Cabrera', 'Guerrero',
            'Prieto', 'Campos', 'Herrera', 'Vargas', 'Castañeda', 'Vega', 'Molina'
        ];

        return $surnames[array_rand($surnames)];
    }

    private function generatePhilippinePhone(): string
    {
        $prefixes = ['0917', '0918', '0919', '0920', '0921', '0922', '0923', '0924', '0925', '0926', '0927', '0928', '0929', '0939', '0949', '0999'];
        $prefix = $prefixes[array_rand($prefixes)];
        return $prefix . fake()->numerify('#######');
    }

    private function generateAddress($city, $province): string
    {
        $streetNumbers = [fake()->numberBetween(1, 999), fake()->numberBetween(1, 999) . '-' . fake()->randomLetter()];
        $streetNames = [
            'Rizal Street', 'Bonifacio Avenue', 'Mabini Street', 'Del Pilar Street', 'Luna Street',
            'Quezon Avenue', 'Roxas Boulevard', 'EDSA', 'Commonwealth Avenue', 'Katipunan Avenue',
            'Taft Avenue', 'Espana Boulevard', 'Magsaysay Boulevard', 'Marcos Highway'
        ];
        
        $streetNumber = $streetNumbers[array_rand($streetNumbers)];
        $streetName = $streetNames[array_rand($streetNames)];
        
        return "$streetNumber $streetName, $city, $province, Philippines";
    }

    private function generateBusinessAddress($city, $province, $businessType): string
    {
        $businessAreas = [
            'Business District', 'Commercial Center', 'Shopping Mall', 'Market Area', 'Downtown',
            'CBD', 'Financial District', 'Trade Center', 'Industrial Zone', 'Economic Zone'
        ];
        
        $area = $businessAreas[array_rand($businessAreas)];
        $unitNumber = fake()->numberBetween(1, 999);
        
        if (in_array($businessType, ['Supermarket', 'Shopping Mall', 'Department Store'])) {
            return "Ground Floor, Unit $unitNumber, $area, $city, $province, Philippines";
        } elseif (in_array($businessType, ['Restaurant', 'Café', 'Fast Food Chain'])) {
            return "Unit $unitNumber, $area, $city, $province, Philippines";
        } else {
            return "$unitNumber $area, $city, $province, Philippines";
        }
    }
}
