<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Operator;
use App\Country;
use App\System;
use Illuminate\Support\Facades\Auth;

class OperatorsController extends Controller
{
    public function get($id){
        return response()->json(Operator::find($id));
    }
    public function detect($id,$number){
        return System::me()->autoDetectOperator($number,Country::find($id)['iso'],-1);
    }
}
