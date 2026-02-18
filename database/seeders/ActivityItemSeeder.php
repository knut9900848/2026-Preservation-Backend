<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityItem;
use Illuminate\Database\Seeder;

class ActivityItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // AP-01-01
            ['code' => 'AP-01-01', 'description' => "Check the equipment, skid in the location is clean and free from debris. Clean if required."],
            ['code' => 'AP-01-01', 'description' => "Check no visual damage/ corrosion to equipment and coating.\nclean the rust & apply the preservative if required."],
            ['code' => 'AP-01-01', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities) with fire retardant dust cover or tarpaulin is not damaged and properly sealed. Repair or replace as required for all equipment, valve, transmitter, light fitting in the location.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'AP-01-01', 'description' => "Record the storage condition of the room Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C."],
            ['code' => 'AP-01-01', 'description' => "Ensure Control rooms, instrument rooms and other areas where electrical and electronic installed was installed is maintained clean, dust free with proper housekeeping, adequately ventilated & humidity controlled if required."],
            ['code' => 'AP-01-01', 'description' => "Ensure the CRA material such as insulation, ss tubing, fittings, duct, cable tray, cable ladder, cable gland, shall be protected from construction debris using VCI clear coating.\nClean if there is any corrosion build up.\nAlso, on the permanent data plates or labels are protected with clear coating."],
            ['code' => 'AP-01-01', 'description' => "Carry out visual inspection of foundation/ holding down bolts for corrosion or damage. Clean if any & apply wax based coating on all the bolt & Nut, earthing cable etc.,\nAlso applies to AVM if applicable."],
            ['code' => 'AP-01-01', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'AP-01-01', 'description' => "Check and apply preservation as required to exposed machined surfaces (includes shafts, carbon steel valve spindles, threaded spindles and coupling hubs) on pump and baseplate. Also, machine flanges and flange face are properly protected with wax-based coating"],
            ['code' => 'AP-01-01', 'description' => "After installed in location, Check no visual damage / corrosion to instrument gauges, RO, rupture disc, PSV, other Instrument misc. items and coating. Re-apply preservation coating, if required."],
            ['code' => 'AP-01-01', 'description' => "After installed in location, Check for no visual damage / corrosion to electrical light fitting / local control stations / welding / power socket outlets / emergency stops and coating."],
            ['code' => 'AP-01-01', 'description' => "Check integrity of mounting plate / securing bolts/ clamps of the equipment and related accessories."],
            ['code' => 'AP-01-01', 'description' => "All the stored pipe spools, valves are covered with end cap on both ends & stored above the ground level using wooden block."],
            ['code' => 'AP-01-01', 'description' => "Uncoated CS hubs, CS seal rings, CS clamps shall be coated with wax-based coating as 100% coverage. Reapply the coating if there is any damage on the coating.\nAll CRA material not required coating during storage but need to cover with Tarpaulin to protect from carbon dust."],

            // EP-01-04
            ['code' => 'EP-01-04', 'description' => "Ensure that the protection (exposed to open sunlight, rain and construction activities), dust cover is not damaged and properly sealed.\nRepair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'EP-01-04', 'description' => "Check no visual damage/ corrosion to motor and coating. Apply preservation to machined surfaces if necessary."],
            ['code' => 'EP-01-04', 'description' => "Check the motor is clean and free from debris. Clean if required."],
            ['code' => 'EP-01-04', 'description' => "Anti-condensation heater function to be verified by indication lamp, if applicable."],
            ['code' => 'EP-01-04', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly."],
            ['code' => 'EP-01-04', 'description' => "Manually rotate shaft 2 ¼ turns and leave the shaft in new orientation."],
            ['code' => 'EP-01-04', 'description' => "Ensure grease is applied on exposed conductor or earth boss. (re-apply if required)."],
            ['code' => 'EP-01-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'EP-01-04', 'description' => "Check and apply preservation as required to exposed machined surfaces (includes shafts and coupling hubs) on pump and baseplate."],
            ['code' => 'EP-01-04', 'description' => "Carry out visual inspection of foundation/ holding down bolts for corrosion or damage."],
            ['code' => 'EP-01-04', 'description' => "Check the terminal box for moisture and change the VCI or desiccant if necessary. Change VCI emitter every 24 weeks & or\nDesiccant bag every 12 weeks."],
            ['code' => 'EP-01-04', 'description' => "Check insulation resistance of winding and record results.\nL1-E:              , L2-E:                   . L3-E:                 \n. (Test Voltage: LV-1KV, MV-5K).\nMinimum Acceptable Value: LV =10 MΩ, MV =150 MΩ."],
            ['code' => 'EP-01-04', 'description' => "For Ex motors, ensure there is not dent or damage or corrosion to the flame path of the equipment."],

            // EP-02-04
            ['code' => 'EP-02-04', 'description' => "Ensure that the protection (exposed to open sunlight, rain and construction activities), dust cover is not damaged and properly sealed. Repair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'EP-02-04', 'description' => "Check no visual damage/ corrosion to generator and coating.\nApply preservation to machined surfaces if necessary."],
            ['code' => 'EP-02-04', 'description' => "Check the equipment skid is clean and free from debris. Clean if required."],
            ['code' => 'EP-02-04', 'description' => "Anti-condensation heater function to be verified by indication lamp. If applicable."],
            ['code' => 'EP-02-04', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly."],
            ['code' => 'EP-02-04', 'description' => "Ensure that oil level in oil lubricated bearing is correct."],

            // EP-02-12
            ['code' => 'EP-02-12', 'description' => "Ensure grease is applied on exposed conductor or earth boss. (re-apply if required)."],
            ['code' => 'EP-02-12', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'EP-02-12', 'description' => "Carry out visual inspection of foundation/ holding down bolts for corrosion or damage."],
            ['code' => 'EP-02-12', 'description' => "Check the cooling system space is preserved with VpCI, if applicable."],
            ['code' => 'EP-02-12', 'description' => "Check the Terminal box for moisture and change the VCI or desiccant if necessary."],
            ['code' => 'EP-02-12', 'description' => "Check insulation resistance of winding and record results.\nL1-E:              , L2-E:                   . L3-E:                    .\nTest Voltage: LV-1KV, MV-5K\nMinimum Acceptable Value: LV =10 MΩ, MV =150 MΩ."],

            // EP-03-08
            ['code' => 'EP-03-08', 'description' => "Ensure the heaters are free from debris & dust.\nInspect the panel & repair corrosion if any, Maintain the heaters free from moisture internal and external. If major issue contact preservation supervisor."],
            ['code' => 'EP-03-08', 'description' => "Check no internal and external damage to the equipment and related instruments, indication lights, coating & same to be protected from mechanical damage during construction."],
            ['code' => 'EP-03-08', 'description' => "All heaters are protected from construction debris. If dust cover is damage repair or replace with the new one."],
            ['code' => 'EP-03-08', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'EP-03-08', 'description' => "Anti-condensation heater function to be verified by indication lamp. If applicable."],
            ['code' => 'EP-03-08', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly. Whereas entry holes with glands installed shall be fitted with standard dust blinds."],
            ['code' => 'EP-03-08', 'description' => "Check insulation resistance of (Stage to Stage and Phase to Earth) with 500V handheld megger for 60 sec from the L1/L2/U/V/W to ground.\nReport to the preservation engineer if insulation is below 10 MΩ.\nL1-E:              , L2-E:                   . L3-E:                   .\nTest Voltage: LV-1KV, MV-5K.\nIf the insulation drops, replace the desiccant bags.\nFor bulk item if there are multiple heaters record in separate sheet & upload to this checklist."],
            ['code' => 'EP-03-08', 'description' => "Ensure terminal box is covered with a VpCI FR stretch wrap."],
            ['code' => 'EP-03-08', 'description' => "Check all the nozzles & flange ends are sealed with painted blind flanges with rubber gasket & spanner torqued with 4 bolts to keep them airtight.\nAfter pipe tie-in during inspection ensure all nozzles are isolated with blind plate or galvanized metal plate for positive isolation to preserve the vessel.\nAfter piping re-instatement, closed loop system by closing the equipment adjacent valve to preserve the vessel."],
            ['code' => 'EP-03-08', 'description' => "Ensure wax based coating is applied on exposed conductor or earth boss & all uncoated surface includes bolt & nut. (re-apply if required)."],
            ['code' => 'EP-03-08', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'EP-03-08', 'description' => "Replace the existing silica gel with new about 3 kg / m3 of the size of the terminal box. Keep it away from ACH. Close and seal the TB immediately asap. Keep the terminal box was always closed."],

            // EP-04-04
            ['code' => 'EP-04-04', 'description' => "Ensure the panels, relays, switchboard are free from debris & dust.\nInspect the panel & repair corrosion if any, Maintain the panels free from moisture internal and external. If major issue contact preservation supervisor."],
            ['code' => 'EP-04-04', 'description' => "Check no internal and external damage to the equipment and related instruments, indication lights, coating & same to be protected from mechanical damage during construction."],
            ['code' => 'EP-04-04', 'description' => "All panels & switchboard are protected from construction debris. If dust cover is damage repair or replace with the new one."],
            ['code' => 'EP-04-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'EP-04-04', 'description' => "Ensure to store the above equipment in the humidity-controlled warehouse. After installed to the location, ensure to maintain the room humidity less than 60% and temp above 5 deg C.\nRecord the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, provide the temporary HVAC to heat up the room to maintain the temp above 5 deg C. if the humidity exceeds 60% install de-humidifier."],
            ['code' => 'EP-04-04', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly."],
            ['code' => 'EP-04-04', 'description' => "Ensure the VCI Emitter (during construction) or Desiccant bags (during transportation & storage) is placed inside the compartments.\nDesiccant bags – 1kg / m3.\nChange VCI emitter every 24 weeks & or Desiccant bag every 12 weeks."],
            ['code' => 'EP-04-04', 'description' => "Ensure Anti-condensation heater is energized & function to be verified by indication lamp. If applicable."],
            ['code' => 'EP-04-04', 'description' => "Ensure grease is applied on exposed conductor or earth boss. (re-apply if required)."],
            ['code' => 'EP-04-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'EP-04-04', 'description' => "Ensure all hinges and fasteners are lubricated if required."],

            // EP-05-04
            ['code' => 'EP-05-04', 'description' => "Check the equipment is clean and free from debris. Clean if required"],
            ['code' => 'EP-05-04', 'description' => "Check no visual damage/ corrosion to equipment and coating.\nclean the rust & apply the preservative if required."],
            ['code' => 'EP-05-04', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities), dust cover is not damaged and properly sealed.\nRepair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'EP-05-04', 'description' => "Record the storage condition of the equipment Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, provide the temporary HVAC to heat up the room to maintain the temp above 5 deg C"],
            ['code' => 'EP-05-04', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly."],
            ['code' => 'EP-05-04', 'description' => "Indoor transformer shall not be stored outside. Outdoor transformer can be stored at any location but need to protect from rain and dust during storage & construction."],
            ['code' => 'EP-05-04', 'description' => "Anti-condensation heater function to be verified by indication lamp or voltmeter after measure the voltage, If applicable."],
            ['code' => 'EP-05-04', 'description' => "If conduit 'knock-outs' removed from the transformer, the opening shall be sealed using watertight plugs."],
            ['code' => 'EP-05-04', 'description' => "On arrival during RI remove all the desiccant bag & add VCI emitter inside the transformer & other EICT available equipment / panel. If the humidity goes higher & found condensation add desiccant bag.\nChange VCI emitter every 24 weeks & or Desiccant bag every 12 weeks."],
            ['code' => 'EP-05-04', 'description' => "Ensure grease is applied on exposed conductor or earth boss. (re-apply if required)."],
            ['code' => 'EP-05-04', 'description' => "Check transformer oil level, if applicable"],
            ['code' => 'EP-05-04', 'description' => "Check insulation resistance of winding and record result include motor if any\nRemove N-E link and perform winding insulation resistance test (Test Duration:1min): Use 5kV megger for MV & 1kV megger for LV Primary Winding - Earth:                     .\nSecondary Winding - Earth:                     . Primary Winding - Secondary Winding:"],
            ['code' => 'EP-05-04', 'description' => "If applicable, Check the heat exchanger are preserved with VCI or N2 blanketing (take photos & record the pressure). Recharge N2 if the pressure falls below 100 PSI."],
            ['code' => 'EP-05-04', 'description' => "Check machine flanges, exposed shaft, flange face, stud, bolts & nuts, earthing points, ID plates, foundation plate, AVM, holding down bolt & all uncoated areas are properly protected with wax-based coating"],
            ['code' => 'EP-05-04', 'description' => "Check all the nozzles & flange ends are sealed with painted blind flanges / galvanized plate with rubber gasket & spanner torqued with 4 bolts to keep them air tights.\nIf the nozzles are tied-in with pipes, ensure piping connections are properly bolted."],
            ['code' => 'EP-05-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],

            // EP-06-04
            ['code' => 'EP-06-04', 'description' => "Ensure the battery & UPS are free from debris & dust. Inspect the panel battery & repair corrosion if any, Maintain the battery free from moisture internal and external. If major issue, contact preservation supervisor."],
            ['code' => 'EP-06-04', 'description' => "Cover the battery & UPS are properly protected from construction debris. If dust cover is damage repair or replace with the new one."],
            ['code' => 'EP-06-04', 'description' => "On arrival check the battery for the shelf-life due date. Report to the preservation supervisor if due date is missing."],
            ['code' => 'EP-06-04', 'description' => "Store the battery & UPS panel @ -10 degC to 30 degC & humidity less than 90%. Avoid direct or indirect sunshine."],
            ['code' => 'EP-06-04', 'description' => "Check batteries are stored in correct orientation as per manufacturers recommendation."],
            ['code' => 'EP-06-04', 'description' => "Apply dielectric grease to exposed conductor or terminals. After cleaning the terminal with VpCI 239."],
            ['code' => 'EP-06-04', 'description' => "Ensure the VCI Emitter (during construction) or Desiccant bags (during transportation & storage) is placed inside the compartments.\nChange VCI emitter every 24 weeks & or Desiccant bag every 12 weeks."],
            ['code' => 'EP-06-04', 'description' => "Measure the open circuit voltage. If it approaches 2.22 v/cell. Start trickle charge."],
            ['code' => 'EP-06-04', 'description' => "Trickle charge shall be carried out after shelf life. Prior to trickle charge due date, carry out the batteries with trickle charger as per the manufacturer recommendation.\nTrickle charge due date:                 "],
            ['code' => 'EP-06-04', 'description' => "Ensure the space heater or ACH coil is always energized if applicable and verify the space heater functioning by measuring the voltage or connect the space heater line with LED bulb."],

            // EP-07-04
            ['code' => 'EP-07-04', 'description' => "Ensure the EICT equipment are free from debris & dust.\nMaintain the EICT equipment are free from moisture internal and external. If major issue contact preservation supervisor."],
            ['code' => 'EP-07-04', 'description' => "Store all the EICT equipment in the humidity-controlled warehouse.\nAvoid direct or indirect sunshine."],
            ['code' => 'EP-07-04', 'description' => "Record the storage condition of the equipment.\nTemp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, provide the temporary HVAC to heat up the room to maintain the temp above 5 deg C."],
            ['code' => 'EP-07-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],

            // EP-08-04
            ['code' => 'EP-08-04', 'description' => "Ensure the panels are free from debris & dust. Inspect the panel & repair corrosion if any, Maintain the panels free from moisture internal and external. If major issue, contact preservation supervisor."],
            ['code' => 'EP-08-04', 'description' => "Check no internal and external damage or rust to the JB & DB and related instruments, indication lights, coating & gland same to be protected from mechanical damage during construction."],
            ['code' => 'EP-08-04', 'description' => "Record the storage condition of the equipment Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, provide the temporary HVAC to heat up the room to maintain the temp above 5 deg C."],
            ['code' => 'EP-08-04', 'description' => "Ensure that the protection (exposed to open sunlight, rain and construction activities), dust cover is not damaged and properly sealed. Repair or replace as required."],
            ['code' => 'EP-08-04', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly."],
            ['code' => 'EP-08-04', 'description' => "Ensure grease is applied on exposed conductor or earth boss. (re-apply if required)."],
            ['code' => 'EP-08-04', 'description' => "Check all cable gland, plug, permanent data plates or labels are protected with clear coating."],
            ['code' => 'EP-08-04', 'description' => "Ensure the VCI Emitter (during construction) or Desiccant bags (during transportation & storage) is placed inside the compartments.\nDesiccant bags – 1kg / m3. Replace the emitter if required."],
            ['code' => 'EP-08-04', 'description' => "Check door seal / gaskets not damaged."],
            ['code' => 'EP-08-04', 'description' => "Ensure all hinges and fasteners are lubricated if required."],

            // EP-09-04
            ['code' => 'EP-09-04', 'description' => "Ensure the heaters are free from debris & dust. Inspect the panel & repair corrosion if any, Maintain the heaters free from moisture internal and external. If major issue, contact preservation supervisor."],
            ['code' => 'EP-09-04', 'description' => "Check no internal and external damage to the equipment and related instruments, indication lights, coating & same to be protected from mechanical damage during construction."],
            ['code' => 'EP-09-04', 'description' => "All heaters are protected from construction debris. If dust cover is damage repair or replace with the new one."],
            ['code' => 'EP-09-04', 'description' => "Anti-condensation heater function to be verified by indication lamp. If applicable."],
            ['code' => 'EP-09-04', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly. Whereas entry holes with glands installed shall be fitted with standard dust blinds."],
            ['code' => 'EP-09-04', 'description' => "Check insulation resistance of (Stage to Stage and Phase to Earth) with 500V handheld megger for 60 sec from the L1/L2/U/V/W to ground.\nReport to the preservation engineer if insulation is below 10 MΩ\nL1-E:              , L2-E:                   . L3-E:                   .\nTest Voltage: LV-1KV, MV-5K\nIf the insulation drops, replace the desiccant bags."],
            ['code' => 'EP-09-04', 'description' => "Ensure terminal box is covered with a VpCI FR stretch wrap."],
            ['code' => 'EP-09-04', 'description' => "For all electrical heaters, Inspect the resistance bundles and ensure the external surface is clean, dry, and free of damages/scratches. If major issue contact preservation engineer."],
            ['code' => 'EP-09-04', 'description' => "Check all the nozzles & flange ends are sealed with painted blind flanges with rubber gasket & spanner torqued with 4 bolts to keep them airtight.\nAfter pipe tie-in during inspection ensure all nozzles are isolated with blind plate or galvanized metal plate for positive isolation to preserve the vessel."],

            // EP-09-12
            ['code' => 'EP-09-12', 'description' => "Ensure wax based coating is applied on exposed conductor or earth boss & all uncoated surface includes bolt & nut. (re-apply if required)."],
            ['code' => 'EP-09-12', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'EP-09-12', 'description' => "Replace the existing silica gel with new about 3 kg / m3 of the size of the terminal box. Keep it away from ACH. Close and seal the TB immediately asap. Keep the terminal box was always closed."],
            ['code' => 'EP-09-12', 'description' => "Inspect the tank/vessel & repair corrosion, cleanliness, and moisture internal and external. If major issue, contact preservation engineer."],

            // EP-09-24
            ['code' => 'EP-09-24', 'description' => "For electrical heater apply VCI on the tank / vessel."],

            // IP-01-04
            ['code' => 'IP-01-04', 'description' => "Ensure the Instruments are free from debris & dust. Inspect the Instruments & repair corrosion if any, Maintain the Instruments free from moisture. If major issue contact preservation supervisor."],
            ['code' => 'IP-01-04', 'description' => "Check for any damage or rust to the instruments, indication lights, coating & gland same to be protected from mechanical damage during construction."],
            ['code' => 'IP-01-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'IP-01-04', 'description' => "Check instrument tubing and fittings are protected where applicable."],
            ['code' => 'IP-01-04', 'description' => "Ensure grease is applied on exposed conductor or earth boss. (re-apply if required)."],
            ['code' => 'IP-01-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'IP-01-04', 'description' => "Ensure all sealing gaskets, O-rings; stopping plugs etc are in good condition. Replace as required."],
            ['code' => 'IP-01-04', 'description' => "Check integrity of mounting / securing bolts/ clamps."],
            ['code' => 'IP-01-04', 'description' => "Check the termination / connection box internally for no internal damage and moisture. Ensure Instrument cover threads are clean and greased."],
            ['code' => 'IP-01-04', 'description' => "Ensure VCI emitter installed and change if expired."],
            ['code' => 'IP-01-04', 'description' => "Check all the instruments, transmitter, gauges unpainted surface is coated with wax-based coating & covered with plastic wrap after protected the glass panel from mechanical protection."],

            // IP-02-08
            ['code' => 'IP-02-08', 'description' => "Ensure the Instruments are free from debris & dust. Inspect the Instruments & repair corrosion if any, Maintain the Instruments free from moisture. If major issue contact preservation supervisor."],
            ['code' => 'IP-02-08', 'description' => "Check for any damage or rust to the instruments, indication lights, coating & gland same to be protected from mechanical damage during construction."],
            ['code' => 'IP-02-08', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'IP-02-08', 'description' => "Check instrument tubing and fittings are protected where applicable."],
            ['code' => 'IP-02-08', 'description' => "Ensure hydrogen gas detector head not installed."],
            ['code' => 'IP-02-08', 'description' => "Ensure gas detector test gas port cap is installed & intact."],
            ['code' => 'IP-02-08', 'description' => "Ensure wax-based coating is applied on exposed conductor or earth boss, uncoated surface. Reapply if required."],
            ['code' => 'IP-02-08', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'IP-02-08', 'description' => "Ensure all sealing gaskets, O-rings; stopping plugs etc are in good condition. Replace as required."],
            ['code' => 'IP-02-08', 'description' => "Check integrity of mounting / securing bolts/ clamps."],
            ['code' => 'IP-02-08', 'description' => "Check the termination / connection box internally for no internal damage and moisture. Ensure Instrument cover threads are clean and greased & installed VCI Emitter. Change or top up if required."],

            // IP-03-04
            ['code' => 'IP-03-04', 'description' => "All valves under storage are packed in the pallet or crate or covered with plastic film such as shrink wrap or stretch wrap – Don't open the box or packing for inspection only verify the packing integrity. If yes don't execute the other activity."],
            ['code' => 'IP-03-04', 'description' => "Check no visual damage/ corrosion to Instrument, tubing, fittings, accessories, and coating."],
            ['code' => 'IP-03-04', 'description' => "Ensure that the protection (exposed to open sunlight, rain and construction activities)/ dust cover is not damaged and properly sealed. Repair or replace as required."],
            ['code' => 'IP-03-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc… Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, ensure there is no water inside the system. Drain the water if any."],
            ['code' => 'IP-03-04', 'description' => "If valve in storage, check that all valve and related accessories ports are plugged or blinded, and flanges are blinded or protected & actuators must be maintained de-energized (with spring released, in case of spring return actuators)."],
            ['code' => 'IP-03-04', 'description' => "Check instrument tubing and fittings are protected where applicable."],
            ['code' => 'IP-03-04', 'description' => "Apply wax based wax-based coating on exposed machined surface and uncoated surface such as shafts, coupling hubs (flange, drive, sleeves and insert bush), flange face, base plate, stems & spindles, conductor or earth boss, Valve spindle rod and bolts & nuts. (re-apply if required)."],
            ['code' => 'IP-03-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'IP-03-04', 'description' => "Check the termination / connection box internally for no internal damage and moisture. Ensure Instrument cover threads are clean and greased. Insert Silica gel bag & VCI emitter."],
            ['code' => 'IP-03-04', 'description' => "Ensure all sealing gaskets, O-rings; stopping plugs etc are in good condition. Replace as required."],
            ['code' => 'IP-03-04', 'description' => "Check integrity of mounting / securing bolts/ clamps."],
            ['code' => 'IP-03-04', 'description' => "Ensure instrument air tubing is drained off collected condensate before the valve operation."],

            // IP-03-24
            ['code' => 'IP-03-24', 'description' => "Valve Actuation\nEnsure the pipe internals are cleaned before actuating the valve. No condensate water inside the instrument air tubing. Also, if required carefully clean the clearance between ball, seats, and body. After the cleaning of internals, the valve shall be fully operated at least 2 or 3 times. Then leave the ball partially open and restore the body cavity by spraying-in a rust inhibitor. Turn the ball to the fully open position and restore the end protection by installing the end caps."],
            ['code' => 'IP-03-24', 'description' => "Control cabinet, limit switch boxes and solenoid valves shall be sealed off for splash proof, but shall be accessible for preservation to install the VCI emitter to the"],

            // MP-01-04
            ['code' => 'MP-01-04', 'description' => "Ensure that the protection (exposed to open sunlight, rain and construction activities), dust cover is not damaged and properly sealed. Repair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'MP-01-04', 'description' => "Check no visual damage/ corrosion to equipment and coating.\nApply preservation to machined surfaces if necessary."],
            ['code' => 'MP-01-04', 'description' => "Check the equipment is clean and free from debris. Clean if required."],
            ['code' => 'MP-01-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'MP-01-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-01-04', 'description' => "Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C ensure there is no water inside the system. Drain the water if any."],
            ['code' => 'MP-01-04', 'description' => "If applicable, check levels of preservation oil and top up as required in bearing housing, oil cooler and lube oil system. Ensure the grease bearing or oil chamber are full, re-fill if required.\nIf oil chamber, bearing housing, oil cooler, LO system is empty spray the preservative (oil based VCI) as required.\nQuantity to be filled as per volume 2 lit / m3."],
            ['code' => 'MP-01-04', 'description' => "Pumps with roller bearings: Manually rotate shaft 2 ¼ turns and leave the shaft in new orientation."],
            ['code' => 'MP-01-04', 'description' => "Check that the grease lubricated bearings are correctly packed."],
            ['code' => 'MP-01-04', 'description' => "Check all the nozzles & flange ends are sealed with painted blind flanges or SS flange with rubber gasket & spanner torqued with 4 bolts to keep them air tights.\nIf the nozzles are tied-in with pipes, ensure piping connections are properly bolted."],
            ['code' => 'MP-01-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-01-04', 'description' => "Check that all dust blinds and sealing plugs are tight and that any valves used for isolating purposes are closed."],

            // MP-02-04
            ['code' => 'MP-02-04', 'description' => "Ensure that the protection (exposed to open sunlight, rain and construction activities), dust cover is not damaged and properly sealed. Repair or replace as required."],
            ['code' => 'MP-02-04', 'description' => "Check machine flanges and face are properly protected."],
            ['code' => 'MP-02-04', 'description' => "Check that all dust blinds and sealing plugs are tight and that any valves used for isolating purposes are closed."],

            // MP-02-12
            ['code' => 'MP-02-12', 'description' => "Check for no Internal /external damage / corrosion to equipment and other accessories and coating."],
            ['code' => 'MP-02-12', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-02-12', 'description' => "Check external surfaces and verify that preservation is in good condition. Re-apply preservation to machined surfaces if necessary."],
            ['code' => 'MP-02-12', 'description' => "Check there is no insulation damage."],
            ['code' => 'MP-02-12', 'description' => "Boiler Piping (VCI dosed): Check that all plugs, blank flanges are tight and that any valves used for isolating purposes are closed."],
            ['code' => 'MP-02-12', 'description' => "Boiler Piping (VCI dosed): If there are corrosion coupon installed, check corrosion coupon and ensure no/minor corrosion of coupons. Verify report and attach Photos."],
            ['code' => 'MP-02-12', 'description' => "De-aerator (VCI dosed): For VCI dosed vessel, check all flanges and nearby valves to ensure no VCI loss."],
            ['code' => 'MP-02-12', 'description' => "De-aerator (VCI dosed): Check corrosion coupon and ensure no/ minor corrosion of coupons. Verify report and attach photos."],
            ['code' => 'MP-02-12', 'description' => "Boiler Fire Side (VCI dosed): Check and ensure all openings of furnace is closed to avoid VCI loss and any air/ water ingress."],
            ['code' => 'MP-02-12', 'description' => "Boiler Fire Side (VCI dosed): Check and ensure funnel outlet is covered and intact."],
            ['code' => 'MP-02-12', 'description' => "Boiler Fire Side (VCI dosed): Check and ensure air duct is blanked and Intact."],
            ['code' => 'MP-02-12', 'description' => "Boiler Fire Side (VCI dosed): Check and ensure all furnace entry lines (valves/ flanges) are positively isolated."],
            ['code' => 'MP-02-12', 'description' => "Boiler Fire Side (VCI dosed): Inspect the fireside for evidence of active corrosion and, if found, take corrective measures."],
            ['code' => 'MP-02-12', 'description' => "Boiler Fire Side (VCI dosed): Check corrosion coupon and ensure no/ minor corrosion of coupons. Verify report and attach photos."],
            ['code' => 'MP-02-12', 'description' => "Boiler Water Side (VCI dosed): Check and ensure all openings of water/ steam drum and steam header is closed to avoid VCI loss and any air/ water ingress."],
            ['code' => 'MP-02-12', 'description' => "Boiler Water Side (VCI dosed): Check and ensure all connected valves are positively isolated."],
            ['code' => 'MP-02-12', 'description' => "Boiler Water Side (VCI dosed): Check corrosion coupon and ensure no/ minor corrosion of coupons. Verify report and attach photos."],

            // MP-03-04
            ['code' => 'MP-03-04', 'description' => "Ensure that the protection (exposed to open sunlight, rain and construction activities), dust cover is not damaged and properly sealed. Repair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'MP-03-04', 'description' => "Check no visual damage/ corrosion to equipment and coating.\nApply preservation to machined surfaces if necessary."],
            ['code' => 'MP-03-04', 'description' => "Check the equipment is clean and free from debris. Clean if required."],
            ['code' => 'MP-03-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-03-04', 'description' => "Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C ensure there is no water inside the system. Drain the water if any."],
            ['code' => 'MP-03-04', 'description' => "If applicable, check levels of preservation oil and top up as required in bearing housing, oil cooler and lube oil system."],
            ['code' => 'MP-03-04', 'description' => "Pumps with roller bearings: Manually rotate shaft 2 ¼ turns and leave the shaft in new orientation."],
            ['code' => 'MP-03-04', 'description' => "Check that the grease lubricated bearings are correctly packed."],
            ['code' => 'MP-03-04', 'description' => "Check all the nozzles & flange ends are sealed with painted blind flanges or SS flange with rubber gasket & spanner torqued with 4 bolts to keep them air tights.\nIf the nozzles are tied-in with pipes, ensure piping connections are properly bolted."],
            ['code' => 'MP-03-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-03-04', 'description' => "Check that all dust blinds and sealing plugs are tight and that any valves used for isolating purposes are closed."],
            ['code' => 'MP-03-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'MP-03-04', 'description' => "Perform crank on the turbine or wet the engine bearing with Brayco 599."],
            ['code' => 'MP-03-04', 'description' => "Ensure the HLO pumps and motors to be greased."],
            ['code' => 'MP-03-04', 'description' => "Check for hydraulic starting electric motor greasing & renew with oil based VCI if required.\nNote: NA for sealed bearing (lubricated for life)."],
            ['code' => 'MP-03-04', 'description' => "Check the inside condition of reduction gear casing from the peeping window and spray the rust preventive oil/ VpCI if necessary."],
            ['code' => 'MP-03-04', 'description' => "Check turbine exhaust side through borescope check for corrosion and water/ condensate."],
            ['code' => 'MP-03-04', 'description' => "Verify no water ingress and drains are not blocked."],
            ['code' => 'MP-03-04', 'description' => "Ensure priming LO pump is running.\nEnsure barring gear is running as applicable or manually turn turbine rotor minimum five turns."],
            ['code' => 'MP-03-04', 'description' => "Check operation of temporary dehumidifier/ dryer and carry out required maintenance on it.\nIf dry air blower is used, Max. 80 deg. C to be maintained."],
            ['code' => 'MP-03-04', 'description' => "Manually rotate shaft 2 ¼ turns and leave the shaft in new orientation."],
            ['code' => 'MP-03-04', 'description' => "On arrival Anti-condensation / space heater energised as per electrical drawing with 220 V & same function will be verified by indication lamp. If applicable.\nNote: Remove the plastic cover for the free flow of air. However, don't remove the protection cover."],
            ['code' => 'MP-03-04', 'description' => "Check insulation resistance of winding and record results as per procedure with DC handheld Meggar meter from L1, L2, L3 to ground.\nL1-E:              , L2-E:                   . L3-E:                  . (Test Voltage: LV-1KV, MV-5K).\nMinimum Acceptable Value: LV =10 MΩ, MV =150 MΩ."],

            // MP-04-04
            ['code' => 'MP-04-04', 'description' => "Check the equipment is clean and free from debris or any type of chemical near to the equipment. Clean if required"],
            ['code' => 'MP-04-04', 'description' => "Check no visual damage/ corrosion to equipment and related accessories and coating. Clean the rust & apply the preservative if required"],
            ['code' => 'MP-04-04', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities), dust cover is not damaged and properly sealed. Repair or replace as required."],
            ['code' => 'MP-04-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-04-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'MP-04-04', 'description' => "Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, ensure there is no water inside the system. Drain the water if any."],
            ['code' => 'MP-04-04', 'description' => "Ensure to inspect the engine external for as per task 1, 2, 3, 4, 5 off MP-05"],
            ['code' => 'MP-04-04', 'description' => "After installation ensure turbo charger exhaust gas pipe closed with positive isolation."],
            ['code' => 'MP-04-04', 'description' => "Ensure to apply the wax-based coating on the pinion behind the governor dial."],
            ['code' => 'MP-04-04', 'description' => "Ensure the speed governor valves are in closed position."],
            ['code' => 'MP-04-04', 'description' => "On arrival Anti-condensation / space heater energised as per electrical drawing with 220 V & same function will be verified by indication lamp. If applicable.\nNote: Remove the plastic cover for the free flow of air. However, don't remove the protection cover."],
            ['code' => 'MP-04-04', 'description' => "On arrival check the winding insulation resistance or using dielectric testing.\nNote: Ensure the AVR wires disconnected before measure the IR."],
            ['code' => 'MP-04-04', 'description' => "Radiator & fuel oil cooler are protected from physical damage. All nozzles opening shall be covered with hard gasket with painted blind flanges with hand torqued 4 bolts."],
            ['code' => 'MP-04-04', 'description' => "Stroke valve open/ close for 3 cycles, grease the moving/ less exposed parts. Please note care should be taken while opening the bleed valve for any oil leak.\nNote: Keep all valve in open position."],
            ['code' => 'MP-04-04', 'description' => "Operate the hand pump for 5 minutes by opening the accumulator (dump back to tank valves which are a black knob which is located on the left-hand side of the accumulator safety valves, this will prevent pressure entering the system.\nAfter this has been completed, please make sure the dump back to tank valves are left in the open position."],
            ['code' => 'MP-04-04', 'description' => "Electric motor must be turned using drive shaft. It should be turned in for both directions a minimum of 10 complete revolution."],

            // MP-04-12
            ['code' => 'MP-04-12', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-04-12', 'description' => "After installation ensure all the inlet & outlet nozzles flanges are isolated with positive isolation."],
            ['code' => 'MP-04-12', 'description' => "Check the panel for moisture and install the VCI emitter or desiccant bag as required."],
            ['code' => 'MP-04-12', 'description' => "Check door seal / gaskets not damaged & ensure the doors are sealed for no water or debris ingress."],
            ['code' => 'MP-04-12', 'description' => "Start LO priming pump, ensure about 0.5 bar line pressure is developed, turn the engine shaft, 2-1/4 revolution."],
            ['code' => 'MP-04-12', 'description' => "Check the panel for moisture and install the VCI emitter or desiccant bag as required."],
            ['code' => 'MP-04-12', 'description' => "Check door seal / gaskets not damaged & ensure the doors are sealed for no water or debris ingress."],
            ['code' => 'MP-04-12', 'description' => "During storage ensure the panel are stored in humidity-controlled warehouse. If happen to store on other warehouse, ensure proper protection with space heater is energised."],

            // MP-04-48
            ['code' => 'MP-04-48', 'description' => "Contact Engine manufacturer through PRE for long term internal preservation to be performed."],

            // MP-05-04
            ['code' => 'MP-05-04', 'description' => "Check the equipment is clean and free from debris. Clean if required."],
            ['code' => 'MP-05-04', 'description' => "Check no visual damage/ corrosion to equipment and related accessories and coating. Clean the rust & apply the preservative if required."],
            ['code' => 'MP-05-04', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities), dust cover is not damaged and properly sealed. Repair or replace as required."],
            ['code' => 'MP-05-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-05-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-05-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-05-04', 'description' => "Anti-condensation / space heater function to be verified by indication lamp. If applicable."],
            ['code' => 'MP-05-04', 'description' => "Check levels of preservation oil in gear box, LO system. Top up as required. If amount added appears excessive, check for leaks."],
            ['code' => 'MP-05-04', 'description' => "Check protection cover and preservation on open gears, wire, rope guide / sheaves, connecting / linkage and if necessary, apply grease."],
            ['code' => 'MP-05-04', 'description' => "Ensure slew ring is greased correctly and slew ring bolts are protected with plastic caps."],
            ['code' => 'MP-05-04', 'description' => "Manually rotate shaft 2 ¼ turns and leave the shaft in new orientation for all roller bearing rotating equipment.\nNote: check the adequate oil level before rotation for bearing with LO housing\nCheck for any abnormal noise while rotating the shaft."],
            ['code' => 'MP-05-04', 'description' => "Check operators' cabin for corrosion, painting damage and condensation."],
            ['code' => 'MP-05-04', 'description' => "Ensure the Jib crane & hooks are not deformed."],
            ['code' => 'MP-05-04', 'description' => "Ensure all the hydraulic cylinders are not stored under sunlight with a resulting temperature of more than 50 Deg. C."],
            ['code' => 'MP-05-04', 'description' => "During Storage, check that permanent seals are installed on each Cylinder Valve, to avoid an intentional opening of the cylinder Valves."],

            // MP-05-12
            ['code' => 'MP-05-12', 'description' => "Check the terminal box for moisture and install the VCI emitter or desiccant bag as required."],
            ['code' => 'MP-05-12', 'description' => "Check door seal / gaskets not damaged & ensure the doors are sealed for no water or debris ingress."],
            ['code' => 'MP-05-12', 'description' => "Check that all dust blinds and sealing plugs are tight and that any valves used for isolating purposes are closed."],
            ['code' => 'MP-05-12', 'description' => "Check all the instruments, transmitter, gauges unpainted surface is coated with wax-based coating & covered with plastic wrap after protected the glass panel from mechanical protection."],
            ['code' => 'MP-05-12', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-05-12', 'description' => "Ensure the jib is resting on the jib rest structure."],
            ['code' => 'MP-05-12', 'description' => "Hydraulic / Lube oil to be sampled for water content if applicable."],

            // MP-06-04
            ['code' => 'MP-06-04', 'description' => "Check the equipment is clean and free from debris or any type of chemical near to the equipment. Clean if required."],
            ['code' => 'MP-06-04', 'description' => "Check no visual damage/ corrosion to equipment and related accessories and coating. Clean the rust & apply the preservative if required."],
            ['code' => 'MP-06-04', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities), dust cover is not damaged and properly sealed. Repair or replace as required."],
            ['code' => 'MP-06-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'MP-06-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-06-04', 'description' => "Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, ensure there is no water inside the system. Drain the water if any."],
            ['code' => 'MP-06-04', 'description' => "Manually rotate shaft 2 ¼ turns and leave the shaft in new orientation."],
            ['code' => 'MP-06-04', 'description' => "Check that the grease lubricated bearings are correctly packed."],
            ['code' => 'MP-06-04', 'description' => "Check the condition of fan belts for damage or deterioration."],
            ['code' => 'MP-06-04', 'description' => "Check all the nozzles & flange ends are sealed with painted blind flanges or SS flange with rubber gasket & spanner torqued with 4 bolts to keep them air tights.\nIf the nozzles are tied-in with pipes, ensure piping connections are properly bolted."],
            ['code' => 'MP-06-04', 'description' => "Check that all dust blinds and sealing plugs are tight and that any valves used for isolating purposes are closed."],
            ['code' => 'MP-06-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-06-04', 'description' => "Check door seal / gaskets not damaged & ensure the doors are sealed for no water or debris ingress."],
            ['code' => 'MP-06-04', 'description' => "Anti-condensation heater or space heater function to be verified by indication lamp for control panel, heaters & electrical motor, if applicable.\nOn arrival ACH & space heater to be energized at warehouse and after installed the same need to be energized."],
            ['code' => 'MP-06-04', 'description' => "Check insulation resistance of winding and record results.\nL1-E:              , L2-E:                   . L3-E:                  . (Test Voltage: LV-1KV, MV-5K)."],

            // MP-07-04
            ['code' => 'MP-07-04', 'description' => "Check the equipment is clean and free from debris or any type of chemical near to the equipment. Clean if required."],
            ['code' => 'MP-07-04', 'description' => "Check no visual damage/ corrosion to equipment and related accessories and coating. Clean the rust & apply the preservative if required."],
            ['code' => 'MP-07-04', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities), dust cover is not damaged and properly sealed. Repair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'MP-07-04', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc…"],
            ['code' => 'MP-07-04', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-07-04', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-07-04', 'description' => "All the cable trays, cable ladder, SS tubing, CRA material shall be coated with VCI clear coating to protect carbon deposit."],
            ['code' => 'MP-07-04', 'description' => "Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, ensure there is no water inside the system. Drain the water if any."],
            ['code' => 'MP-07-04', 'description' => "Check all the nozzles & flange ends are sealed with painted blind flanges or SS flange with rubber gasket & spanner torqued with 4 bolts to keep them air tights.\nIf the nozzles are tied-in with pipes, ensure piping connections are properly bolted."],
            ['code' => 'MP-07-04', 'description' => "Check the condition of hydraulic tank breathers."],
            ['code' => 'MP-07-04', 'description' => "Check accumulators and nitrogen cylinders are correctly pressurised."],
            ['code' => 'MP-07-04', 'description' => "Ensure the hydraulic includes hydraulic tank, piping is preserved with oil based hydraulic VCI or water based hydraulic VCI.\nCheck for hydraulic oil leak if the hydraulic system was filled with oil.\nNote: Water based hydraulic oil applied for subsea system. All other system is preserved with oil based hydraulic system."],
            ['code' => 'MP-07-04', 'description' => "Ensure the gear box chamber, grease bearing, or oil chamber are full, re-fill if required.\nIf oil chamber, bearing housing, oil cooler is empty spray the preservative (oil based VCI) as required.\nQuantity to be filled as per volume 2 lit / m3."],
            ['code' => 'MP-07-04', 'description' => "Check for the Barrier fluid tank is filled with barrier fluid during storage & ensure there is no leakage during storage."],
            ['code' => 'MP-07-04', 'description' => "During Storage, check that permanent seals are installed on each Cylinder Valve, to avoid an intentional opening of the cylinder Valves."],
            ['code' => 'MP-07-04', 'description' => "Check that the grease lubricated bearings are correctly packed."],
            ['code' => 'MP-07-04', 'description' => "Manually rotate shaft 2 ¼ turns and leave the shaft in new orientation."],
            ['code' => 'MP-07-04', 'description' => "Energize the Anti-condensation heater / space heater, same need to be verified by indication lamp for all electrical motors & panel if applicable."],
            ['code' => 'MP-07-04', 'description' => "Check blind plugs are installed on all spare cable entries and sealed properly."],
            ['code' => 'MP-07-04', 'description' => "Ensure the cable entry holes and instrument connections are sealed with threaded plugs. Whereas entry holes with glands installed shall be fitted with standard dust blinds."],
            ['code' => 'MP-07-04', 'description' => "Ensure grease is applied on exposed conductor or earth boss. (re-apply if required)."],
            ['code' => 'MP-07-04', 'description' => "Open & check the terminal box, Panel, JB for moisture and add / replace / ensure the VCI emitter available during construction or desiccant bag during transportation and storage as required.\nChange VCI emitter every 24 weeks & Desiccant bag every 12 weeks."],
            ['code' => 'MP-07-04', 'description' => "Check door seal / gaskets not damaged & ensure the doors are sealed for no water or debris ingress."],
            ['code' => 'MP-07-04', 'description' => "Check insulation resistance of winding and record results.\nL1-E:              , L2-E:                   . L3-E:                  . (Test Voltage: LV-1KV, MV-5K).\nMinimum Acceptable Value: LV =10 MΩ, MV =150 MΩ."],
            ['code' => 'MP-07-04', 'description' => "For Ex motors, ensure there is not dent or damage or corrosion to the flame path of the equipment."],

            // MP-08-08
            ['code' => 'MP-08-08', 'description' => "Check the Dampers are clean and free from debris or any type of chemical near to the equipment and not subject to vibration. Clean if required."],
            ['code' => 'MP-08-08', 'description' => "Check no visual damage/ corrosion to equipment and related accessories and coating. Clean the rust & apply the wax-based coating on all machined surface & uncoated surface."],
            ['code' => 'MP-08-08', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities), dust cover is not damaged and properly sealed. Repair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'MP-08-08', 'description' => "Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, ensure there is no water inside the system. Drain the water if any."],
            ['code' => 'MP-08-08', 'description' => "Check and apply wax based coating on the exposed machined surfaces (include shafts, coupling hubs, flange face, base plate, stems & spindles), AVM, bolt & nut both PTFE coated & uncoated, holding down bolt, earthing cable, uncoated surface etc.,"],
            ['code' => 'MP-08-08', 'description' => "Check all permanent data plates or labels are protected with clear coating."],
            ['code' => 'MP-08-08', 'description' => "All the glass displays prone to damage due to mechanical activity such as transmitter, pressure gauge, control panel etc are protected with plywood or rubber or bubble wrap…"],
            ['code' => 'MP-08-08', 'description' => "Dampers with integrated with Junction box shall be stored in the dry environment by adding Silica gel & VpCI Emitter if required.\nReplace every 3 months"],
            ['code' => 'MP-08-08', 'description' => "Check operation of vane/ dampers handle."],
            ['code' => 'MP-08-08', 'description' => "Check dampers functionality blade by open / close Test pressure: Pneumatic air 5 bar\nIf pneumatic air will be supplied continuously, damper can be opened/closed by manually switching the solenoid valve without additional electrical signal.\nIn case of electrical dampers, functionality testing i.e. blades opening/closing can be checked while damper is energised.\nIf manual checks should be done, it should be done by a qualified personnel / approved engineer by manufacturer."],
            ['code' => 'MP-08-08', 'description' => "Check all the inlet & outlet ends are covered with painted blind flanges or SS flange to protect from dust until the duct installed to the inlet & outlet end."],
            ['code' => 'MP-08-08', 'description' => "Check the terminal box, DB, CP for moisture and change the VCI or desiccant if necessary."],

            // MP-09-12
            ['code' => 'MP-09-12', 'description' => "Check the equipment is clean and free from debris or any type of chemical near to the equipment. Clean if required."],
            ['code' => 'MP-09-12', 'description' => "Check no visual damage/ corrosion to equipment and related accessories and coating. Clean the rust & apply the wax-based coating on all machined surface & uncoated surface."],
            ['code' => 'MP-09-12', 'description' => "Ensure the protection (Exposed to open sunlight, rain, and Construction activities), dust cover is not damaged and properly sealed. Repair or replace as required.\nAlso check for any water contamination inside the packaging protection due to condensation or rainwater."],
            ['code' => 'MP-09-12', 'description' => "Record the storage condition of the equipment. Temp:                              Humidity:\nInform the preservation supervisor if the equipment storage location humidity more than 60% & temp less than 5 deg C.\nIf the temp falls below 5 deg C, ensure there is no water inside the system. Drain the water if any."],

            // *** DATA TRUNCATED - remaining codes need to be added ***
            // MP-10-04, MP-11-04, MP-11-12, MP-12-04, MP-12-12, MP-12-52,
            // MP-13-04, MP-14-04, TP-01-04, PP-01-01, PP-02-04,
            // SP-01-12, SP-02-04, SP-02-24, DP-01-04, DP-02-04,
            // DP-03-04, DP-03-12, DP-03-48, DP-04-04, DP-04-12
        ];

        $orderCounters = [];

        foreach ($items as $item) {
            $activity = Activity::where('code', $item['code'])->first();

            if (!$activity) {
                continue;
            }

            if (!isset($orderCounters[$item['code']])) {
                $orderCounters[$item['code']] = 1;
            }

            ActivityItem::create([
                'activity_id' => $activity->id,
                'description' => $item['description'],
                'order' => $orderCounters[$item['code']]++,
                'is_active' => true,
            ]);
        }
    }
}
