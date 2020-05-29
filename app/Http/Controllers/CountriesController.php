<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;

class CountriesController extends Controller
{
    public function index(){
        return view('dashboard.countries.home', [
            'page' => [
                'type' => 'dashboard'
            ],
            'countries' => Country::all()
        ]);
    }
    public function getOperatorsForCountry($id){
        return response()->json(Country::find($id)['operators']);
    }

    public function getAll(){
        return response()->json(Country::get());
    }
}
