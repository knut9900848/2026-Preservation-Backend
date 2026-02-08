<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetPhotoGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_sheet_id',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function checkSheet()
    {
        return $this->belongsTo(CheckSheet::class);
    }

    public function photos()
    {
        return $this->hasMany(CheckSheetPhoto::class, 'photo_group_id')->orderBy('order');
    }
}
