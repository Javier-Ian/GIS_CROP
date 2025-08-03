<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'location',
        'latitude',
        'longitude',
        'crop_type',
        'area_size',
        'planting_date',
        'harvest_date',
        'notes',
        'status'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'planting_date' => 'date',
        'harvest_date' => 'date',
        'area_size' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mapFiles(): HasMany
    {
        return $this->hasMany(MapFile::class);
    }

    // Get the map image URL
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
