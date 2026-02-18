<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
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
}
