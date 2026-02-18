<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'discipline_id',
        'discipline_item_id',
        'code',
        'description',
        'notes',
        'frequency',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'frequency' => 'integer',
    ];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function disciplineItem()
    {
        return $this->belongsTo(DisciplineItem::class);
    }

    public function assignedEquipments()
    {
        return $this->belongsToMany(Equipment::class, 'activity_equipment')
            ->withTimestamps();
    }

    public function activityItems()
    {
        return $this->hasMany(ActivityItem::class);
    }

    public function checkSheets()
    {
        return $this->hasMany(CheckSheet::class);
    }
}
