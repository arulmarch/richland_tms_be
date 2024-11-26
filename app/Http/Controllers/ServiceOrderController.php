<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\ServiceOrder;
use App\Models\ServiceTaskEntries;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceOrderType;
use Illuminate\Support\Facades\DB;

class ServiceOrderController extends Controller
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
        
            $data = ServiceOrder::select('tb_service_order.*', 'vehicle.vehicle_id as vehicle_id_name', 'vendor.vendor_id as vendor_id_name',
                        'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                    ->selectRaw('case 
                                    when service_type = 1 then "INTERM CAR SERVICE"
                                    when service_type = 2 then "FULL CAR SERVICE"
                                    when service_type = 3 then "MAJOR CAR SERVICE"
                                    end as service_type_name')
                    ->selectRaw('case 
                                    when service_status = 1 then "REJECTED"
                                    when service_status = 2 then "QUEUED"
                                    when service_status = 3 then "IN PROGRESS"
                                    when service_status = 4 then "COMPLETED"
                                    end as service_status_name')
                    ->leftJoin('tb_vehicles as vehicle', 'tb_service_order.vehicle_id', '=', 'vehicle.id')
                    ->leftJoin('tb_vendors as vendor', 'tb_service_order.vendor_id', '=', 'vendor.id')
                    ->leftJoin('user as user', 'tb_service_order.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_service_order.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_service_order.id_company', '=', 'tb_company.id')
                    ->where('tb_service_order.id_company', $id_company)
                    ->where('tb_service_order.deleted', 0)
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

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = ServiceOrder::select('tb_service_order.*', 'vehicle.vehicle_id as vehicle_id_name', 'vendor.vendor_id as vendor_id_name',
                       'tb_company.name as name_company', 'mechanic.name as assigned_to_name')
                    ->selectRaw('case 
                                    when service_type = 1 then "INTERM CAR SERVICE"
                                    when service_type = 2 then "FULL CAR SERVICE"
                                    when service_type = 3 then "MAJOR CAR SERVICE"
                                    end as service_type_name')
                    ->selectRaw('case 
                                    when service_status = 1 then "REJECTED"
                                    when service_status = 2 then "QUEUED"
                                    when service_status = 3 then "IN PROGRESS"
                                    when service_status = 4 then "COMPLETED"
                                    end as service_status_name')
                    ->leftJoin('tb_vehicles as vehicle', 'tb_service_order.vehicle_id', '=', 'vehicle.id')
                    ->leftJoin('tb_vendors as vendor', 'tb_service_order.vendor_id', '=', 'vendor.id')
                    ->leftJoin('tb_company as tb_company', 'tb_service_order.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_mechanics as mechanic', 'tb_service_order.assigned_to', '=', 'mechanic.id')
                    ->where('tb_service_order.id', $id)
                    ->where('tb_service_order.deleted', 0)
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

    public function Create (Request $request) {
        $reference = $request->reference;
        $vehicle_id = $request->vehicle_id;
        $odometer = $request->odometer;
        $registered_date = $request->registered_date;
        $registered_time = $request->registered_time;
        $completion_date = $request->completion_date;
        $completion_time = $request->completion_time;
        $vendor_id = $request->vendor_id;
        $service_type = $request->service_type;
        $service_status = $request->service_status;
        $assigned_to = $request->assigned_to;
        $vat = $request->vat;
        $remark = $request->remark;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'reference'         => 'required',
            'vehicle_id'        => 'required',
            'odometer'          => 'required',
            'registered_date'   => 'required',
            'registered_time'   => 'required',
            'vendor_id'         => 'required',
            'service_type'      => 'required',
            'service_status'    => 'required',
            'assigned_to'       => 'required',
            'vendor_id'         => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = ServiceOrder::where('reference', $reference)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "No Reference tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new ServiceOrder;
                    $create->reference = $reference;
                    $create->vehicle_id = $vehicle_id;
                    $create->odometer = $odometer;
                    $create->registered_date = $registered_date;
                    $create->registered_time = $registered_time;
                    $create->completion_date = $completion_date;
                    $create->completion_time = $completion_time;
                    $create->vendor_id = $vendor_id;
                    $create->service_type = $service_type;
                    $create->service_status = $service_status;
                    $create->assigned_to = $assigned_to;
                    $create->vat = $vat;
                    $create->remark = $remark;
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
        }

        return response()->json($respon);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $reference = $request->reference;
        $vehicle_id = $request->vehicle_id;
        $odometer = $request->odometer;
        $registered_date = $request->registered_date;
        $registered_time = $request->registered_time;
        $completion_date = $request->completion_date;
        $completion_time = $request->completion_time;
        $vendor_id = $request->vendor_id;
        $service_type = $request->service_type;
        $service_status = $request->service_status;
        $assigned_to = $request->assigned_to;
        $vat = $request->vat;
        $remark = $request->remark;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'reference'         => 'required',
            'vehicle_id'        => 'required',
            'odometer'          => 'required',
            'registered_date'   => 'required',
            'registered_time'   => 'required',
            'vendor_id'         => 'required',
            'service_type'      => 'required',
            'service_status'    => 'required',
            'assigned_to'       => 'required',
            'vendor_id'         => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = ServiceOrder::where('reference', $reference)
                ->where('id_company', $id_company)
                ->where('id','!=', $id)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "No Reference tidak boleh sama",
                );
            } else {
                try 
                {
                    DB::beginTransaction();

                    $update = ServiceOrder::find($id);
                    $update->reference = $reference;
                    $update->vehicle_id = $vehicle_id;
                    $update->odometer = $odometer;
                    $update->registered_date = $registered_date;
                    $update->registered_time = $registered_time;
                    $update->completion_date = $completion_date;
                    $update->completion_time = $completion_time;
                    $update->vendor_id = $vendor_id;
                    $update->service_type = $service_type;
                    $update->service_status = $service_status;
                    $update->assigned_to = $assigned_to;
                    $update->vat = $vat;
                    $update->remark = $remark;
                    $update->updated_by = $updated_by;
                    $update->id_company = $id_company;

                    $update->save();

                    $data_task = ServiceTaskEntries::select('amount')
                            ->where('id_service_order', $id)
                            ->where('deleted', 0)
                            ->get();

                    $data_order = ServiceOrder::select('vat')
                            ->where('id', $id)
                            ->where('deleted', 0)
                            ->first();

                    $total = 0;
                    if ($data_task !== null && sizeof($data_task) !== 0) {
                        foreach ($data_task as $task) {
                            $total = $total + $task->amount;
                        }
                    }

                    $total_vat = 0;
                    $total_amount = 0;
                    if ($vat !== null && $vat !== 0) {
                        $total_vat = $total * ($vat/100);
                    }

                    $total_amount = $total + $total_vat;

                    $update_order = ServiceOrder::find($id);
                    $update_order->sub_total = $total;
                    $update_order->total_vat = $total_vat;
                    $update_order->total_amount = $total_amount;
                    $update_order->updated_by = $updated_by;

                    $update_order->save();

                    DB::commit();
                                    
                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil menyimpan data",
                    );
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
                DB::beginTransaction();

                $update = ServiceOrder::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();

                ServiceTaskEntries::where('id_service_order', $id)
                                    ->update([
                                                'deleted' => 1,
                                                'updated_by' => $updated_by
                                            ]);

                DB::commit();
                                
                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil menghapus data",
                );
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

        return response()->json($respon);
    }

    public function SearchStatus (Request $request) {

        $id_company = $request->id_company;
        $search = $request->search;
        
        $data = ServiceOrderStatus::select('id', 'name')
                ->whereRaw("name like '%${search}%'")
                ->where('deleted', 0)
                ->limit(10);

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(id_company = $id_company or id_company is null)");
        }

        $data_response = $data->get();

        $respon = array(
            "code" => "01",
            "data" => $data_response 
        );

        $response_code = 200;

        return response()->json($respon, $response_code);
    }

    public function SearchType (Request $request) {

        $id_company = $request->id_company;
        $search = $request->search;
        
        $data = ServiceOrderType::select('id', 'name')
                ->whereRaw("name like '%${search}%'")
                ->where('deleted', 0)
                ->limit(10);

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(id_company = $id_company or id_company is null)");
        }

        $data_response = $data->get();

        $respon = array(
            "code" => "01",
            "data" => $data_response 
        );

        $response_code = 200;

        return response()->json($respon, $response_code);
    }

    public function GetTotalAmount(Request $request) {

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
            $data = ServiceOrder::select('sub_total', 'total_vat', 'total_amount')
                    ->where('tb_service_order.id', $id)
                    ->where('tb_service_order.deleted', 0)
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
}