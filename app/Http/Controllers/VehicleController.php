<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use App\Exports\VehicleExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class VehicleController extends Controller
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
        
            $data = Vehicle::select('tb_vehicles.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company', 
                                        'tb_drivers.name as driver_name', 'co_driver.name as co_driver_name', 'tb_vehicle_types.type_id as type_name', 
                                        'tb_transporters.transporter_id as transporter_name')
                    ->selectRaw('(case status when 1 then "ON CALL" else "DEDICATED" end) as vehicle_status_name')
                    ->leftJoin('user as user', 'tb_vehicles.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_vehicles.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_vehicles.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_drivers as tb_drivers', 'tb_vehicles.driver', '=', 'tb_drivers.id')
                    ->leftJoin('tb_drivers as co_driver', 'tb_vehicles.co_driver', '=', 'co_driver.id')
                    ->leftJoin('tb_vehicle_types as tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                    ->leftJoin('tb_transporters as tb_transporters', 'tb_vehicles.transporter_id', '=', 'tb_transporters.id')
                    ->where('tb_vehicles.id_company', $id_company)
                    ->where('tb_vehicles.deleted', '0')
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
            $data = Vehicle::select('tb_vehicles.*', 'tb_company.name as name_company', 'tb_drivers.name as driver_name', 'co_driver.name as co_driver_name',
                                    'tb_vehicle_types.type_id as type_name', 'tb_transporters.transporter_id as transporter_name', 'subcon.transporter_id as subcon_name')
                    ->selectRaw('(case status when 1 then "ON CALL" else "DEDICATED" end) as vehicle_status_name')
                    ->leftJoin('tb_company as tb_company', 'tb_vehicles.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_drivers as tb_drivers', 'tb_vehicles.driver', '=', 'tb_drivers.id')
                    ->leftJoin('tb_drivers as co_driver', 'tb_vehicles.co_driver', '=', 'co_driver.id')
                    ->leftJoin('tb_vehicle_types as tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                    ->leftJoin('tb_transporters as tb_transporters', 'tb_vehicles.transporter_id', '=', 'tb_transporters.id')
                    ->leftJoin('tb_transporters as subcon', 'tb_vehicles.subcon', '=', 'subcon.id')
                    ->where('tb_vehicles.id', $id)
                    ->where('tb_vehicles.deleted', 0)
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
        $vehicle_id = $request->vehicle_id;
        $driver = $request->driver;
        $co_driver = $request->co_driver;
        $transporter_id = $request->transporter_id;
        $status = $request->status;
        $type = $request->type;
        $max_volume = $request->max_volume;
        $max_weight = $request->max_weight;
        $subcon = $request->subcon;
        $additional_information = $request->additional_information;
        $created_by = $request->created_by;
        $id_company = $request->id_company;
        $no_lambung = $request->no_lambung;
        $no_stnk = $request->no_stnk;
        $no_kir = $request->no_kir;
        $tgl_aktif_stnk = $request->tgl_aktif_stnk;
        $tgl_aktif_kir = $request->tgl_aktif_kir;
        $stnk_file = '';
        $kir_file = '';
        $vehicle_id_rsp = Str::replace(' ', '-', $vehicle_id);

        if ($request->file('stnk_file')) {
            $size = floor($request->file('stnk_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('stnk_file')->getClientOriginalExtension();
                $stnk_file = 'stnk-'.$vehicle_id_rsp.'-'.$date_now . '.' . $ext;
                $request->file('stnk_file')->move(env("PATH_IMAGE_DATA_VEHICLE"), $stnk_file);
            }
        }

        if ($request->file('kir_file')) {
            $size = floor($request->file('kir_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('kir_file')->getClientOriginalExtension();
                $kir_file = 'kir-'.$vehicle_id_rsp.'-'.$date_now . '.' . $ext;
                $request->file('kir_file')->move(env("PATH_IMAGE_DATA_VEHICLE"), $kir_file);
            }
        }

        $validator = Validator::make($request->all(), [
            'vehicle_id'   => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (Vehicle::where('vehicle_id', $vehicle_id)->where('deleted', 0)
                ->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Vehicle Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new Vehicle;
                    $create->vehicle_id = $vehicle_id;
                    $create->driver = $driver;
                    $create->co_driver = $co_driver;
                    $create->transporter_id = $transporter_id;
                    $create->status = $status;
                    $create->type = $type;
                    $create->max_volume = $max_volume;
                    $create->max_weight = $max_weight;
                    $create->subcon = $subcon;
                    $create->additional_information = $additional_information;
                    $create->created_by = $created_by;
                    $create->id_company = $id_company;
                    $create->no_lambung = $no_lambung;
                    $create->no_stnk = $no_stnk;
                    $create->no_kir = $no_kir;
                    $create->tgl_aktif_stnk = $tgl_aktif_stnk;
                    $create->tgl_aktif_kir = $tgl_aktif_kir;
                    $create->foto_stnk = $stnk_file;
                    $create->foto_kir = $kir_file;
    
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
        $vehicle_id = $request->vehicle_id;
        $driver = $request->driver;
        $co_driver = $request->co_driver;
        $transporter_id = $request->transporter_id;
        $status = $request->status;
        $type = $request->type;
        $max_volume = $request->max_volume;
        $max_weight = $request->max_weight;
        $subcon = $request->subcon;
        $additional_information = $request->additional_information;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;
        $no_lambung = $request->no_lambung;
        $no_stnk = $request->no_stnk;
        $no_kir = $request->no_kir;
        $tgl_aktif_stnk = $request->tgl_aktif_stnk;
        $tgl_aktif_kir = $request->tgl_aktif_kir;
        $stnk_file = '';
        $kir_file = '';
        $vehicle_id_rsp = Str::replace(' ', '-', $vehicle_id);
        $change_stnk = false;
        if ($request->file('stnk_file')) {
            $size = floor($request->file('stnk_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('stnk_file')->getClientOriginalExtension();
                $stnk_file = 'stnk-'.$vehicle_id_rsp.'-'.$date_now . '.' . $ext;
                $request->file('stnk_file')->move(env("PATH_IMAGE_DATA_VEHICLE"), $stnk_file);
                $change_stnk = true;
            }
        }
        $change_kir = false;
        if ($request->file('kir_file')) {
            $size = floor($request->file('kir_file')->getSize() / 1024);
            if ($size > 1000) { //1 MB
                $respon = array(
                    "code" => "02",
                    "message" =>  "File terlalu besar",
                );
                return response()->json($respon);
            } else {
                $date_now = date('YmdHisv');
                $ext = $request->file('kir_file')->getClientOriginalExtension();
                $kir_file = 'kir-'.$vehicle_id_rsp.'-'.$date_now . '.' . $ext;
                $request->file('kir_file')->move(env("PATH_IMAGE_DATA_VEHICLE"), $kir_file);
                $change_kir = true;
            }
        }
        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'vehicle_id'    => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (Vehicle::where('vehicle_id', $vehicle_id)->where('id','!=', $id)
                ->where('deleted', 0)->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Vehicle Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = Vehicle::find($id);
                    $update->vehicle_id = $vehicle_id;
                    $update->driver = $driver;
                    $update->co_driver = $co_driver;
                    $update->transporter_id = $transporter_id;
                    $update->status = $status;
                    $update->type = $type;
                    $update->max_volume = $max_volume;
                    $update->max_weight = $max_weight;
                    $update->subcon = $subcon;
                    $update->additional_information = $additional_information;
                    $update->updated_by = $updated_by;
                    $update->id_company = $id_company;
                    $update->no_lambung = $no_lambung;
                    $update->no_stnk = $no_stnk;
                    $update->no_kir = $no_kir;
                    $update->tgl_aktif_stnk = $tgl_aktif_stnk;
                    $update->tgl_aktif_kir = $tgl_aktif_kir;
                    if($change_stnk){
                        $update->foto_stnk = $stnk_file;
                    }
                    if($change_kir){
                        $update->foto_kir = $kir_file;
                    }
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
                $update = Vehicle::find($id);
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

    public function Export (Request $request) {
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_company'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );

            return;

        } else {
            try {

                return Excel::download(new VehicleExport($request), 'Vehicle-List-' . date('d-m-Y') . '-' . date('h.i.s') . '.xlsx');

            } catch (Exception $e) {

                return;

            }

        }
    }

    public function SearchData (Request $request) {

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

            $data = Vehicle::select('tb_vehicles.id', 'tb_vehicles.vehicle_id', 'tb_vehicles.no_lambung', 'tb_vehicle_types.type_id')
                ->leftJoin('tb_vehicle_types as tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                ->where('tb_vehicles.id_company', $id_company)
                ->whereRaw("tb_vehicles.vehicle_id like'%$search%'")
                ->where('tb_vehicles.deleted', 0)
                ->get();

            $respon = array(
                "code" => "01",
                "data" => $data 
            );

            $response_code = 200;

        }
    
        return response()->json($respon, $response_code);
    }
}