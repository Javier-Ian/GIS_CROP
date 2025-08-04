<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapImage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'filename',
        'original_name',
        'file_path',
        'gis_files',
        'map_image_path',
        'file_size',
        'mime_type',
        'user_id',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'gis_files' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
