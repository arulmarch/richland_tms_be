<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MasterCompany;
use Illuminate\Support\Facades\DB;

class MasterCompanyController extends Controller
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
        
        $data = MasterCompany::select('tb_company.*', 'user.name as created_name', 'user_update.name as updated_name')
                ->leftJoin('user', 'tb_company.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'tb_company.updated_by', '=', 'user_update.user_id')
                ->where('tb_company.deleted', '0')
                ->get();

        $respon = array(
          "code" => "01",
          "data" => $data 
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
            $data = MasterCompany::where('id', $id)->first();
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

    public function SearchData (Request $request) {

        $search = $request->search;

        $data = MasterCompany::select('tb_company.*', 'user.name as created_name', 'user_update.name as updated_name')
                ->leftJoin('user', 'tb_company.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'tb_company.updated_by', '=', 'user_update.user_id')
                ->where('tb_company.deleted', '0')
                ->where('tb_company.name', 'like', '%' . $search . '%')
                ->limit(10)
                ->get();

        $respon = array(
          "code" => "01",
          "data" => $data 
        );

        return response()->json($respon);
    }

    public function Create (Request $request) {
        $name = $request->name;
        $address = $request->address;
        $no_telp = $request->no_telp;
        $email = $request->email;
        $image = $request->image;
        $created_by = $request->created_by;

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'image'     => 'required'
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
                    $path = $request->file('image')->move(env("PATH_IMAGE_COMPANY"), $date_now .'.'.$ext);
                    $name_image = $date_now .'.'.$ext;
                }
            }
            try 
            {
                $create = new MasterCompany;
                $create->name = $name;
                $create->address = $address;
                $create->no_telp = $no_telp;
                $create->email = $email;
                $create->image = $name_image;
                $create->created_by = $created_by;

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
        $name = $request->name;
        $address = $request->address;
        $no_telp = $request->no_telp;
        $email = $request->email;
        $image = $request->image;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'      => 'required',
            'name'      => 'required',
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
                    $path = $request->file('image')->move(env("PATH_IMAGE_COMPANY"), $date_now .'.'.$ext);
                    $name_image = $date_now .'.'.$ext;
                }
            }
            try 
            {
                $update = MasterCompany::find($id);
                $update->name = $name;
                $update->address = $address;
                $update->no_telp = $no_telp;
                $update->email = $email;
                if ($name_image !== '') {
                    $update->image = $name_image;
                }
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
                $update = MasterCompany::find($id);
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