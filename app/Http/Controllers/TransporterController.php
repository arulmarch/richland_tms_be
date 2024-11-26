<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Transporter;
use Illuminate\Support\Facades\DB;

class TransporterController extends Controller
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
        
            $data = Transporter::select('tb_transporters.*', 'user.name as created_name', 'user_update.name as updated_name', 
                        'tb_company.name as name_company')
                    ->selectRaw('case 
                                when transport_mode = 1 then "LAND"
                                when transport_mode = 2 then "AIR"
                                when transport_mode = 3 then "SEA"
                                when transport_mode = 4 then "RAILWAY"
                                end as transport_mode_name')
                    ->leftJoin('user as user', 'tb_transporters.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_transporters.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_transporters.id_company', '=', 'tb_company.id')
                    ->where('tb_transporters.id_company', $id_company)
                    ->where('tb_transporters.deleted', 0)
                    ->get();

            $respon = array(
                "code" => "01",
                "data" => $data 
            );

            $response_code = 200;

        }

        return response()->json($respon, $response_code);
    }

    public function Search (Request $request) {

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
        
            $data = Transporter::select('tb_transporters.id', 'tb_transporters.transporter_id', 'tb_transporters.name')
                    ->where('tb_transporters.id_company', $id_company)
                    ->whereRaw("tb_transporters.transporter_id like '%${search}%'")
                    ->where('tb_transporters.deleted', 0)
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
            $data = Transporter::select('tb_transporters.*', 'tb_company.name as name_company')
                    ->selectRaw('case 
                        when transport_mode = 1 then "LAND"
                        when transport_mode = 2 then "AIR"
                        when transport_mode = 3 then "SEA"
                        when transport_mode = 4 then "RAILWAY"
                        end as transport_mode_name')
                    ->leftJoin('tb_company as tb_company', 'tb_transporters.id_company', '=', 'tb_company.id')
                    ->where('tb_transporters.id', $id)
                    ->where('tb_transporters.deleted', 0)
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
        $transporter_id = $request->transporter_id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $postal_code = $request->postal_code;
        $phone = $request->phone;
        $fax = $request->fax;
        $transport_mode = $request->transport_mode;
        $payment_term = $request->payment_term;
        $additional_information = $request->additional_information;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'transporter_id'        => 'required',
            'name'                  => 'required',
            'transport_mode'        => 'required',
            'id_company'            => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = Transporter::where('transporter_id', $transporter_id)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Transporter Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new Transporter;
                    $create->transporter_id = $transporter_id;
                    $create->name = $name;
                    $create->address1 = $address1;
                    $create->address2 = $address2;
                    $create->city = $city;
                    $create->postal_code = $postal_code;
                    $create->phone = $phone;
                    $create->fax = $fax;
                    $create->transport_mode = $transport_mode;
                    $create->payment_term = $payment_term;
                    $create->additional_information = $additional_information;
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
        $transporter_id = $request->transporter_id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $postal_code = $request->postal_code;
        $phone = $request->phone;
        $fax = $request->fax;
        $transport_mode = $request->transport_mode;
        $payment_term = $request->payment_term;
        $additional_information = $request->additional_information;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                    => 'required',
            'transporter_id'        => 'required',
            'name'                  => 'required',
            'transport_mode'        => 'required',
            'id_company'            => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = Transporter::where('transporter_id', $transporter_id)
                ->where('id','!=', $id)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Transporter Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = Transporter::find($id);
                    $update->transporter_id = $transporter_id;
                    $update->name = $name;
                    $update->address1 = $address1;
                    $update->address2 = $address2;
                    $update->city = $city;
                    $update->postal_code = $postal_code;
                    $update->phone = $phone;
                    $update->fax = $fax;
                    $update->transport_mode = $transport_mode;
                    $update->payment_term = $payment_term;
                    $update->additional_information = $additional_information;
                    $update->updated_by = $updated_by;
                    $update->id_company = $id_company;

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
                $update = Transporter::find($id);
                $update->deleted = 1;
                $update->updated_by = $updated_by;

                $update->save();
                                
                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil menghapus data",
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