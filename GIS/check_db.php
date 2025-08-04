<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking map_images table structure:\n\n";

try {
    $columns = DB::select('DESCRIBE map_images');
    foreach ($columns as $column) {
        echo "Field: {$column->Field}, Type: {$column->Type}, Null: {$column->Null}, Default: {$column->Default}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
