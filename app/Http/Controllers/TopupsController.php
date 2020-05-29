<?php

namespace App\Http\Controllers;

use App\Country;
use App\Currency;
use App\Invoice;
use App\Mail\InvoiceCreated;
use App\Operator;
use App\System;
use App\TopupSubscription;
use Illuminate\Http\Request;
use App\Topup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TopupsController extends Controller
{
    public function index(){
        return view('dashboard.topups.home', [
            'page' => [
                'type' => 'dashboard'
            ],
            'topups' => Topup::all()
        ]);
    }

    public function getPinDetail($id){
        return view('dashboard.topups.pin',[
            'topup' => Topup::find($id)
        ]);
    }

    public function getFailedDetail($id){
        return view('dashboard.topups.failed',[
            'topup' => Topup::find($id)
        ]);
    }
    public function retryTopup($id){
        $user = Auth::user();
        $topup = Topup::find($id);
        if ($topup === null)
            return response()->json(['errors' => ['error' => 'Topup Not Found!']],422);
        if ($topup['user_id'] !== $user['id'])
            return response()->json(['errors' => ['error' => 'Unauthorized Action.']],422);
        $topup['status'] = 'PENDING';
        $topup->save();
        return response()->json([
            'location' => '/topups',
            'message' => 'Retry Scheduled Successfully.'
        ]);
    }

}
