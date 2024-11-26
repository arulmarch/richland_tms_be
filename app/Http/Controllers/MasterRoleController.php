<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\UserRole;
use App\Models\UserMenu;
use App\Models\UserMenuItem;
use App\Models\UserSubMenu;
use App\Models\UserAccessMenu;
use App\Models\UserAccessMenuItem;
use App\Models\UserAccessSubMenu;
use Illuminate\Support\Facades\DB;

class MasterRoleController extends Controller
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
        
        $data = UserRole::select('user_role.*', 'user.name as created_name', 'user_update.name as updated_name')
                ->leftJoin('user', 'user_role.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'user_role.updated_by', '=', 'user_update.user_id')
                ->where('user_role.is_active', '1')
                ->get();

        $respon = array(
          "code" => "01",
          "data" => $data 
        );

        return response()->json($respon);
    }

    public function SearchData (Request $request) {

        $search = $request->search;

        $data = UserRole::select('user_role.*', 'user.name as created_name', 'user_update.name as updated_name')
                ->leftJoin('user', 'user_role.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'user_role.updated_by', '=', 'user_update.user_id')
                ->where('user_role.is_active', '1')
                ->where('user_role.role', 'like', '%' . $search . '%')
                ->limit(10)
                ->get();

        $respon = array(
          "code" => "01",
          "data" => $data 
        );

        return response()->json($respon);
    }

    public function GetMenu (Request $request) {
        
        $data_menu = UserMenu::select('*')
                ->where('is_active', '1')
                ->get();
        $data_menu_item = UserMenuItem::select('*')
                ->where('is_active', '1')
                ->get();
        $data_sub_menu = UserSubMenu::select('*')
                ->where('is_active', '1')
                ->get();

        $respon = array(
          "code" => "01",
          "data_menu" => $data_menu,
          "data_menu_item" => $data_menu_item,
          "data_sub_menu" => $data_sub_menu,
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
            $data_role = UserRole::where('id', $id)->first();
            $data_menu = DB::table('user_menu as um')
                            ->select('um.*', 'uam.role_id')
                            ->leftJoin('user_access_menu as uam', 'um.id', '=', DB::raw('uam.menu_id and (uam.menu_id is NULL or uam.role_id = '.$id.')'))
                            ->where('um.is_active', '1')
                            ->get();
            $data_menu_item = DB::table('user_menu_item as umi')
                            ->select('umi.*', 'uami.role_id')
                            ->leftJoin('user_access_menu_item as uami', 'umi.id', '=', DB::raw('uami.menu_item_id and (uami.menu_item_id is NULL or uami.role_id = '.$id.')'))
                            ->where('umi.is_active', '1')
                            ->get();
            $data_sub_menu = DB::table('user_sub_menu as usm')
                            ->select('usm.*', 'uasm.role_id')
                            ->leftJoin('user_access_sub_menu as uasm', 'usm.id', '=', DB::raw('uasm.sub_menu_id and (uasm.sub_menu_id is NULL or uasm.role_id = '.$id.')'))
                            ->where('usm.is_active', '1')
                            ->get();

            if (!$data_role) {
                $respon = array(
                    "code" => "02",
                    "message" =>  'Data tidak ditemukan !',
                );
            } else {
                $respon = array(
                    "code" => "01",
                    "data_role" => $data_role,
                    "data_menu" => $data_menu,
                    "data_menu_item" => $data_menu_item,
                    "data_sub_menu" => $data_sub_menu
                );
            }
        }

        return response()->json($respon);
    }

    public function InsertMenu(Request $request) {
        $role = $request->role;
        $data_menu = $request->data_menu;
        $data_menu_item = $request->data_menu_item;
        $data_sub_menu = $request->data_sub_menu;

        $validator = Validator::make($request->all(), [
            'role'   => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                DB::beginTransaction();
                
                //delete access menu data where role id
                $delete_data_menu = UserAccessMenu::where("role_id", "=", $role);
                $delete_data_menu->delete();

                //delete access menu item data where role id
                $delete_data_menu_item = UserAccessMenuItem::where("role_id", "=", $role);
                $delete_data_menu_item->delete();

                //delete access sub menu data where role id
                $delete_data_sub_menu = UserAccessSubMenu::where("role_id", "=", $role);
                $delete_data_sub_menu->delete();

                $data_menu_array = array();
                if ($data_menu !== null) {
                    if (sizeof($data_menu) !== 0) {
                        foreach ($data_menu as $value) {
                            $data_menu_object = [
                                'role_id' => $role,
                                'menu_id' => $value
                            ];
                            array_push($data_menu_array, $data_menu_object);
                        }
                    }
                }

                $data_menu_item_array = array();
                if ($data_menu_item !== null) {
                    if (sizeof($data_menu_item) !== 0) {
                        foreach ($data_menu_item as $value) {
                            $data_menu_item_object = [
                                'role_id' => $role,
                                'menu_item_id' => $value
                            ];
                            array_push($data_menu_item_array, $data_menu_item_object);
                        }
                    }
                }

                $data_sub_menu_array = array();
                if ($data_sub_menu !== null) {
                    if (sizeof($data_sub_menu) !== 0) {
                        foreach ($data_sub_menu as $value) {
                            $data_sub_menu_object = [
                                'role_id' => $role,
                                'sub_menu_id' => $value
                            ];
                            array_push($data_sub_menu_array, $data_sub_menu_object);
                        }
                    }
                }
                
                //insert table
                UserAccessMenu::insert($data_menu_array);
                UserAccessMenuItem::insert($data_menu_item_array);
                UserAccessSubMenu::insert($data_sub_menu_array);

                DB::commit();
                                
                $respon = array(
                    "code" => "01",
                    // "data_menu" => $data_menu_array,
                    // "data_menu_item" => $data_menu_item_array,
                    // "data_sub_menu" => $data_sub_menu_array,
                    "message" => "Berhasil menyimpan data",
                );
            }
            catch(Exception $e)
            {
                DB::rollback();

                $respon = array(
                    "code" => "03",
                    "message" =>  "Ada masalah dengan server, harap coba lagi nanti !",
                    "error_message" =>  $e,
                );
            }
        }

        return response()->json($respon);
    }

    public function Create (Request $request) {
        $role = $request->role;
        $created_by = $request->created_by;

        $validator = Validator::make($request->all(), [
            'role'   => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $create = new UserRole;
                $create->role = $role;
                $create->created_by = $created_by;

                $create->save();
                $id_role = $create->id;
                                
                $respon = array(
                    "code" => "01",
                    "id_role" => $id_role,
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
        $role = $request->role;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $update = UserRole::find($id);
                $update->role = $role;
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
                $update = UserRole::find($id);
                $update->is_active = 0;
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