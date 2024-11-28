<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_drivers', function (Blueprint $table) {
            $table->string('no_sim')->nullable()->after('foto_sim');
            $table->date('license_exp_date')->nullable()->after('no_sim');
            $table->string('no_nik')->nullable()->after('foto_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_drivers', function (Blueprint $table) {
            $table->dropColumn('no_sim');
            $table->dropColumn('license_exp_date');
            $table->dropColumn('no_nik');
        });
    }
};
