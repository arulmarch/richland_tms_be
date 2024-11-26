<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\TrafficMonitoring;
use App\Models\TruckingOrder;

class DetailOrderManagementController extends Controller
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

    public function DetailOrder (Request $request) {

        $id_trucking_order = $request->id_trucking_order;

        $validator = Validator::make($request->all(), [
            'id_trucking_order'    => 'required',
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
          
            $data = TruckingOrder::select(
                'tb_manifests.id as manifest_id', 'tb_manifests.tr_id', 'tb_clients.name as client_name', 'tb_vehicles.vehicle_id',
                'co_driver.name', 'tb_vehicle_types.type_id', 'tb_vehicles.max_volume as volume_cap', 'tb_vehicles.max_weight as weight_cap', 
                'tb_manifests.start', 'tb_transport_mode.transport_mode', 'tb_manifests.trip', 'tb_vehicles.max_volume', 'tb_vehicles.max_weight',
                'tb_manifests.load_m3', 'tb_manifests.load_kg', 'tb_manifests.mileage', 'tb_manifests.finish', 
                'tb_trucking_order.id_company', 'tb_company.name as company_name')
            ->selectRaw('DATE(tb_trucking_order.req_pickup_time) as pickup_time')
            ->selectRaw('DATE(tb_trucking_order.req_arrival_time) as arrival_time')
            ->selectRaw("CASE 
                            WHEN tb_vehicles.status = '1' THEN 'ON CALL' 
                            WHEN tb_vehicles.status = '2' THEN 'DEDICATED' 
                            END AS vehicle_status")
            ->leftJoin('tb_manifests', 'tb_trucking_order.id', '=', 'tb_manifests.tr_id')
            ->leftJoin('tb_clients', 'tb_trucking_order.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_company', 'tb_trucking_order.id_company', '=', 'tb_company.id')
            ->leftJoin('tb_vehicles', 'tb_manifests.vehicle_id', '=', 'tb_vehicles.id')
            ->leftJoin('tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
            ->leftJoin('tb_drivers as co_driver', 'tb_manifests.co_driver_id', '=', 'co_driver.id')
            ->leftJoin('tb_transport_mode', 'tb_manifests.mode', '=', 'tb_transport_mode.id')
            ->where('tb_manifests.deleted', 0)
            ->where('tb_trucking_order.deleted', 0)
            ->where('tb_trucking_order.id',  $id_trucking_order);

            $data_response = $data->first();

            $respon = array(
                "code" => "01",
                "data" => $data_response,
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function DetailOrderOrigin (Request $request) {

        $id_traffic = $request->id_traffic;

        $validator = Validator::make($request->all(), [
            'id_traffic'     => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
           
            $data = TrafficMonitoring::select(
                'tb_traffic_monitoring.tm_status', 'ttm_status.status_name', 'tb_manifests.id as manifest_id', 'origin.name as origin_name',
                'origin.address1 as origin_address', 'origin_area.area_id as origin_area_name', 'tb_traffic_monitoring.arrival_atatime', 
                'tb_traffic_monitoring.arrival_note', 'tb_traffic_monitoring.arrival_image', 'tb_traffic_monitoring.arrival_latlng', 
                'tb_traffic_monitoring.loading_starttime', 'tb_traffic_monitoring.loading_start_note', 'tb_traffic_monitoring.loading_start_image', 
                'tb_traffic_monitoring.loading_start_latlng', 'tb_traffic_monitoring.loading_finishtime', 'tb_traffic_monitoring.loading_finish_note', 
                'tb_traffic_monitoring.loading_finish_image', 'tb_traffic_monitoring.loading_finish_latlng', 'tb_traffic_monitoring.id_company', 
                'tb_company.name as company_name')
            ->selectRaw('DATE(tb_traffic_monitoring.arrival_ata) as arrival_ata')
            ->selectRaw('DATE(tb_traffic_monitoring.loading_start) as loading_start')
            ->selectRaw('DATE(tb_traffic_monitoring.loading_finish) as loading_finish')
            ->leftJoin('tb_transport_order as tro', 'tb_traffic_monitoring.transport_order_id', '=', 'tro.id')
            ->leftJoin('tb_manifests', 'tro.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_clients', 'tro.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_status_traffic_monitoring as ttm_status', 'tb_traffic_monitoring.tm_status', '=', 'ttm_status.id')
            ->leftJoin('tb_customers as origin', 'tro.origin_id', '=', 'origin.id')
            ->leftJoin('tb_areas as origin_area', 'origin.area_id', '=', 'origin_area.id')
            ->leftJoin('tb_company', 'tb_traffic_monitoring.id_company', '=', 'tb_company.id')
            ->where('tb_traffic_monitoring.deleted', 0)
            ->where('tb_manifests.deleted', 0)
            ->where('tro.deleted', 0)
            ->where('tb_traffic_monitoring.id',  $id_traffic);

            $data_response = $data->first();

            $respon = array(
                "code" => "01",
                "data" => $data_response,
            );

            $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function DetailOrderDest (Request $request) {

        $id_traffic = $request->id_traffic;

        $validator = Validator::make($request->all(), [
            'id_traffic'     => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $data = TrafficMonitoring::select(
                'tb_traffic_monitoring.tm_status', 'ttm_status.status_name', 'tb_manifests.id as manifest_id', 'dest.name as dest_name',
                'dest.address1 as dest_address', 'dest_area.area_id as dest_area_name', 'tb_traffic_monitoring.arrival_atatime', 
                'tb_traffic_monitoring.arrival_note', 'tb_traffic_monitoring.arrival_image', 'tb_traffic_monitoring.arrival_latlng', 
                'tb_traffic_monitoring.loading_starttime', 'tb_traffic_monitoring.loading_start_note', 'tb_traffic_monitoring.loading_start_image', 
                'tb_traffic_monitoring.loading_start_latlng', 'tb_traffic_monitoring.loading_finishtime', 'tb_traffic_monitoring.loading_finish_note', 
                'tb_traffic_monitoring.loading_finish_image', 'tb_traffic_monitoring.loading_finish_latlng', 'tb_traffic_monitoring.id_company', 
                'tb_company.name as company_name')
            ->selectRaw('DATE(tb_traffic_monitoring.arrival_ata) as arrival_ata')
            ->selectRaw('DATE(tb_traffic_monitoring.loading_start) as loading_start')
            ->selectRaw('DATE(tb_traffic_monitoring.loading_finish) as loading_finish')
            ->leftJoin('tb_transport_order as tro', 'tb_traffic_monitoring.transport_order_id', '=', 'tro.id')
            ->leftJoin('tb_manifests', 'tro.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_clients', 'tro.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_status_traffic_monitoring as ttm_status', 'tb_traffic_monitoring.tm_status', '=', 'ttm_status.id')
            ->leftJoin('tb_customers as dest', 'tro.dest_id', '=', 'dest.id')
            ->leftJoin('tb_areas as dest_area', 'dest.area_id', '=', 'dest_area.id')
            ->leftJoin('tb_company', 'tb_traffic_monitoring.id_company', '=', 'tb_company.id')
            ->where('tb_traffic_monitoring.deleted', 0)
            ->where('tb_manifests.deleted', 0)
            ->where('tro.deleted', 0)
            ->where('tb_traffic_monitoring.id',  $id_traffic);

            $data_response = $data->first();

            $respon = array(
                "code" => "01",
                "data" => $data_response,
            );

            $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function UpdateOrder (Request $request) {
        $id_traffic = $request->id_traffic;
        $id_driver = $request->id_driver;
        $status = $request->status;
        $note = $request->note;
        $latlng = $request->latlng;

        $validator = Validator::make($request->all(), [
            'id_traffic'    => 'required',
            'id_driver'     => 'required',
            'status'        => 'required',
            'image'         => 'mimes:jpeg,png'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $name_file = '';
            if($request->file('image')) {
                $size = floor($request->file('image')->getSize() / 1024);
                if ($size > 2000) {
                    $respon = array(
                        "code" => "02",
                        "message" =>  "File terlalu besar",
                    );
                    return response()->json($respon);
                } else {
                    $date_now = date('YmdHis');
                    $ext = $request->file('image')->getClientOriginalExtension();
                    $path = $request->file('image')->move(env("PATH_IMAGE_ORDER"), $request->id_traffic.'_'.$date_now.'.'.$ext);
                    $name_file = $request->id_traffic.'_'.$date_now.'.'.$ext;
                }
            }
            try
            {
                $update = TrafficMonitoring::find($id_traffic);
                if ($status === env("TRAFFIC_MONITORING_STATUS_ARRIVAL")) {
                    $update->arrival_ata = date('Y-m-d');
                    $update->arrival_atatime = date('H:i');
                    $update->arrival_note = $note;
                    if ($name_file != "") {
                        $update->arrival_image = $name_file;
                    }
                    $update->arrival_latlng = $latlng;
                } else if ($status === env("TRAFFIC_MONITORING_STATUS_LOADING") 
                                || $status === env("TRAFFIC_MONITORING_STATUS_UNLOADING")) {
                    $update->loading_start = date('Y-m-d');
                    $update->loading_starttime = date('H:i');
                    $update->loading_start_note = $note;
                    if ($name_file != "") {
                        $update->loading_start_image = $name_file;
                    }
                    $update->loading_start_latlng = $latlng;
                } else {
                    $update->loading_finish = date('Y-m-d');
                    $update->loading_finishtime = date('H:i');
                    $update->departure_ata = date('Y-m-d');
                    $update->departure_atatime = date('H:i');
                    $update->loading_finish_note = $note;
                    if ($name_file != "") {
                        $update->loading_finish_image = $name_file;
                    }
                    $update->loading_finish_latlng = $latlng;
                }
                $update->tm_status = $status;
                $update->save();

                $respon = array(
                    "code" => "01",
                    "message" =>  "Perubahan data berhasil",
                );

                $response_code = 200;
            }
            catch(Exception $e)
            {
                $respon = array(
                    "code" => "03",
                    "message" =>  "Tidak dapat terhubung dengan server, silahkan coba lagi nanti !",
                    "error_message" =>  $e,
                );
            }
        }

        return response()->json($respon, $response_code);
    }
}