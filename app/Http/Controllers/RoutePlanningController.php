<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\TransportOrder;
use App\Models\Manifest;
use App\Models\TrafficMonitoring;
use App\Models\Pod;
use App\Models\TransportMode;
use App\Models\TransporterRate;
use App\Models\ClientRate;
use App\Models\TruckingOrder;
use Illuminate\Support\Facades\DB;

class RoutePlanningController extends Controller
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

    public function GetDataTransportOrder (Request $request) {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $client_id = $request->client_id;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_company'        => 'required',
            'start_date'        => 'required',
            'end_date'          => 'required',
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = TransportOrder::select(
                        'tb_transport_order.id', 'tb_transport_order.reference_id', 'tb_transport_order.do_number',
                        'tb_transport_order.so_number', 'tb_transport_order.created_by', 'tb_transport_order.created_date', 
                        'tb_transport_order.updated_by', 'tb_transport_order.updated_date', 'user.name as created_name', 
                        'user_update.name as updated_name', 'tb_company.name as name_company', 'clients.client_id as client_id',
                        'origin.customer_id as origin_id', 'origin.name as origin_name', 'origin.address1 as origin_address',
                        'dest.customer_id as dest_id', 'dest.name as dest_name', 'dest.address1 as dest_address', 'tb_transport_order.order_qty',
                        'tb_transport_order.origin_id as origin_point_id', 'tb_transport_order.dest_id as dest_point_id')
                    ->selectRaw('DATE(tb_transport_order.delivery_date) as delivery_date')
                    ->selectRaw('DATE(tb_transport_order.req_arrival_date) as req_arrival_date')
                    ->selectRaw('case 
                                when tb_transport_order.order_status = 0 then "OPEN"
                                when tb_transport_order.order_status = 1 then "CLOSE"
                                end as order_status_name')
                    ->leftJoin('user as user', 'tb_transport_order.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_transport_order.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_clients as clients', 'tb_transport_order.client_id', '=', 'clients.id')
                    ->leftJoin('tb_customers as origin', 'tb_transport_order.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_customers as dest', 'tb_transport_order.dest_id', '=', 'dest.id')
                    ->where('tb_transport_order.id_company', $id_company)
                    ->where('tb_transport_order.deleted', 0)
                    ->where('tb_transport_order.order_status', 0)
                    ->whereRaw("tb_transport_order.manifest_id is null")
                    ->whereRaw("tb_transport_order.delivery_date BETWEEN '$start_date' and '$end_date'");

            if ($client_id !== null && $client_id !== "") {
                $data->where('tb_transport_order.client_id', $client_id);
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

    public function GetDataManifest (Request $request) {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $client_id = $request->client_id;
        $trip = $request->trip;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_company'        => 'required',
            'start_date'        => 'required',
            'end_date'          => 'required',
            'trip'              => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = Manifest::select(
                        'tb_manifests.id', 'tb_transport_order.id as id_tro', 'tb_transport_order.reference_id', 'tb_transport_order.do_number',
                        'tb_transport_order.so_number', 'tb_transport_order.created_by', 'tb_transport_order.created_date', 
                        'tb_transport_order.updated_by', 'tb_transport_order.updated_date', 'user.name as created_name', 
                        'user_update.name as updated_name', 'tb_company.name as name_company', 'clients.client_id as client_id',
                        'origin.customer_id as origin_id', 'origin.name as origin_name', 'origin.address1 as origin_address',
                        'dest.customer_id as dest_id', 'dest.name as dest_name', 'dest.address1 as dest_address', 'vehicle.vehicle_id', 'vehicle.no_lambung', 
                        'type.type_id', 'transporter.transporter_id', 'tb_transport_order.order_qty', 'tb_manifests.manifest_status', 'status.status as status_name')
                    ->selectRaw('DATE(tb_transport_order.delivery_date) as delivery_date')
                    ->selectRaw('DATE(tb_transport_order.req_arrival_date) as req_arrival_date')
                    ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
                    ->leftJoin('user as user', 'tb_manifests.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_manifests.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_manifests.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_transport_order as tb_transport_order', 'tb_manifests.id', '=', 'tb_transport_order.manifest_id')
                    ->leftJoin('tb_clients as clients', 'tb_transport_order.client_id', '=', 'clients.id')
                    ->leftJoin('tb_customers as origin', 'tb_transport_order.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_customers as dest', 'tb_transport_order.dest_id', '=', 'dest.id')
                    ->leftJoin('tb_vehicles as vehicle', 'tb_manifests.vehicle_id', '=', 'vehicle.id')
                    ->leftJoin('tb_vehicle_types as type', 'vehicle.type', '=', 'type.id')
                    ->leftJoin('tb_transporters as transporter', 'vehicle.transporter_id', '=', 'transporter.id')
                    ->leftJoin('tb_manifest_status as status', 'tb_manifests.manifest_status', '=', 'status.id')
                    ->where('tb_manifests.id_company', $id_company)
                    ->where('tb_manifests.deleted', 0)
                    ->where('tb_manifests.trip', $trip)
                    ->whereRaw("tb_manifests.schedule_date BETWEEN '$start_date' and '$end_date'");
            
            if ($client_id !== null && $client_id !== "") {
                $data->where('tb_transport_order.client_id', $client_id);
            }

            $data->orderBy('tb_manifests.id', 'asc');
            $data->orderBy('tb_transport_order.id', 'asc');

            $data_response = $data->get();

            $row = 0;
            foreach($data_response as $data_row) {
                $no_lambung = "";
                if (isset($data_row->no_lambung)) {
                    $no_lambung = " - " . $data_row->no_lambung;
                }
                $data_response[$row]->group = "Manifest " . $data_row->id . " - " . $data_row->schedule_date . " - " 
                    . $data_row->vehicle_id . $no_lambung . " - " . $data_row->type_id . " - " . $data_row->transporter_id;
                $row = $row + 1;
            }

            $respon = array(
                "code" => "01",
                "data" => $data_response
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function SearchManifest (Request $request) {

        $id_company = $request->id_company;
        $search = $request->search;

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

            $data = Manifest::select('id')
                ->where('id_company', $id_company)
                ->whereRaw("id like'%$search%'")
                ->where('deleted', 0)
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

    public function RouteAddTransportOrder(Request $request) {
        $id_manifest = $request->id_manifest;
        $transport_order = $request->transport_order;
        $id_company = $request->id_company;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id_manifest'       => 'required',
            'transport_order'   => 'required',
            'id_company'        => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (Manifest::where('id', $id_manifest)->where('manifest_status', env('MANIFEST_STATUS_CONFIRM'))
                ->where('deleted', 0)->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Manifest Already confirmed",
                );
            } else {
                try 
                {
                    $data_manifest = Manifest::selectRaw("tb_manifests.*, DATE(tb_manifests.schedule_date) as schedule_date_format")
                                                ->where('id', $id_manifest)->first();

                    $failed_transport_order = array();
                    $load_kg_tro = array(); // order_qty

                    DB::beginTransaction();

                    $array_point_id_origin = array();
                    $array_point_id_dest = array();

                    foreach($transport_order as $order) {
                        if (TransportOrder::where('id', $order['id_tro'])->where('deleted', 0)->where('id_company', $id_company)
                            ->whereRaw("(manifest_id is not null or delivery_date != '$data_manifest->schedule_date_format')")->count() > 0) {
                            array_push($failed_transport_order, $order['id_tro']);
                        } else {
                            array_push($load_kg_tro, $order['load_kg']);
                            
                            $update_tro = TransportOrder::find($order['id_tro']);
                            $update_tro->manifest_id = $id_manifest;
                            $update_tro->trip = $data_manifest->trip;
                            $update_tro->updated_by = $updated_by;
                            $update_tro->id_company = $id_company;
                            $update_tro->save();

                            if ($this->checkDataPointId($array_point_id_origin, $order['origin_point_id'], "Origin", $id_manifest)) {
                                $create_trm_origin = new TrafficMonitoring;
                                $create_trm_origin->point_id = $order['origin_point_id'];
                                $create_trm_origin->tm_state = "Origin";
                                $create_trm_origin->transport_order_id = $order['id_tro'];
                                $create_trm_origin->created_by = $updated_by;
                                $create_trm_origin->id_company = $id_company;
                                $create_trm_origin->save();

                                array_push($array_point_id_origin, $order['origin_point_id']);
                            }

                            if ($this->checkDataPointId($array_point_id_dest, $order['dest_point_id'], "Destination", $id_manifest)) {
                                $create_trm_dest = new TrafficMonitoring;
                                $create_trm_dest->point_id = $order['dest_point_id'];
                                $create_trm_dest->tm_state = "Destination";
                                $create_trm_dest->transport_order_id = $order['id_tro'];
                                $create_trm_dest->created_by = $updated_by;
                                $create_trm_dest->id_company = $id_company;
                                $create_trm_dest->save();

                                array_push($array_point_id_dest, $order['dest_point_id']);
                            }

                            $create_pod = new Pod;
                            $create_pod->transport_order_id = $order['id_tro'];
                            $create_pod->created_by = $updated_by;
                            $create_pod->id_company = $id_company;
                            $create_pod->save();
                        }    
                    }

                    $total_load_kg = $data_manifest->load_kg;
                    foreach($load_kg_tro as $load_kg) {
                        $total_load_kg = $total_load_kg + $load_kg;
                    }

                    $update_manifest = Manifest::find($id_manifest);
                    $update_manifest->load_kg = $total_load_kg;
                    $update_manifest->updated_by = $updated_by;
                    $update_manifest->id_company = $id_company;
                    $update_manifest->save();

                    DB::commit();

                    $message_failed_tro = "";
                    if (!empty($failed_transport_order)) {
                        $data_failed_tro = "";
                        $count = 0;
                        foreach($failed_transport_order as $failed_tro) {
                            if ($data_failed_tro === "") {
                                $data_failed_tro = "( " . $failed_tro;
                            } else {
                                $data_failed_tro = $data_failed_tro . ", " . $failed_tro;
                            }

                            if ($count === sizeof($failed_transport_order) - 1) {
                                $data_failed_tro = $data_failed_tro . " )";
                            }

                            $count = $count + 1;
                        }

                        $message_failed_tro = "Transport Order " . $data_failed_tro . " failed on route";
                    }
                                    
                    $respon = array(
                        "code" => "01",
                        "failed_tro" => $message_failed_tro,
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

    public function UnRouteTransportOrder(Request $request) {
        $id_manifest = $request->id_manifest;
        $transport_order = $request->transport_order;
        $id_company = $request->id_company;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id_manifest'       => 'required',
            'transport_order'   => 'required',
            'id_company'        => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (Manifest::where('id', $id_manifest)->where('manifest_status', env('MANIFEST_STATUS_CONFIRM'))
                ->where('deleted', 0)->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Manifest Already confirmed",
                );
            } else {
                try 
                {
                    $data_manifest = Manifest::where('id', $id_manifest)->first();

                    $load_kg_tro = array(); // order_qty

                    DB::beginTransaction();

                    foreach($transport_order as $order) {

                        array_push($load_kg_tro, $order['load_kg']);
                        
                        $update_tro = TransportOrder::find($order['id_tro']);
                        $update_tro->manifest_id = null;
                        $update_tro->trip = null;
                        $update_tro->updated_by = $updated_by;
                        $update_tro->id_company = $id_company;
                        $update_tro->save();

                        TrafficMonitoring::where('transport_order_id', $order['id_tro'])->update(['deleted' => 1]);

                        Pod::where('transport_order_id', $order['id_tro'])->update(['deleted' => 1]);

                    }

                    $total_load_kg = $data_manifest->load_kg;
                    foreach($load_kg_tro as $load_kg) {
                        $total_load_kg = $total_load_kg - $load_kg;
                    }

                    $update_manifest = Manifest::find($id_manifest);
                    $update_manifest->load_kg = $total_load_kg;
                    $update_manifest->updated_by = $updated_by;
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

    public function CreateManifest (Request $request) {
        $vehicle_id = $request->vehicle_id;
        $driver = $request->driver;
        $co_driver = $request->co_driver;
        $trip = $request->trip;
        $schedule_date = $request->schedule_date;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'vehicle_id'        => 'required',
            'driver'            => 'required',
            'co_driver'         => 'required',
            'trip'              => 'required',
            'schedule_date'     => 'required'
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
                    $create = new Manifest;
                    $create->vehicle_id = $vehicle_id;
                    $create->driver_id = $driver;
                    $create->co_driver_id = $co_driver;
                    $create->trip = $trip;
                    $create->schedule_date = $schedule_date;
                    $create->manifest_status = env('MANIFEST_STATUS_OPEN');
                    $create->created_by = $created_by;
                    $create->id_company = $id_company;
    
                    $create->save();
                                    
                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menyimpan data",
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

    public function GetDataManifestById(Request $request) {

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

            $data = Manifest::select(
                        'tb_manifests.id', 'tb_manifests.trip', 'tb_manifests.tr_id', 'tb_manifests.start', 'tb_manifests.finish', 'tb_manifests.file_name',
                        'tb_manifests.mileage', 'tb_manifests.order_case', 'tb_manifests.mode', 'tb_manifests.id_company', 'tb_manifests.load_kg',
                        'tb_manifests.approved_by', 'mode.transport_mode', 'tb_company.name as name_company', 'tb_manifests.driver_id',
                        'tb_manifests.co_driver_id', 'tb_drivers.name as driver_name', 'co_driver.name as co_driver_name', 'tb_manifests.vehicle_id',
                        'vehicle.vehicle_id as vehicle_name', 'tb_vehicle_types.type_id as type_name', 'tb_transporters.transporter_id as transporter_name',
                        'vehicle.max_volume', 'vehicle.max_weight', 'tb_manifests.load_m3', 'tb_manifests.manifest_status', 'tb_transporters.name as transporter_full_name',
                        'tb_clients.client_id', 'tb_clients.name as client_id_name', 'tb_manifest_status.status as manifest_status_name', 
                        'area_origin.area_id as origin_area_id', 'area_origin.description as origin_area_name', 'area_dest.area_id as dest_area_id', 
                        'area_dest.description as dest_area_name', 'tb_manifests.variable_cost', 'tb_manifests.client_variable_cost', 'vehicle.no_lambung')
                    ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
                    ->selectRaw('case 
                                when tb_manifests.order_case = 0 then "Regular"
                                when tb_manifests.order_case = 1 then "Urgent"
                                end as order_case_name')
                    ->selectRaw('(case vehicle.status when 1 then "ON CALL" else "DEDICATED" end) as vehicle_status_name')
                    ->leftJoin('tb_company as tb_company', 'tb_manifests.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_transport_mode as mode', 'tb_manifests.mode', '=', 'mode.id')
                    ->leftJoin('tb_drivers as tb_drivers', 'tb_manifests.driver_id', '=', 'tb_drivers.id')
                    ->leftJoin('tb_drivers as co_driver', 'tb_manifests.co_driver_id', '=', 'co_driver.id')
                    ->leftJoin('tb_vehicles as vehicle', 'tb_manifests.vehicle_id', '=', 'vehicle.id')
                    ->leftJoin('tb_vehicle_types as tb_vehicle_types', 'vehicle.type', '=', 'tb_vehicle_types.id')
                    ->leftJoin('tb_transporters as tb_transporters', 'vehicle.transporter_id', '=', 'tb_transporters.id')
                    ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
                    ->leftJoin('tb_clients', 'tb_trucking_order.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_manifest_status', 'tb_manifests.manifest_status', '=', 'tb_manifest_status.id')
                    ->leftJoin('tb_areas as area_origin', 'tb_trucking_order.origin_area_id', '=', 'area_origin.id')
                    ->leftJoin('tb_areas as area_dest', 'tb_trucking_order.dest_area_id', '=', 'area_dest.id')
                    ->where('tb_manifests.id', $id)
                    ->where('tb_manifests.deleted', 0)
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

    public function SearchDataTransportMode(Request $request) {

        $search = $request->search;

        $data = TransportMode::select('id', 'transport_mode')
            ->whereRaw("transport_mode like'%$search%'")
            ->get();

        $respon = array(
            "code" => "01",
            "data" => $data 
        );

        $response_code = 200;

        return response()->json($respon, $response_code);
    }

    public function UpdateManifest(Request $request) {
        $id = $request->id;
        $start = $request->start;
        $finish = $request->finish;
        $mileage = $request->mileage;
        $trucking_order_id = $request->trucking_order_id;
        $transport_mode = $request->transport_mode;
        $order_case = $request->order_case;
        $approved_by = $request->approved_by;
        $driver = $request->driver;
        $co_driver = $request->co_driver;
        $load_volume = $request->load_volume;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'id_company'    => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (Manifest::where('tr_id', $trucking_order_id)->where('id','!=', $id)
                ->where('deleted', 0)->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Trucking order has been used",
                );
            } else {
                try 
                {
                    $update = Manifest::find($id);
                    $update->start = $start;
                    $update->finish = $finish;
                    $update->mileage = $mileage;
                    $update->tr_id = $trucking_order_id;
                    $update->mode = $transport_mode;
                    $update->order_case = $order_case;
                    $update->approved_by = $approved_by;
                    $update->driver_id = $driver;
                    $update->co_driver_id = $co_driver;
                    $update->load_m3 = $load_volume;
                    $update->updated_by = $updated_by;
                    $update->id_company = $id_company;

                    $update->save();
                                    
                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menyimpan data",
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
        }

        return response()->json($respon, $response_code);
    }

    public function RouteConfirmManifest(Request $request) {
        $id_manifest = $request->id_manifest;
        $id_company = $request->id_company;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
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
            if ($data_transporter_rate === NULL) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Transporter Rate belum tersedia !",
                );
            } else if ($data_client_rate === NULL) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Client Rate belum tersedia !",
                );
            } else {
                try 
                {
                    DB::beginTransaction();

                    $load_kg = $data_manifest->load_kg != null ? $data_manifest->load_kg : 0;

                    $transporter_variable_cost = 0;
                    if ($data_transporter_rate->rate_type === 1) {
                        $transporter_variable_cost = $data_transporter_rate->vehicle_rate;
                    } else {
                        $transporter_variable_cost = $load_kg * $data_transporter_rate->vehicle_rate;
                    }

                    $client_variable_cost = 0;
                    if ($data_client_rate->rate_type === 1) {
                        $client_variable_cost = $data_client_rate->vehicle_rate;
                    } else {
                        $client_variable_cost = $load_kg * $data_client_rate->vehicle_rate;
                    }

                    $status_manifest = env('MANIFEST_STATUS_CONFIRM');

                    $check_data_traffic = TrafficMonitoring::leftJoin('tb_transport_order', 'tb_traffic_monitoring.transport_order_id', '=', 'tb_transport_order.id')
                        ->where('tb_traffic_monitoring.tm_status', '!=', 1)
                        ->where('tb_transport_order.manifest_id', $id_manifest)
                        ->where('tb_traffic_monitoring.deleted', 0)
                        ->count();

                    if ($check_data_traffic > 0) {
                        $status_manifest = env('MANIFEST_STATUS_DELIVERY');
                    }
                    
                    $update_manifest = Manifest::find($id_manifest);
                    $update_manifest->manifest_status = $status_manifest;
                    $update_manifest->client_rate_id = $data_client_rate->id;
                    $update_manifest->min_weight_client_rate = $data_client_rate->min_weight;
                    $update_manifest->client_rate_status = $data_client_rate->rate_type;
                    $update_manifest->transporter_rate_id = $data_transporter_rate->id;
                    $update_manifest->min_weight_transporter_rate = $data_transporter_rate->min_weight;
                    $update_manifest->transporter_rate_status = $data_transporter_rate->rate_type;
                    $update_manifest->variable_cost = $transporter_variable_cost;
                    $update_manifest->client_variable_cost = $client_variable_cost;
                    $update_manifest->updated_by = $updated_by;
                    $update_manifest->id_company = $id_company;
                    $update_manifest->save();

                    $update_trucking_order = TruckingOrder::find($data_manifest->tr_id);
                    $update_trucking_order->tr_status = 1;
                    $update_trucking_order->updated_by = $updated_by;
                    $update_trucking_order->id_company = $id_company;
                    $update_trucking_order->save();

                    TransportOrder::where('manifest_id', $id_manifest)->update(
                        [
                            'order_status' => 1,
                            'updated_by' => $updated_by,
                            'id_company' => $id_company
                        ]
                    );
                    
                    DB::commit();

                    $respon = array(
                        "code" => "01",
                        "data_client_rate" => $data_client_rate,
                        "message" => "Confirm manifest berhasil",
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

    public function RouteUnConfirmManifest(Request $request) {
        $id_manifest = $request->id_manifest;
        $id_company = $request->id_company;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
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
            try 
            {

                $data_manifest = Manifest::select('trucking_order.client_id', 'trucking_order.origin_area_id', 'trucking_order.dest_area_id', 
                            'vehicle.transporter_id', 'vehicle.type', 'vehicle.status', 'tb_manifests.load_kg', 'tb_manifests.tr_id')
                        ->leftJoin('tb_trucking_order as trucking_order', 'tb_manifests.tr_id', '=', 'trucking_order.id')
                        ->leftJoin('tb_vehicles as vehicle', 'tb_manifests.vehicle_id', '=', 'vehicle.id')
                        ->where('tb_manifests.id', $id_manifest)
                        ->where('tb_manifests.id_company', $id_company)
                        ->where('tb_manifests.deleted', 0)
                        ->first();
                        
                DB::beginTransaction();
                
                $update_manifest = Manifest::find($id_manifest);
                $update_manifest->manifest_status = env('MANIFEST_STATUS_OPEN');
                $update_manifest->client_rate_id = null;
                $update_manifest->min_weight_client_rate = 0;
                $update_manifest->client_rate_status = null;
                $update_manifest->transporter_rate_id = null;
                $update_manifest->min_weight_transporter_rate = 0;
                $update_manifest->transporter_rate_status = null;
                $update_manifest->variable_cost = 0;
                $update_manifest->client_variable_cost = 0;
                $update_manifest->updated_by = $updated_by;
                $update_manifest->id_company = $id_company;
                $update_manifest->save();

                $update_trucking_order = TruckingOrder::find($data_manifest->tr_id);
                $update_trucking_order->tr_status = 0;
                $update_trucking_order->updated_by = $updated_by;
                $update_trucking_order->id_company = $id_company;
                $update_trucking_order->save();

                TransportOrder::where('manifest_id', $id_manifest)->update(
                    [
                        'order_status' => 0,
                        'updated_by' => $updated_by,
                        'id_company' => $id_company
                    ]
                );
                
                DB::commit();

                $respon = array(
                    "code" => "01",
                    "message" => "UnConfirm manifest berhasil",
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

        return response()->json($respon, $response_code);
    }

    public function UpdateVehicleRate (Request $request) {
        $id = $request->id;
        $vehicle_rate = $request->vehicle_rate;
        $type = $request->type;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'vehicle_rate'  => 'required',
            'type'          => 'required',
            'id_company'    => 'required'
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
                $update = Manifest::find($id);
                if ($type == env('COMPONENT_ENTRIES_TYPE_TRANSPORTER')) {
                    $update->variable_cost = $vehicle_rate;
                } else {
                    $update->client_variable_cost = $vehicle_rate;
                }
                $update->updated_by = $updated_by;
                $update->id_company = $id_company;

                $update->save();
                                
                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil menyimpan data",
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

    private function checkDataPointId($array_data, $point_id, $state, $id_manifest) {
        $isData = true;
        foreach($array_data as $data_object) {
            if ($data_object == $point_id) {
                $isData = false;
            }
        }
        if ($isData) {
            $check_data_traffic_monitoring = TrafficMonitoring::leftJoin('tb_transport_order', 'tb_traffic_monitoring.transport_order_id', '=', 'tb_transport_order.id')
                    ->where('tb_transport_order.manifest_id', $id_manifest)
                    ->where('tb_traffic_monitoring.point_id', $point_id)
                    ->where('tb_traffic_monitoring.tm_state', $state)
                    ->where('tb_traffic_monitoring.deleted', 0)
                    ->count();

            if ($check_data_traffic_monitoring > 0) {
                $isData = false;
            }
        }

        return $isData;
    }

    public function Delete (Request $request) {
        $id = $request->id;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'              => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (Manifest::where('id', $id)->where('manifest_status', env('MANIFEST_STATUS_CONFIRM'))
                ->where('deleted', 0)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Manifest Already confirmed",
                );
            } else {
                try 
                {
                    $data_tro = TransportOrder::where('manifest_id', $id)->get();

                    DB::beginTransaction();

                    foreach($data_tro as $tro) {
                        $update_tro = TransportOrder::find($tro['id']);
                        $update_tro->manifest_id = null;
                        $update_tro->trip = null;
                        $update_tro->updated_by = $updated_by;
                        $update_tro->save();

                        TrafficMonitoring::where('transport_order_id', $tro['id'])->update(['deleted' => 1]);

                        Pod::where('transport_order_id', $tro['id'])->update(['deleted' => 1]);
                    }

                    $update_manifest = Manifest::find($id);
                    $update_manifest->deleted = 1;
                    $update_manifest->updated_by = $updated_by;
                    $update_manifest->save();

                    DB::commit();
                                    
                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menghapus data",
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

    public function UploadFile (Request $request) {
        $id_manifest = $request->id_manifest;
        $file_data = $request->file_data;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id_manifest'       => 'required',
            'file_data'         => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $name_file = '';
            if($request->file('file_data')) {
                $size = floor($request->file('file_data')->getSize() / 1024);
                if ($size > 2000) { //2 MB
                    $respon = array(
                        "code" => "02",
                        "message" =>  "File terlalu besar",
                    );
                    return response()->json($respon);
                } else {
                    $date_now = date('YmdHisv');
                    $ext = $request->file('file_data')->getClientOriginalExtension();
                    $path = $request->file('file_data')->move(env("PATH_MANIFEST_FILE"), $date_now .'.'.$ext);
                    $name_file = $date_now .'.'.$ext;
                }
            }
            try 
            {
                $update = Manifest::where('id', $id_manifest);
                $update->update(
                    ['file_name' => $name_file],
                    ['updated_by' => $updated_by]
                );
                                
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