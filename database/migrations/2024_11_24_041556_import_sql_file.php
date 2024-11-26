<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        $sqlPath = database_path('db_tms_empty_24112024.sql');
        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            DB::unprepared($sql);
        } else {
            throw new \Exception("SQL file not found: $sqlPath");
        }
    }

    public function down(): void
    {
        // Tambahkan logika rollback jika memungkinkan, misalnya:
        DB::table('user')->truncate();  // atau DROP TABLE jika tabel spesifik.
        DB::table('ci_sessions')->truncate();
        DB::table('currency')->truncate();
        DB::table('tb_accident_type')->truncate();
        DB::table('tb_areas')->truncate();
        DB::table('tb_banner')->truncate();
        DB::table('tb_clients')->truncate();
        DB::table('tb_client_rates')->truncate();
        DB::table('tb_company')->truncate();
        DB::table('tb_component')->truncate();
        DB::table('tb_component_entries')->truncate();
        DB::table('tb_customers')->truncate();
        DB::table('tb_dedicated_rate')->truncate();
        DB::table('tb_drivers')->truncate();
        DB::table('tb_history_change_load')->truncate();
        DB::table('tb_manifests')->truncate();
        DB::table('tb_manifest_status')->truncate();
        DB::table('tb_mechanics')->truncate();
        DB::table('tb_mobile_apps_version')->truncate();
        DB::table('tb_notification')->truncate();
        DB::table('tb_pod')->truncate();
        DB::table('tb_pod_code')->truncate();
        DB::table('tb_pt_owned')->truncate();
        DB::table('tb_purchase_invoice')->truncate();
        DB::table('tb_ring_code')->truncate();
        DB::table('tb_sales_invoice')->truncate();
        DB::table('tb_service_order')->truncate();
        DB::table('tb_service_order_status')->truncate();
        DB::table('tb_service_order_type')->truncate();
        DB::table('tb_service_tasks')->truncate();
        DB::table('tb_service_task_entries')->truncate();
        DB::table('tb_setting')->truncate();
        DB::table('tb_status_traffic_monitoring')->truncate();
        DB::table('tb_tracking_driver')->truncate();
        DB::table('tb_traffic_monitoring')->truncate();
        DB::table('tb_transporters')->truncate();
        DB::table('tb_transporter_rates')->truncate();
        DB::table('tb_transport_mode')->truncate();
        DB::table('tb_transport_order')->truncate();
        DB::table('tb_trucking_order')->truncate();
        DB::table('tb_truck_accident')->truncate();
        DB::table('tb_type_taxable')->truncate();
        DB::table('tb_uom')->truncate();
        DB::table('tb_vehicles')->truncate();
        DB::table('tb_vehicle_types')->truncate();
        DB::table('tb_vendors')->truncate();
        DB::table('user')->truncate();
        DB::table('user_access_menu')->truncate();
        DB::table('user_access_menu_item')->truncate();
        DB::table('user_access_sub_menu')->truncate();
        DB::table('user_menu')->truncate();
        DB::table('user_menu_item')->truncate();
        DB::table('user_role')->truncate();
        DB::table('user_sub_menu')->truncate();
        // Kosongkan jika rollback tidak diperlukan.
    }
};
