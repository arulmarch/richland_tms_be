<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\TransportOrder;

class ListOrderManagementController extends Controller
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

    public function ListProgressOrder (Request $request) {
        $id_driver = $request->id_driver;

        $validator = Validator::make($request->all(), [
            'id_driver'      => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = TransportOrder::select(
                'tb_transport_order.id as tro_id', 'tb_transport_order.manifest_id', 'tb_manifests.tr_id', 'ttm.id as ttm_id', 
                'ttm.tm_state', 'ttm.tm_status', 'tb_manifests.manifest_status', 'tb_clients.name as client_name', 'origin.name as origin_name', 
                'origin.address1 as origin_address', 'origin_area.area_id as origin_area_name', 'dest.name as dest_name', 
                'dest.address1 as dest_address', 'dest_area.area_id as dest_area_name', 'tb_transport_order.id_company', 
                'tb_company.name as company_name')
            ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
            ->selectRaw('DATE(tb_trucking_order.req_pickup_time) as pickup_time')
            ->selectRaw('DATE(tb_trucking_order.req_arrival_time) as arrival_time')
            ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_traffic_monitoring as ttm', 'tb_transport_order.id', '=', 'ttm.transport_order_id')
            ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
            ->leftJoin('tb_clients', 'tb_transport_order.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
            ->leftJoin('tb_customers as origin', 'tb_trucking_order.origin_id', '=', 'origin.id')
            ->leftJoin('tb_areas as origin_area', 'tb_trucking_order.origin_area_id', '=', 'origin_area.id')
            ->leftJoin('tb_customers as dest', 'tb_trucking_order.dest_id', '=', 'dest.id')
            ->leftJoin('tb_areas as dest_area', 'tb_trucking_order.dest_area_id', '=', 'dest_area.id')
            ->where('tb_manifests.deleted', 0)
            ->where('tb_transport_order.deleted', 0)
            ->where('tb_trucking_order.deleted', 0)
            ->where('ttm.deleted', 0)
            ->where('tb_manifests.driver_id', $id_driver)
            ->where('tb_manifests.manifest_status', '!=', 0)
            ->where('ttm.tm_status', '!=', 6)
            ->groupBy('tb_transport_order.manifest_id')
            ->orderBy('tb_manifests.schedule_date', 'desc');

            $data_response = $data->get();

            $respon = array(
                "code" => "01",
                "data" => $data_response,
            );

            $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function ListAllOrder (Request $request) {
        $id_driver = $request->id_driver;
        $offset = $request->offset;

        $validator = Validator::make($request->all(), [
            'id_driver'      => 'required',
            'offset'         => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = TransportOrder::select(
                'tb_transport_order.id as tro_id', 'tb_transport_order.manifest_id', 'tb_manifests.tr_id', 'ttm.id as ttm_id', 
                'ttm.tm_state', 'ttm.tm_status', 'tb_manifests.manifest_status', 'tb_clients.name as client_name', 'origin.name as origin_name', 
                'origin.address1 as origin_address', 'origin_area.area_id as origin_area_name', 'dest.name as dest_name', 
                'dest.address1 as dest_address', 'dest_area.area_id as dest_area_name', 'tb_transport_order.id_company', 
                'tb_company.name as company_name')
            ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
            ->selectRaw('DATE(tb_trucking_order.req_pickup_time) as pickup_time')
            ->selectRaw('DATE(tb_trucking_order.req_arrival_time) as arrival_time')
            ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_traffic_monitoring as ttm', 'tb_transport_order.id', '=', 'ttm.transport_order_id')
            ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
            ->leftJoin('tb_clients', 'tb_transport_order.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
            ->leftJoin('tb_customers as origin', 'tb_trucking_order.origin_id', '=', 'origin.id')
            ->leftJoin('tb_areas as origin_area', 'tb_trucking_order.origin_area_id', '=', 'origin_area.id')
            ->leftJoin('tb_customers as dest', 'tb_trucking_order.dest_id', '=', 'dest.id')
            ->leftJoin('tb_areas as dest_area', 'tb_trucking_order.dest_area_id', '=', 'dest_area.id')
            ->where('tb_manifests.deleted', 0)
            ->where('tb_transport_order.deleted', 0)
            ->where('tb_trucking_order.deleted', 0)
            ->where('ttm.deleted', 0)
            ->where('tb_manifests.driver_id', $id_driver)
            ->where('tb_manifests.manifest_status', '!=', 0)
            ->groupBy('tb_transport_order.manifest_id')
            ->orderBy('tb_manifests.schedule_date', 'desc')
            ->offset($offset)
            ->limit(10)
            ->get();

            $i = 0;
            foreach($data as $item) {
                $total_origin = TransportOrder::select('ttm.id as ttm_id')
                ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                ->leftJoin('tb_traffic_monitoring as ttm', 'tb_transport_order.id', '=', 'ttm.transport_order_id')
                ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
                ->leftJoin('tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
                ->where('tb_manifests.deleted', 0)
                ->where('tb_transport_order.deleted', 0)
                ->where('tb_trucking_order.deleted', 0)
                ->where('ttm.deleted', 0)
                ->where('ttm.tm_state', 'Origin')
                ->where('tb_manifests.driver_id', $id_driver)
                ->where('tb_manifests.manifest_status', '!=', 0)
                ->orderBy('tb_manifests.schedule_date', 'desc')
                ->get()->count();
                $data[$i]['origin_status_count'] = $total_origin;

                $total_dest = TransportOrder::select('ttm.id as ttm_id')
                ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                ->leftJoin('tb_traffic_monitoring as ttm', 'tb_transport_order.id', '=', 'ttm.transport_order_id')
                ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
                ->leftJoin('tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
                ->where('tb_manifests.deleted', 0)
                ->where('tb_transport_order.deleted', 0)
                ->where('tb_trucking_order.deleted', 0)
                ->where('ttm.deleted', 0)
                ->where('ttm.tm_state', 'Destination')
                ->where('tb_manifests.driver_id', $id_driver)
                ->where('tb_manifests.manifest_status', '!=', 0)
                ->orderBy('tb_manifests.schedule_date', 'desc')
                ->get()->count();
                $data[$i]['dest_status_count'] = $total_dest;

                $i++;
            }

            $total_row = TransportOrder::select('tb_transport_order.manifest_id')
            ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_traffic_monitoring as ttm', 'tb_transport_order.id', '=', 'ttm.transport_order_id')
            ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
            ->leftJoin('tb_clients', 'tb_transport_order.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
            ->leftJoin('tb_customers as origin', 'tb_trucking_order.origin_id', '=', 'origin.id')
            ->leftJoin('tb_areas as origin_area', 'tb_trucking_order.origin_area_id', '=', 'origin_area.id')
            ->leftJoin('tb_customers as dest', 'tb_trucking_order.dest_id', '=', 'dest.id')
            ->leftJoin('tb_areas as dest_area', 'tb_trucking_order.dest_area_id', '=', 'dest_area.id')
            ->where('tb_manifests.deleted', 0)
            ->where('tb_transport_order.deleted', 0)
            ->where('tb_trucking_order.deleted', 0)
            ->where('ttm.deleted', 0)
            ->where('tb_manifests.driver_id', $id_driver)
            ->where('tb_manifests.manifest_status', '!=', 0)
            ->groupBy('tb_transport_order.manifest_id')
            ->orderBy('tb_manifests.schedule_date', 'desc')
            ->get()->count();

            $respon = array(
                "code" => "01",
                "total_row" => $total_row,
                "data" => $data
            );

            $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function ListOrderOrigin (Request $request) {
        $id_driver = $request->id_driver;
        $id_trucking_order = $request->id_trucking_order;

        $validator = Validator::make($request->all(), [
            'id_trucking_order'     => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $data = TransportOrder::select(
                'tb_transport_order.id as tro_id', 'tb_transport_order.manifest_id', 'tb_manifests.tr_id', 'ttm.id as id_traffic', 
                'ttm.tm_state', 'ttm.tm_status', 'tb_manifests.manifest_status', 'tb_clients.name as client_name', 'origin.name as origin_name', 
                'origin.address1 as origin_address', 'origin_area.area_id as origin_area_name', 'origin.position', 'ttm_status.status_name',
                'tb_transport_order.id_company', 'tb_company.name as company_name')
            ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
            ->selectRaw('DATE(tb_transport_order.req_arrival_date) as arrival_date')
            ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_traffic_monitoring as ttm', 'tb_transport_order.id', '=', 'ttm.transport_order_id')
            ->leftJoin('tb_status_traffic_monitoring as ttm_status', 'ttm.tm_status', '=', 'ttm_status.id')
            ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
            ->leftJoin('tb_clients', 'tb_transport_order.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
            ->leftJoin('tb_customers as origin', 'tb_transport_order.origin_id', '=', 'origin.id')
            ->leftJoin('tb_areas as origin_area', 'origin.area_id', '=', 'origin_area.id')
            ->where('tb_manifests.deleted', 0)
            ->where('tb_transport_order.deleted', 0)
            ->where('tb_trucking_order.deleted', 0)
            ->where('ttm.deleted', 0)
            ->where('ttm.tm_state', 'Origin')
            ->where('tb_trucking_order.id',  $id_trucking_order)
            ->orderBy('ttm.id', 'asc');

            $data_response = $data->get();
                                    
            $respon = array(
                "code" => "01",
                "data" => $data_response,
            );

            $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function ListOrderDest (Request $request) {
        $id_driver = $request->id_driver;
        $id_trucking_order = $request->id_trucking_order;

        $validator = Validator::make($request->all(), [
            'id_trucking_order'     => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
           
            $data = TransportOrder::select(
                'tb_transport_order.id as tro_id', 'tb_transport_order.manifest_id', 'tb_manifests.tr_id', 'ttm.id as id_traffic', 
                'ttm.tm_state', 'ttm.tm_status', 'tb_manifests.manifest_status', 'tb_clients.name as client_name', 'dest.name as dest_name', 
                'dest.address1 as dest_address', 'dest_area.area_id as dest_area_name', 'dest.position', 'ttm_status.status_name',
                'tb_transport_order.id_company', 'tb_company.name as company_name')
            ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
            ->selectRaw('DATE(tb_transport_order.delivery_date) as delivery_date')
            ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_traffic_monitoring as ttm', 'tb_transport_order.id', '=', 'ttm.transport_order_id')
            ->leftJoin('tb_status_traffic_monitoring as ttm_status', 'ttm.tm_status', '=', 'ttm_status.id')
            ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
            ->leftJoin('tb_clients', 'tb_transport_order.client_id', '=', 'tb_clients.id')
            ->leftJoin('tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
            ->leftJoin('tb_customers as dest', 'tb_transport_order.dest_id', '=', 'dest.id')
            ->leftJoin('tb_areas as dest_area', 'dest.area_id', '=', 'dest_area.id')
            ->where('tb_manifests.deleted', 0)
            ->where('tb_transport_order.deleted', 0)
            ->where('tb_trucking_order.deleted', 0)
            ->where('ttm.deleted', 0)
            ->where('ttm.tm_state', 'Destination')
            ->where('tb_trucking_order.id',  $id_trucking_order)
            ->orderBy('ttm.id', 'asc');

            $data_response = $data->get();

            $respon = array(
                "code" => "01",
                "data" => $data_response,
            );

            $response_code = 200;

        }

        return response()->json($respon,  $response_code);
    }

}