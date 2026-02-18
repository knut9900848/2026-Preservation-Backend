<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'discipline_id',
        'code',
        'name',
        'method',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
