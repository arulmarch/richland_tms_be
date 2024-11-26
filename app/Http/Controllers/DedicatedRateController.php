<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\DedicatedRate;
use Illuminate\Support\Facades\DB;

class DedicatedRateController extends Controller
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
        
            $data = DedicatedRate::select('tb_dedicated_rate.*', 'user.name as created_name', 'user_update.name as updated_name', 
                        'tb_company.name as name_company', 'tb_clients.client_id as client_id_data', 'tb_vehicles.vehicle_id', 
                        'tb_vehicles.no_lambung', 'tb_vehicle_types.type_id', 'tb_transporters.transporter_id as transporter_id_data')
                    ->leftJoin('user as user', 'tb_dedicated_rate.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_dedicated_rate.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_dedicated_rate.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_clients', 'tb_dedicated_rate.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_transporters', 'tb_dedicated_rate.id_transporter', '=', 'tb_transporters.id')
                    ->leftJoin('tb_vehicles', 'tb_dedicated_rate.id_vehicle', '=', 'tb_vehicles.id')
                    ->leftJoin('tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                    ->where('tb_dedicated_rate.id_company', $id_company)
                    ->where('tb_dedicated_rate.deleted', 0)
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

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = DedicatedRate::select('tb_dedicated_rate.*', 'user.name as created_name', 'user_update.name as updated_name', 
                        'tb_company.name as name_company', 'tb_clients.client_id as client_id_data', 'tb_vehicles.vehicle_id', 
                        'tb_vehicles.no_lambung', 'tb_vehicle_types.type_id', 'tb_transporters.transporter_id as transporter_id_data')
                    ->leftJoin('user as user', 'tb_dedicated_rate.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_dedicated_rate.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_dedicated_rate.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_clients', 'tb_dedicated_rate.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_transporters', 'tb_dedicated_rate.id_transporter', '=', 'tb_transporters.id')
                    ->leftJoin('tb_vehicles', 'tb_dedicated_rate.id_vehicle', '=', 'tb_vehicles.id')
                    ->leftJoin('tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                    ->where('tb_dedicated_rate.id', $id)
                    ->where('tb_dedicated_rate.deleted', 0)
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

                $response_code = 200;
            }
        }

        return response()->json($respon, $response_code);
    }

    public function Create (Request $request) {
        $id_vehicle = $request->id_vehicle;
        $id_transporter = $request->id_transporter;
        $vehicle_rate = $request->vehicle_rate;
        $currency = $request->currency;
        $client_id = $request->client_id;
        $desc = $request->desc;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_vehicle'            => 'required',
            'id_transporter'        => 'required',
            'vehicle_rate'          => 'required',
            'currency'              => 'required',
            'vehicle_rate'          => 'required',
            'id_company'            => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = DedicatedRate::where('id_vehicle', $id_vehicle)
                ->where('client_id', $client_id)
                ->where('id_transporter', $id_transporter)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Data Dedicated Rate sudah ada",
                );
            } else {
                try 
                {
                    $create = new DedicatedRate;
                    $create->id_vehicle = $id_vehicle;
                    $create->id_transporter = $id_transporter;
                    $create->vehicle_rate = $vehicle_rate;
                    $create->currency = $currency;
                    $create->client_id = $client_id;
                    $create->desc = $desc;
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
        $id_vehicle = $request->id_vehicle;
        $id_transporter = $request->id_transporter;
        $vehicle_rate = $request->vehicle_rate;
        $currency = $request->currency;
        $client_id = $request->client_id;
        $desc = $request->desc;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                    => 'required',
            'id_vehicle'            => 'required',
            'id_transporter'        => 'required',
            'vehicle_rate'          => 'required',
            'currency'              => 'required',
            'vehicle_rate'          => 'required',
            'id_company'            => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = DedicatedRate::where('id_vehicle', $id_vehicle)
                ->where('client_id', $client_id)
                ->where('id_transporter', $id_transporter)
                ->where('id','!=', $id)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Data Dedicated Rate sudah ada",
                );
            } else {
                try 
                {
                    $update = DedicatedRate::find($id);
                    $update->id_vehicle = $id_vehicle;
                    $update->id_transporter = $id_transporter;
                    $update->vehicle_rate = $vehicle_rate;
                    $update->currency = $currency;
                    $update->client_id = $client_id;
                    $update->desc = $desc;
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
                $update = DedicatedRate::find($id);
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