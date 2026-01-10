<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'tag_no',
        'category_id',
        'sub_category_id',
        'supplier_id',
        'current_location_id',
        'serial_number',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currentLocation()
    {
        return $this->belongsTo(CurrentLocation::class);
    }

    public function assignedActivities()
    {
        return $this->belongsToMany(Activity::class, 'activity_equipment')
            ->withTimestamps();
    }

    public function checkSheets()
    {
        return $this->hasMany(CheckSheet::class);
    }
}
