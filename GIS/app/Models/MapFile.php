<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapFile extends Model
{
    protected $table = 'map_files';
    
    protected $fillable = [
        'map_id',
        'filename',
        'original_name',
        'file_path',
        'file_extension',
        'file_type',
        'file_size',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class, 'map_id');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
