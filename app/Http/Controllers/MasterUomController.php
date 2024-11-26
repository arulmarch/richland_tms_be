<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MasterUom;
use Illuminate\Support\Facades\DB;

class MasterUomController extends Controller
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
        
        $data = MasterUom::select('tb_uom.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                ->leftJoin('user as user', 'tb_uom.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'tb_uom.updated_by', '=', 'user_update.user_id')
                ->leftJoin('tb_company as tb_company', 'tb_uom.id_company', '=', 'tb_company.id')
                ->where('tb_uom.deleted', 0);
        
        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(tb_uom.id_company = $id_company or tb_uom.id_company is null)");
        }

        $data_response = $data->get();

        $respon = array(
          "code" => "01",
          "data" => $data_response
        );

        return response()->json($respon);
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
            $data = MasterUom::select('tb_uom.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_uom.id_company', '=', 'tb_company.id')
                    ->where('tb_uom.id', $id)
                    ->where('tb_uom.deleted', 0)
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

    public function GetDataByCode(Request $request) {

        $code = $request->code;

        $validator = Validator::make($request->all(), [
            'code'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = MasterUom::where('code', $code)->where('deleted', 0)->first();
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
        $code = $request->code;
        $description = $request->description;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'code'          => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_code = MasterUom::where('code', $code)->where('deleted', 0);
            if ($id_company != null && $id_company != 'null') {
                $check_code->whereRaw("(id_company = $id_company or id_company is null)");
            } else {
                $check_code->whereRaw("id_company is null");
            }
            $ischeck = $check_code->count();
            if ($ischeck > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Code tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new MasterUom;
                    $create->code = $code;
                    $create->description = $description;
                    $create->created_by = $created_by;
                    if ($id_company != null && $id_company != 'null') {
                        $create->id_company = $id_company;
                    }
    
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
        $code = $request->code;
        $description = $request->description;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'code'          => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_code = MasterUom::where('code', $code)->where('id','!=', $id)->where('deleted', 0);
            if ($id_company != null && $id_company != 'null') {
                $check_code->whereRaw("(id_company = $id_company or id_company is null)");
            } else {
                $check_code->whereRaw("id_company is null");
            }
            $ischeck = $check_code->count();
            if ($ischeck > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Code tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = MasterUom::find($id);
                    $update->code = $code;
                    $update->description = $description;
                    $update->updated_by = $updated_by;
                    if ($id_company != null && $id_company != 'null') {
                        $update->id_company = $id_company;
                    }

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
                $update = MasterUom::find($id);
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

        $data = MasterUom::select('id', 'code', 'description')
            ->where('code', 'like', '%' . $search . '%')
            ->where('deleted', 0)
            ->limit(10);

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(id_company = $id_company or id_company is null)");
        } else {
            $data->whereRaw("id_company is null");
        }

        $data_response = $data->get();

        $respon = array(
            "code" => "01",
            "data" => $data_response 
        );

        $response_code = 200;

        return response()->json($respon, $response_code);
    }

}