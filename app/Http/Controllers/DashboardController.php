<?php

namespace App\Http\Controllers;

use App\Currency;
use App\File;
use App\FileEntry;
use App\Invoice;
use App\MobileNumber;
use App\System;
use App\User;
use Illuminate\Http\Request;
use App\Country;
use App\Operator;
use Illuminate\Support\Facades\Auth;
use App\Topup;
use PhpParser\Node\Stmt\Else_;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.dashboard', [
            'page' => [
                'type' => 'dashboard'
            ],
            'stats' => [
                'balance' => System::me()->getBalance(),
                'countries' => Country::all()->count(),
                'operators' => Operator::all()->count(),
                'topups' => Topup::all()->count(),
                'users' => User::all()->count(),
                'topups_total' => FileEntry::whereIn('file_id',Auth::user()->files()->pluck('id'))->sum('amount')
            ]
        ]);
    }

    public  function statsTopupAmount(){
        $topups = FileEntry::whereIn('file_id',Auth::user()->files()->pluck('id'))->pluck('amount')->toArray();
        return response()->json(array_map(function($num){return number_format($num,2);}, $topups));
    }

}
