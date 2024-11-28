<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MasterDriver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function GetData (Request $request) {

        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_company'      => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $data = MasterDriver::select('tb_drivers.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company',
                                        'tb_transporters.transporter_id as transporter_name')
                    ->leftJoin('user as user', 'tb_drivers.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_drivers.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_drivers.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_transporters as tb_transporters', 'tb_drivers.transporter_id', '=', 'tb_transporters.id')
                    ->where('tb_drivers.id_company', $id_company)
                    ->where('tb_drivers.deleted', '0')
                    ->get();

            $respon = array(
                "code" => "01",
                "data" => $data
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function Search (Request $request) {

        $id_company = $request->id_company;
        $search = $request->search;

        $validator = Validator::make($request->all(), [
            'id_company'      => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $data = MasterDriver::select('tb_drivers.id', 'tb_drivers.name')
                    ->where('tb_drivers.id_company', $id_company)
                    ->where('tb_drivers.deleted', '0')
                    ->whereRaw("tb_drivers.name like '%${search}%'")
                    ->limit(10)
                    ->get();

            $respon = array(
                "code" => "01",
                "data" => $data
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function GetDataById(Request $request) {

        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'id'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = MasterDriver::select('tb_drivers.*', 'tb_company.name as name_company', 'tb_transporters.transporter_id as transporter_name')
                    ->leftJoin('tb_company as tb_company', 'tb_drivers.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_transporters as tb_transporters', 'tb_drivers.transporter_id', '=', 'tb_transporters.id')
                    ->where('tb_drivers.id', $id)
                    ->where('tb_drivers.deleted', 0)
                    ->first();
            if (!$data) {
                $respon = array(
                    "code" => "02",
                    "message" =>  'Data tidak ditemukan !',
                );
            } else {
                $respon = array(
                    "code" => "01",
                    "data" => $data
                );
            }
        }

        return response()->json($respon);
    }

    public function Create (Request $request) {
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $phone = $request->phone;
        $transporter_id = $request->transporter_id;
        $created_by = $request->created_by;
        $id_company = $request->id_company;
        $sim_file = '';
        $ktp_file = '';
        $name_rsp = Str::replace(' ', '-', $name);

        if ($request->file('sim_file')) {
            $size = floor($request->file('sim_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('sim_file')->getClientOriginalExtension();
                $sim_file = 'sim-'.$name_rsp.'-'.$date_now . '.' . $ext;
                $request->file('sim_file')->move(env("PATH_IMAGE_DATA_DRIVER"), $sim_file);
            }
        }

        if ($request->file('ktp_file')) {
            $size = floor($request->file('ktp_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('ktp_file')->getClientOriginalExtension();
                $ktp_file = 'ktp-'.$name_rsp.'-'.$date_now . '.' . $ext;
                $request->file('ktp_file')->move(env("PATH_IMAGE_DATA_DRIVER"), $ktp_file);
            }
        }
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'phone'         => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (MasterDriver::where('phone', $phone)->where('deleted', 0)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Nomor Telpon tidak boleh sama",
                );
            } else {
                try
                {
                    $create = new MasterDriver;
                    $create->name = $name;
                    $create->address1 = $address1;
                    $create->address2 = $address2;
                    $create->city = $city;
                    $create->phone = $phone;
                    $create->transporter_id = $transporter_id;
                    $create->created_by = $created_by;
                    $create->id_company = $id_company;
                    $create->foto_sim = $sim_file;
                    $create->foto_ktp = $ktp_file;
                    $create->license_exp_date = $request->license_exp_date;
                    $create->no_sim = $request->no_sim;
                    $create->no_ktp = $request->no_ktp;


                    $create->save();

                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menyimpan data",
                    );
                }
                catch(Exception $e)
                {
                    $respon = array(
                        "code" => "03",
                        "message" =>  "Ada masalah dengan server, harap coba lagi nanti !",
                        "error_message" =>  $e,
                    );
                }
            }
        }

        return response()->json($respon);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $phone = $request->phone;
        $transporter_id = $request->transporter_id;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;
        $sim_file = '';
        $ktp_file = '';
        $name_rsp = Str::replace(' ', '-', $name);
        $change_sim = false;
        if ($request->file('sim_file')) {
            $size = floor($request->file('sim_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('sim_file')->getClientOriginalExtension();
                $sim_file = 'sim-'.$name_rsp.'-'.$date_now . '.' . $ext;
                $request->file('sim_file')->move(env("PATH_IMAGE_DATA_DRIVER"), $sim_file);
                $change_sim = true;
            }
        }
        $change_ktp = false;
        if ($request->file('ktp_file')) {
            $size = floor($request->file('ktp_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('ktp_file')->getClientOriginalExtension();
                $ktp_file = 'ktp-'.$name_rsp.'-'.$date_now . '.' . $ext;
                $request->file('ktp_file')->move(env("PATH_IMAGE_DATA_DRIVER"), $ktp_file);
                $change_ktp = true;
            }
        }

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'name'          => 'required',
            'phone'         => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (MasterDriver::where('phone', $phone)->where('id','!=', $id)
                ->where('deleted', 0)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Nomor Telpon tidak boleh sama",
                );
            } else {
                try
                {
                    $update = MasterDriver::find($id);
                    $update->name = $name;
                    $update->address1 = $address1;
                    $update->address2 = $address2;
                    $update->city = $city;
                    $update->phone = $phone;
                    $update->transporter_id = $transporter_id;
                    $update->updated_by = $updated_by;
                    $update->id_company = $id_company;
                    if($change_sim){
                        $update->foto_sim = $sim_file;
                    }
                    if($change_ktp){
                        $update->foto_ktp = $ktp_file;
                    }

                    $update->license_exp_date = $request->license_exp_date;
                    $update->no_sim = $request->no_sim;
                    $update->no_ktp = $request->no_ktp;

                    $update->save();

                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menyimpan data",
                    );
                }
                catch(Exception $e)
                {
                    $respon = array(
                        "code" => "03",
                        "message" =>  "Ada masalah dengan server, harap coba lagi nanti !",
                        "error_message" =>  $e,
                    );
                }
            }
        }

        return response()->json($respon);
    }

    public function Delete (Request $request) {
        $id = $request->id;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'              => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try
            {
                $update = MasterDriver::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();

                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil menghapus data",
                );
            }
            catch(Exception $e)
            {
                $respon = array(
                    "code" => "03",
                    "message" =>  "Ada masalah dengan server, harap coba lagi nanti !",
                    "error_message" =>  $e,
                );
            }
        }

        return response()->json($respon);
    }
}
