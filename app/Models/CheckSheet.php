<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CheckSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'equipment_id',
        'activity_code',
        'current_round',
        'sheet_number',
        'reviewed_by',
        'reviewed_date',
        'performed_date',
        'due_date',
        'notes',
        'instruction',
        'frequency',
        'status',
    ];

    protected $attributes = [
        'instruction' => "Preservation Instructions:\nIf the Unit / Equipment is operational or under commissioning, DO NOT execute this check sheet and activities.\nIf any of the Activities are not able to be performed, explain in detail in the Remarks column & inform Preservation Supervisor / Coordinator.\nIf any of the equipment is found to be damaged, a punch list shall be raised.\nEnsure the preservation Label is filled & signed after preservation routine activity.",
    ];

    protected $casts = [
        'current_round' => 'integer',
        'frequency' => 'integer',
        'performed_date' => 'date',
        'reviewed_date' => 'date',
        'due_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($checkSheet) {
            $equipment = Equipment::find($checkSheet->equipment_id);
            $activity = Activity::find($checkSheet->activity_id);

            // Auto-set activity_code
            if (!$checkSheet->activity_code) {
                $checkSheet->activity_code = $activity->code;
            }

            // Auto-generate sheet_number: {tag_no}-{activity_code}-{current_round}
            if (!$checkSheet->sheet_number) {
                $checkSheet->sheet_number = $equipment->tag_no . '-' . $activity->code . '-' . $checkSheet->current_round;
            }
        });
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function checkSheetItems()
    {
        return $this->hasMany(CheckSheetItem::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function technicians()
    {
        return $this->belongsToMany(User::class, 'checksheet_technician')
            ->withTimestamps();
    }

    public function inspectors()
    {
        return $this->belongsToMany(User::class, 'checksheet_inspector')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function histories()
    {
        return $this->hasMany(CheckSheetHistory::class);
    }

    public function photoGroups()
    {
        return $this->hasMany(CheckSheetPhotoGroup::class)->orderBy('order');
    }

    public function reports()
    {
        return $this->hasMany(CheckSheetReport::class)->orderByDesc('revision_number');
    }
}
