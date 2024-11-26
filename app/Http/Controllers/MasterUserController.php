<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
Use Exception;
use App\Models\MasterUser;
use Illuminate\Support\Facades\DB;

class MasterUserController extends Controller
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

        $start = $request->start;
        $length = $request->length;
        $draw = $request->draw;
        $search = $request->search['value'];
        $order_by = $request->order[0]['column'];
        $name_order_by = "";
        $type_order_by = $request->order[0]['dir'];
        $id_company = $request->id_company;

        if ($order_by === "1") {
            $name_order_by = "username";
        } else if ($order_by === "2") {
            $name_order_by = "employee_id";
        } else if ($order_by === "3") {
            $name_order_by = "name";
        } else if ($order_by === "4") {
            $name_order_by = "role";
        } else if ($order_by === "5") {
            $name_order_by = "gender";
        } else if ($order_by === "6") {
            $name_order_by = "contact_email";
        } else if ($order_by === "7") {
            $name_order_by = "phone";
        } else if ($order_by === "8") {
            $name_order_by = "name_company";
        } 

        $data = MasterUser::select('user.*', 'tb_company.name as name_company', 'user_role.role');
        $data->selectRaw("case when user.gender = 1 then 'Laki - Laki' when user.gender = 2 then 'Wanita' end as jenis_kelamin");
        $data->leftJoin('tb_company as tb_company', 'user.id_company', '=', 'tb_company.id');
        $data->leftJoin('user_role as user_role', 'user.role_id', '=', 'user_role.id');
        $data->where('user.deleted', 0);
        if ($id_company !== null && $id_company !== "") {
            $data->where('user.id_company', $id_company);
        }
        if ($search !== '' && $search !== null) {
            $data->where('user.name', 'like', '%' . $search . '%');
        }
        if ($name_order_by !== '' && $name_order_by !== null) {
            $data->orderBy($name_order_by, $type_order_by);
        }
        $data->get();
        
        $count_all_data = $data->count();

        $data_filter = MasterUser::select('user.*', 'tb_company.name as name_company', 'user_role.role', 'user_created.name as created_name', 'user_update.name as updated_name');
        $data_filter->selectRaw("case when user.gender = 1 then 'Laki - Laki' when user.gender = 2 then 'Wanita' end as jenis_kelamin");
        $data_filter->leftJoin('tb_company as tb_company', 'user.id_company', '=', 'tb_company.id');
        $data_filter->leftJoin('user_role as user_role', 'user.role_id', '=', 'user_role.id');
        $data_filter->leftJoin('user as user_created', 'user.created_by', '=', 'user_created.user_id');
        $data_filter->leftJoin('user as user_update', 'user.updated_by', '=', 'user_update.user_id');
        $data_filter->where('user.deleted', '0');
        if ($id_company !== null && $id_company !== "") {
            $data_filter->where('user.id_company', $id_company);
        }
        if ($search !== '' && $search !== null) {
            $data_filter->where('user.name', 'like', '%' . $search . '%');
        }
        $data_filter->offset($start);
        $data_filter->limit($length);
        if ($name_order_by !== '' && $name_order_by !== null) {
            $data_filter->orderBy($name_order_by, $type_order_by);
        }
        $data_filter_data = $data_filter->get();

        $count_filter_data = $data_filter->count();

        $respon = array(
          "code" => "01",
          "name_order_by" => $name_order_by,
          "draw" => $draw,
          "search" => $search,
          "recordsTotal" => $count_all_data,
          "recordsFiltered" => $count_all_data,
          "data" => $data_filter_data,
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
            $data = MasterUser::select('user.*', 'user_role.role as role_name', 'tb_company.name as name_company')
                    ->where('user_id', $id)
                    ->leftJoin('user_role as user_role', 'user.role_id', '=', 'user_role.id')
                    ->leftJoin('tb_company as tb_company', 'user.id_company', '=', 'tb_company.id')
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
        $username = $request->username;
        $password = $request->password;
        $name = $request->name;
        $role_id = $request->role_id;
        $phone = $request->phone;
        $gender = $request->gender;
        $contact_email = $request->contact_email;
        $employee_id = $request->employee_id;
        $image = $request->image;
        $id_company = $request->id_company;
        $created_by = $request->created_by;

        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'password'  => 'required',
            'name'      => 'required',
            'role_id'   => 'required',
            'gender'    => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_username = MasterUser::where('username', $username)->where('deleted', 0)->count();
            if ($check_username >= 1) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Username ini sudah terdaftar, silahkan ganti dengan yang lain!",
                );
                return response()->json($respon);
            }
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
                    $path = $request->file('image')->move(env("PATH_IMAGE_USER_PROFILE"), $date_now .'.'.$ext);
                    $name_image = $date_now .'.'.$ext;
                }
            }

            $hashed_password = sha1($password);
            
            try 
            {
                $create = new MasterUser;
                $create->username = $username;
                $create->password = $hashed_password;
                $create->name = $name;
                $create->role_id = $role_id;
                $create->phone = $phone;
                $create->gender = $gender;
                $create->contact_email = $contact_email;
                $create->employee_id = $employee_id;
                $create->image = $name_image;
                $create->id_company = $id_company;
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
        $username = $request->username;
        $role_id = $request->role_id;
        $phone = $request->phone;
        $gender = $request->gender;
        $contact_email = $request->contact_email;
        $employee_id = $request->employee_id;
        $image = $request->image;
        $id_company = $request->id_company;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'        => 'required',
            'name'      => 'required',
            'username'  => 'required',
            'role_id'   => 'required',
            'gender'    => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_username = MasterUser::where('username', $username)->where('user_id', '!=', $id)->where('deleted', 0)->count();
            if ($check_username >= 1) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Username ini sudah terdaftar, silahkan ganti dengan yang lain!",
                );
                return response()->json($respon);
            }
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
                    $path = $request->file('image')->move(env("PATH_IMAGE_USER_PROFILE"), $date_now .'.'.$ext);
                    $name_image = $date_now .'.'.$ext;
                }
            }
            
            try 
            {
                $update = MasterUser::where('user_id', $id);
                $array_update = array(
                    'name' => $name,
                    'username' => $username,
                    'role_id' => $role_id,
                    'phone' => $phone,
                    'gender' => $gender,
                    'contact_email' => $contact_email,
                    'employee_id' => $employee_id,
                    'id_company' => $id_company,
                    'updated_by' => $updated_by
                );
                if ($name_image !== '') {
                    $array_update['image'] = $name_image;
                }
                
                $update->update($array_update);
                                
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
                $update = MasterUser::where('user_id', $id);
                $array_update = array(
                    'deleted' => 1,
                    'updated_by' => $updated_by
                );

                $update->update($array_update);
                                
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

    public function Activated (Request $request) {
        $id = $request->id;
        $status = $request->status;
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
                $update = MasterUser::where('user_id', $id);
                $array_update = array(
                    'is_active' => $status,
                    'updated_by' => $updated_by
                );

                $update->update($array_update);
                                
                $respon = array(
                    "code" => "01",
                    "message" => "Berhasil ubah data",
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

    public function ChangeImage (Request $request) {
        $user_id = $request->user_id;
        $image = $request->image;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'image'         => 'required'
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
                    $path = $request->file('image')->move(env("PATH_IMAGE_USER_PROFILE"), $date_now .'.'.$ext);
                    $name_image = $date_now .'.'.$ext;
                }
            }
            try 
            {
                $update = MasterUser::where('user_id', $user_id);
                $update->update(
                    ['image' => $name_image],
                    ['updated_by' => $updated_by]
                );
                                
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

    public function ChangePassword (Request $request) {
        $id = $request->id;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'                    => 'required',
            'password'              => 'required',
            'confirm_password'      => 'required'
        ]);

        $response_code = 400;

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            if ($password !== $confirm_password) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Confirm Password tidak sama",
                );
            } else {

                $hashed_password = sha1($password);

                try 
                {
                    $update = MasterUser::where('user_id', $id);
                    $array_update = array(
                        'password' => $hashed_password,
                        'updated_by' => $updated_by
                    );
                    $update->update($array_update);
                                    
                    $respon = array(
                        "code" => "01",
                        "message" => "Berhasil mengganti password",
                    );

                    $response_code = 200;
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

        return response()->json($respon, $response_code);
    }

}