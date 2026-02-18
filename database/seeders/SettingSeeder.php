<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'ies_name' => 'Intrust Energy Solution Inc.',
                'ies_address' => '62, Jangpyeong 4-ro, Geoje-si, Gyeongsangnam-do, Republic of Korea',
                'ies_email' => 'contract@intrustenergysolution.com',
                'ies_slogan' => 'Safety and Trust',
                'ies_vat_code' => '786-88-00117',
                'ies_website_url' => 'https://intrustenergysolution.com',
                'ies_ceo_name' => 'Scott Kim',
                'ies_contact_number' => '+82 55 255 8800',
                'project_code' => 'TRI',
                'project_name' => 'Trion',
                'client_name' => 'Baker Hughes',
            ]
        );
    }
}
