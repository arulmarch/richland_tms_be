<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\RingCode;
use Illuminate\Support\Facades\DB;

class RingCodeController extends Controller
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

    public function SearchData (Request $request) {

        $id_company = $request->id_company;
        $search = $request->search;

        $data = RingCode::select('id', 'ring_name')
            ->where('ring_name', 'like', '%' . $search . '%')
            ->where('deleted', 0)
            ->limit(10);

        if ($id_company !== null && $id_company !== "") {
            $data->whereRaw("(id_company = $id_company or id_company is null)");
        } else {
            $data->whereRaw("id_company is null");
        }

        $data_response = $data->get();

        $respon = array(
            "code" => "01",
            "data" => $data_response 
        );

        $response_code = 200;

        return response()->json($respon, $response_code);
    }
}