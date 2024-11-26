<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Owned;
use Illuminate\Support\Facades\DB;

class OwnedController extends Controller
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
        
            $data = Owned::select('tb_pt_owned.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company', 'taxable_type.name as taxable_name')
                    ->leftJoin('user', 'tb_pt_owned.created_by', '=', 'user.user_id')
                    ->leftJoin('user as user_update', 'tb_pt_owned.updated_by', '=', 'user_update.user_id')
                    ->leftJoin('tb_type_taxable as taxable_type', 'tb_pt_owned.taxable', '=', 'taxable_type.id')
                    ->leftJoin('tb_company as tb_company', 'tb_pt_owned.id_company', '=', 'tb_company.id')
                    ->where('tb_pt_owned.id_company', $id_company)
                    ->where('tb_pt_owned.deleted', '0')
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
            $data = Owned::select('tb_pt_owned.*', 'tb_company.name as name_company', 'taxable_type.name as taxable_name')
                    ->leftJoin('tb_company as tb_company', 'tb_pt_owned.id_company', '=', 'tb_company.id')
                    ->leftJoin('tb_type_taxable as taxable_type', 'tb_pt_owned.taxable', '=', 'taxable_type.id')
                    ->where('tb_pt_owned.id', $id)
                    ->where('tb_pt_owned.deleted', 0)
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
        $code = $request->code;
        $name_pt = $request->name_pt;
        $address_pt = $request->address_pt;
        $account_bank_name = $request->account_bank_name;
        $account_name = $request->account_name;
        $account_bank_number = $request->account_bank_number;
        $director = $request->director;
        $telp_pt = $request->telp_pt;
        $taxable = $request->taxable;
        $created_by = $request->created_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'name_pt'           => 'required',
            'address_pt'        => 'required',
            'taxable'           => 'required',
            'id_company'        => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $countDataOwned = 0;
            if ($code !== null && $code !== '') {
                $countDataOwned = Owned::where('code', $code)->where('deleted', 0)
                ->where('id_company', $id_company)->count();
            }
            if ($countDataOwned > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Code tidak boleh sama",
                );
            } else {
                try 
                {
                    $create = new Owned;
                    $create->code = $code;
                    $create->name_pt = $name_pt;
                    $create->address_pt = $address_pt;
                    $create->account_bank_name = $account_bank_name;
                    $create->account_name = $account_name;
                    $create->account_bank_number = $account_bank_number;
                    $create->director = $director;
                    $create->telp_pt = $telp_pt;
                    $create->taxable = $taxable;
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
        $code = $request->code;
        $name_pt = $request->name_pt;
        $address_pt = $request->address_pt;
        $account_bank_name = $request->account_bank_name;
        $account_name = $request->account_name;
        $account_bank_number = $request->account_bank_number;
        $director = $request->director;
        $telp_pt = $request->telp_pt;
        $taxable = $request->taxable;
        $updated_by = $request->updated_by;
        $id_company = $request->id_company;

        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'name_pt'       => 'required',
            'address_pt'    => 'required',
            'taxable'       => 'required',
            'id_company'    => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $countDataOwned = 0;
            if ($code !== null && $code !== '') {
                $countDataOwned = Owned::where('code', $code)->where('id','!=', $id)
                ->where('deleted', 0)->where('id_company', $id_company)->count();
            }
            if ($countDataOwned > 0) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Code tidak boleh sama",
                );
            } else {
                try 
                {
                    $update = Owned::find($id);
                    $update->code = $code;
                    $update->name_pt = $name_pt;
                    $update->address_pt = $address_pt;
                    $update->account_bank_name = $account_bank_name;
                    $update->account_name = $account_name;
                    $update->account_bank_number = $account_bank_number;
                    $update->director = $director;
                    $update->telp_pt = $telp_pt;
                    $update->taxable = $taxable;
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
                $update = Owned::find($id);
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