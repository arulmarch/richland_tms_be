<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\HistoryChangeLoad;
use App\Models\Manifest;
use App\Models\TransporterRate;
use App\Models\ClientRate;
use Illuminate\Support\Facades\DB;

class HistoryChangeLoadController extends Controller
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

        $id_manifest = $request->id_manifest;

        $validator = Validator::make($request->all(), [
            'id_manifest'      => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = HistoryChangeLoad::select('tb_history_change_load.*', 'user.name as created_name')
                    ->selectRaw('case 
                                    when tb_history_change_load.status = 1 then "Reduced"
                                    when tb_history_change_load.status = 2 then "Increase"
                                    end as status_name')
                    ->leftJoin('user', 'tb_history_change_load.created_by', '=', 'user.user_id')
                    ->where('tb_history_change_load.id_manifest', $id_manifest)
                    ->where('tb_history_change_load.deleted', 0)
                    ->get();

            $respon = array(
                "code" => "01",
                "data" => $data 
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function Create (Request $request) {
        $new_load = $request->new_load;
        $desc = $request->desc;
        $id_manifest = $request->id_manifest;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'new_load'          => 'required',
            'id_manifest'       => 'required',
            'id_company'        => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $is_failed = false;

            $data_manifest = Manifest::select('trucking_order.client_id', 'trucking_order.origin_area_id', 'trucking_order.dest_area_id', 
                        'vehicle.transporter_id', 'vehicle.type', 'vehicle.status', 'tb_manifests.load_kg', 'tb_manifests.tr_id')
                    ->leftJoin('tb_trucking_order as trucking_order', 'tb_manifests.tr_id', '=', 'trucking_order.id')
                    ->leftJoin('tb_vehicles as vehicle', 'tb_manifests.vehicle_id', '=', 'vehicle.id')
                    ->where('tb_manifests.id', $id_manifest)
                    ->where('tb_manifests.id_company', $id_company)
                    ->where('tb_manifests.deleted', 0)
                    ->first();

            $data_transporter_rate = TransporterRate::where('client_id', $data_manifest->client_id)
                    ->where('transporter_id', $data_manifest->transporter_id)
                    ->where('origin_id', $data_manifest->origin_area_id)
                    ->where('destination_id', $data_manifest->dest_area_id)
                    ->where('type_id', $data_manifest->type)
                    ->where('status', $data_manifest->status)
                    ->where('id_company', $id_company)
                    ->where('deleted', 0)
                    ->first();

            $data_client_rate = ClientRate::where('client_id', $data_manifest->client_id)
                    ->where('origin_id', $data_manifest->origin_area_id)
                    ->where('destination_id', $data_manifest->dest_area_id)
                    ->where('type_id', $data_manifest->type)
                    ->where('status', $data_manifest->status)
                    ->where('id_company', $id_company)
                    ->where('deleted', 0)
                    ->first();

            $load_kg = $data_manifest->load_kg;

            if ($load_kg == null) {
                $respon = array(
                    "code" => "02",
                    "message" => "Gagal mengambil data manifest",
                );
                $is_failed = true;
            } else if ($data_transporter_rate === NULL) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Transporter Rate belum tersedia !",
                );
                $is_failed = true;
            } else if ($data_client_rate === NULL) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Client Rate belum tersedia !",
                );
                $is_failed = true;
            }

            if (!$is_failed) {

                $transporter_variable_cost = 0;
                if ($data_transporter_rate->rate_type === 1) {
                    $transporter_variable_cost = $data_transporter_rate->vehicle_rate;
                } else {
                    $transporter_variable_cost = $new_load * $data_transporter_rate->vehicle_rate;
                }

                $client_variable_cost = 0;
                if ($data_client_rate->rate_type === 1) {
                    $client_variable_cost = $data_client_rate->vehicle_rate;
                } else {
                    $client_variable_cost = $new_load * $data_client_rate->vehicle_rate;
                }

                if ($load_kg > $new_load) {
                    $range = $load_kg - $new_load;
                    $status = 1;
                } else {
                    $range = $new_load - $load_kg;
                    $status = 2;
                }

                try 
                {
                    DB::beginTransaction();

                    $create = new HistoryChangeLoad;
                    $create->old_load = $load_kg;
                    $create->new_load = $new_load;
                    $create->range = $range;
                    $create->desc = $desc;
                    $create->status = $status;
                    $create->id_manifest = $id_manifest;
                    $create->created_by = $created_by;
                    $create->id_company = $id_company;

                    $create->save();

                    $update_manifest = Manifest::find($id_manifest);
                    $update_manifest->variable_cost = $transporter_variable_cost;
                    $update_manifest->client_variable_cost = $client_variable_cost;
                    $update_manifest->load_kg = $new_load;
                    $update_manifest->updated_by = $created_by;
                    $update_manifest->id_company = $id_company;

                    $update_manifest->save();

                    DB::commit();
                                    
                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menyimpan data",
                    );

                    $response_code = 200;
                }
                catch(Exception $e)
                {
                    DB::rollback();

                    $respon = array(
                        "code" => "03",
                        "message" =>  "Ada masalah dengan server, harap coba lagi nanti !",
                        "error_message" =>  $e,
                    );
                }
            }
        }

        return response()->json($respon, $response_code);
    }

    public function Delete (Request $request) {
        $id = $request->id;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'              => 'required',
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $update = HistoryChangeLoad::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();
                                
                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil menghapus data",
                );

                $response_code = 200;
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

        return response()->json($respon, $response_code);
    }
}