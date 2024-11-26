<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\PurchaseInvoice;
use App\Models\Manifest;
use App\Models\TrafficMonitoring;
use Illuminate\Support\Facades\DB;

class SelfBillingController extends Controller
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
        
            $data = PurchaseInvoice::select(
                        'tb_purchase_invoice.id', 'tb_purchase_invoice.reference', 'tb_transporters.transporter_id', 'tb_clients.client_id',
                        'tb_purchase_invoice.vat', 'tb_purchase_invoice.created_date', 'tb_purchase_invoice.updated_date', 'user.name as created_name', 
                        'user_update.name as updated_name', 'tb_company.name as name_company', 'tb_purchase_invoice.inv_status')
                    ->selectRaw('DATE(tb_purchase_invoice.invoice_date) as invoice_date')
                    ->selectRaw('DATE(tb_purchase_invoice.due_date) as due_date')
                    ->selectRaw('to_days(curdate()) - to_days(DATE(tb_purchase_invoice.invoice_date)) AS aging')
                    ->selectRaw('to_days(DATE(tb_purchase_invoice.payment_date)) - to_days(DATE(tb_purchase_invoice.invoice_date)) AS agingafterpayment')
                    ->selectRaw('to_days(DATE(tb_purchase_invoice.due_date)) - to_days(DATE(tb_purchase_invoice.invoice_date)) AS payment_term')
                    ->selectRaw('to_days(curdate()) - to_days(DATE(tb_purchase_invoice.due_date)) AS overdue')
                    ->selectRaw('to_days(DATE(tb_purchase_invoice.payment_date)) - to_days(DATE(tb_purchase_invoice.due_date)) AS overdueafterpayment')
                    ->selectRaw('case 
                                when tb_purchase_invoice.type = 1 then "ON CALL"
                                when tb_purchase_invoice.type = 2 then "DEDICATED"
                                end as type')
                    ->selectRaw('DATE(tb_purchase_invoice.from_date) as from_date')
                    ->selectRaw('DATE(tb_purchase_invoice.to_date) as to_date')
                    ->selectRaw('case 
                                when tb_purchase_invoice.area_type = 1 then "SALES"
                                when tb_purchase_invoice.area_type = 2 then "BRANCH"
                                end as area_type')
                    ->selectRaw('case 
                                when tb_purchase_invoice.inv_status = 0 then "UNPAID"
                                when tb_purchase_invoice.inv_status = 1 then "PAID"
                                end as inv_status_name')
                    ->leftJoin('user as user', 'tb_purchase_invoice.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_purchase_invoice.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_purchase_invoice.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_clients', 'tb_purchase_invoice.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_transporters', 'tb_purchase_invoice.transporter_id', '=', 'tb_transporters.id')
                    ->where('tb_purchase_invoice.id_company', $id_company)
                    ->where('tb_purchase_invoice.deleted', 0);

            if ($status !== null && $status !== "") {
                $data->where('tb_purchase_invoice.inv_status', $status);
            }

            if ($filter_date === true) {
                $data->whereRaw("tb_purchase_invoice.invoice_date BETWEEN '$start_date' and '$end_date'");
            }
    
            $data_response = $data->get();

            $index = 0;
            foreach($data_response as $response) {
                if ($response['inv_status'] == 1) {
                    $data_response[$index]['aging_select'] = $response['agingafterpayment'] . ' Days';
                    if ($response['overdueafterpayment'] < 0) {
                        $data_response[$index]['overdue_select'] = '0 Days';
                    } else {
                        $data_response[$index]['overdue_select'] = $response['overdueafterpayment'] . ' Days';
                    }
                } else {
                    $data_response[$index]['aging_select'] = $response['aging'] . ' Days';
                    $data_response[$index]['overdue_select'] = $response['overdue'] . ' Days';
                }
                $index++;
            }

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
            $data = PurchaseInvoice::select(
                    'tb_purchase_invoice.id', 'tb_purchase_invoice.reference', 'tb_transporters.transporter_id as transporter_id_name', 
                    'tb_clients.client_id as client_id_name', 'tb_purchase_invoice.vat', 'tb_purchase_invoice.transporter_id', 
                    'tb_purchase_invoice.client_id', 'tb_purchase_invoice.type', 'tb_purchase_invoice.area_type', 'tb_purchase_invoice.taxable',
                    'tb_purchase_invoice.pph', 'tb_company.name as name_company', 'tb_purchase_invoice.payment_term', 'tb_purchase_invoice.id_company', 
                    'tb_purchase_invoice.purchase_invoice_type', 'tb_purchase_invoice.sub_total', 'tb_purchase_invoice.total_vat', 
                    'tb_purchase_invoice.total_pph', 'tb_purchase_invoice.total_amount', 'tb_purchase_invoice.file_name')
                ->selectRaw('DATE(tb_purchase_invoice.invoice_date) as invoice_date')
                ->selectRaw('DATE(tb_purchase_invoice.from_date) as from_date')
                ->selectRaw('DATE(tb_purchase_invoice.to_date) as to_date')
                ->selectRaw('DATE(tb_purchase_invoice.payment_date) as payment_date')
                ->leftJoin('tb_company as tb_company', 'tb_purchase_invoice.id_company', '=', 'tb_company.id')
                ->leftJoin('tb_clients', 'tb_purchase_invoice.client_id', '=', 'tb_clients.id')
                ->leftJoin('tb_transporters', 'tb_purchase_invoice.transporter_id', '=', 'tb_transporters.id')
                ->where('tb_purchase_invoice.id', $id)
                ->where('tb_purchase_invoice.deleted', 0)
                ->first();

            $respon = array(
                "code" => "01",
                "data" => $data
            );

            $response_code = 200;
        }

        return response()->json($respon, $response_code);
    }

    public function GetManifest(Request $request) {
        $id_purchase_invoice = $request->id_purchase_invoice;
        $vehicle_status = $request->vehicle_status;
        $client_id = $request->client_id;
        $transporter_id = $request->transporter_id;
        $area_type = $request->area_type;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_company'        => 'required',
            'vehicle_status'    => 'required',
            'client_id'         => 'required',
            'transporter_id'    => 'required',
            'area_type'         => 'required',
            'start_date'        => 'required',
            'end_date'          => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = Manifest::select(
                        'tb_manifests.id as manifest_id', 'tb_manifests.variable_cost', 'tb_manifests.client_variable_cost', 'tb_manifests.client_sum_component_cost',
                        'tb_manifests.sum_component_cost', 'tb_clients.id as client_id', 'tb_clients.client_id as client_name', 'tb_manifests.load_kg as tonnage',
                        'tb_manifests.trip', 'tb_manifests.manifest_status', 'tb_vehicles.vehicle_id', 'tb_vehicles.status as vehicle_status', 'tb_transporters.transporter_id',
                        'tb_transporters.id as t_id', 'tb_vehicle_types.type_id', 'origin.name as origin_name', 'origin.address1 as origin_address',
                        'origin_area.area_id as origin_area_id', 'dest.name as dest_name', 'dest.address1 as dest_address', 'dest_area.area_id as dest_area_id',
                        'dest_area.area_type as dest_area_type', 'tb_manifests.id_purchase_invoice', 'tb_manifests.id_sales_invoice', 
                        'tb_purchase_invoice.reference as reference_purchase_invoice', 'tb_manifests.min_weight_client_rate', 'tb_manifests.client_rate_status', 
                        'tb_manifests.client_rate_id', 'tb_manifests.min_weight_transporter_rate', 'tb_manifests.transporter_rate_status', 'tb_manifests.transporter_rate_status', 'tb_manifests.transporter_rate_id')
                    ->selectRaw('group_concat(tb_transport_order.do_number separator ", ") as do_number')
                    ->selectRaw('DATE(tb_manifests.schedule_date) as schedule_date')
                    ->leftJoin('tb_vehicles', 'tb_manifests.vehicle_id', '=', 'tb_vehicles.id')
                    ->leftJoin('tb_transport_order', 'tb_manifests.id', '=', 'tb_transport_order.manifest_id')
                    ->leftJoin('tb_transporters', 'tb_vehicles.transporter_id', '=', 'tb_transporters.id')
                    ->leftJoin('tb_vehicle_types', 'tb_vehicles.type', '=', 'tb_vehicle_types.id')
                    ->leftJoin('tb_trucking_order', 'tb_manifests.tr_id', '=', 'tb_trucking_order.id')
                    ->leftJoin('tb_purchase_invoice', 'tb_manifests.id_purchase_invoice', '=', 'tb_purchase_invoice.id')
                    ->leftJoin('tb_clients', 'tb_trucking_order.client_id', '=', 'tb_clients.id')
                    ->leftJoin('tb_customers as origin', 'tb_trucking_order.origin_id', '=', 'origin.id')
                    ->leftJoin('tb_customers as dest', 'tb_trucking_order.dest_id', '=', 'dest.id')
                    ->leftJoin('tb_areas as origin_area', 'origin.area_id', '=', 'origin_area.id')
                    ->leftJoin('tb_areas as dest_area', 'dest.area_id', '=', 'dest_area.id')
                    ->where('tb_manifests.manifest_status', '!=', 0)
                    ->where('tb_vehicles.status', $vehicle_status)
                    ->where('tb_clients.id', $client_id)
                    ->where('tb_transporters.id', $transporter_id)
                    ->where('dest_area.area_type', $area_type)
                    ->whereRaw("tb_manifests.schedule_date BETWEEN '$start_date' and '$end_date'")
                    ->where('tb_manifests.id_company', $id_company)
                    ->where('tb_manifests.deleted', 0);

            if ($id_purchase_invoice !== null && $id_purchase_invoice !== "") {
                $data->where('tb_manifests.id_purchase_invoice', $id_purchase_invoice);
            } else {
                $data->whereRaw("tb_manifests.id_purchase_invoice is null");
            }

            $data->groupBy('tb_transport_order.manifest_id');

            $data_response = $data->get();

            $respon = array(
                "code" => "01",
                "data" => $data_response 
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function Create (Request $request) {
        $invoice_date = $request->invoice_date;
        $reference = $request->reference;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $type = $request->type;
        $area_type = $request->area_type;
        $client_id = $request->client_id;
        $transporter_id = $request->transporter_id;
        $taxable = $request->taxable;
        $payment_term = $request->payment_term;
        $vat = $request->vat;
        $pph = $request->pph;
        $add_type = $request->add_type;
        $manifest_data = $request->manifest_data;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'invoice_date'      => 'required',
            'reference'         => 'required',
            'from_date'         => 'required',
            'to_date'           => 'required',
            'type'              => 'required',
            'area_type'         => 'required',
            'client_id'         => 'required',
            'transporter_id'    => 'required',
            'taxable'           => 'required',
            'payment_term'      => 'required',
            'vat'               => 'required',
            'pph'               => 'required',
            'add_type'          => 'required',
            'manifest_data'     => 'required',
            'id_company'        => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $due_date = date('Y-m-d', strtotime($invoice_date. '+ '. $payment_term .' days'));
            $sub_total = 0;
            $manifest_id_array = array();

            foreach($manifest_data as $data) {
                if($add_type == 1) {
                    $entry_amount = round(($data['variable_cost'] + $data['sum_component_cost']), 2);
                } else {
                    if ($data['transporter_rate_status'] == "1") {
                        if ($data['min_weight_transporter_rate'] > $data['tonnage']) {
                            $total = $data['min_weight_transporter_rate'] * $data['variable_cost'];
                        } else {
                            $total = $data['tonnage'] * $data['variable_cost'];
                        }
                        $entry_amount = round($total + $data['sum_component_cost'], 2);
                    } else {
                        $entry_amount = round(($data['variable_cost'] + $data['sum_component_cost']), 2);
                    }
                }
                $sub_total += $entry_amount;
                array_push($manifest_id_array, $data['manifest_id']);
            }

            $calc_tax = round($sub_total *($vat /100) ,2);
			$calc_pph = round($sub_total *($pph /100) ,2);

            try 
            {
                DB::beginTransaction();

                $create = new PurchaseInvoice;
                $create->reference = $reference;
                $create->invoice_date = $invoice_date;
                $create->due_date = $due_date;
                $create->type = $type;
                $create->client_id = $client_id;
                $create->transporter_id = $transporter_id;
                $create->from_date = $from_date;
                $create->to_date = $to_date;
                $create->area_type = $area_type;
                $create->inv_status = 0;
                $create->taxable = $taxable;
                $create->payment_term = $payment_term;
                $create->vat = $vat;
                $create->pph = $pph;
                $create->sub_total = $sub_total;
                $create->total_vat = $calc_tax;
                $create->total_pph = $calc_pph;
                $create->total_amount = $sub_total + $calc_tax - $calc_pph;
                $create->purchase_invoice_type = $add_type;
                $create->created_by = $created_by;
                $create->id_company = $id_company;

                $create->save();

                $id_purchase_invoice = $create->id;

                Manifest::whereIn('id', $manifest_id_array)
                    ->update([
                        'id_purchase_invoice' => $id_purchase_invoice,
                        'manifest_status' => env('MANIFEST_STATUS_COMPLETED')
                    ]);

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

        return response()->json($respon, $response_code);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $invoice_date = $request->invoice_date;
        $reference = $request->reference;
        $taxable = $request->taxable;
        $payment_term = $request->payment_term;
        $vat = $request->vat;
        $pph = $request->pph;
        $payment_date = $request->payment_date;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'invoice_date'      => 'required',
            'reference'         => 'required',
            'taxable'           => 'required',
            'payment_term'      => 'required',
            'vat'               => 'required',
            'pph'               => 'required',
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
                $due_date = date('Y-m-d', strtotime($invoice_date. '+ '. $payment_term .' days'));

                $data_purchase_invoice = PurchaseInvoice::where('id', $id)->first();
                $sub_total = $data_purchase_invoice->sub_total;
                $calc_tax = round($sub_total *($vat /100) ,2);
			    $calc_pph = round($sub_total *($pph /100) ,2);

                $update = PurchaseInvoice::find($id);
                $update->reference = $reference;
                $update->invoice_date = $invoice_date;
                $update->due_date = $due_date;
                if($payment_date) {
                    $update->payment_date = $payment_date;
                    $update->inv_status = 1;
                }
                $update->taxable = $taxable;
                $update->payment_term = $payment_term;
                $update->vat = $vat;
                $update->pph = $pph;
                $update->total_vat = $calc_tax;
                $update->total_pph = $calc_pph;
                $update->total_amount = $sub_total + $calc_tax - $calc_pph;
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
                DB::beginTransaction();

                $update = PurchaseInvoice::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();

                $data_manifest = Manifest::where('id_purchase_invoice', $id)
                    ->whereRaw('id_sales_invoice is null')->get();

                Manifest::where('id_purchase_invoice', $id)
                    ->update(['id_purchase_invoice' => null]);

                foreach($data_manifest as $manifest) {
                    if (TrafficMonitoring::leftJoin('tb_transport_order', 'tb_traffic_monitoring.transport_order_id', '=', 'tb_transport_order.id')
                            ->leftJoin('tb_manifests', 'tb_transport_order.manifest_id', '=', 'tb_manifests.id')
                            ->where('tb_traffic_monitoring.tm_status', '!=', 1)
                            ->where('tb_manifests.id', $manifest->id)->count() > 0) {
                        $update_manifest = Manifest::find($manifest->id);
                        $update_manifest->manifest_status = env('MANIFEST_STATUS_DELIVERY');
                        $update_manifest->save();
                    } else {
                        $update_manifest = Manifest::find($manifest->id);
                        $update_manifest->manifest_status = env('MANIFEST_STATUS_CONFIRM');
                        $update_manifest->save();
                    }
                }

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

        return response()->json($respon, $response_code);
    }

    public function UploadFile (Request $request) {
        $id = $request->id;
        $file_data = $request->file_data;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'file_data'     => 'required'
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
                    $path = $request->file('file_data')->move(env("PATH_SELF_BILLING_FILE"), $date_now .'.'.$ext);
                    $name_file = $date_now .'.'.$ext;
                }
            }
            try 
            {
                $update = PurchaseInvoice::find($id);
                $update->file_name = $name_file;
                $update->updated_by = $updated_by;

                $update->save();
                                
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