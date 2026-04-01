<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    // Actually the easiest way is to call the Artisan command programmatically so DB facade works
    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'AnalyticsSeeder']);
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
