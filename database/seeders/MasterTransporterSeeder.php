<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTransporterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('tb_transporters')->exists()) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('tb_transporters')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $transporters = [
            [
                'transporter_id' => 'RICHLAND',
                'name' => 'RICHLAND',
                'address1' => 'BANDAR LAMPUNG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '2127883535',
                'fax' => '82211584657',
                'additional_information' => 'richland@gmail.com',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
            [
                'transporter_id' => 'KTA',
                'name' => 'Karyya Transport Abadi',
                'address1' => 'BANDAR LAMPUNG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '873232675',
                'fax' => '873232675',
                'additional_information' => 'karyatransportabadi@gmail.com',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
            [
                'transporter_id' => 'OJS',
                'name' => 'Ogan Jaya Sakti',
                'address1' => 'BANDAR LAMPUNG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '12345',
                'fax' => '12345',
                'additional_information' => 'oganjayasakti@gmail.com',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
            [
                'transporter_id' => 'SBA',
                'name' => 'Sinar Berkat Abadi',
                'address1' => 'BANDAR LAMPUNG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '81369213588',
                'fax' => '81369213588',
                'additional_information' => 'tunassuryabumindo@yahoo.co.id',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
            [
                'transporter_id' => 'SKB',
                'name' => 'Sumber Karya Berkah',
                'address1' => 'BANDAR LAMPUNG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '81369588848',
                'fax' => '81369588848',
                'additional_information' => 'deaprayoga@pt-skb.com',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
            [
                'transporter_id' => 'MAP',
                'name' => 'Mapersada',
                'address1' => 'BANDAR LAMPUNG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '8156613364',
                'fax' => '8156613364',
                'additional_information' => 'mapersada@gmail.com',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
            [
                'transporter_id' => 'BLZ',
                'name' => 'Buleza',
                'address1' => 'BANDAR LAMPUNG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '12345',
                'fax' => '12345',
                'additional_information' => 'bulezatk@gmail.com',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
            [
                'transporter_id' => 'SS',
                'name' => 'SIBA SURYA',
                'address1' => 'SEMARANG',
                'city' => 'INDONESIA',
                'postal_code' => '1',
                'phone' => '81393435254',
                'fax' => null,
                'additional_information' => 'surya.palembang@sibagroup.net',
                'deleted' => 0,
                'id_company' => 1,
                'created_by' => 1,
                'transport_mode' => 1,
                'payment_term' => 30,
            ],
        ];

        DB::table('tb_transporters')->insert($transporters);
    }
}
