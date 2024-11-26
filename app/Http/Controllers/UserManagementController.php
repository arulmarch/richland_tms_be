<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MasterDriver;
use App\Models\MasterUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
Use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Str;

class UserManagementController extends Controller
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

    protected function jwt($dataJwt) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $dataJwt['id'], // Subject of the token
            'random' => $dataJwt['random_string'], // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            // 'exp' => time() + 60*60*24 // Expiration time
            // 'exp' => time() + 60*1 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, config('app.jwt_secret'));
    }

    protected function jwtWeb($dataJwt) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $dataJwt['id'], // Subject of the token
            'random' => $dataJwt['random_string'], // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60*24 // Expiration time
            // 'exp' => time() + 60*1 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, config('app.jwt_secret'));
    }

    public function Login (Request $request) {
        $phone = $request->phone;
        $password = $request->password;
        $token_fcm = $request->token_fcm;

        $validator = Validator::make($request->all(), [
            'phone'      => 'required',
            'password'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_phone = MasterDriver::where('phone', $phone)->where('deleted', 0)->first();
            if (!$check_phone) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Nomer telpon anda tidak terdaftar",
                );
            } else {
                if ($check_phone->password == '' || $check_phone->password == null) {
                    $respon = array(
                        "code" => "02",
                        "message" =>  "User anda belum terdaftar !",
                    );
                } else {
                    if (Hash::check($password, $check_phone->password)) {
                        $data_driver = MasterDriver::where('id', $check_phone->id)->first();
                        $random_string = Str::random(32);
                        $dataJwt = array(
                            'id' => $check_phone->id,
                            'random_string' => $random_string
                        );
                        $token = $this->jwt($dataJwt);

                        try
                        {
                            $update_driver = MasterDriver::find($check_phone->id);
                            $update_driver->token = $random_string;
                            $update_driver->token_fcm = $token_fcm;

                            $update_driver->save();

                            $respon = array(
                                "code" => "01",
                                "token" => $token,
                                "data_driver" => $data_driver,
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
                            "message" =>  "Nomer telpon atau kata sandi anda tidak sesuai !",
                        );
                    }
                }
            }
        }

        return response()->json($respon);
    }

    public function Logout(Request $request) {
        $id_driver = $request->id_driver;

        $validator = Validator::make($request->all(), [
            'id_driver'      => 'required',
        ]);

        try
        {
            $update_driver = MasterDriver::find($id_driver);
            $update_driver->token = "";
            $update_driver->token_fcm = "";

            $update_driver->save();

            $respon = array(
                "code" => "01",
                "message" =>  "Berhasil Keluar",
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

        return response()->json($respon);
    }

    public function Register(Request $request) {
        $phone = $request->phone;
        $password = $request->password;

        $validator = Validator::make($request->all(), [
            'phone'      => 'required',
            'password'      => 'required'
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_phone = MasterDriver::where('phone', $phone)->first();
            if (!$check_phone) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "Nomer telpon anda tidak terdaftar",
                );
            } else {
                if ($check_phone->password != '' && $check_phone->password != null) {
                    $respon = array(
                        "code" => "02",
                        "message" =>  "User anda sudah terdaftar !",
                    );
                } else {
                    $hashed_password = Hash::make($password, [
                        'rounds' => 12
                    ]);

                    try
                    {
                        $regist_driver = MasterDriver::find($check_phone->id);
                        $regist_driver->password = $hashed_password;

                        $regist_driver->save();

                        $respon = array(
                            "code" => "01",
                            "message" =>  "Pendaftaran Pengemudi berhasil",
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
        }

        return response()->json($respon);
    }

    public function GetDataDriverById(Request $request){
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
            $data_driver = MasterDriver::find($id_driver)->first();

            $respon = array(
                "code" => "01",
                "data_driver" => $data_driver,
            );
        }

        return response()->json($respon);
    }

    public function ChangePassword(Request $request){
        $id_driver = $request->id_driver;
        $old_password = $request->old_password;
        $new_password = $request->new_password;

        $validator = Validator::make($request->all(), [
            'id_driver'         => 'required',
            'old_password'      => 'required',
            'new_password'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_user = MasterDriver::where('id', $id_driver)->first();
            if (!$check_user) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "User anda tidak terdaftar",
                );
            } else {
                if (Hash::check($old_password, $check_user->password)) {
                    $hashed_password = Hash::make($new_password, [
                        'rounds' => 12
                    ]);

                    try
                    {
                        $change_password = MasterDriver::find($check_user->id);
                        $change_password->password = $hashed_password;

                        $change_password->save();

                        $respon = array(
                            "code" => "01",
                            "message" =>  "Kata sandi anda berhasil di ubah",
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
                        "message" =>  "Kata sandi lama anda tidak sesuai !",
                    );
                }
            }
        }
        return response()->json($respon);
    }

    public function ResetPassword(Request $request){
        $id_driver = $request->id_driver;

        $validator = Validator::make($request->all(), [
            'id_driver'         => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_user = MasterDriver::where('id', $id_driver)->first();
            if (!$check_user) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "User anda tidak terdaftar",
                );
            } else {
                $new_password = Str::random(6);
                $hashed_password = Hash::make($new_password, [
                    'rounds' => 12
                ]);

                try
                {
                    $change_password = MasterDriver::find($check_user->id);
                    $change_password->password = $hashed_password;

                    $change_password->save();

                    $respon = array(
                        "code" => "01",
                        "new_password" => $new_password,
                        "message" =>  "Reset kata sandi berhasil",
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

    public function LoginWeb (Request $request) {
        $userName = $request->userName;
        $password = $request->password;

        $validator = Validator::make($request->all(), [
            'userName'      => 'required',
            'password'      => 'required',
        ]);

        if ($validator->fails()) {
            $respon = array(
                "code" => "02",
                "message" =>  $validator->messages(),
            );
        } else {
            $check_user = MasterUser::where('username', $userName)->where('deleted', 0)->first();
            if (!$check_user) {
                $respon = array(
                    "code" => "02",
                    "message" =>  "User anda tidak terdaftar",
                );
            } else {

                if ($check_user->password == '' || $check_user->password == null) {
                    $respon = array(
                        "code" => "02",
                        "message" =>  "User anda belum terdaftar !",
                    );
                } else {
                    if (sha1($password) === $check_user->password) {
                        $data_user = MasterUser::where('user_id', $check_user->user_id)->first();
                        $random_string = Str::random(32);
                        $dataJwt = array(
                            'id' => $check_user->user_id,
                            'random_string' => $random_string
                        );
                        $token = $this->jwtWeb($dataJwt);

                        try
                        {
                            $update_user = MasterUser::where('user_id', $check_user->user_id);
                            $array_update = array(
                                'token' => $random_string
                            );

                            $update_user->update($array_update);

                            $respon = array(
                                "code" => "01",
                                "token" => $token,
                                "data_user" => $data_user,
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
                            "message" =>  "Username atau kata sandi anda tidak sesuai !",
                        );
                    }
                }
            }
        }

        return response()->json($respon);
    }

}
