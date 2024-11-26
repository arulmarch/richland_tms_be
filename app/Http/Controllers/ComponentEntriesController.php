<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\ComponentEntries;
use App\Models\Manifest;
use Illuminate\Support\Facades\DB;

class ComponentEntriesController extends Controller
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
        $id_manifest = $request->id_manifest;
        $type = $request->type;

        $validator = Validator::make($request->all(), [
            'id_company'    => 'required',
            'id_manifest'   => 'required',
            'type'          => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
        
            $data = ComponentEntries::select('tb_component_entries.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company', 
                                        'component.description as component_name')
                    ->leftJoin('user as user', 'tb_component_entries.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_component_entries.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_component_entries.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_component as component', 'tb_component_entries.id_cost_component', '=', 'component.id')
                    ->where('tb_component_entries.id_manifest', $id_manifest)
                    ->where('tb_component_entries.type', $type)
                    ->where('tb_component_entries.id_company', $id_company)
                    ->where('tb_component_entries.deleted', '0')
                    ->get();

            $data_manifest = Manifest::where('id', $id_manifest)->first();

            $total_cost_component = 0;

            if ($type == env('COMPONENT_ENTRIES_TYPE_TRANSPORTER')) {
                $total_cost_component = $data_manifest->sum_component_cost;
            } else {
                $total_cost_component = $data_manifest->client_sum_component_cost;
            }

            $respon = array(
                "code" => "01",
                "total_cost_component" => $total_cost_component,
                "data" => $data
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function Create (Request $request) {
        $id_manifest = $request->id_manifest;
        $id_cost_component = $request->id_cost_component;
        $qty = $request->qty;
        $price = $request->price;
        $type = $request->type;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id_manifest'       => 'required',
            'id_cost_component'   => 'required',
            'type'              => 'required',
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
                DB::beginTransaction();

                $amount = 0;
                if ($price != null && $qty != null) {
                    $amount = $price * $qty;
                }

                $data_manifest = Manifest::where('id', $id_manifest)->first();

                $create = new ComponentEntries;
                $create->id_manifest = $id_manifest;
                $create->id_cost_component = $id_cost_component;
                $create->qty = $qty;
                $create->price = $price;
                $create->amount = $amount;
                $create->type = $type;
                $create->created_by = $created_by;
                $create->id_company = $id_company;

                $create->save();

                $update_manifest = Manifest::find($id_manifest);

                if ($type == env('COMPONENT_ENTRIES_TYPE_TRANSPORTER')) {
                    $total_cost_component = $data_manifest->sum_component_cost + $amount;
                    $update_manifest->sum_component_cost = $total_cost_component;
                } else {
                    $total_cost_component = $data_manifest->client_sum_component_cost + $amount;
                    $update_manifest->client_sum_component_cost = $total_cost_component;
                }

                $update_manifest->updated_by = $created_by;
                $update_manifest->id_company = $id_company;

                $update_manifest->save();
                                
                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil menyimpan data",
                );

                $response_code = 200;
                
                DB::commit();

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

    public function Delete (Request $request) {
        $id = $request->id;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'        => 'required'
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

                $component_entries = ComponentEntries::where('id', $id)->first();

                $data_manifest = Manifest::where('id', $component_entries->id_manifest)->first();

                $update = ComponentEntries::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();

                $update_manifest = Manifest::find($component_entries->id_manifest);
                if ($component_entries->type == env('COMPONENT_ENTRIES_TYPE_TRANSPORTER')) {
                    $total_cost_component = $data_manifest->sum_component_cost - $component_entries->amount;
                    $update_manifest->sum_component_cost = $total_cost_component;
                } else {
                    $total_cost_component = $data_manifest->client_sum_component_cost - $component_entries->amount;
                    $update_manifest->client_sum_component_cost = $total_cost_component;
                }

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

        return response()->json($respon, $response_code);
    }

}