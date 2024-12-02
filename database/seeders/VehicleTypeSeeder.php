<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('tb_vehicle_types')->exists()) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('tb_vehicle_types')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $vehicleTypes = [
            ['volume_cap' => 36, 'weight_cap' => 50000, 'type_id' => 'PBR-36', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 20, 'weight_cap' => 20000, 'type_id' => 'PPJ-20', 'description' => 'RLI ENGKEL LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 20, 'weight_cap' => 20000, 'type_id' => 'PBR-20', 'description' => 'RLI ENGKEL LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 50, 'weight_cap' => 50000, 'type_id' => 'PPJ-50', 'description' => 'TRAILER', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 50, 'weight_cap' => 50000, 'type_id' => 'PBR-50', 'description' => 'TRAILER', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 30, 'weight_cap' => 30000, 'type_id' => 'TJ-30', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 32, 'weight_cap' => 32000, 'type_id' => 'PBR-32', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 10, 'weight_cap' => 10000, 'type_id' => 'PPJ-10', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 30, 'weight_cap' => 30000, 'type_id' => 'PPJ-30', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 30, 'weight_cap' => 30000, 'type_id' => 'PBR-30', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 10, 'weight_cap' => 10000, 'type_id' => 'PPJ-10', 'description' => 'RLI ENGKEL LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 35, 'weight_cap' => 35000, 'type_id' => 'PPJ-35', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 10, 'weight_cap' => 10000, 'type_id' => 'BKJ-10', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 20, 'weight_cap' => 20000, 'type_id' => 'PPJ-20', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 50, 'weight_cap' => 50000, 'type_id' => 'RLI-50', 'description' => 'TRAILER', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 35, 'weight_cap' => 35000, 'type_id' => 'PBR-35', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 50000, 'weight_cap' => 50000, 'type_id' => 'PBR-50000', 'description' => 'TRAILER', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 32, 'weight_cap' => 32000, 'type_id' => 'PPJ-32', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 50, 'weight_cap' => 50000, 'type_id' => 'PBR-50', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 32, 'weight_cap' => 32000, 'type_id' => '32000-32', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 21.25, 'weight_cap' => 21250, 'type_id' => 'PPJ-21.25', 'description' => 'RLI ENGKEL LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 44, 'weight_cap' => 44000, 'type_id' => 'PBR-44', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 45, 'weight_cap' => 45000, 'type_id' => 'PBR-45', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 50, 'weight_cap' => 50000, 'type_id' => 'KTA-50', 'description' => 'TRAILER', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 35, 'weight_cap' => 35000, 'type_id' => 'PBR-35', 'description' => 'RLI ENGKEL LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 48, 'weight_cap' => 48000, 'type_id' => 'PBR-48', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 9.05, 'weight_cap' => 9500, 'type_id' => 'PPJ-45421', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 50, 'weight_cap' => 50000, 'type_id' => 'PBR-50', 'description' => 'RLI ENGKEL LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 10, 'weight_cap' => 10000, 'type_id' => 'PBR-10', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 12, 'weight_cap' => 12000, 'type_id' => 'PBR-12', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 12, 'weight_cap' => 12000, 'type_id' => 'PPJ-12', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 13, 'weight_cap' => 2600, 'type_id' => 'PBR-13', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 20, 'weight_cap' => 20000, 'type_id' => 'PBR-20', 'description' => 'RLI CDD LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 36, 'weight_cap' => 36000, 'type_id' => 'OJS-36', 'description' => 'RLI TRONTON LOS BAK', 'id_company' => 1, 'created_by' => 1],
            ['volume_cap' => 60, 'weight_cap' => 20000, 'type_id' => 'PBR-60', 'description' => 'TRAILER', 'id_company' => 1, 'created_by' => 1],
        ];

        DB::table('tb_vehicle_types')->insert($vehicleTypes);
    }
}
