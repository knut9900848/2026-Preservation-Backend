<?php

namespace Database\Seeders;

use App\Models\DisciplineItem;
use Illuminate\Database\Seeder;

class DisciplineItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['discipline_id' => 2, 'code' => 'EP-01', 'name' => 'Electrical Motors (Ex)', 'method' => 'Tag'],
            ['discipline_id' => 2, 'code' => 'EP-02', 'name' => 'Alternators / Generators', 'method' => 'Tag'],
            ['discipline_id' => 2, 'code' => 'EP-03', 'name' => 'Duct Heater', 'method' => 'Tag'],
            ['discipline_id' => 2, 'code' => 'EP-04', 'name' => 'DB - Relay Boxes - SWBD - Panels - Cabinets', 'method' => 'Tag'],
            ['discipline_id' => 2, 'code' => 'EP-05', 'name' => 'Transformers - Neutral Earthing Resistors', 'method' => 'Tag'],
            ['discipline_id' => 2, 'code' => 'EP-06', 'name' => 'UPS, Battery Charger, Batteries Bank', 'method' => 'Tag'],
            ['discipline_id' => 2, 'code' => 'EP-07', 'name' => 'Telecom Devices / Computers / Electronic Equipment', 'method' => 'Tag'],
            ['discipline_id' => 2, 'code' => 'EP-08', 'name' => 'Junction Box', 'method' => 'Bulk'],
            ['discipline_id' => 2, 'code' => 'EP-09', 'name' => 'Electrical Heaters', 'method' => 'Tag'],
            ['discipline_id' => 1, 'code' => 'AP-01', 'name' => 'Area Preservation Inspection', 'method' => 'Bulk'],
            ['discipline_id' => 3, 'code' => 'IP-01', 'name' => 'Field transmitter, Analyzer,', 'method' => 'Bulk'],
            ['discipline_id' => 3, 'code' => 'IP-02', 'name' => 'Fire and Gas Devices', 'method' => 'Bulk'],
            ['discipline_id' => 3, 'code' => 'IP-03', 'name' => 'Actuated Valves', 'method' => 'Bulk'],
            ['discipline_id' => 4, 'code' => 'MP-01', 'name' => 'Electrical motor driven pump', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-02', 'name' => 'Boiler (Not applicable for Barossa)', 'method' => 'N/A'],
            ['discipline_id' => 4, 'code' => 'MP-03', 'name' => 'Gas / Steam Turbine Generators', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-04', 'name' => 'Diesel Engine Generator', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-05', 'name' => 'Cranes', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-06', 'name' => 'Fans', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-07', 'name' => 'Hydraulic Power Units', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-08', 'name' => 'Dampers', 'method' => 'Bulk'],
            ['discipline_id' => 4, 'code' => 'MP-09', 'name' => 'HVAC', 'method' => 'Bulk'],
            ['discipline_id' => 4, 'code' => 'MP-10', 'name' => 'Pressure Vessels, Separators, Atmospheric Tanks, Scrubber, Calorifier', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-11', 'name' => 'Heat Exchanger (Shell & Tube / Plate & Frame/ Air cooler / Spiral)', 'method' => 'Tag'],
            ['discipline_id' => 5, 'code' => 'DP-01', 'name' => 'Skid – Mechanical / Electrical / Instruments / Piping', 'method' => 'Tag'],
            ['discipline_id' => 5, 'code' => 'DP-02', 'name' => 'Gas Compressor Motor Driven', 'method' => 'Tag'],
            ['discipline_id' => 5, 'code' => 'DP-03', 'name' => 'Diesel Engine Pump', 'method' => 'Tag'],
            ['discipline_id' => 5, 'code' => 'DP-04', 'name' => 'Hydraulic powered Pump', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-12', 'name' => 'Air compressor & Dryer', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-13', 'name' => 'Tank Cleaning Machine', 'method' => 'Tag'],
            ['discipline_id' => 4, 'code' => 'MP-14', 'name' => 'Hydraulic Powered Centrifugal Pump', 'method' => 'Tag'],
            ['discipline_id' => 6, 'code' => 'TP-01', 'name' => 'Membrane / Filter Storage', 'method' => 'Bulk'],
            ['discipline_id' => 7, 'code' => 'PP-01', 'name' => 'Piping', 'method' => 'Bulk'],
            ['discipline_id' => 7, 'code' => 'PP-02', 'name' => 'Manual Valves', 'method' => 'Bulk'],
            ['discipline_id' => 8, 'code' => 'SP-01', 'name' => 'Fixed Fighting Equipment', 'method' => 'Bulk'],
            ['discipline_id' => 8, 'code' => 'SP-02', 'name' => 'Lifeboat, Rescue boat, Life raft & Life raft station', 'method' => 'Tag'],
        ];

        foreach ($items as $item) {
            DisciplineItem::firstOrCreate(
                ['code' => $item['code']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
