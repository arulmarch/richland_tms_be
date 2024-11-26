<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\TransportOrder;
use App\Models\TrafficMonitoring;
use App\Models\Manifest;
use Illuminate\Support\Facades\DB;

class TrafficMonitoringController extends Controller
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

    public function GetDataByManifest (Request $request) {

        $id_company = $request->id_company;
        $id_manifest = $request->id_manifest;

        $validator = Validator::make($request->all(), [
            'id_company'    => 'required',
            'id_manifest'   => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = TransportOrder::select('tb_traffic_monitoring.id', 'tb_traffic_monitoring.transport_order_id', 'tb_traffic_monitoring.tm_state',
                                    'tb_customers.customer_id', 'tb_customers.name', 'tb_customers.address1 as address', 'tb_traffic_monitoring.tm_status as status',
                                    'status.status_name')
                    ->leftJoin('tb_traffic_monitoring', 'tb_transport_order.id', '=', 'tb_traffic_monitoring.transport_order_id')
                    ->leftJoin('tb_customers', 'tb_traffic_monitoring.point_id', '=', 'tb_customers.id')
                    ->leftJoin('tb_status_traffic_monitoring as status', 'tb_traffic_monitoring.tm_status', '=', 'status.id')
                    ->where('tb_transport_order.manifest_id', $id_manifest)
                    ->where('tb_transport_order.deleted', 0)
                    ->where('tb_traffic_monitoring.deleted', 0)
                    ->orderBy('tb_traffic_monitoring.transport_order_id', 'ASC')
                    ->orderBy('tb_traffic_monitoring.tm_state', 'DESC')
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

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = TrafficMonitoring::select('tb_traffic_monitoring.*','tb_customers.customer_id', 'tb_customers.name', 'tb_customers.address1 as address')
                    ->leftJoin('tb_customers', 'tb_traffic_monitoring.point_id', '=', 'tb_customers.id')
                    ->where('tb_traffic_monitoring.id', $id)
                    ->where('tb_traffic_monitoring.deleted', 0)
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
                $response_code = 200;
            }
        }

        return response()->json($respon, $response_code);
    }

    public function UpdateTimeWindow (Request $request) {
        $id = $request->id;
        $arrival_eta = $request->arrival_eta;
        $arrival_etatime = $request->arrival_etatime;
        $arrival_ata = $request->arrival_ata;
        $arrival_atatime = $request->arrival_atatime;
        $arrival_note = $request->arrival_note;
        $spm_submit = $request->spm_submit;
        $spm_submittime = $request->spm_submittime;
        $loading_start = $request->loading_start;
        $loading_starttime = $request->loading_starttime;
        $loading_start_note = $request->loading_start_note;
        $loading_finish = $request->loading_finish;
        $loading_finishtime = $request->loading_finishtime;
        $loading_finish_note = $request->loading_finish_note;
        $documentation = $request->documentation;
        $documentationtime = $request->documentationtime;
        $departure_eta = $request->departure_eta;
        $departure_etatime = $request->departure_etatime;
        $departure_ata = $request->departure_ata;
        $departure_atatime = $request->departure_atatime;
        $tm_state = $request->tm_state;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'tm_state'      => 'required',
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

                $data_manifest = TrafficMonitoring::select('tb_transport_order.manifest_id')
                                    ->leftJoin('tb_transport_order', 'tb_traffic_monitoring.transport_order_id', '=', 'tb_transport_order.id')
                                    ->where('tb_traffic_monitoring.id', $id)->first();
                
                DB::beginTransaction();

                $update = TrafficMonitoring::find($id);
                $update->arrival_eta = $arrival_eta;
                $update->arrival_etatime = $arrival_etatime;
                $update->arrival_ata = $arrival_ata;
                $update->arrival_atatime = $arrival_atatime;
                $update->arrival_note = $arrival_note;
                $update->spm_submit = $spm_submit;
                $update->spm_submittime = $spm_submittime;
                $update->loading_start = $loading_start;
                $update->loading_starttime = $loading_starttime;
                $update->loading_start_note = $loading_start_note;
                $update->loading_finish = $loading_finish;
                $update->loading_finishtime = $loading_finishtime;
                $update->loading_finish_note = $loading_finish_note;
                $update->documentation = $documentation;
                $update->documentationtime = $documentationtime;
                $update->departure_eta = $departure_eta;
                $update->departure_etatime = $departure_etatime;
                $update->departure_ata = $departure_ata;
                $update->departure_atatime = $departure_atatime;
                $update->updated_by = $updated_by;
                $update->id_company = $id_company;

                $bool_arrival_ata = false;
                $bool_loading_start = false;
                $bool_loading_finish = false;

                if ($arrival_ata != null || $arrival_ata != '') {
                    $bool_arrival_ata = true;
                }
                if ($loading_start != null || $loading_start != '') {
                    $bool_loading_start = true;
                }
                if ($loading_finish != null || $loading_finish != '') {
                    $bool_loading_finish = true;
                }

                $status = null;
                if ($bool_arrival_ata === true && $bool_loading_start === true && $bool_loading_finish === true) {
                    $status = 6;
                } else if ($bool_arrival_ata === true && $bool_loading_start === true) {
                    if ($tm_state == env('TM_STATE_ORIGIN')) {
                        $status = 4;
                    } else {
                        $status = 5;
                    }
                } else if ($bool_arrival_ata === true) {
                    $status = 3;
                }

                if ($status === null) {
                    
                    $respon = array(
                        "code" => "02",
                        "message" => "Gagal menyimpan data, pastikan data yang anda isi sudah sesuai dengan urutannya",
                    );

                } else {
                    $update->tm_status = $status;

                    $update->save();

                    $update_manifest = Manifest::find($data_manifest->manifest_id);
                    $update_manifest->manifest_status = env('MANIFEST_STATUS_DELIVERY');
                    $update_manifest->updated_by = $updated_by;

                    $update_manifest->save();

                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menyimpan data",
                    );

                    $response_code = 200;

                    DB::commit();
                }

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

    public function GetTrafficMonitoring(Request $request) {
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
        
            $data = TransportOrder::select(
                        'tb_transport_order.manifest_id', 'tb_clients.client_id', 'tb_vehicles.vehicle_id', 'tb_vehicles.no_lambung',
                        'tb_transporters.transporter_id', 'tb_vehicle_types.type_id', 'tb_transport_mode.transport_mode',
                        'tb_manifests.manifest_status', 'tb_customers.customer_id as point_id', 'tb_customers.id as customer_id',
                        'tb_customers.name', 'tb_customers.address1', 'tb_areas.area_id', 'tb_traffic_monitoring.tm_state',
                        'tb_traffic_monitoring.tm_status', 'tb_traffic_monitoring.arrival_note as notes', 'tb_transport_order.do_number', 
                        'tb_transport_order.so_number', 'tb_company.name as name_company', 'tb_manifest_status.status as manifest_status_name',
                        'tb_status_traffic_monitoring.status_name')
                    ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
                    ->selectRaw('DATE(tb_traffic_monitoring.arrival_eta) as arrival_eta')
                    ->selectRaw('DATE(tb_traffic_monitoring.arrival_ata) as arrival_ata')
                    ->leftJoin('tb_clients', 'tb_transport_order.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                    ->leftJoin('tb_manifest_status', 'tb_manifests.manifest_status', '=', 'tb_manifest_status.id')
                    ->leftJoin('tb_transport_mode', 'tb_manifests.mode', '=', 'tb_transport_mode.id')
                    ->leftJoin('tb_vehicles', 'tb_manifests.vehicle_id', '=', 'tb_vehicles.id')
                    ->leftJoin('tb_transporters', 'tb_vehicles.transporter_id', '=', 'tb_transporters.id')
                    ->leftJoin('tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                    ->leftJoin('tb_traffic_monitoring', 'tb_transport_order.id', '=', 'tb_traffic_monitoring.transport_order_id')
                    ->leftJoin('tb_status_traffic_monitoring', 'tb_traffic_monitoring.tm_status', '=', 'tb_status_traffic_monitoring.id')
                    ->leftJoin('tb_customers', 'tb_traffic_monitoring.point_id', '=', 'tb_customers.id')
                    ->leftJoin('tb_areas', 'tb_customers.area_id', '=', 'tb_areas.id')
                    ->leftJoin('tb_company as tb_company', 'tb_transport_order.id_company', '=', 'tb_company.id')
                    ->where('tb_manifests.manifest_status', '!=', 0)
                    ->where('tb_transport_order.id_company', $id_company)
                    ->where('tb_transport_order.deleted', 0)
                    ->where('tb_traffic_monitoring.deleted', 0)
                    ->orderBy('tb_transport_order.manifest_id', 'DESC')
                    ->orderBy('tb_traffic_monitoring.transport_order_id', 'ASC')
                    ->orderBy('tb_traffic_monitoring.tm_state', 'DESC');

            if ($search_by !== null && $search_by !== "") {
                $data->where($search_by, 'like', '%' . $search_input . '%');
            }

            if ($status !== null && $status !== "") {
                $data->where('tb_manifests.manifest_status', $status);
            }

            if ($filter_date === true) {
                $data->whereRaw("tb_manifests.schedule_date BETWEEN '$start_date' and '$end_date'");
            }
    
            $data_response = $data->get();

            $row = 0;
            foreach($data_response as $data_row) {
                $no_lambung = "";
                if (isset($data_row->no_lambung)) {
                    $no_lambung = " - " . $data_row->no_lambung;
                }
                $transport_mode = "";
                if (isset($data_row->transport_mode)) {
                    $transport_mode = " - " . $data_row->transport_mode;
                }
                $data_response[$row]->group = "Manifest " . $data_row->manifest_id . " - " . $data_row->schedule_date . " - " 
                    . $data_row->vehicle_id . $no_lambung . " - " . $data_row->type_id . " - " . $data_row->transporter_id . $transport_mode;
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

}