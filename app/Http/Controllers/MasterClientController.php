<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MasterClient;
use Illuminate\Support\Facades\DB;

class MasterClientController extends Controller
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
        
            $data = MasterClient::select('tb_clients.*', 'user.name as created_name', 'user_update.name as updated_name', 
                        'tb_company.name as name_company')
                    ->leftJoin('user as user', 'tb_clients.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_clients.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_clients.id_company', '=', 'tb_company.id')
                    ->where('tb_clients.id_company', $id_company)
                    ->where('tb_clients.deleted', 0)
                    ->get();

            $respon = array(
                "code" => "01",
                "data" => $data 
            );

            $response_code = 200;

        }
     
        return response()->json($respon, $response_code);
    }

    public function SearchData (Request $request) {

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

            $data = MasterClient::select('tb_clients.*')
                ->where('tb_clients.id_company', $id_company)
                ->where('tb_clients.deleted', 0)
                ->whereRaw("(tb_clients.client_id like'%$search%' or tb_clients.name like'%$search%') ")
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
            $data = MasterClient::select('tb_clients.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_clients.id_company', '=', 'tb_company.id')
                    ->where('tb_clients.id', $id)
                    ->where('tb_clients.deleted', 0)
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
        $client_id = $request->client_id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $postal_code = $request->postal_code;
        $phone = $request->phone;
        $fax = $request->fax;
        $pic = $request->pic;
        $email = $request->email;
        $additional_information = $request->additional_information;
        $payment_term = $request->payment_term;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'client_id'         => 'required',
            'name'              => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = MasterClient::where('client_id', $client_id)
                ->where('id_company', $id_company)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Client Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new MasterClient;
                    $create->client_id = $client_id;
                    $create->name = $name;
                    $create->address1 = $address1;
                    $create->address2 = $address2;
                    $create->city = $city;
                    $create->postal_code = $postal_code;
                    $create->phone = $phone;
                    $create->fax = $fax;
                    $create->pic = $pic;
                    $create->email = $email;
                    $create->additional_information = $additional_information;
                    $create->payment_term = $payment_term;
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
        $client_id = $request->client_id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $postal_code = $request->postal_code;
        $phone = $request->phone;
        $fax = $request->fax;
        $pic = $request->pic;
        $email = $request->email;
        $additional_information = $request->additional_information;
        $payment_term = $request->payment_term;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'client_id'         => 'required',
            'name'              => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_data = MasterClient::where('client_id', $client_id)
                ->where('id_company', $id_company)
                ->where('id','!=', $id)
                ->where('deleted', 0)
                ->count();
            if ($check_data > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Client Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = MasterClient::find($id);
                    $update->client_id = $client_id;
                    $update->name = $name;
                    $update->address1 = $address1;
                    $update->address2 = $address2;
                    $update->city = $city;
                    $update->postal_code = $postal_code;
                    $update->phone = $phone;
                    $update->fax = $fax;
                    $update->pic = $pic;
                    $update->email = $email;
                    $update->additional_information = $additional_information;
                    $update->payment_term = $payment_term;
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
                $update = MasterClient::find($id);
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