<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Vehicle;
use App\Models\Manifest;
use App\Models\Pod;
use App\Models\ServiceOrder;
use App\Models\TransportOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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

    public function getAssetData (Request $request) {
        
        $id_company = $request->id_company;

        $data = Vehicle::select('tb_vehicle_types.type_id as type')
                ->selectRaw('count(type) as c_type')
                ->leftJoin('tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                ->where('tb_vehicles.deleted', 0)
                ->groupBy('tb_vehicle_types.type_id')
                ->orderBy('type', 'ASC');

        if ($id_company !== null && $id_company !== "") {
            $data->where('tb_vehicles.id_company', $id_company);
        }

        $data_response = $data->get();

        $data_vehicle = Vehicle::where('deleted', 0)->where('id_company', $id_company)->get();
        $count_data = $data_vehicle->count();

        $respon = array(
          "code" => "01",
          "total_data" => $count_data,
          "data" => $data_response
        );

        return response()->json($respon);
    }

    public function getShipmentActivity(Request $request) {

        $id_company = $request->id_company;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $validator = Validator::make($request->all(), [
            'from_date'      => 'required',
            'to_date'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $data = Manifest::select('t_type.type_id as type')
                    ->selectRaw('count(tb_manifests.id) as quantity')
                    ->leftJoin('tb_vehicles', 'tb_manifests.vehicle_id', '=', 'tb_vehicles.id')
                    ->leftJoin('tb_vehicle_types as t_type', 'tb_vehicles.type', '=', 't_type.id')
                    ->where('schedule_date', '>=', $from_date)
                    ->where('schedule_date', '<=', $to_date)
                    ->where('tb_manifests.deleted', 0)
                    ->groupBy('t_type.type_id');

            if ($id_company !== null && $id_company !== "") {
                $data->where('tb_vehicles.id_company', $id_company);
            }

            $data_response = $data->get();

            $respon = array(
                "code" => "01",
                "data" => $data_response
            );
        }

        return response()->json($respon);
    }

    public function getTrafficMonitoringStatus(Request $request) {

        $id_company = $request->id_company;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $validator = Validator::make($request->all(), [
            'from_date'      => 'required',
            'to_date'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $data_op_kpi = $this->selectDataTraffic($id_company)->selectRaw('concat(round((count(tb_traffic_monitoring.arrival_ata) / count(tb_transport_order.manifest_id)) * 100,2),"%") as count_data')
                    ->where('tb_traffic_monitoring.tm_state' , 'Origin')
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $op_kpi = $data_op_kpi->first();

            $data_ol_kpi = $this->selectDataTraffic($id_company)->selectRaw('concat(round((count(tb_traffic_monitoring.loading_start) / count(tb_transport_order.manifest_id)) * 100,2),"%") as count_data')
                    ->where('tb_traffic_monitoring.tm_state' , 'Origin')
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $ol_kpi = $data_ol_kpi->first();

            $data_of_kpi = $this->selectDataTraffic($id_company)->selectRaw('concat(round((count(tb_traffic_monitoring.loading_finish) / count(tb_transport_order.manifest_id)) * 100,2),"%") as count_data')
                    ->where('tb_traffic_monitoring.tm_state' , 'Origin')
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $of_kpi = $data_of_kpi->first();

            $data_da_kpi = $this->selectDataTraffic($id_company)->selectRaw('concat(round((count(tb_traffic_monitoring.arrival_ata) / count(tb_transport_order.manifest_id)) * 100,2),"%") as count_data')
                    ->where('tb_traffic_monitoring.tm_state' , 'Destination')
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $da_kpi = $data_da_kpi->first();

            $data_du_kpi = $this->selectDataTraffic($id_company)->selectRaw('concat(round((count(tb_traffic_monitoring.loading_start) / count(tb_transport_order.manifest_id)) * 100,2),"%") as count_data')
                    ->where('tb_traffic_monitoring.tm_state' , 'Destination')
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $du_kpi = $data_du_kpi->first();

            $data_df_kpi = $this->selectDataTraffic($id_company)->selectRaw('concat(round((count(tb_traffic_monitoring.loading_finish) / count(tb_transport_order.manifest_id)) * 100,2),"%") as count_data')
                    ->where('tb_traffic_monitoring.tm_state' , 'Destination')
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $df_kpi = $data_df_kpi->first();

            $data_origin_pick = $this->selectDataTraffic($id_company)->where('tb_traffic_monitoring.tm_state' , 'Origin')
                    ->whereRaw('tb_traffic_monitoring.arrival_ata is not null')
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $origin_pick = $data_origin_pick->count();

            $data_origin_load = $this->selectDataTraffic($id_company)->where('tb_traffic_monitoring.tm_state' , 'Origin')
                    ->where('tb_traffic_monitoring.loading_start', '!=', NULL)
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $origin_load = $data_origin_load->count();

            $data_origin_finishload = $this->selectDataTraffic($id_company)->where('tb_traffic_monitoring.tm_state' , 'Origin')
                    ->where('tb_traffic_monitoring.loading_finish', '!=', NULL)
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $origin_finishload = $data_origin_finishload->count();

            $data_destination_arrived = $this->selectDataTraffic($id_company)->where('tb_traffic_monitoring.tm_state' , 'Destination')
                    ->where('tb_traffic_monitoring.arrival_ata', '!=', NULL)
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $destination_arrived = $data_destination_arrived->count();

            $data_destination_unload = $this->selectDataTraffic($id_company)->where('tb_traffic_monitoring.tm_state' , 'Destination')
                    ->where('tb_traffic_monitoring.loading_start', '!=', NULL)
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $destination_unload = $data_destination_unload->count();

            $data_destination_finishunload = $this->selectDataTraffic($id_company)->where('tb_traffic_monitoring.tm_state' , 'Destination')
                    ->where('tb_traffic_monitoring.loading_finish', '!=', NULL)
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $destination_finishunload = $data_destination_finishunload->count();

            $respon = array(
                "code" => "01",
                "op_kpi" => is_null($op_kpi->count_data) ? "0%" : $op_kpi->count_data,
                "ol_kpi" => is_null($ol_kpi->count_data) ? "0%" : $ol_kpi->count_data,
                "of_kpi" => is_null($of_kpi->count_data) ? "0%" : $of_kpi->count_data,
                "da_kpi" => is_null($da_kpi->count_data) ? "0%" : $da_kpi->count_data,
                "du_kpi" => is_null($du_kpi->count_data) ? "0%" : $du_kpi->count_data,
                "df_kpi" => is_null($df_kpi->count_data) ? "0%" : $df_kpi->count_data,
                "origin_pick" => $origin_pick,
                "origin_load" => $origin_load,
                "origin_finishload" => $origin_finishload,
                "destination_arrived" => $destination_arrived,
                "destination_unload" => $destination_unload,
                "destination_finishunload" => $destination_finishunload,
            );
        }

        return response()->json($respon);
    }

    function selectDataTraffic($id_company) {
        
        $data_traffic = TransportOrder::select('tb_traffic_monitoring.id')
            ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
            ->leftJoin('tb_traffic_monitoring', 'tb_transport_order.id', '=', 'tb_traffic_monitoring.transport_order_id')
            ->where('tb_manifests.manifest_status', '!=', 0)
            ->where('tb_transport_order.deleted', 0)
            ->where('tb_traffic_monitoring.deleted', 0);

        if ($id_company !== null && $id_company !== "") {
            $data_traffic->where('tb_transport_order.id_company', $id_company);
        }

        return $data_traffic;

    }

    public function getTruckingOrder(Request $request) {

        $id_company = $request->id_company;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $validator = Validator::make($request->all(), [
            'from_date'      => 'required',
            'to_date'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = DB::table('tb_trucking_order')
                ->selectRaw('(case when (tb_trucking_order.tr_status = 0) then "OPEN" 
                    when (tb_trucking_order.tr_status = 1) then "CLOSE" end) AS tr_status,count(id) as value')
                ->where('schedule_date', '>=', $from_date)
                ->where('schedule_date', '<=', $to_date)
                ->where('tb_trucking_order.deleted', 0)
                ->groupBy('tb_trucking_order.tr_status');

            if ($id_company !== null && $id_company !== "") {
                $data->where('id_company', $id_company);
            }

            $data_response = $data->get();

            $respon = array(
                "code" => "01",
                "data" => $data_response
            );
        }

        return response()->json($respon);
    }

    public function getSummaryData(Request $request) {

        $id_company = $request->id_company;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $validator = Validator::make($request->all(), [
            'from_date'      => 'required',
            'to_date'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $data_total_cost = DB::table('tb_manifests')
                    ->selectRaw('concat("IDR ", format(sum(variable_cost)+sum(sum_component_cost),0)) as total_cost')
                    ->where('schedule_date', '>=', $from_date)
                    ->where('schedule_date', '<=', $to_date)
                    ->where('tb_manifests.manifest_status', '!=', 0)
                    ->where('tb_manifests.deleted', 0);
            if ($id_company !== null && $id_company !== "") {
                $data_total_cost->where('id_company', $id_company);
            }
            $total_cost = $data_total_cost->first()->total_cost;

            $data_total_revenue = DB::table('tb_manifests')
                    ->selectRaw('concat("IDR ", format(sum(client_variable_cost)+sum(client_sum_component_cost),0)) as total_revenue')
                    ->where('schedule_date', '>=', $from_date)
                    ->where('schedule_date', '<=', $to_date)
                    ->where('tb_manifests.manifest_status', '!=', 0)
                    ->where('tb_manifests.deleted', 0);
            if ($id_company !== null && $id_company !== "") {
                $data_total_revenue->where('id_company', $id_company);
            }
            $total_revenue = $data_total_revenue->first()->total_revenue;

            $data_total_shipment = DB::table('tb_manifests')
                    ->where('schedule_date', '>=', $from_date)
                    ->where('schedule_date', '<=', $to_date)
                    ->where('tb_manifests.deleted', 0);
            if ($id_company !== null && $id_company !== "") {
                $data_total_shipment->where('id_company', $id_company);
            }
            $total_shipment = $data_total_shipment->count();

            $data_pending_pod = $this->selectDataPOD($id_company)
                    ->where('tb_pod.pod_time', NULL)
                    ->where('tb_manifests.schedule_date', '>=', $from_date)
                    ->where('tb_manifests.schedule_date', '<=', $to_date);
            $pending_pod = $data_pending_pod->count();

            $data_finish_pod = $this->selectDataPOD($id_company)
                    ->where('tb_pod.pod_time', '!=', NULL)
                    ->where('schedule_date', '>=', $from_date)
                    ->where('schedule_date', '<=', $to_date);
            $finish_pod = $data_finish_pod->count();

            $total_amount = ServiceOrder::selectRaw('format(sum(tb_service_order.total_amount),0) as grand_total')
                                ->leftJoin('tb_service_order_status', 'tb_service_order.service_status', '=', 'tb_service_order_status.id')
                                ->where('tb_service_order.deleted', 0)
                                ->whereIn('tb_service_order_status.name', ['QUEUED','IN PROGRESS']);
            if ($id_company !== null && $id_company !== "") {
                $total_amount->where('tb_service_order.id_company', $id_company);
            }
            $respon_total_amount = $total_amount->first();

            $respon = array(
                "code" => "01",
                "total_cost" => $total_cost,
                "total_revenue" => $total_revenue,
                "total_shipment" => $total_shipment,
                "pending_pod" => $pending_pod,
                "finish_pod" => $finish_pod,
                "total_amount_service_unit" => $respon_total_amount->grand_total,
            );
        }

        return response()->json($respon);
    }

    function selectDataPOD($id_company) {
        $pod_data = Pod::select(
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
                ->where('tb_pod.deleted', 0);

        if ($id_company !== null && $id_company !== "") {
            $pod_data->where('tb_pod.id_company', $id_company);
        }

        return $pod_data;
    }

    public function getDataUnitService (Request $request) {

        $id_company = $request->id_company;

        $data = ServiceOrder::selectRaw('tb_service_order.*, format(tb_service_order.total_amount,0) as cost, 
                        tb_vehicles.vehicle_id as vehicle_id_name, tb_vendors.vendor_id as vendor_id_name,
                        tb_service_order_status.name as name_status')
                ->selectRaw('case 
                        when tb_service_order.service_type = 1 then "INTERM CAR SERVICE"
                        when tb_service_order.service_type = 2 then "FULL CAR SERVICE"
                        when tb_service_order.service_type = 3 then "MAJOR CAR SERVICE"
                        end as service_type_name')
                ->leftJoin('tb_service_order_status', 'tb_service_order.service_status', '=', 'tb_service_order_status.id')
                ->leftJoin('tb_vehicles', 'tb_service_order.vehicle_id', '=', 'tb_vehicles.id')
                ->leftJoin('tb_vendors', 'tb_service_order.vendor_id', '=', 'tb_vendors.id')
                ->where('tb_service_order.deleted', 0)
                ->where('tb_vendors.deleted', 0)
                ->whereIn('tb_service_order_status.name', ['QUEUED','IN PROGRESS']);
        if ($id_company !== null && $id_company !== "") {
            $data->where('tb_service_order.id_company', $id_company);
        }

        $respon_data = $data->get();

        $respon = array(
            "code" => "01",
            "data" => $respon_data
        );

        $response_code = 200;

        return response()->json($respon, $response_code);
    }
}