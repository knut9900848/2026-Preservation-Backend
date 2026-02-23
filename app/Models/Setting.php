<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $appends = ['ies_logo_url', 'client_logo_url'];

    protected $fillable = [
        // IES (Company) Information
        'ies_name',
        'ies_address',
        'ies_contact_number',
        'ies_logo',
        'ies_vat_code',
        'ies_email',
        'ies_website_url',
        'ies_slogan',
        'ies_ceo_name',

        // Client Information
        'client_name',
        'client_address',
        'client_logo',
        'client_contact_number',

        // Project Information
        'project_name',
        'project_code',
    ];

    /**
     * Get the singleton setting instance
     */
    public static function instance(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    public function getIesLogoUrlAttribute()
    {
        if (!$this->ies_logo) {
            return null;
        }

        if (config('filesystems.default') === 's3') {
            return Storage::temporaryUrl($this->ies_logo, now()->addMinutes(60));
        }

        return Storage::url($this->ies_logo);
    }

    public function getClientLogoUrlAttribute()
    {
        if (!$this->client_logo) {
            return null;
        }

        if (config('filesystems.default') === 's3') {
            return Storage::temporaryUrl($this->client_logo, now()->addMinutes(60));
        }

        return Storage::url($this->client_logo);
    }
}
