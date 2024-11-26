<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\MobileAppsVersion;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MobileAppsVersionController extends Controller
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
        
        $data = MobileAppsVersion::select('tb_mobile_apps_version.*', 'user.name as created_name', 'user_update.name as updated_name', 'tb_company.name as name_company')
                ->selectRaw('case 
                    when priority = 0 then "Non Mandatory"
                    when priority = 1 then "Mandatory"
                    end as priority_name')
                ->leftJoin('user as user', 'tb_mobile_apps_version.created_by', '=', 'user.user_id')
                ->leftJoin('user as user_update', 'tb_mobile_apps_version.updated_by', '=', 'user_update.user_id')
                ->leftJoin('tb_company as tb_company', 'tb_mobile_apps_version.id_company', '=', 'tb_company.id')
                ->where('tb_mobile_apps_version.deleted', '0')
                ->limit(50);

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("tb_mobile_apps_version.id_company = $id_company");
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
            $data = MobileAppsVersion::select('tb_mobile_apps_version.*', 'tb_company.name as name_company')
                    ->selectRaw('case 
                        when priority = 0 then "Non Mandatory"
                        when priority = 1 then "Mandatory"
                        end as priority_name')
                    ->leftJoin('tb_company as tb_company', 'tb_mobile_apps_version.id_company', '=', 'tb_company.id')
                    ->where('tb_mobile_apps_version.id', $id)
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

    public function DownloadMobileApps(Request $request) {

        $data = MobileAppsVersion::where('deleted', 0)->orderBy('created_date', 'DESC')->first();

        if (!$data) {
            $respon = array(
                "code" => "02",
                "message" =>  'Data tidak ditemukan !',
            );
        } else {
            $url_link = env("HOST_PATH") . env("PATH_FILE_MOBILE_APPS") . "/" . $data->file;
            $respon = array(
                "code" => "01",
                "data" => $data,
                "download_file" => $url_link,
            );
        }

        return response()->json($respon);
    }

    public function QRCodeMobileApps(Request $request) {

        $data = MobileAppsVersion::where('deleted', 0)->orderBy('created_date', 'DESC')->first();

        if (!$data) {
            $respon = array(
                "code" => "02",
                "message" =>  'Data tidak ditemukan !',
            );
        } else {
            $url_link = '';
            if ($data->file === '') {
                $url_link = env("URL_PLAY_STORE");
            } else {
                $url_link = env("HOST_PATH") . env("PATH_FILE_MOBILE_APPS") . "/" . $data->file;
            }
            // $url_link = env("HOST_PATH") . env("PATH_FILE_MOBILE_APPS") . "/" . $data->file;
            $qrcode = QrCode::color(66, 90, 245)->size(250)->generate($url_link);
            return $qrcode;
        }
    }

    public function CheckVersion(Request $request) {
        $code_version = $request->code_version;
        $type_platform = $request->type_platform;

        $validator = Validator::make($request->all(), [
            'code_version'    => 'required',
            'type_platform'   => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = MobileAppsVersion::where('deleted', 0)->where('type_platform', $type_platform)->orderBy('created_date', 'DESC')->first();
            $url_link = env("HOST_PATH") . env("PATH_FILE_MOBILE_APPS") . "/" . $data->file;
            if ($data->code_version > $code_version) {
                $respon = array(
                    "code" => "02",
                    "message" => "Update Applikasi Versi " . $data->name_version,
                    "version" => $data->name_version,
                    "link" => $url_link,
                    "priority" => $data->priority
                );
            } else {
                $respon = array(
                    "code" => "01",
                    "message" => "Versi Applikasi Sudah Terbaru",
                );
            }
        }

        return response()->json($respon);
    }

    public function Create (Request $request) {
        $code_version = $request->code_version;
        $name_version = $request->name_version;
        $type_platform = $request->type_platform;
        $desc_version = $request->desc_version;
        $priority = $request->priority;
        $file = $request->file;
        $created_by = $request->created_by;

        $validator = Validator::make($request->all(), [
            'code_version'      => 'required',
            'name_version'      => 'required',
            'type_platform'     => 'required',
            'priority'          => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $name_file = '';
            if($request->file('file')) {
                $size = floor($request->file('file')->getSize() / 1024);
                if ($size > 100000) { //100 MB
                    $respon = array(
                        "code" => "02",
                        "message" =>  "File terlalu besar",
                    );
                    return response()->json($respon);
                } else {
                    $ext = $request->file('file')->getClientOriginalExtension();
                    $path = $request->file('file')->move(env("PATH_FILE_MOBILE_APPS"), $type_platform . '_' . $code_version . '_' . str_replace('.', '_', $name_version) .'.'.$ext);
                    $name_file = $type_platform . '_' . $code_version . '_' . str_replace('.', '_', $name_version) .'.'.$ext;
                }
            }
            try 
            {
                $create = new MobileAppsVersion;
                $create->code_version = $code_version;
                $create->name_version = $name_version;
                $create->type_platform = $type_platform;
                $create->desc_version = $desc_version;
                $create->priority = $priority;
                $create->file = $name_file;
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
        $code_version = $request->code_version;
        $name_version = $request->name_version;
        $type_platform = $request->type_platform;
        $desc_version = $request->desc_version;
        $priority = $request->priority;
        $file = $request->file;
        $updated_by = $request->updated_by;

        $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'code_version'      => 'required',
            'name_version'      => 'required',
            'type_platform'     => 'required',
            'priority'          => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $name_file = '';
            if($request->file('file')) {
                $size = floor($request->file('file')->getSize() / 1024);
                if ($size > 100000) { //100 MB
                    $respon = array(
                        "code" => "02",
                        "message" =>  "File terlalu besar",
                    );
                    return response()->json($respon);
                } else {
                    $ext = $request->file('file')->getClientOriginalExtension();
                    $path = $request->file('file')->move(env("PATH_FILE_MOBILE_APPS"), $type_platform . '_' . $code_version . '_' . str_replace('.', '_', $name_version) .'.'.$ext);
                    $name_file = $type_platform . '_' . $code_version . '_' . str_replace('.', '_', $name_version) .'.'.$ext;
                }
            }
            try 
            {
                $update = MobileAppsVersion::find($id);
                $update->code_version = $code_version;
                $update->name_version = $name_version;
                $update->type_platform = $type_platform;
                $update->desc_version = $desc_version;
                $update->priority = $priority;
                if ($name_file !== '') {
                    $update->file = $name_file;
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
                $update = MobileAppsVersion::find($id);
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