<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('map_images', function (Blueprint $table) {
            $table->json('gis_files')->nullable()->after('file_path');
            $table->string('map_image_path')->nullable()->after('gis_files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_images', function (Blueprint $table) {
            $table->dropColumn(['gis_files', 'map_image_path']);
        });
    }
};
