<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\TransportOrder;
use App\Models\MasterCustomer;
use Illuminate\Support\Facades\DB;

class TransportOrderController extends Controller
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
        
            $data = TransportOrder::select(
                        'tb_transport_order.id', 'tb_transport_order.reference_id', 'tb_transport_order.do_number',
                        'tb_transport_order.so_number', 'tb_transport_order.created_by', 'tb_transport_order.created_date', 
                        'tb_transport_order.updated_by', 'tb_transport_order.updated_date', 'user.name as created_name', 
                        'user_update.name as updated_name', 'tb_company.name as name_company', 'clients.client_id as client_id',
                        'origin.customer_id as origin_id', 'origin.name as origin_name', 'origin.address1 as origin_address',
                        'dest.customer_id as dest_id', 'dest.name as dest_name', 'dest.address1 as dest_address', 'tb_transport_order.order_status')
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
                    ->where('tb_transport_order.deleted', 0);

            if ($search_by !== null && $search_by !== "") {
                $data->where($search_by, 'like', '%' . $search_input . '%');
            }

            if ($status !== null && $status !== "") {
                $data->where('tb_transport_order.order_status', $status);
            }

            if ($filter_date === true) {
                $data->whereRaw("tb_transport_order.delivery_date BETWEEN '$start_date' and '$end_date'");
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
            $data = TransportOrder::select(
                    'tb_transport_order.id', 'tb_transport_order.reference_id', 'tb_transport_order.do_number',
                    'tb_transport_order.so_number', 'tb_transport_order.created_by', 'tb_transport_order.created_date', 
                    'tb_transport_order.updated_by', 'tb_transport_order.updated_date', 'user.name as created_name', 
                    'user_update.name as updated_name', 'tb_company.name as name_company', 'clients.id as client_id', 'clients.client_id as client_id_name',
                    'origin.id as origin_id', 'origin.customer_id as origin_id_name', 'origin.name as origin_name', 'origin.address1 as origin_address',
                    'dest.id as dest_id', 'dest.customer_id as dest_id_name', 'dest.name as dest_name', 'dest.address1 as dest_address', 'ring.id as order_type', 
                    'ring.ring_name as order_type_name', 'tb_transport_order.order_qty', 'tb_transport_order.order_qty_v2',
                    'uom.id as uom', 'uom.code as uom_name', 'uom_v2.id as uom_v2', 'uom_v2.code as uom_name_v2', 'tb_transport_order.id_company',
                    'tb_transport_order.remark')
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
                ->leftJoin('tb_ring_code as ring', 'tb_transport_order.order_type', '=', 'ring.id')
                ->leftJoin('tb_uom as uom', 'tb_transport_order.uom', '=', 'uom.id')
                ->leftJoin('tb_uom as uom_v2', 'tb_transport_order.uom_v2', '=', 'uom_v2.id')
                ->where('tb_transport_order.id', $id)
                ->where('tb_transport_order.deleted', 0)
                ->first();

            $respon = array(
                "code" => "01",
                "data" => $data
            );

            $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function Create (Request $request) {
        $client_id = $request->client_id;
        $do_number = $request->do_number;
        $so_number = $request->so_number;
        $delivery_date = $request->delivery_date;
        $req_arrival_date = $request->req_arrival_date;
        $order_type = $request->order_type;
        $origin_id = $request->origin_id;
        $dest_id = $request->dest_id;
        $order_qty = $request->order_qty;
        $uom = $request->uom;
        $order_qty_v2 = $request->order_qty_v2;
        $uom_v2 = $request->uom_v2;
        $remark = $request->remark;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'client_id'         => 'required',
            'do_number'         => 'required',
            'so_number'         => 'required',
            'delivery_date'     => 'required',
            'req_arrival_date'  => 'required',
            'order_type'        => 'required',
            'origin_id'         => 'required',
            'dest_id'           => 'required',
            'order_qty'         => 'required',
            'uom'               => 'required',
            'id_company'        => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {

            if (TransportOrder::where('do_number', $do_number)->where('id_company', $id_company)->where('deleted', 0)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "DO Number tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new TransportOrder;
                    $create->client_id = $client_id;
                    $create->do_number = $do_number;
                    $create->so_number = $so_number;
                    $create->delivery_date = $delivery_date;
                    $create->req_arrival_date = $req_arrival_date;
                    $create->order_type = $order_type;
                    $create->origin_id = $origin_id;
                    $create->dest_id = $dest_id;
                    $create->order_qty = $order_qty;
                    $create->uom = $uom;
                    $create->order_qty_v2 = $order_qty_v2;
                    $create->uom_v2 = $uom_v2;
                    $create->order_status = 0;
                    $create->remark = $remark;
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
        }

        return response()->json($respon, $response_code);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $client_id = $request->client_id;
        $do_number = $request->do_number;
        $so_number = $request->so_number;
        $delivery_date = $request->delivery_date;
        $req_arrival_date = $request->req_arrival_date;
        $order_type = $request->order_type;
        $origin_id = $request->origin_id;
        $dest_id = $request->dest_id;
        $order_qty = $request->order_qty;
        $uom = $request->uom;
        $order_qty_v2 = $request->order_qty_v2;
        $uom_v2 = $request->uom_v2;
        $remark = $request->remark;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'client_id'         => 'required',
            'do_number'         => 'required',
            'so_number'         => 'required',
            'delivery_date'     => 'required',
            'req_arrival_date'  => 'required',
            'order_type'        => 'required',
            'origin_id'         => 'required',
            'dest_id'           => 'required',
            'order_qty'         => 'required',
            'uom'               => 'required',
            'id_company'        => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (TransportOrder::where('do_number', $do_number)->where('id_company', $id_company)->where('id','!=', $id)->where('deleted', 0)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "DO Number tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = TransportOrder::find($id);
                    $update->client_id = $client_id;
                    $update->do_number = $do_number;
                    $update->so_number = $so_number;
                    $update->delivery_date = $delivery_date;
                    $update->req_arrival_date = $req_arrival_date;
                    $update->order_type = $order_type;
                    $update->origin_id = $origin_id;
                    $update->dest_id = $dest_id;
                    $update->order_qty = $order_qty;
                    $update->uom = $uom;
                    $update->order_qty_v2 = $order_qty_v2;
                    $update->uom_v2 = $uom_v2;
                    $update->remark = $remark;
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

    public function Delete (Request $request) {
        $id = $request->id;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'              => 'required',
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
                $update = TransportOrder::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();
                                
                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil menghapus data",
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
}