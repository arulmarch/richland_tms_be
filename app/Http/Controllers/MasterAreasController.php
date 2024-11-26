<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MasterAreas;
use Illuminate\Support\Facades\DB;

class MasterAreasController extends Controller
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
        
            $data = MasterAreas::select('tb_areas.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                    ->selectRaw('(case area_type when 1 then "SALES" else "BRANCH" end) as area_type_name')
                    ->leftJoin('user as user', 'tb_areas.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_areas.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_areas.id_company', '=', 'tb_company.id')
                    ->where('tb_areas.id_company', $id_company)
                    ->where('tb_areas.deleted', '0')
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
            $data = MasterAreas::select('tb_areas.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_areas.id_company', '=', 'tb_company.id')
                    ->where('tb_areas.id', $id)
                    ->where('tb_areas.deleted', 0)
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

            $data = MasterAreas::select('tb_areas.*', 'tb_company.name as name_company')
                    ->leftJoin('tb_company as tb_company', 'tb_areas.id_company', '=', 'tb_company.id')
                    ->where('tb_areas.deleted', 0)
                    ->where('tb_areas.id_company', $id_company)
                    ->whereRaw("(tb_areas.area_id like'%$search%' or tb_areas.description like'%$search%') ")
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

    public function Create (Request $request) {
        $area_id = $request->area_id;
        $description = $request->description;
        $area_type = $request->area_type;
        $additional_information = $request->additional_information;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'area_id'           => 'required',
            'description'       => 'required',
            'area_type'         => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (MasterAreas::where('area_id', $area_id)->where('deleted', 0)
                ->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Area Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new MasterAreas;
                    $create->area_id = $area_id;
                    $create->description = $description;
                    $create->area_type = $area_type;
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
        $area_id = $request->area_id;
        $description = $request->description;
        $area_type = $request->area_type;
        $additional_information = $request->additional_information;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'area_id'           => 'required',
            'description'       => 'required',
            'area_type'         => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (MasterAreas::where('area_id', $area_id)->where('id','!=', $id)
                ->where('deleted', 0)->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Area Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = MasterAreas::find($id);
                    $update->area_id = $area_id;
                    $update->description = $description;
                    $update->area_type = $area_type;
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
                $update = MasterAreas::find($id);
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