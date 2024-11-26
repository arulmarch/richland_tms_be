<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use Illuminate\Support\Facades\DB;

class ProcedureController extends Controller
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

    public function CloseTrafficMonitoring (Request $request) {

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $validator = Validator::make($request->all(), [
            'start_date'      => 'required',
            'end_date'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            try {

                $data = DB::table('tb_transport_order as tto')
                        ->select('tc.client_id', 'tto.manifest_id', 'ttm.arrival_ata', 'ttm.id', 'ttm.tm_state', 'ttm.tm_status')
                        ->selectRaw('DATE(tm.schedule_date) as schedule_date')
                        ->selectRaw("DATE(DATE_ADD(tm.schedule_date, INTERVAL 1 DAY)) as schedule_date_day")
                        ->selectRaw("DATE(NOW()) as date_now")
                        ->leftJoin('tb_manifests as tm', 'tto.manifest_id', '=', 'tm.id')
                        ->leftJoin('tb_traffic_monitoring as ttm', 'tto.id', '=', 'ttm.transport_order_id')
                        ->leftJoin('tb_clients as tc', 'tto.client_id', '=', 'tc.id')
                        ->where('tm.manifest_status', '!=', 0)
                        ->where('ttm.tm_status', '!=', 6)
                        ->where('tm.deleted', 0)
                        ->where('ttm.deleted', 0)
                        ->whereRaw('ttm.id is not null')
                        ->whereRaw("tm.schedule_date BETWEEN '$start_date' and '$end_date'")
                        ->get();

                foreach ($data as $data_object) {
                    if ($data_object->tm_state === "Origin") {

                        DB::table('tb_traffic_monitoring')
                        ->where('id', $data_object->id)
                        ->update([
                            'tm_status' => 6,
                            'arrival_ata' => $data_object->schedule_date,
                            'arrival_atatime' => "07:36:34",
                            'loading_start' => $data_object->schedule_date,
                            'loading_starttime' => "07:50:00",
                            'loading_finish' => $data_object->schedule_date,
                            'loading_finishtime' => "08:20:00",
                            'departure_ata' => $data_object->schedule_date,
                            'departure_atatime' => "08:25:00",
                            'updated_by' => 1,
                            'updated_date' => $data_object->date_now,
                        ]);
                    } else {

                        DB::table('tb_traffic_monitoring')
                        ->where('id', $data_object->id)
                        ->update([
                            'tm_status' => 1,
                            'arrival_ata' => $data_object->schedule_date_day,
                            'arrival_atatime' => "09:05:00",
                            'loading_start' => $data_object->schedule_date_day,
                            'loading_starttime' => "09:20:00",
                            'loading_finish' => $data_object->schedule_date_day,
                            'loading_finishtime' => "09:50:00",
                            'departure_ata' => $data_object->schedule_date_day,
                            'departure_atatime' => "10:00:00",
                            'updated_by' => 1,
                            'updated_date' => $data_object->date_now,
                        ]);
                    }
                }
                
                $respon = array(
                    "code" => "01",
                    "message" => "Success Update Data",
                    "data" => $data 
                );

            } catch(Exception $e) {
                $respon = array(
                    "code" => "03",
                    "message" =>  "Ada masalah dengan server, harap coba lagi nanti !",
                    "error_message" =>  $e,
                );
            }
        }
        return response()->json($respon);
    }

    public function CloseTrafficMonitoringByPass (Request $request) {

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $validator = Validator::make($request->all(), [
            'start_date'      => 'required',
            'end_date'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            try {

                $data = DB::table('tb_transport_order as tto')
                        ->select('tc.client_id', 'tto.manifest_id', 'tm.schedule_date', 'ttm.arrival_ata', 'ttm.id', 'ttm.tm_state')
                        ->selectRaw("(tm.schedule_date + 24*3600) as schedule_date_day")
                        ->selectRaw("date_format(from_unixtime(tm.schedule_date), '%d %M %Y') AS schedule_date_format")
                        ->selectRaw("UNIX_TIMESTAMP(NOW()) as date_now")
                        ->leftJoin('tb_manifests as tm', 'tto.manifest_id', '=', 'tm.id')
                        ->leftJoin('tb_traffic_monitoring as ttm', 'tto.id', '=', 'ttm.transport_order_id')
                        ->leftJoin('tb_clients as tc', 'tto.client_id', '=', 'tc.id')
                        ->whereRaw("tm.schedule_date BETWEEN UNIX_TIMESTAMP(STR_TO_DATE('$start_date', '%d/%m/%Y')) 
                            and UNIX_TIMESTAMP(STR_TO_DATE('$end_date', '%d/%m/%Y'))")
                        ->get();

                foreach ($data as $data_object) {
                    if ($data_object->tm_state === "Origin") {
                        DB::table('tb_traffic_monitoring')
                        ->where('id', $data_object->id)
                        ->update([
                            'tm_status' => 3,
                            'arrival_ata' => $data_object->schedule_date,
                            'arrival_atatime' => "07:36:34",
                            'loading_start' => $data_object->schedule_date,
                            'loading_starttime' => "07:50:00",
                            'loading_finish' => $data_object->schedule_date,
                            'loading_finishtime' => "08:20:00",
                            'departure_ata' => $data_object->schedule_date,
                            'departure_atatime' => "08:25:00",
                            'updated_by' => 1,
                            'updated_date' => $data_object->date_now,
                        ]);
                    } else {
                        DB::table('tb_traffic_monitoring')
                        ->where('id', $data_object->id)
                        ->update([
                            'tm_status' => 3,
                            'arrival_ata' => $data_object->schedule_date_day,
                            'arrival_atatime' => "09:05:00",
                            'loading_start' => $data_object->schedule_date_day,
                            'loading_starttime' => "09:20:00",
                            'loading_finish' => $data_object->schedule_date_day,
                            'loading_finishtime' => "09:50:00",
                            'departure_ata' => $data_object->schedule_date_day,
                            'departure_atatime' => "10:00:00",
                            'updated_by' => 1,
                            'updated_date' => $data_object->date_now,
                        ]);
                    }
                }
                
                $respon = array(
                    "code" => "01",
                    "data" => $data 
                );

            } catch(Exception $e) {
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