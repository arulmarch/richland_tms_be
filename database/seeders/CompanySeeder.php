<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_company')->insert([
            'name' => 'PT. Richland Logistics Indonesia',
            'address' => 'Satrio Tower Building 25th floor JL. Prof. Dr. Satrio, Jl. Mega Kuningan Barat No.Kav 1-4 Blok C-4, RT.7/RW.2, Kuningan, East Kuningan, Setiabudi, South Jakarta City, Jakarta 12950',
            'no_telp' => '(+62)3906319',
            'email' => 'support@richlandlogistik.com',
        ]);
    }
}
