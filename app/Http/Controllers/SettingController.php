<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Get the current settings
     */
    public function show()
    {
        $setting = Setting::instance();

        return response()->json([
            'setting' => $setting,
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // IES (Company) Information
            'ies_name' => 'nullable|string|max:255',
            'ies_address' => 'nullable|string',
            'ies_contact_number' => 'nullable|string|max:255',
            'ies_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'ies_vat_code' => 'nullable|string|max:255',
            'ies_email' => 'nullable|email|max:255',
            'ies_website_url' => 'nullable|url|max:255',
            'ies_slogan' => 'nullable|string|max:255',
            'ies_ceo_name' => 'nullable|string|max:255',

            // Client Information
            'client_name' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',
            'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'client_contact_number' => 'nullable|string|max:255',

            // Project Information
            'project_name' => 'nullable|string|max:255',
            'project_code' => 'nullable|string|max:255',
        ]);

        $setting = Setting::instance();

        // Handle IES logo upload
        if ($request->hasFile('ies_logo')) {
            if ($setting->ies_logo) {
                Storage::disk('public')->delete($setting->ies_logo);
            }
            $validated['ies_logo'] = $request->file('ies_logo')->store('settings', 'public');
        }

        // Handle Client logo upload
        if ($request->hasFile('client_logo')) {
            if ($setting->client_logo) {
                Storage::disk('public')->delete($setting->client_logo);
            }
            $validated['client_logo'] = $request->file('client_logo')->store('settings', 'public');
        }

        $setting->update($validated);

        return response()->json([
            'message' => 'Settings updated successfully',
            'setting' => $setting->fresh(),
        ]);
    }
}
