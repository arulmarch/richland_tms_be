<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\AccidentType;
use Illuminate\Support\Facades\DB;

class AccidentTypeController extends Controller
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
        
            $data = AccidentType::select('tb_accident_type.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                    ->leftJoin('user as user', 'tb_accident_type.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_accident_type.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_accident_type.id_company', '=', 'tb_company.id')
                    ->where('tb_accident_type.id_company', $id_company)
                    ->where('tb_accident_type.deleted', '0')
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
            $data = AccidentType::select('tb_accident_type.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_accident_type.id_company', '=', 'tb_company.id')
                    ->where('tb_accident_type.id', $id)
                    ->where('tb_accident_type.deleted', 0)
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
        $accident_id = $request->accident_id;
        $desc = $request->desc;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'accident_id'       => 'required',
            'desc'              => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (AccidentType::where('accident_id', $accident_id)->where('deleted', 0)
                ->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Accident Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new AccidentType;
                    $create->accident_id = $accident_id;
                    $create->desc = $desc;
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
        $accident_id = $request->accident_id;
        $desc = $request->desc;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'accident_id'       => 'required',
            'desc'              => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (AccidentType::where('accident_id', $accident_id)->where('id','!=', $id)
                ->where('deleted', 0)->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Accident Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = AccidentType::find($id);
                    $update->accident_id = $accident_id;
                    $update->desc = $desc;
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
                $update = AccidentType::find($id);
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

            $data = AccidentType::select('id', 'accident_id')
                ->where('id_company', $id_company)
                ->whereRaw("accident_id like'%$search%'")
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
}