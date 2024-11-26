<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\TruckAccident;
use Illuminate\Support\Facades\DB;

class TruckAccidentController extends Controller
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
        
            $data = TruckAccident::select('tb_truck_accident.*', 'vehicle.vehicle_id as vehicle_id_name', 'driver.name as driver_name',
                        'client.client_id as client_name', 'accident_type.accident_id as accident_type_name', 'user.name as created_name', 'user_update.name as updated_name',
                        'tb_company.name as name_company')
                    ->leftJoin('tb_vehicles as vehicle', 'tb_truck_accident.vehicle_id', '=', 'vehicle.id')
                    ->leftJoin('tb_drivers as driver', 'tb_truck_accident.driver_id', '=', 'driver.id')
                    ->leftJoin('tb_clients as client', 'tb_truck_accident.client_id', '=', 'client.id')
                    ->leftJoin('tb_accident_type as accident_type', 'tb_truck_accident.accident_type', '=', 'accident_type.id')
                    ->leftJoin('user as user', 'tb_truck_accident.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_truck_accident.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_truck_accident.id_company', '=', 'tb_company.id')
                    ->where('tb_truck_accident.id_company', $id_company)
                    ->where('tb_truck_accident.deleted', 0)
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
            $data = TruckAccident::select('tb_truck_accident.*', 'vehicle.vehicle_id as vehicle_id_name', 'driver.name as driver_name',
                        'client.client_id as client_name', 'accident_type.accident_id as accident_type_name', 'tb_company.name as name_company')
                    ->leftJoin('tb_vehicles as vehicle', 'tb_truck_accident.vehicle_id', '=', 'vehicle.id')
                    ->leftJoin('tb_drivers as driver', 'tb_truck_accident.driver_id', '=', 'driver.id')
                    ->leftJoin('tb_clients as client', 'tb_truck_accident.client_id', '=', 'client.id')
                    ->leftJoin('tb_accident_type as accident_type', 'tb_truck_accident.accident_type', '=', 'accident_type.id')
                    ->leftJoin('tb_company as tb_company', 'tb_truck_accident.id_company', '=', 'tb_company.id')
                    ->where('tb_truck_accident.id', $id)
                    ->where('tb_truck_accident.deleted', 0)
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
        $driver_id = $request->driver_id;
        $client_id = $request->client_id;
        $accident_date = $request->accident_date;
        $accident_type = $request->accident_type;
        $location = $request->location;
        $chronology_accident = $request->chronology_accident;
        $vehicle_condition = $request->vehicle_condition;
        $amount_less = $request->amount_less;
        $police_investigation_report = $request->police_investigation_report;
        $additional_information = $request->additional_information;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'vehicle_id'        => 'required',
            'driver_id'         => 'required',
            'accident_date'     => 'required',
            'accident_type'     => 'required',
            'location'          => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $create = new TruckAccident;
                $create->vehicle_id = $vehicle_id;
                $create->driver_id = $driver_id;
                $create->client_id = $client_id;
                $create->accident_date = $accident_date;
                $create->accident_type = $accident_type;
                $create->location = $location;
                $create->chronology_accident = $chronology_accident;
                $create->vehicle_condition = $vehicle_condition;
                $create->amount_less = $amount_less;
                $create->police_investigation_report = $police_investigation_report;
                $create->additional_information = $additional_information;
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

        return response()->json($respon);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $vehicle_id = $request->vehicle_id;
        $driver_id = $request->driver_id;
        $client_id = $request->client_id;
        $accident_date = $request->accident_date;
        $accident_type = $request->accident_type;
        $location = $request->location;
        $chronology_accident = $request->chronology_accident;
        $vehicle_condition = $request->vehicle_condition;
        $amount_less = $request->amount_less;
        $police_investigation_report = $request->police_investigation_report;
        $additional_information = $request->additional_information;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'vehicle_id'        => 'required',
            'driver_id'         => 'required',
            'accident_date'     => 'required',
            'accident_type'     => 'required',
            'location'          => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $update = TruckAccident::find($id);
                $update->vehicle_id = $vehicle_id;
                $update->driver_id = $driver_id;
                $update->client_id = $client_id;
                $update->accident_date = $accident_date;
                $update->accident_type = $accident_type;
                $update->location = $location;
                $update->chronology_accident = $chronology_accident;
                $update->vehicle_condition = $vehicle_condition;
                $update->amount_less = $amount_less;
                $update->police_investigation_report = $police_investigation_report;
                $update->additional_information = $additional_information;
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
                $update = TruckAccident::find($id);
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