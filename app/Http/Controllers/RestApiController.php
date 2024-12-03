<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestApiController extends Controller
{

    public function all_order(Request $request){
        return $request->all();
    }
}
