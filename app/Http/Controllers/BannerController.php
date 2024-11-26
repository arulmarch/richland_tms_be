<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
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
        
        $data = Banner::select('tb_banner.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                ->leftJoin('user as user', 'tb_banner.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'tb_banner.updated_by', '=', 'user_update.user_id')
                ->leftJoin('tb_company as tb_company', 'tb_banner.id_company', '=', 'tb_company.id')
                ->where('tb_banner.deleted', '0');

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(tb_banner.id_company = $id_company or tb_banner.id_company is null)");
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
            $data = Banner::select('tb_banner.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_banner.id_company', '=', 'tb_company.id')        
                    ->where('tb_banner.id', $id)
                    ->where('tb_banner.deleted', 0)
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
        $name = $request->name;
        $desc = $request->desc;
        $image = $request->image;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'image'   => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $count = Banner::where('deleted',0)->count();
            if ($count < 4) {
                $name_image = '';
                if($request->file('image')) {
                    $size = floor($request->file('image')->getSize() / 1024);
                    if ($size > 1000) { //1 MB
                        $respon = array(
                            "code" => "02",
                            "message" =>  "File terlalu besar",
                        );
                        return response()->json($respon);
                    } else {
                        $date_now = date('YmdHisv');
                        $ext = $request->file('image')->getClientOriginalExtension();
                        $path = $request->file('image')->move(env("PATH_IMAGE_BANNER"), $date_now .'.'.$ext);
                        $name_image = $date_now .'.'.$ext;
                    }
                }
                try 
                {
                    $create = new Banner;
                    $create->name = $name;
                    $create->desc = $desc;
                    $create->image = $name_image;
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
            } else {
                $respon = array(
                    "code" => "02",
                    "message" =>  'Tidak dapat membuat banner lebih dari 4 !',
                );
            }
        }

        return response()->json($respon);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $name = $request->name;
        $desc = $request->desc;
        $image = $request->image;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $name_image = '';
            if($request->file('image')) {
                $size = floor($request->file('image')->getSize() / 1024);
                if ($size > 1000) { //1 MB
                    $respon = array(
                        "code" => "02",
                        "message" =>  "File terlalu besar",
                    );
                    return response()->json($respon);
                } else {
                    $date_now = date('YmdHisv');
                    $ext = $request->file('image')->getClientOriginalExtension();
                    $path = $request->file('image')->move(env("PATH_IMAGE_BANNER"), $date_now .'.'.$ext);
                    $name_image = $date_now .'.'.$ext;
                }
            }
            try 
            {
                $update = Banner::find($id);
                $update->name = $name;
                $update->desc = $desc;
                if ($name_image !== '') {
                    $update->image = $name_image;
                }
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
                $update = Banner::find($id);
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