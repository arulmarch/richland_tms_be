<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
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
        
        $data = Setting::select('tb_setting.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                ->selectRaw('case 
                                when type = 1 then "Text"
                                when type = 2 then "Number"
                                when type = 3 then "Boolean" 
                                when type = 4 then "Array" 
                                end as type_name')
                ->leftJoin('user as user', 'tb_setting.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'tb_setting.updated_by', '=', 'user_update.user_id')
                ->leftJoin('tb_company as tb_company', 'tb_setting.id_company', '=', 'tb_company.id')
                ->where('tb_setting.deleted', '0');

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(tb_setting.id_company = $id_company or tb_setting.id_company is null)");
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
            $data = Setting::select('tb_setting.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_setting.id_company', '=', 'tb_company.id')
                    ->where('tb_setting.id', $id)
                    ->where('tb_setting.deleted', 0)
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
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'code'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if ($id_company != null && $id_company != 'null') {
                if(Setting::where('code', $code)->where('id_company', $id_company)->where('deleted', 0)->count() > 0) {
                    $data = Setting::where('code', $code)->where('id_company', $id_company)->where('deleted', 0)->first();
                } else {
                    $data = Setting::where('code', $code)->whereNull('id_company')->where('deleted', 0)->first();
                }
            } else {
                $data = Setting::where('code', $code)->whereNull('id_company')->where('deleted', 0)->first();
            }
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
        $type = $request->type;
        $name_value = $request->name_value;
        $default_value = $request->default_value;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'code'          => 'required',
            'type'          => 'required',
            'name_value'    => 'required',
            'default_value' => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $create = new Setting;
                $create->code = $code;
                $create->type = $type;
                $create->name_value = $name_value;
                $create->default_value = $default_value;
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

        return response()->json($respon);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $code = $request->code;
        $type = $request->type;
        $name_value = $request->name_value;
        $default_value = $request->default_value;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'code'          => 'required',
            'type'          => 'required',
            'name_value'    => 'required',
            'default_value' => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $update = Setting::find($id);
                $update->code = $code;
                $update->type = $type;
                $update->name_value = $name_value;
                $update->default_value = $default_value;
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
                $update = Setting::find($id);
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