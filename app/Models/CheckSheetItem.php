<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'check_sheet_id',
        'activity',
        'description',
        'status',
        'remarks',
        'order',
    ];

    protected $casts = [
        'status' => 'integer',
        'order' => 'integer',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function checkSheet()
    {
        return $this->belongsTo(CheckSheet::class);
    }
}
