<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Pod;
use App\Models\PodCode;
use Illuminate\Support\Facades\DB;

class PODController extends Controller
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
        
            $data = Pod::select(
                        'tb_transport_order.manifest_id', 'tb_pod.transport_order_id as reference', 'tb_transport_order.so_number',
                        'tb_transport_order.do_number', 'tb_manifests.manifest_status', 'tb_transport_order.trip', 'tb_vehicles.vehicle_id',
                        'tb_transporters.transporter_id', 'origin.name as origin_name', 'origin.address1 as origin_address',
                        'origin_area.area_id as origin_area_id', 'dest.name as dest_name', 'dest.address1 as dest_address', 
                        'dest_area.area_id as dest_area_id', 'tb_pod.doc_reference', 'tb_pod.receiver', 'pc.code', 
                        'pc.pod_description as poddesc', 'pc.pic as podpic', 'pp.code as pending_code', 'pp.pod_description as podpendingdesc',
                        'pp.pic as podpendingpic', 'tb_pod.remark', 'tb_pod.status', 'tb_company.name as name_company', 'tb_pod.id')
                    ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
                    ->selectRaw('DATE(tb_traffic_monitoring.arrival_ata) as arrival_ata')
                    ->selectRaw('DATE(tb_pod.pod_time) as pod_time')
                    ->selectRaw('DATE(tb_pod.receivetime) as receivetime')
                    ->selectRaw('DATE(tb_pod.submit_time) as submit_time')
                    ->selectRaw('case 
                                when tb_pod.status = 0 then "Open"
                                when tb_pod.status = 1 then "Close"
                                end as status_name')
                    ->leftJoin('tb_company as tb_company', 'tb_pod.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_transport_order', 'tb_pod.transport_order_id', '=', 'tb_transport_order.id')
                    ->leftJoin('tb_customers as origin', 'tb_transport_order.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_customers as dest', 'tb_transport_order.dest_id', '=', 'dest.id')
                    ->leftJoin('tb_areas as origin_area', 'origin.area_id', '=', 'origin_area.id')
                    ->leftJoin('tb_areas as dest_area', 'dest.area_id', '=', 'dest_area.id')
                    ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                    ->leftJoin('tb_vehicles', 'tb_manifests.vehicle_id', '=', 'tb_vehicles.id')
                    ->leftJoin('tb_transporters', 'tb_vehicles.transporter_id', '=', 'tb_transporters.id')
                    ->leftJoin('tb_traffic_monitoring', 'tb_transport_order.id', '=', 'tb_traffic_monitoring.transport_order_id')
                    ->leftJoin('tb_pod_code as pc', 'tb_pod.code', '=', 'pc.id')
                    ->leftJoin('tb_pod_code as pp', 'tb_pod.pending_code', '=', 'pp.id')
                    ->where('tb_traffic_monitoring.tm_state', 'Destination')
                    ->where('tb_traffic_monitoring.deleted', 0)
                    ->where('tb_manifests.manifest_status', '!=', 0)
                    ->where('tb_pod.id_company', $id_company)
                    ->where('tb_pod.deleted', 0);

            if ($search_by !== null && $search_by !== "") {
                $data->where($search_by, 'like', '%' . $search_input . '%');
            }

            if ($status !== null && $status !== "") {
                $data->where('tb_pod.status', $status);
            }

            if ($filter_date === true) {
                $data->whereRaw("tb_manifests.schedule_date BETWEEN '$start_date' and '$end_date'");
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

    public function GetPODCode(Request $request) {
        $id_company = $request->id_company;
        $search = $request->search;

        $data = PodCode::select('id', 'code', 'pod_description', 'pic')
            ->where('pod_description', 'like', '%' . $search . '%')
            ->where('deleted', 0)
            ->limit(10);

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(id_company = $id_company or id_company is null)");
        } else {
            $data->whereRaw("id_company is null");
        }

        $data_response = $data->get();

        $respon = array(
            "code" => "01",
            "data" => $data_response 
        );

        $response_code = 200;

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
            $data = Pod::select(
                    'tb_transport_order.manifest_id', 'tb_pod.transport_order_id as reference', 'tb_transport_order.so_number',
                    'tb_transport_order.do_number', 'tb_manifests.manifest_status', 'tb_transport_order.trip', 'tb_vehicles.vehicle_id',
                    'tb_transporters.transporter_id', 'origin.name as origin_name', 'origin.address1 as origin_address',
                    'origin_area.area_id as origin_area_id', 'dest.name as dest_name', 'dest.address1 as dest_address', 
                    'dest_area.area_id as dest_area_id', 'tb_pod.doc_reference', 'tb_pod.receiver', 'pc.id as code_id', 'pc.code', 
                    'pc.pod_description as poddesc', 'pc.pic as podpic', 'pp.id as pending_code_id', 'pp.code as pending_code', 
                    'pp.pod_description as podpendingdesc', 'pp.pic as podpendingpic', 'tb_pod.remark', 'tb_pod.status', 
                    'tb_company.id as id_company', 'tb_company.name as name_company', 'tb_pod.id', 'tb_manifests.finish')
                ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
                ->selectRaw('DATE(tb_traffic_monitoring.arrival_ata) as arrival_ata')
                ->selectRaw('DATE(tb_pod.pod_time) as pod_time')
                ->selectRaw('DATE(tb_pod.receivetime) as receivetime')
                ->selectRaw('DATE(tb_pod.submit_time) as submit_time')
                ->selectRaw('case 
                            when tb_pod.status = 0 then "Open"
                            when tb_pod.status = 1 then "Close"
                            end as status_name')
                ->leftJoin('tb_company as tb_company', 'tb_pod.id_company', '=', 'tb_company.id')
                ->leftJoin('tb_transport_order', 'tb_pod.transport_order_id', '=', 'tb_transport_order.id')
                ->leftJoin('tb_customers as origin', 'tb_transport_order.origin_id', '=', 'origin.id')
                ->leftJoin('tb_customers as dest', 'tb_transport_order.dest_id', '=', 'dest.id')
                ->leftJoin('tb_areas as origin_area', 'origin.area_id', '=', 'origin_area.id')
                ->leftJoin('tb_areas as dest_area', 'dest.area_id', '=', 'dest_area.id')
                ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                ->leftJoin('tb_vehicles', 'tb_manifests.vehicle_id', '=', 'tb_vehicles.id')
                ->leftJoin('tb_transporters', 'tb_vehicles.transporter_id', '=', 'tb_transporters.id')
                ->leftJoin('tb_traffic_monitoring', 'tb_transport_order.id', '=', 'tb_traffic_monitoring.transport_order_id')
                ->leftJoin('tb_pod_code as pc', 'tb_pod.code', '=', 'pc.id')
                ->leftJoin('tb_pod_code as pp', 'tb_pod.pending_code', '=', 'pp.id')
                ->where('tb_traffic_monitoring.tm_state', 'Destination')
                ->where('tb_pod.id', $id)
                ->where('tb_pod.deleted', 0)
                ->first();

                $respon = array(
                    "code" => "01",
                    "data" => $data,
                );

                $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $pending_code = $request->pending_code;
        $code = $request->code;
        $pod_time = $request->pod_time;
        $submit_time = $request->submit_time;
        $doc_reference = $request->doc_reference;
        $receivetime = $request->receivetime;
        $receiver = $request->receiver;
        $remark = $request->remark;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'pending_code'      => 'required',
            'code'              => 'required',
            'pod_time'          => 'required',
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
                $update = Pod::find($id);
                $update->pending_code = $pending_code;
                $update->code = $code;
                $update->pod_time = $pod_time;
                $update->submit_time = $submit_time;
                $update->doc_reference = $doc_reference;
                $update->receivetime = $receivetime;
                $update->receiver = $receiver;
                $update->remark = $remark;
                $update->status = 1;
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

    public function CancelPod (Request $request) {
        $id = $request->id;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $update = Pod::find($id);
                $update->pending_code = null;
                $update->code = null;
                $update->pod_time = null;
                $update->status = 0;
                $update->updated_by = $updated_by;

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
}