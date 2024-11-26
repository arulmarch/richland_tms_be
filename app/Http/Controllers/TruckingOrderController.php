<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\TruckingOrder;
use App\Models\MasterCustomer;
use Illuminate\Support\Facades\DB;

class TruckingOrderController extends Controller
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

        $status = $request->status;
        $filter_date = $request->boolean('filter_date');
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $search_by = $request->search_by;
        $search_input = $request->search_input;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_company'        => 'required',
            'filter_date'       => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = TruckingOrder::select(
                        'tb_trucking_order.id', 'tb_trucking_order.transport_mode', 'tb_trucking_order.budget',
                        'tb_trucking_order.tr_status', 'tb_trucking_order.created_by', 'tb_trucking_order.created_date', 
                        'tb_trucking_order.updated_by', 'tb_trucking_order.updated_date', 'user.name as created_name', 
                        'user_update.name as updated_name', 'tb_company.name as name_company', 'clients.client_id as client_id',
                        'origin.customer_id as origin_id', 'origin.name as origin_name', 'origin.address1 as origin_address',
                        'dest.customer_id as dest_id', 'dest.name as dest_name', 'dest.address1 as dest_address',
                        'vehicle_types.type_id as pref_vehicle_type', 'origin_area.area_id as origin_area_id',
                        'dest_area.area_id as dest_area_id', 'manifest.id as manifest_id')
                    ->selectRaw('DATE(tb_trucking_order.schedule_date) as schedule_date')
                    ->selectRaw('DATE(tb_trucking_order.req_pickup_time) as req_pickup_time')
                    ->selectRaw('DATE(tb_trucking_order.req_arrival_time) as req_arrival_time')
                    ->selectRaw('case 
                                when tb_trucking_order.transport_mode = 1 then "LAND"
                                when tb_trucking_order.transport_mode = 2 then "AIR"
                                when tb_trucking_order.transport_mode = 3 then "SEA"
                                when tb_trucking_order.transport_mode = 4 then "RAILWAY"
                                end as transport_mode_name')
                    ->selectRaw('case 
                                when tb_trucking_order.tr_status = 0 then "OPEN"
                                when tb_trucking_order.tr_status = 1 then "CLOSE"
                                end as tr_status_name')
                    ->leftJoin('user as user', 'tb_trucking_order.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_trucking_order.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_trucking_order.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_clients as clients', 'tb_trucking_order.client_id', '=', 'clients.id')
                    ->leftJoin('tb_customers as origin', 'tb_trucking_order.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_customers as dest', 'tb_trucking_order.dest_id', '=', 'dest.id')
                    ->leftJoin('tb_vehicle_types as vehicle_types', 'tb_trucking_order.pref_vehicle_type', '=', 'vehicle_types.id')
                    ->leftJoin('tb_areas as origin_area', 'tb_trucking_order.origin_area_id', '=', 'origin_area.id')
                    ->leftJoin('tb_areas as dest_area', 'tb_trucking_order.dest_area_id', '=', 'dest_area.id')
                    ->leftJoin('tb_manifests as manifest', 'tb_trucking_order.id', '=', 'manifest.tr_id')
                    ->where('tb_trucking_order.id_company', $id_company)
                    ->where('tb_trucking_order.deleted', 0);

            if ($search_by !== null && $search_by !== "") {
                $data->where($search_by, 'like', '%' . $search_input . '%');
            }

            if ($status !== null && $status !== "") {
                $data->where('tb_trucking_order.tr_status', $status);
            }

            if ($filter_date === true) {
                $data->whereRaw("tb_trucking_order.schedule_date BETWEEN '$start_date' and '$end_date'");
            }
    
            $data_response = $data->get();

            $respon = array(
                "code" => "01",
                "data" => $data_response 
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
            $data = TruckingOrder::select(
                    'tb_trucking_order.id', 'tb_trucking_order.transport_mode', 'tb_trucking_order.budget',
                    'tb_trucking_order.tr_status', 'tb_trucking_order.created_by', 'tb_trucking_order.created_date', 
                    'tb_trucking_order.updated_by', 'tb_trucking_order.updated_date',  'tb_trucking_order.origin_id',
                    'tb_trucking_order.dest_id', 'tb_trucking_order.origin_area_id', 'tb_trucking_order.dest_area_id', 
                    'tb_trucking_order.pref_vehicle_type', 'user.name as created_name', 'user_update.name as updated_name', 
                    'tb_company.name as name_company', 'clients.client_id as client_id', 'clients.name as client_id_name', 
                    'origin.customer_id as origin_id_name', 'origin.name as origin_name', 'origin.address1 as origin_address', 
                    'dest.customer_id as dest_id_name', 'dest.name as dest_name', 'dest.address1 as dest_address', 
                    'vehicle_types.type_id as pref_vehicle_type_name', 'origin_area.area_id as origin_area_id_name', 
                    'dest_area.area_id as dest_area_id_name', 'manifest.id as manifest_id', 'tb_trucking_order.id_company',
                    'origin_area.description as origin_area_id_desc', 'dest_area.description as dest_area_id_desc', 'clients.id as id_client')
                ->selectRaw('DATE(tb_trucking_order.schedule_date) as schedule_date')
                ->selectRaw('DATE(tb_trucking_order.req_pickup_time) as req_pickup_time')
                ->selectRaw('DATE(tb_trucking_order.req_arrival_time) as req_arrival_time')
                ->selectRaw('case 
                            when tb_trucking_order.transport_mode = 1 then "LAND"
                            when tb_trucking_order.transport_mode = 2 then "AIR"
                            when tb_trucking_order.transport_mode = 3 then "SEA"
                            when tb_trucking_order.transport_mode = 4 then "RAILWAY"
                            end as transport_mode_name')
                ->selectRaw('case 
                            when tb_trucking_order.tr_status = 0 then "OPEN"
                            when tb_trucking_order.tr_status = 1 then "CLOSE"
                            end as tr_status_name')
                ->leftJoin('user as user', 'tb_trucking_order.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'tb_trucking_order.updated_by', '=', 'user_update.user_id')
                ->leftJoin('tb_company as tb_company', 'tb_trucking_order.id_company', '=', 'tb_company.id')
                ->leftJoin('tb_clients as clients', 'tb_trucking_order.client_id', '=', 'clients.id')
                ->leftJoin('tb_customers as origin', 'tb_trucking_order.origin_id', '=', 'origin.id')
                ->leftJoin('tb_customers as dest', 'tb_trucking_order.dest_id', '=', 'dest.id')
                ->leftJoin('tb_vehicle_types as vehicle_types', 'tb_trucking_order.pref_vehicle_type', '=', 'vehicle_types.id')
                ->leftJoin('tb_areas as origin_area', 'tb_trucking_order.origin_area_id', '=', 'origin_area.id')
                ->leftJoin('tb_areas as dest_area', 'tb_trucking_order.dest_area_id', '=', 'dest_area.id')
                ->leftJoin('tb_manifests as manifest', 'tb_trucking_order.id', '=', 'manifest.tr_id')
                ->where('tb_trucking_order.id', $id)
                ->where('tb_trucking_order.deleted', 0)
                ->first();

            if (!$data) {
                $respon = array(
                    "code" => "02",
                    "message" =>  'Data tidak ditemukan !',
                );
            } else {

                $data_origin_id = null;
                if ($data->origin_id) {
                    $data_origin_id = MasterCustomer::select('tb_customers.id', 'tb_customers.customer_id', 'tb_customers.name', 'tb_customers.address1', 
                            'tb_customers.area_id', 'area.area_id as area_id_name', 'area.description')
                        ->leftJoin('tb_areas as area', 'tb_customers.area_id', '=', 'area.id')
                        ->where('tb_customers.id', $data->origin_id)
                        ->first();
                }

                $data_dest_id = null;
                if ($data->dest_id) {
                    $data_dest_id = MasterCustomer::select('tb_customers.id', 'tb_customers.customer_id', 'tb_customers.name', 'tb_customers.address1', 
                            'tb_customers.area_id', 'area.area_id as area_id_name', 'area.description')
                        ->leftJoin('tb_areas as area', 'tb_customers.area_id', '=', 'area.id')
                        ->where('tb_customers.id', $data->dest_id)
                        ->first();
                }

                $respon = array(
                    "code" => "01",
                    "data" => $data,
                    "data_origin_id" => $data_origin_id,
                    "data_dest_id" => $data_dest_id
                );

                $response_code = 200;
            }
        }

        return response()->json($respon, $response_code);
    }

    public function Create (Request $request) {
        $client_id = $request->client_id;
        $schedule_date = $request->schedule_date;
        $transport_mode = $request->transport_mode;
        $type_id = $request->type_id;
        $budget = $request->budget;
        $origin_id = $request->origin_id;
        $dest_id = $request->dest_id;
        $origin_area = $request->origin_area;
        $dest_area = $request->dest_area;
        $pickup_date = $request->pickup_date;
        $arrival_date = $request->arrival_date;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'client_id'         => 'required',
            'schedule_date'     => 'required',
            'transport_mode'    => 'required',
            'type_id'           => 'required',
            'origin_id'         => 'required',
            'dest_id'           => 'required',
            'origin_area'       => 'required',
            'dest_area'         => 'required',
            'pickup_date'       => 'required',
            'arrival_date'      => 'required',
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
                $create = new TruckingOrder;
                $create->client_id = $client_id;
                $create->schedule_date = $schedule_date;
                $create->transport_mode = $transport_mode;
                $create->pref_vehicle_type = $type_id;
                $create->budget = $budget;
                $create->origin_id = $origin_id;
                $create->dest_id = $dest_id;
                $create->origin_area_id = $origin_area;
                $create->dest_area_id = $dest_area;
                $create->req_pickup_time = $pickup_date;
                $create->req_arrival_time = $arrival_date;
                $create->tr_status = 0;
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
        $client_id = $request->client_id;
        $schedule_date = $request->schedule_date;
        $transport_mode = $request->transport_mode;
        $type_id = $request->type_id;
        $budget = $request->budget;
        $origin_id = $request->origin_id;
        $dest_id = $request->dest_id;
        $origin_area = $request->origin_area;
        $dest_area = $request->dest_area;
        $pickup_date = $request->pickup_date;
        $arrival_date = $request->arrival_date;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'client_id'         => 'required',
            'schedule_date'     => 'required',
            'transport_mode'    => 'required',
            'type_id'           => 'required',
            'origin_id'         => 'required',
            'dest_id'           => 'required',
            'origin_area'       => 'required',
            'dest_area'         => 'required',
            'pickup_date'       => 'required',
            'arrival_date'      => 'required',
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
                $update = TruckingOrder::find($id);
                $update->client_id = $client_id;
                $update->schedule_date = $schedule_date;
                $update->transport_mode = $transport_mode;
                $update->pref_vehicle_type = $type_id;
                $update->budget = $budget;
                $update->origin_id = $origin_id;
                $update->dest_id = $dest_id;
                $update->origin_area_id = $origin_area;
                $update->dest_area_id = $dest_area;
                $update->req_pickup_time = $pickup_date;
                $update->req_arrival_time = $arrival_date;
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
                $update = TruckingOrder::find($id);
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

    public function SearchData (Request $request) {

        $id_company = $request->id_company;
        $search = $request->search;
        $schedule_date = $request->schedule_date;
        $id_manifest = $request->id_manifest;

        $validator = Validator::make($request->all(), [
            'id_company'        => 'required',
            'schedule_date'     => 'required',
            'id_manifest'       => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = TruckingOrder::select(
                        'tb_trucking_order.id', 'clients.client_id as client_id', 'origin.name as origin_name',
                        'dest.name as dest_name', 'vehicle_types.type_id as pref_vehicle_type', 'manifest.id as manifest_id')
                    ->selectRaw("CONCAT(tb_trucking_order.id, ' - ', vehicle_types.type_id, ' - ', clients.client_id, ' - ', origin.name, ' - ',
                            dest.name) as concat_data")
                    ->leftJoin('tb_clients as clients', 'tb_trucking_order.client_id', '=', 'clients.id')
                    ->leftJoin('tb_customers as origin', 'tb_trucking_order.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_customers as dest', 'tb_trucking_order.dest_id', '=', 'dest.id')
                    ->leftJoin('tb_vehicle_types as vehicle_types', 'tb_trucking_order.pref_vehicle_type', '=', 'vehicle_types.id')
                    ->leftJoin('tb_manifests as manifest', 'tb_trucking_order.id', '=', 'manifest.tr_id')
                    ->where('tb_trucking_order.id_company', $id_company)
                    ->where('tb_trucking_order.deleted', 0)
                    ->whereRaw("(manifest.id is null or manifest.id = '$id_manifest')")
                    ->where('tb_trucking_order.schedule_date', $schedule_date)
                    ->whereRaw("(tb_trucking_order.id like'%$search%' or clients.client_id like'%$search%' or origin.name like'%$search%'
                        or dest.name like'%$search%' or vehicle_types.type_id like'%$search%')")
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
}