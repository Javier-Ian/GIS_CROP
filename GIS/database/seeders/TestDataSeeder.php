<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MapImage;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create a test user
        $user = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        // Create some test map data
        MapImage::create([
            'title' => 'Sample Rice Farm Map',
            'description' => 'A sample rice farming area in Central Luzon',
            'crop_type' => 'Rice',
            'hectares' => 2.50,
            'location' => 'Nueva Ecija, Philippines',
            'planting_date' => '2025-06-15',
            'land_status' => 'planted',
            'filename' => 'sample_map.jpg',
            'original_name' => 'sample_map.jpg',
            'file_path' => 'maps/sample_map.jpg',
            'file_size' => 1024000,
            'mime_type' => 'image/jpeg',
            'user_id' => $user->id,
            'gis_files' => [],
            'map_image_path' => null,
        ]);

        MapImage::create([
            'title' => 'Corn Field Analysis',
            'description' => 'Corn cultivation monitoring data',
            'crop_type' => 'Corn',
            'hectares' => 1.75,
            'location' => 'Isabela, Philippines',
            'planting_date' => '2025-07-01',
            'land_status' => 'harvested',
            'filename' => 'corn_field.jpg',
            'original_name' => 'corn_field.jpg',
            'file_path' => 'maps/corn_field.jpg',
            'file_size' => 2048000,
            'mime_type' => 'image/jpeg',
            'user_id' => $user->id,
            'gis_files' => [
                [
                    'filename' => 'corn_area.shp',
                    'original_name' => 'corn_area.shp',
                    'file_path' => 'gis-files/corn_area.shp',
                    'file_size' => 512000,
                    'mime_type' => 'application/x-shp',
                    'extension' => 'shp',
                ]
            ],
            'map_image_path' => 'maps/corn_field.jpg',
        ]);

        echo "Test data created successfully!\n";
        echo "Login with: test@example.com / password\n";
    }
}
