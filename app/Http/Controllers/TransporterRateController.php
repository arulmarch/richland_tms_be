<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\TransporterRate;
use Illuminate\Support\Facades\DB;

class TransporterRateController extends Controller
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
        
            $data = TransporterRate::select('tb_transporter_rates.*', 'user.name as created_name', 'user_update.name as updated_name', 
                        'tb_company.name as name_company', 'tb_clients.client_id as client_id_data', 'tb_transporters.transporter_id as transporter_id_data', 
                        'tb_vehicle_types.type_id as type_id_data', 'origin.area_id as origin', 'dest.area_id as destination')
                    ->selectRaw('case 
                                    when status = 1 then "ON CALL"
                                    when status = 2 then "DEDICATED"
                                    end as status_data')
                    ->selectRaw('case 
                                    when rate_type = 1 then "REGULAR"
                                    when status = 2 then "WEIGHT"
                                    end as rate_type_name')
                    ->leftJoin('user as user', 'tb_transporter_rates.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_transporter_rates.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_transporter_rates.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_clients', 'tb_transporter_rates.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_transporters as tb_transporters', 'tb_transporter_rates.transporter_id', '=', 'tb_transporters.id')
                    ->leftJoin('tb_vehicle_types as tb_vehicle_types', 'tb_transporter_rates.type_id', '=', 'tb_vehicle_types.id')
                    ->leftJoin('tb_areas as origin', 'tb_transporter_rates.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_areas as dest', 'tb_transporter_rates.destination_id', '=', 'dest.id')
                    ->where('tb_transporter_rates.id_company', $id_company)
                    ->where('tb_transporter_rates.deleted', 0)
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
            $data = TransporterRate::select('tb_transporter_rates.*', 'tb_company.name as name_company', 'tb_clients.client_id as client_id_data', 
                        'tb_transporters.transporter_id as transporter_id_data', 'tb_vehicle_types.type_id as type_id_data', 
                        'origin.area_id as origin', 'dest.area_id as destination')
                    ->selectRaw('case 
                                    when status = 1 then "ON CALL"
                                    when status = 2 then "DEDICATED"
                                    end as status_data')
                    ->selectRaw('case 
                                    when rate_type = 1 then "REGULAR"
                                    when rate_type = 2 then "WEIGHT"
                                    end as rate_type_name')
                    ->leftJoin('tb_company as tb_company', 'tb_transporter_rates.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_clients', 'tb_transporter_rates.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_transporters as tb_transporters', 'tb_transporter_rates.transporter_id', '=', 'tb_transporters.id')
                    ->leftJoin('tb_vehicle_types as tb_vehicle_types', 'tb_transporter_rates.type_id', '=', 'tb_vehicle_types.id')
                    ->leftJoin('tb_areas as origin', 'tb_transporter_rates.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_areas as dest', 'tb_transporter_rates.destination_id', '=', 'dest.id')
                    ->where('tb_transporter_rates.id', $id)
                    ->where('tb_transporter_rates.deleted', 0)
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
        $client_id = $request->client_id;
        $transporter_id = $request->transporter_id;
        $origin_id = $request->origin_id;
        $destination_id = $request->destination_id;
        $type_id = $request->type_id;
        $status = $request->status;
        $currency = $request->currency;
        $rate_type = $request->rate_type;
        $vehicle_rate = $request->vehicle_rate;
        $min_weight = $request->min_weight;
        $remark = $request->remark;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'client_id'         => 'required',
            'transporter_id'    => 'required',
            'origin_id'         => 'required',
            'destination_id'    => 'required',
            'type_id'           => 'required',
            'status'            => 'required',
            'currency'          => 'required',
            'rate_type'         => 'required',
            'vehicle_rate'      => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = TransporterRate::where('client_id', $client_id)
                ->where('transporter_id', $transporter_id)
                ->where('origin_id', $origin_id)
                ->where('destination_id', $destination_id)
                ->where('type_id', $type_id)
                ->where('status', $status)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Data Transporter Rate sudah ada",
                );
            } else {
                try 
                {
                    $create = new TransporterRate;
                    $create->client_id = $client_id;
                    $create->transporter_id = $transporter_id;
                    $create->origin_id = $origin_id;
                    $create->destination_id = $destination_id;
                    $create->type_id = $type_id;
                    $create->status = $status;
                    $create->currency = $currency;
                    $create->rate_type = $rate_type;
                    $create->vehicle_rate = $vehicle_rate;
                    $create->min_weight = $min_weight;
                    $create->remark = $remark;
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
        $client_id = $request->client_id;
        $transporter_id = $request->transporter_id;
        $origin_id = $request->origin_id;
        $destination_id = $request->destination_id;
        $type_id = $request->type_id;
        $status = $request->status;
        $currency = $request->currency;
        $rate_type = $request->rate_type;
        $vehicle_rate = $request->vehicle_rate;
        $min_weight = $request->min_weight;
        $remark = $request->remark;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'client_id'         => 'required',
            'transporter_id'    => 'required',
            'origin_id'         => 'required',
            'destination_id'    => 'required',
            'type_id'           => 'required',
            'status'            => 'required',
            'currency'          => 'required',
            'rate_type'         => 'required',
            'vehicle_rate'      => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = TransporterRate::where('client_id', $client_id)
                ->where('transporter_id', $transporter_id)
                ->where('origin_id', $origin_id)
                ->where('destination_id', $destination_id)
                ->where('type_id', $type_id)
                ->where('status', $status)
                ->where('id','!=', $id)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Data Transporter Rate sudah ada",
                );
            } else {
                try 
                {
                    $update = TransporterRate::find($id);
                    $update->client_id = $client_id;
                    $update->transporter_id = $transporter_id;
                    $update->origin_id = $origin_id;
                    $update->destination_id = $destination_id;
                    $update->type_id = $type_id;
                    $update->status = $status;
                    $update->currency = $currency;
                    $update->rate_type = $rate_type;
                    $update->vehicle_rate = $vehicle_rate;
                    $update->min_weight = $min_weight;
                    $update->remark = $remark;
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
                $update = TransporterRate::find($id);
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