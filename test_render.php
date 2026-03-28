<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $demands = \App\Models\Demand::with(['buyer', 'matches'])->paginate(10);
    $buyers = \App\Models\User::where('role', 'buyer')->get();
    
    $view = view('admin.demands.index', [
        'demands' => $demands,
        'buyers' => $buyers,
        'search' => null,
        'buyerFilter' => null,
        'sortBy' => 'created_at',
        'sortDirection' => 'desc'
    ])->render();
    
    echo "SUCCESS\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString() . "\n";
}
