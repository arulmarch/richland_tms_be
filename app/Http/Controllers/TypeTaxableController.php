<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use Exception;
use App\Models\TypeTaxable;
use Illuminate\Support\Facades\DB;

class TypeTaxableController extends Controller
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

        $search = $request->search;

        $data = TypeTaxable::select('id', 'name')
                ->where('deleted', '0')
                ->whereRaw("name like '%${search}%'")
                ->limit(10)
                ->get();

        $respon = array(
            "code" => "01",
            "data" => $data 
        );

        return response()->json($respon);
    }

}