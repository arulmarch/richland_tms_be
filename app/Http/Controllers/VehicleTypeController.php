<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\VehicleType;
use Illuminate\Support\Facades\DB;

class VehicleTypeController extends Controller
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
        
            $data = VehicleType::select('tb_vehicle_types.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                    ->leftJoin('user as user', 'tb_vehicle_types.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_vehicle_types.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_vehicle_types.id_company', '=', 'tb_company.id')
                    ->where('tb_vehicle_types.id_company', $id_company)
                    ->where('tb_vehicle_types.deleted', '0')
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
        
            $data = VehicleType::select('tb_vehicle_types.id', 'tb_vehicle_types.type_id', 'tb_vehicle_types.volume_cap', 'tb_vehicle_types.weight_cap')
                    ->where('tb_vehicle_types.id_company', $id_company)
                    ->where('tb_vehicle_types.deleted', '0')
                    ->whereRaw("tb_vehicle_types.type_id like '%${search}%'")
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
            $data = VehicleType::select('tb_vehicle_types.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_vehicle_types.id_company', '=', 'tb_company.id')
                    ->where('tb_vehicle_types.id', $id)
                    ->where('tb_vehicle_types.deleted', 0)
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
        $type_id = $request->type_id;
        $description = $request->description;
        $volume_cap = $request->volume_cap;
        $weight_cap = $request->weight_cap;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'type_id'       => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (VehicleType::where('type_id', $type_id)->where('id_company', $id_company)
                ->where('deleted', 0)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Vehicle Type tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new VehicleType;
                    $create->type_id = $type_id;
                    $create->description = $description;
                    $create->volume_cap = $volume_cap;
                    $create->weight_cap = $weight_cap;
                    $create->created_by = $created_by;
                    $create->id_company = $id_company;

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
        $type_id = $request->type_id;
        $description = $request->description;
        $volume_cap = $request->volume_cap;
        $weight_cap = $request->weight_cap;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'type_id'       => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (VehicleType::where('type_id', $type_id)->where('id','!=', $id)
                ->where('id_company', $id_company)->where('deleted', 0)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Vehicle Type tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = VehicleType::find($id);
                    $update->type_id = $type_id;
                    $update->description = $description;
                    $update->volume_cap = $volume_cap;
                    $update->weight_cap = $weight_cap;
                    $update->updated_by = $updated_by;
                    $update->id_company = $id_company;

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
                $update = VehicleType::find($id);
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