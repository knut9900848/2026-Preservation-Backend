<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckSheetHistory extends Model
{
    protected $fillable = [
        'check_sheet_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the check sheet this history belongs to
     */
    public function checkSheet()
    {
        return $this->belongsTo(CheckSheet::class);
    }

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
