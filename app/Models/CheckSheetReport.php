<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CheckSheetReport extends Model
{
    protected $fillable = [
        'check_sheet_id',
        'generated_by',
        'revision_number',
        'file_path',
        'file_name',
        'file_size',
        'checksheet_status',
        'notes',
    ];

    protected $casts = [
        'revision_number' => 'decimal:1',
        'file_size' => 'integer',
    ];

    public function checkSheet()
    {
        return $this->belongsTo(CheckSheet::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function fileExists(): bool
    {
        return Storage::disk('local')->exists($this->file_path);
    }
}
