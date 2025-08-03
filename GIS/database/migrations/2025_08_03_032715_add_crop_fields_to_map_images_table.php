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
            $table->string('crop_type')->nullable()->after('description');
            $table->decimal('hectares', 8, 2)->nullable()->after('crop_type');
            $table->string('location')->nullable()->after('hectares');
            $table->date('planting_date')->nullable()->after('location');
            $table->enum('land_status', ['planted', 'harvested', 'fallow', 'prepared'])->default('planted')->after('planting_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_images', function (Blueprint $table) {
            $table->dropColumn(['crop_type', 'hectares', 'location', 'planting_date', 'land_status']);
        });
    }
};
