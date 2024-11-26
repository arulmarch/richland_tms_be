<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\Manifest;
use Illuminate\Support\Facades\DB;

class ManifestController extends Controller
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

    public function GetDataOdometer (Request $request) {
        $id_manifest = $request->id_manifest;

        $data = Manifest::select('start', 'finish', 'mileage')
                ->where('id', $id_manifest)
                ->first();

        $respon = array(
          "code" => "01",
          "data" => $data 
        );

        return response()->json($respon);
    }

    public function UpdateOdometer (Request $request) {
        $id_manifest = $request->id_manifest;
        $start_odometer = $request->start_odometer;
        $finish_odometer = $request->finish_odometer;
        $mileage = $request->mileage;

        $validator = Validator::make($request->all(), [
            'id_manifest'      => 'required',
            'start_odometer'   => 'required',
            'finish_odometer'  => 'required',
            'mileage'          => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            try 
            {
                $update = Manifest::find($id_manifest);
                $update->start = $start_odometer;
                $update->finish = $finish_odometer;
                $update->mileage = $mileage;

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
    
}