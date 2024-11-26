<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Mechanic;
use Illuminate\Support\Facades\DB;

class MechanicController extends Controller
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
        
            $data = Mechanic::select('tb_mechanics.*', 'vendor.vendor_id as vendor_id_name', 'user.name as created_name', 'user_update.name as updated_name', 
                        'tb_company.name as name_company')
                    ->leftJoin('tb_vendors as vendor', 'tb_mechanics.vendor_id', '=', 'vendor.id')
                    ->leftJoin('user as user', 'tb_mechanics.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_mechanics.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_company as tb_company', 'tb_mechanics.id_company', '=', 'tb_company.id')
                    ->where('tb_mechanics.id_company', $id_company)
                    ->where('tb_mechanics.deleted', 0)
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
            $data = Mechanic::select('tb_mechanics.*', 'vendor.vendor_id as vendor_id_name', 'tb_company.name as name_company')
                    ->leftJoin('tb_vendors as vendor', 'tb_mechanics.vendor_id', '=', 'vendor.id')
                    ->leftJoin('tb_company as tb_company', 'tb_mechanics.id_company', '=', 'tb_company.id')
                    ->where('tb_mechanics.id', $id)
                    ->where('tb_mechanics.deleted', 0)
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
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $phone = $request->phone;
        $vendor_id = $request->vendor_id;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $create = new Mechanic;
                $create->name = $name;
                $create->address1 = $address1;
                $create->address2 = $address2;
                $create->city = $city;
                $create->phone = $phone;
                $create->vendor_id = $vendor_id;
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

        return response()->json($respon);
    }

    public function Update (Request $request) {
        $id = $request->id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $phone = $request->phone;
        $vendor_id = $request->vendor_id;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'name'              => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $update = Mechanic::find($id);
                $update->name = $name;
                $update->address1 = $address1;
                $update->address2 = $address2;
                $update->city = $city;
                $update->phone = $phone;
                $update->vendor_id = $vendor_id;
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
                $update = Mechanic::find($id);
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

            $data = Mechanic::select('id', 'name')
                ->where('id_company', $id_company)
                ->whereRaw("name like'%$search%'")
                ->where('deleted', 0)
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