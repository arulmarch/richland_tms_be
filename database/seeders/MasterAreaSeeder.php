<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('tb_areas')->exists()) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('tb_areas')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }


        DB::table('tb_areas')->insert([
            ['area_id' => 'KALIREJO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'PESISIR TENGAH', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'GADING REJO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PRINGSEWU', 'id_company' => 1],
            ['area_id' => 'KOTABUMI SELATAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG UTARA', 'id_company' => 1],
            ['area_id' => 'MUARA ENIM', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB MUARA ENIM', 'id_company' => 1],
            ['area_id' => 'SIMPANG PEMATANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB MESUJI', 'id_company' => 1],
            ['area_id' => 'PARDASUKA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PRINGSEWU', 'id_company' => 1],
            ['area_id' => 'PENENGAHAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'WAY KHILAU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'PASAWARAN', 'id_company' => 1],
            ['area_id' => 'PESISIR UTARA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'PRINGSEWU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PRINGSEWU', 'id_company' => 1],
            ['area_id' => 'REBANG TANGKAS', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB WAY KANAN', 'id_company' => 1],
            ['area_id' => 'SEKINCAU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'TANJUNG KARANG BARAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'SUKARAME', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'GEDONG TATAAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'PASAWARAN', 'id_company' => 1],
            ['area_id' => '', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'TELUKBETUNG TIMUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'JATI AGUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'LUMBOK SEMINUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'KOTA JAMBI', 'description' => 'JAMBI', 'area_type' => 1, 'additional_information' => 'KOTA JAMBI', 'id_company' => 1],
            ['area_id' => 'KAB LAMPUNG SELATAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'LABUHAN MARINGGAI', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'TERBANGGI BESAR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'PASIR SAKTI', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'TULANG BAWANG TENGAH', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'SUKOHARJO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PRINGSEWU', 'id_company' => 1],
            ['area_id' => 'KETAPANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'BALIK BUKIT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'KAB TLG BWG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'PENUKAL', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB PENUKAL ABAB LEMATANG ILIR', 'id_company' => 1],
            ['area_id' => 'SUMBEREJO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'WONOSOBO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'RAWAJITU SELATAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'BRAJA SLEBAH', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'KOTA AGUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'BANJAR AGUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'BANJAR MARGO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'MEGANG SAKTI', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB MUSI RAWAS', 'id_company' => 1],
            ['area_id' => 'TELUKBETUNG BARAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'KERTAPATI', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA PALEMBANG', 'id_company' => 1],
            ['area_id' => 'PANJANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'NGAMBUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'BELALAU', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB LUBUK LINGGAU SELANTAN', 'id_company' => 1],
            ['area_id' => 'AMBARAWA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PRINGSEWU', 'id_company' => 1],
            ['area_id' => 'WAY KRUI', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'TANJUNG KARANG TIMUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'TULANG BAWANG TENGAH', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG BARAT', 'id_company' => 1],
            ['area_id' => 'KAB WAY KANAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB WAY KANAN', 'id_company' => 1],
            ['area_id' => 'KOTA LUBUK LINGGAU', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA LUBUK LINGGAU', 'id_company' => 1],
            ['area_id' => 'KALIANDA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'KAB LAMPUNG TENGAH', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'LAWANG KIDUL', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB MUARA ENIM', 'id_company' => 1],
            ['area_id' => 'TALANG UBI', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB MUARA ENIM', 'id_company' => 1],
            ['area_id' => 'ABAB', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB PENUKAL ABAB LEMATANG ILIR', 'id_company' => 1],
            ['area_id' => 'KEMILING', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'BENGKUNAT BELIMBING', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'SUKAU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'BANJAR BARU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'KATIBUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'WONOSOBO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PRINGSEWU', 'id_company' => 1],
            ['area_id' => 'BANJIT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB WAY KANAN', 'id_company' => 1],
            ['area_id' => 'SEMAKA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'KEDATON', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'SUKABUMI', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'SUMBER AGUNG', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'LUBUK LINGGAU UTARA I', 'id_company' => 1],
            ['area_id' => 'TABA PINGGIN', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB LUBUK LINGGAU SELANTAN', 'id_company' => 1],
            ['area_id' => 'KOTA BANDAR LAMPUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'KAB LAMPUNG UTARA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG UTARA', 'id_company' => 1],
            ['area_id' => 'KAB LAMPUNG BARAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'BUMI AGUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB WAY KANAN', 'id_company' => 1],
            ['area_id' => 'KECAMATAN SUKARAMI', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA PALEMBANG', 'id_company' => 1],
            ['area_id' => 'SAKO', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA PALEMBANG', 'id_company' => 1],
            ['area_id' => 'MENGGALA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'KEDONDONG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'PASAWARAN', 'id_company' => 1],
            ['area_id' => 'PAGELARAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PRINGSEWU', 'id_company' => 1],
            ['area_id' => 'BANDAR SRIBAWONO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'SUKADANA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'KOTA METRO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA METRO', 'id_company' => 1],
            ['area_id' => 'METRO TIMUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'METRO TIMUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA METRO', 'id_company' => 1],
            ['area_id' => 'METRO UTARA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA METRO', 'id_company' => 1],
            ['area_id' => 'RUMBIA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'GEDUNG AJI', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'BARADATU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB WAY KANAN', 'id_company' => 1],
            ['area_id' => 'TELUKBETUNG SELATAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'LUBUK LINGGAU TIMUR I', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA LUBUK LINGGAU', 'id_company' => 1],
            ['area_id' => 'NATAR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'TABA PINGGIN', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => '', 'id_company' => 1],
            ['area_id' => 'MUARO JAMBI', 'description' => 'JAMBI', 'area_type' => 1, 'additional_information' => 'KOTA JAMBI', 'id_company' => 1],
            ['area_id' => 'GISTING', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'TALANG PADANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'SUNGKAI UTARA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG UTARA', 'id_company' => 1],
            ['area_id' => 'KECAMATAN SUKARAMI', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'BENGKUNAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'LEMONG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'NGAMBUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'KAB TANGGAMUS', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'KARANG JAYA', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA PALEMBANG', 'id_company' => 1],
            ['area_id' => 'WAY SULAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'KECAMATAN KALIDONI', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA PALEMBANG', 'id_company' => 1],
            ['area_id' => 'KOTA PALEMBANG', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KOTA PALEMBANG', 'id_company' => 1],
            ['area_id' => 'JAMBI SELATAN', 'description' => 'JAMBI', 'area_type' => 1, 'additional_information' => 'KOTA JAMBI', 'id_company' => 1],
            ['area_id' => 'RAJABASA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'WAY GELANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'PASAWARAN', 'id_company' => 1],
            ['area_id' => 'TANJUNG SENANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'WAY HALIM', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'BATU BRAK', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'KOTA AGUNG TIMUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'SEPUTIH SURABAYA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'PURBOLINGGO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'WAWAY KARYA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'SEPUTIH MATARAM', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'BANDAR SURABAYA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'BALIK BUKIT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'LIWA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'JABUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'PANCA JAYA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB MESUJI', 'id_company' => 1],
            ['area_id' => 'AIR NANINGAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'MATARAM BARU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'WAY GELANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB MESUJI', 'id_company' => 1],
            ['area_id' => 'SIDOMULYO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'PUGUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'PUBIAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TENGAH', 'id_company' => 1],
            ['area_id' => 'BENGKUNAT BELIMBING', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'WAY BUNGUR (PURBOLINGGO UTARA)', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'MESUJI TIMUR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB MESUJI', 'id_company' => 1],
            ['area_id' => 'TUMIJAJAR', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG BARAT', 'id_company' => 1],
            ['area_id' => 'BENGKUNAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'BANJAR BARU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG BARAT', 'id_company' => 1],
            ['area_id' => 'TANJUNG KARANG PUSAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'TALANG UBI', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB PENUKAL ABAB LEMATANG ILIR', 'id_company' => 1],
            ['area_id' => 'MARGA SEKAMPUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'GUNUNG MEGANG', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB MUARA ENIM', 'id_company' => 1],
            ['area_id' => 'LUMBOK SEMINUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'KEDATON', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'LABUHAN RATU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'WAY TUBA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB WAY KANAN', 'id_company' => 1],
            ['area_id' => 'RAJABASA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'KRUI SELATAN', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'LABUHAN RATU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'BATURAJA', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'OGAN KOMERING ULU', 'id_company' => 1],
            ['area_id' => 'KARANG JAYA', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB MUSI RAWAS', 'id_company' => 1],
            ['area_id' => 'CANDIPURO', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'BELALAU', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG BARAT', 'id_company' => 1],
            ['area_id' => 'KAB MESUJI', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB MESUJI', 'id_company' => 1],
            ['area_id' => 'PALAS', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'KAB PESISIR BARAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'WAWAY KARYA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG SELATAN', 'id_company' => 1],
            ['area_id' => 'GUNUNG MEGANG', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB PENUKAL ABAB LEMATANG ILIR', 'id_company' => 1],
            ['area_id' => 'TALANG UBI', 'description' => 'SUMATERA SELATAN', 'area_type' => 1, 'additional_information' => 'KAB MUSI RAWAS', 'id_company' => 1],
            ['area_id' => 'KARYA PENGGAWA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'GUNUNG TERANG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG BARAT', 'id_company' => 1],
            ['area_id' => 'PENAWAR TAMA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TLG BWG', 'id_company' => 1],
            ['area_id' => 'SUKARAME', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB PESISIR BARAT', 'id_company' => 1],
            ['area_id' => 'WAY BUNGUR (PURBOLINGGO UTARA)', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB LAMPUNG TIMUR', 'id_company' => 1],
            ['area_id' => 'AMBARAWA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'PULAU PANGGUNG', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
            ['area_id' => 'TANJUNG RAYA', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KOTA BANDAR LAMPUNG', 'id_company' => 1],
            ['area_id' => 'KELUMBAYAN BARAT', 'description' => 'LAMPUNG', 'area_type' => 1, 'additional_information' => 'KAB TANGGAMUS', 'id_company' => 1],
        ]);
    }
}