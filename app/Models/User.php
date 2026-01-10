<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'date_of_birth',
        'job_start_date',
        'job_end_date',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'job_start_date' => 'date',
            'job_end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function reviewedCheckSheets()
    {
        return $this->hasMany(CheckSheet::class, 'reviewed_by');
    }

    public function technicianCheckSheets()
    {
        return $this->belongsToMany(CheckSheet::class, 'checksheet_technician')
            ->withTimestamps();
    }

    public function inspectorCheckSheets()
    {
        return $this->belongsToMany(CheckSheet::class, 'checksheet_inspector')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
