<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\VehicleTypeSeeder;
use Database\Seeders\MasterTransporterSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CompanySeeder::class);
        $this->call(VehicleTypeSeeder::class);
        $this->call(MasterTransporterSeeder::class);
        $this->call(MasterDriverSeeder::class);
        $this->call(MasterAreaSeeder::class);
        $this->call(VehiclesSeeder::class);
    }
}
