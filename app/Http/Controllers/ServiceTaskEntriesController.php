<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\ServiceTaskEntries;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\DB;

class ServiceTaskEntriesController extends Controller
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

        $id_service_order = $request->id_service_order;

        $validator = Validator::make($request->all(), [
            'id_service_order'      => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = ServiceTaskEntries::select('tb_service_task_entries.*', 'task.name as task_name', 'user.name as created_name', 'user_update.name as updated_name')
                    ->leftJoin('tb_service_tasks as task', 'tb_service_task_entries.id_service_task', '=', 'task.id')
                    ->leftJoin('user as user', 'tb_service_task_entries.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_service_task_entries.updated_by', '=', 'user_update.user_id')
                    ->where('tb_service_task_entries.id_service_order', $id_service_order)
                    ->where('tb_service_task_entries.deleted', 0)
                    ->orderBy('tb_service_task_entries.created_date', 'desc')
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
            $data = ServiceTaskEntries::select('tb_service_task_entries.*', 'task.name as task_name')
                    ->leftJoin('tb_service_tasks as task', 'tb_service_task_entries.id_service_task', '=', 'task.id')
                    ->where('tb_service_task_entries.id', $id)
                    ->where('tb_service_task_entries.deleted', 0)
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
        $id_service_order = $request->id_service_order;
        $id_service_task = $request->id_service_task;
        $qty = $request->qty;
        $price = $request->price;
        $amount = $request->amount;
        $created_by = $request->created_by;

        $validator = Validator::make($request->all(), [
            'id_service_order'      => 'required',
            'id_service_task'       => 'required',
            'qty'                   => 'required',
            'price'                 => 'required',
            'amount'                => 'required'
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

                $create = new ServiceTaskEntries;
                $create->id_service_order = $id_service_order;
                $create->id_service_task = $id_service_task;
                $create->qty = $qty;
                $create->price = $price;
                $create->amount = $amount;
                $create->created_by = $created_by;

                $create->save();

                $data_task = ServiceTaskEntries::select('amount')
                            ->where('id_service_order', $id_service_order)
                            ->where('deleted', 0)
                            ->get();
                
                $data_order = ServiceOrder::select('vat')
                            ->where('id', $id_service_order)
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
                if ($data_order !== null && $data_order->vat !== null && $data_order->vat !== 0) {
                    $total_vat = $total * ($data_order->vat/100);
                }

                $total_amount = $total + $total_vat;

                $update = ServiceOrder::find($id_service_order);
                $update->sub_total = $total;
                $update->total_vat = $total_vat;
                $update->total_amount = $total_amount;
                $update->updated_by = $created_by;

                $update->save();

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

        return response()->json($respon);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $id_service_order = $request->id_service_order;
        $id_service_task = $request->id_service_task;
        $qty = $request->qty;
        $price = $request->price;
        $amount = $request->amount;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'                    => 'required',
            'id_service_order'      => 'required',
            'id_service_task'       => 'required',
            'qty'                   => 'required',
            'price'                 => 'required',
            'amount'                => 'required'
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

                $update = ServiceTaskEntries::find($id);
                $update->id_service_order = $id_service_order;
                $update->id_service_task = $id_service_task;
                $update->qty = $qty;
                $update->price = $price;
                $update->amount = $amount;
                $update->updated_by = $updated_by;

                $update->save();

                $data_task = ServiceTaskEntries::select('amount')
                            ->where('id_service_order', $id_service_order)
                            ->where('deleted', 0)
                            ->get();
    
                $data_order = ServiceOrder::select('vat')
                            ->where('id', $id_service_order)
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
                if ($data_order !== null && $data_order->vat !== null && $data_order->vat !== 0) {
                    $total_vat = $total * ($data_order->vat/100);
                }

                $total_amount = $total + $total_vat;

                $update_order = ServiceOrder::find($id_service_order);
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

                $update = ServiceTaskEntries::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();

                $data_service_task = ServiceTaskEntries::select('id_service_order')
                                    ->where('id', $id)
                                    ->first();
                
                $id_order = 0;

                if ($data_service_task !== null && $data_service_task->id_service_order !== null) {
                    $id_order = $data_service_task->id_service_order;
                }

                $data_task = ServiceTaskEntries::select('amount')
                            ->where('id_service_order', $id_order)
                            ->where('deleted', 0)
                            ->get();
                
                $data_order = ServiceOrder::select('vat')
                            ->where('id', $id_order)
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
                if ($data_order !== null && $data_order->vat !== null && $data_order->vat !== 0) {
                    $total_vat = $total * ($data_order->vat/100);
                }

                $total_amount = $total + $total_vat;

                if ($id_order !== 0) {
                    $update_order = ServiceOrder::find($id_order);
                    $update_order->sub_total = $total;
                    $update_order->total_vat = $total_vat;
                    $update_order->total_amount = $total_amount;
                    $update_order->updated_by = $updated_by;
    
                    $update_order->save();

                    DB::commit();
                }
                                
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
}