<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MasterDriver;
use App\Models\TrackingDriver;
use App\Models\Manifest;
use Illuminate\Support\Facades\DB;

class TrackingDriverController extends Controller
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

    public function UpdateLocationDriver (Request $request) {
        $id_company = $request->id_company;
        $id_manifest = $request->id_manifest;
        $id_driver = $request->id_driver;
        $latlng = $request->latlng;

        $validator = Validator::make($request->all(), [
            'id_company'        => 'required',
            'id_driver'         => 'required',
            'latlng'            => 'required',
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            $check_data = Manifest::select('tb_manifests.id')
                            ->leftJoin('tb_transport_order', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                            ->leftJoin('tb_traffic_monitoring', 'tb_traffic_monitoring.transport_order_id', '=', 'tb_transport_order.id')
                            ->where('tb_manifests.id_company', $id_company)
                            ->where('tb_manifests.manifest_status', '!=', 0)
                            ->where('tb_traffic_monitoring.tm_status','!=', env('TRAFFIC_MONITORING_STATUS_COMPLETED'))
                            ->where('tb_manifests.deleted', 0)
                            ->where('tb_transport_order.deleted', 0)
                            ->where('tb_traffic_monitoring.deleted', 0)
                            ->where('tb_manifests.driver_id', $id_driver)
                            ->first();

            if (!empty($check_data)) {
                try 
                {
                    $create = new TrackingDriver;
                    $create->id_driver = $id_driver;
                    $create->id_manifest = $check_data->id;
                    $create->latlng = $latlng;

                    $create->save();
                                    
                    $respon = array(
                        "code" => "01",
                        "message" => "Update location success"
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
            } else {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Update location failed",
                );
            }

        }

        return response()->json($respon);
    }

    public function GetLocationDriver (Request $request) {
        $driver = $request->driver;
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

            $data_tracking = Manifest::select('tb_manifests.id as manifest_id', 'tb_vehicles.vehicle_id', 'tb_drivers.name', 'co_driver.name as co_driver_name', 
                                            'tb_clients.client_id as client_name', 'tb_manifests.driver_id')
                            ->leftJoin('tb_vehicles', 'tb_manifests.vehicle_id', '=', 'tb_vehicles.id')
                            ->leftJoin('tb_transport_order', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                            ->leftJoin('tb_traffic_monitoring', 'tb_traffic_monitoring.transport_order_id', '=', 'tb_transport_order.id')
                            ->leftJoin('tb_drivers', 'tb_manifests.driver_id', '=', 'tb_drivers.id')
                            ->leftJoin('tb_drivers as co_driver', 'tb_manifests.co_driver_id', '=', 'co_driver.id')
                            ->leftJoin('tb_clients', 'tb_transport_order.client_id', '=', 'tb_clients.id')
                            ->where('tb_manifests.id_company', $id_company)
                            ->where('tb_manifests.manifest_status', '!=', 0)
                            ->where('tb_traffic_monitoring.tm_status','!=', env('TRAFFIC_MONITORING_STATUS_COMPLETED'))
                            ->where('tb_manifests.deleted', 0)
                            ->where('tb_transport_order.deleted', 0)
                            ->where('tb_traffic_monitoring.deleted', 0);

            if ($driver !== null && sizeof($driver) !== 0) {
                $data_tracking->whereIn('tb_manifests.driver_id', $driver);
            }

            $data_tracking->groupBy('tb_manifests.driver_id');
            $data_tracking->orderBy('tb_manifests.id', 'asc');

            $data_response_tracking = $data_tracking->get();

            if (!empty($data_response_tracking)) {
                $data_tracking_filter = array();
                foreach($data_response_tracking as $data) {
                    $getTracking = TrackingDriver::where('id_manifest', $data->manifest_id)
                        ->where('id_driver', $data->driver_id)
                        ->where('latlng', '!=', NULL)
                        ->orderBy('created_at', 'desc')
                        ->first();
                
                    if (!empty($getTracking)) {
                        $data_tracking_filter_object = $data;
                        $data_tracking_filter_object->latlng = $getTracking->latlng;
                        $data_tracking_filter_object->tracking_date = $getTracking->created_at;
                        array_push($data_tracking_filter, $data_tracking_filter_object);
                    }
                }
              
                $respon = array(
                    "code" => "01",
                    "data" => $data_tracking_filter,
                );

                $response_code = 200;
            } else {
                $respon = array(
                    "code" => "02",
                    "message" => "Not found driver",
                );
            }
            
        }

        return response()->json($respon, $response_code);
    }

}