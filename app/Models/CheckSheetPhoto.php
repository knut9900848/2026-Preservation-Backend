<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CheckSheetPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_group_id',
        'filename',
        'original_filename',
        'path',
        'thumbnail_path',
        'mime_type',
        'size',
        'order',
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer',
    ];

    protected $appends = ['url', 'thumbnail_url'];

    public function photoGroup()
    {
        return $this->belongsTo(CheckSheetPhotoGroup::class, 'photo_group_id');
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : null;
    }
}
