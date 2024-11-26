<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MasterCustomer;
use Illuminate\Support\Facades\DB;

class MasterCustomerController extends Controller
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
        
            $data = MasterCustomer::select('tb_customers.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company', 'tb_areas.area_id as area_id_name')
                    ->selectRaw('(case type when 1 then "MODERN" else "TRADITIONAL" end) as customer_type_name')
                    ->leftJoin('user as user', 'tb_customers.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_customers.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_areas as tb_areas', 'tb_customers.area_id', '=', 'tb_areas.id')
                    ->leftJoin('tb_company as tb_company', 'tb_customers.id_company', '=', 'tb_company.id')
                    ->where('tb_customers.id_company', $id_company)
                    ->where('tb_customers.deleted', '0')
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
            $data = MasterCustomer::select('tb_customers.*', 'tb_company.name as name_company', 'tb_areas.area_id as area_id_name', 'tb_areas.description')
                    ->selectRaw('(case type when 1 then "MODERN" else "TRADITIONAL" end) as customer_type_name')
                    ->leftJoin('tb_company as tb_company', 'tb_customers.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_areas as tb_areas', 'tb_customers.area_id', '=', 'tb_areas.id')
                    ->where('tb_customers.id', $id)
                    ->where('tb_customers.deleted', 0)
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
        $customer_id = $request->customer_id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $type = $request->type;
        $city = $request->city;
        $position = $request->position;
        $region_id = $request->region_id;
        $area_id = $request->area_id;
        $postal_code = $request->postal_code;
        $phone = $request->phone;
        $fax = $request->fax;
        $pic = $request->pic;
        $email = $request->email;
        $additional_information = $request->additional_information;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required',
            'name'          => 'required',
            'area_id'       => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (MasterCustomer::where('customer_id', $customer_id)->where('deleted', 0)
                ->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Customer Id tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new MasterCustomer;
                    $create->customer_id = $customer_id;
                    $create->name = $name;
                    $create->address1 = $address1;
                    $create->address2 = $address2;
                    $create->type = $type;
                    $create->city = $city;
                    $create->position = $position;
                    $create->region_id = $region_id;
                    $create->area_id = $area_id;
                    $create->postal_code = $postal_code;
                    $create->phone = $phone;
                    $create->fax = $fax;
                    $create->pic = $pic;
                    $create->email = $email;
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
        $customer_id = $request->customer_id;
        $name = $request->name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $type = $request->type;
        $city = $request->city;
        $position = $request->position;
        $region_id = $request->region_id;
        $area_id = $request->area_id;
        $postal_code = $request->postal_code;
        $phone = $request->phone;
        $fax = $request->fax;
        $pic = $request->pic;
        $email = $request->email;
        $additional_information = $request->additional_information;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'customer_id'   => 'required',
            'name'          => 'required',
            'area_id'       => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if (MasterCustomer::where('customer_id', $customer_id)->where('id','!=', $id)
                ->where('deleted', 0)->where('id_company', $id_company)->count() > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Code tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = MasterCustomer::find($id);
                    $update->customer_id = $customer_id;
                    $update->name = $name;
                    $update->address1 = $address1;
                    $update->address2 = $address2;
                    $update->type = $type;
                    $update->city = $city;
                    $update->position = $position;
                    $update->region_id = $region_id;
                    $update->area_id = $area_id;
                    $update->postal_code = $postal_code;
                    $update->phone = $phone;
                    $update->fax = $fax;
                    $update->pic = $pic;
                    $update->email = $email;
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
                $update = MasterCustomer::find($id);
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
        
            $data = MasterCustomer::select('tb_customers.id', 'tb_customers.customer_id', 'tb_customers.name', 'tb_customers.address1', 
                        'tb_customers.area_id', 'area.area_id as area_id_name', 'area.description')
                    ->leftJoin('tb_areas as area', 'tb_customers.area_id', '=', 'area.id')
                    ->where('tb_customers.id_company', $id_company)
                    ->where('tb_customers.deleted', '0')
                    ->whereRaw("(tb_customers.customer_id like '%${search}%' or tb_customers.name like '%${search}%')")
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