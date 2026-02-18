<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function disciplineItems()
    {
        return $this->hasMany(DisciplineItem::class);
    }
}
