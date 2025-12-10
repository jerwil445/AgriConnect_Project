<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Farmer;
use Illuminate\Support\Facades\Hash;

class BulkFarmersSeeder extends Seeder
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
            'Bataan', 'Bulacan', 'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales',
            'Rizal', 'Cavite', 'Laguna', 'Batangas', 'Quezon', 'Aurora',
            'Albay', 'Camarines Norte', 'Camarines Sur', 'Catanduanes', 'Masbate', 'Sorsogon',
            'Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental',
            'Bohol', 'Cebu', 'Negros Oriental', 'Siquijor', 'Leyte', 'Southern Leyte',
            'Agusan del Norte', 'Agusan del Sur', 'Surigao del Norte', 'Surigao del Sur',
            'Davao del Norte', 'Davao del Sur', 'Davao de Oro', 'Davao Oriental', 'Davao Occidental'
        ];

        $farmNames = [
            'Golden Sunrise Farm', 'Green Valley Poultry', 'Happy Hen Farm', 'Fresh Farm Philippines',
            'Sunrise Poultry Farm', 'Countryside Eggs', 'Nature\'s Best Farm', 'Organic Valley Farm',
            'Sunrise Chicken Farm', 'Mountain View Poultry', 'Greenfield Farm', 'Blue Sky Farm',
            'Harvest Moon Farm', 'Rainbow Farm', 'Paradise Poultry', 'Freedom Farm',
            'Healthy Hen Farm', 'Pure Gold Farm', 'Crystal Clear Farm', 'Silver Star Farm',
            'Diamond Poultry', 'Emerald Farm', 'Ruby Ridge Farm', 'Sapphire Valley',
            'Platinum Farm', 'Golden Gate Farm', 'Sunshine Acres', 'Peaceful Valley',
            'Harmony Farm', 'Prosperity Poultry', 'Lucky Star Farm', 'Blessed Farm',
            'Victory Farm', 'Champion Poultry', 'Elite Farm', 'Premier Farm',
            'Excellence Farm', 'Quality Poultry', 'Prime Farm', 'Superior Farm'
        ];

        $businessTypes = [
            'Sole Proprietorship', 'Partnership', 'Corporation', 'Cooperative',
            'Single Proprietorship', 'Family Farm', 'Corporate Farm'
        ];

        $certifications = [
            'Organic Certification', 'Good Agricultural Practices (GAP)', 'HACCP Certified',
            'ISO 22000', 'Halal Certification', 'Non-GMO Verified', 'Free Range Certified',
            'Animal Welfare Approved', 'Biosafety Certified', 'Quality Assurance Certified'
        ];

        $farmingMethods = [
            'Free Range', 'Cage Free', 'Organic', 'Conventional', 'Pasture Raised',
            'Battery Cage', 'Enriched Cage', 'Barn System', 'Aviary System'
        ];

        $bankNames = [
            'BDO Unibank', 'Metrobank', 'Bank of the Philippine Islands (BPI)', 'Landbank',
            'Philippine National Bank (PNB)', 'Security Bank', 'UnionBank', 'Eastwest Bank',
            'RCBC', 'Chinabank', 'BPI Family Savings Bank', 'Maybank Philippines',
            'HSBC Philippines', 'Standard Chartered Philippines', 'Citibank Philippines'
        ];

        $mobileWalletProviders = [
            'GCash', 'PayMaya', 'Coins.ph', 'GrabPay', 'PayPal', 'UnionBank Online',
            'BPI Mobile', 'Metrobank Mobile Banking', 'BDO Mobile Banking'
        ];

        echo "Creating 1000 farmers with realistic Philippine data...\n";

        for ($i = 1; $i <= 1000; $i++) {
            $firstName = $this->getRandomFilipinoFirstName();
            $lastName = $this->getRandomFilipinoLastName();
            $city = $philippineCities[array_rand($philippineCities)];
            $province = $provinces[array_rand($provinces)];
            
            // Create user first
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i) . '@farmer.agriconnect.ph',
                'password' => Hash::make('password123'),
                'role' => 'farmer',
                'phone_number' => $this->generatePhilippinePhone(),
                'address' => $this->generateAddress($city, $province),
                'kyc_status' => fake()->randomElement(['verified', 'verified', 'verified', 'pending']), // 75% verified
            ]);

            // Generate coordinates within Philippines
            $coordinates = $this->generatePhilippineCoordinates();
            
            // Create farmer profile
            Farmer::create([
                'user_id' => $user->id,
                'farm_name' => $farmNames[array_rand($farmNames)] . ' ' . $i,
                'farm_size' => fake()->randomFloat(2, 0.5, 50), // 0.5 to 50 hectares
                'farm_size_unit' => fake()->randomElement(['hectares', 'acres', 'square meters']),
                'experience_years' => fake()->numberBetween(1, 35),
                'certification' => $certifications[array_rand($certifications)],
                'farm_address' => $this->generateFarmAddress($city, $province),
                
                // Business Information
                'business_registration_number' => 'DTI-' . fake()->numerify('########'),
                'business_type' => $businessTypes[array_rand($businessTypes)],
                'tax_id_number' => fake()->numerify('###-###-###'),
                
                // Contact & Communication
                'secondary_phone' => $this->generatePhilippinePhone(),
                'whatsapp_number' => $this->generatePhilippinePhone(),
                
                // Payment Information
                'bank_name' => $bankNames[array_rand($bankNames)],
                'bank_account_number' => fake()->numerify('################'),
                'bank_account_name' => $firstName . ' ' . $lastName,
                'mobile_wallet_provider' => $mobileWalletProviders[array_rand($mobileWalletProviders)],
                'mobile_wallet_number' => $this->generatePhilippinePhone(),
                
                // Certifications & Compliance
                'organic_certification' => fake()->boolean(30), // 30% have organic cert
                'certification_expiry_date' => fake()->dateTimeBetween('now', '+2 years'),
                'food_safety_certification' => fake()->boolean(70),
                'gmp_certified' => fake()->boolean(60),
                'halal_certified' => fake()->boolean(40),
                
                // Farm Details
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lng'],
                'total_chickens' => fake()->numberBetween(100, 10000),
                'farming_method' => $farmingMethods[array_rand($farmingMethods)],
                
                // Performance Metrics
                'average_rating' => fake()->randomFloat(2, 3.0, 5.0),
                'total_reviews' => fake()->numberBetween(0, 50),
                'completed_orders' => fake()->numberBetween(0, 200),
                'success_rate' => fake()->randomFloat(2, 75.0, 99.5),
                
                // Status & Verification
                'verification_status' => fake()->randomElement(['verified', 'verified', 'pending', 'rejected']),
                'verified_at' => fake()->boolean(80) ? fake()->dateTimeBetween('-1 year', 'now') : null,
                'verified_by' => fake()->boolean(80) ? 1 : null, // Admin user ID
                'verification_notes' => fake()->sentence(),
                
                // Operational
                'is_active' => fake()->boolean(90), // 90% active
                'accepting_orders' => fake()->boolean(85), // 85% accepting orders
                'operation_start_time' => '06:00:00',
                'operation_end_time' => '18:00:00',
                'operation_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            ]);

            if ($i % 50 == 0) {
                echo "Created $i farmers...\n";
            }
        }

        echo "Successfully created 1000 farmers!\n";
    }

    private function getRandomFilipinoFirstName(): string
    {
        $maleNames = [
            'Jose', 'Juan', 'Antonio', 'Pedro', 'Miguel', 'Francisco', 'Carlos', 'Luis',
            'Rafael', 'Manuel', 'Ricardo', 'Roberto', 'Eduardo', 'Fernando', 'Jorge',
            'Alberto', 'Daniel', 'Gabriel', 'Mario', 'Angel', 'Alejandro', 'Diego',
            'Ryan', 'Mark', 'John', 'Michael', 'James', 'Christopher', 'Joshua',
            'Matthew', 'Anthony', 'Paul', 'Kenneth', 'Kevin', 'Jason', 'Robert',
            'Emmanuel', 'Jonathan', 'Arnel', 'Reynaldo', 'Eduardo', 'Ferdinand'
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

    private function generateFarmAddress($city, $province): string
    {
        $barangays = [
            'Barangay San Jose', 'Barangay Santa Cruz', 'Barangay San Antonio', 'Barangay Santa Maria',
            'Barangay San Juan', 'Barangay Santo Domingo', 'Barangay San Pedro', 'Barangay Santa Ana',
            'Barangay San Miguel', 'Barangay Santo Rosario', 'Barangay Poblacion', 'Barangay Centro'
        ];
        
        $barangay = $barangays[array_rand($barangays)];
        return "$barangay, $city, $province, Philippines";
    }

    private function generatePhilippineCoordinates(): array
    {
        // Philippines is roughly between 4°N to 21°N latitude and 116°E to 127°E longitude
        $lat = fake()->randomFloat(6, 4.0, 21.0);
        $lng = fake()->randomFloat(6, 116.0, 127.0);
        
        return ['lat' => $lat, 'lng' => $lng];
    }
}
