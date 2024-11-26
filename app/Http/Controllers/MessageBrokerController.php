<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Firebase\Firebase;
use App\Firebase\Push;
use App\Models\MasterDriver;
use App\Models\Notification;


class MessageBrokerController extends Controller
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

    function fcm($title, $message, $register_id, $pushType, $type_notif, $data)
    {
        // Enabling error reporting
        error_reporting(-1);
        ini_set('display_errors', 'On');

        $firebase = new Firebase();
        $push = new Push();

        $push_type = $pushType;

        // whether to include to image or not
        $include_image = FALSE;

        $data = array(
            "title" => $title,
            "message" =>  $message,
            "type_notif" => $type_notif,
            "data" => $data
        );
        $push->setPayload(json_encode($data));
        
        $data_notif = array(
            "title" => $title,
            "body" =>  $message,
        );

        $json = '';
        $responsef = '';

        if ($push_type == 'topic') {
            $json = $push->getPush();
            $responsef = $firebase->sendToTopic('global', $json);
        } else if ($push_type == 'individual') {
            $json = $push->getPush();
            $responsef = $firebase->send($register_id, $json, $data_notif);
        } else if ($push_type == 'multiple') {
            $json = $push->getPush();
            $responsef = $firebase->sendMultiple($register_id, $json);
        } 
    }

    public function SendNotifTracking (Request $request) {
        $id_driver = $request->id_driver;

        $validator = Validator::make($request->all(), [
            'id_driver'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $driver_data = MasterDriver::where('id', $id_driver)->first();
            if (!$driver_data) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "User anda tidak terdaftar",
                );
            } else {
                $this->fcm("Tracking", "Get Location", $driver_data->token_fcm, 'individual', 'notif_tracking', null);
                $respon = array(
                    "code" => "01",
                    "message" =>  "Sending Notif",
                    // "token_fcm" => $driver_data->token_fcm,
                );
            }
        }

        return response()->json($respon);

    }

    public function SendNotifSingleToken (Request $request) {
        $title = $request->title;
        $message = $request->message;
        // $token_fcm = $request->token_fcm;
        $type_user =  $request->type_user;
        $id_user =  $request->id_user;
        $type_notif =  $request->type_notif;
        $id_reference =  $request->id_reference;
        // $data = $request->data;
        // "data": {
        //     "type_notif": "order",
        //     "id_reference": "1234",
        //     "title" : "test1",
        //     "desc" : "test2"
        // }

        $validator = Validator::make($request->all(), [
            'title'         => 'required',
            'message'       => 'required',
            // 'token_fcm'     => 'required',
            'type_user'     => 'required',
            'id_user'       => 'required',
            'type_notif'    => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $token_fcm = '';
            if ($type_user === env("TYPE_USER_DRIVER")) {
                $driver_data = MasterDriver::where('id', $id_user)->first();
                if ($driver_data) {
                    $token_fcm = $driver_data->token_fcm;
                }
                
            }

            if ($token_fcm !== '') {
                try 
                {
                    $create = new Notification;
                    $create->title = $title;
                    $create->description = $message;
                    $create->type_user = $type_user;
                    $create->id_user = $id_user;
                    $create->type_notif = $type_notif;
                    $create->id_reference = $id_reference;

                    $create->save();
                                    
                    // $this->fcm($title, $message, $token_fcm, 'individual', 'notif', $data);
                    $this->fcm($title, $message, $token_fcm, 'individual', $type_notif, null);

                    $respon = array(
                        "code" => "01",
                        "token_fcm" => $token_fcm,
                        "message" =>  "Sending Notif Success",
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
                    "message" =>  "Token tidak ditemukan !",
                );
            }
        }

        return response()->json($respon);

    }

    public function GetData (Request $request) {

        $id_user =  $request->id_user;

        $validator = Validator::make($request->all(), [
            'id_user'         => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $data = Notification::where('id_user', $id_user)->limit(50)->orderBy('created_date', 'desc')->get();
            $respon = array(
                "code" => "01",
                "data" => $data 
            );
        }

        return response()->json($respon);
    }

}