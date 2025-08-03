<?php
// Test script to verify edit functionality
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\MapImage;

// Test if we can find and load a map
$mapImage = MapImage::first();

if ($mapImage) {
    echo "Found map: " . $mapImage->title . "\n";
    echo "Current crop type: " . $mapImage->crop_type . "\n";
    echo "Current hectares: " . $mapImage->hectares . "\n";
    echo "Current location: " . $mapImage->location . "\n";
    echo "Current land status: " . $mapImage->land_status . "\n";
    echo "Current description: " . $mapImage->description . "\n";
    echo "Current planting date: " . ($mapImage->planting_date ? $mapImage->planting_date->format('Y-m-d') : 'None') . "\n";
    echo "GIS Files: " . (is_array($mapImage->gis_files) ? count($mapImage->gis_files) : 0) . " files\n";
    echo "Map image path: " . ($mapImage->map_image_path ?? 'None') . "\n";
    echo "\nEdit URL: http://127.0.0.1:8000/maps/{$mapImage->id}/edit\n";
} else {
    echo "No maps found in database.\n";
    
    // Check if we have any data at all
    $count = MapImage::count();
    echo "Total maps in database: $count\n";
}
