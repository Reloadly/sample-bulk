<?php

namespace App\Http\Controllers;

use App\Country;
use App\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index(){
        return view('dashboard.settings',[
            'page' => [
                'type' => 'dashboard'
            ],
            'countries' => Country::all()
        ]);
    }

    public  function save(Request $request){
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'reloadly_api_key' => 'required',
            'reloadly_api_secret' => 'required'
        ]);
        if (isset($request['password']) && $request['password'] != ''){
            $request->validate([
                'password' => 'required_with:confirm_password|same:confirm_password'
            ]);
            $user['password'] = Hash::make($request['password']);
        }
        $user['name'] = $request['name'];
        $user['email'] = $request['email'];
        $system = System::me();
        $system['reloadly_api_key'] = $request['reloadly_api_key'];
        $system['reloadly_api_secret'] = $request['reloadly_api_secret'];
        $system['reloadly_api_mode'] = isset($request['reloadly_api_mode'])?'LIVE':'TEST';
        $token = $system->getToken();
        if ($token === null)
            return response()->json([ 'errors' => ['Error' => 'Api Auth Failed. Please Check Key/Secret and try again.']],422);
        $system['reloadly_api_token'] = $token;
        $system->save();
        $user->save();
        Artisan::call('schedule:run');
        return response()->json(['message' => 'Settings Saved. Operator Sync Started!']);
    }

    public function uploadLogo(Request $request){
        $request->validate([
            'image' => 'required|file',
            'type' => 'required'
        ]);
        $system = System::me();
        switch ($request['type']){
            case 'full':
                $system['full_logo'] = '/'.$request['image']->store('assets/uploads/','public');
            break;
            case 'icon':
                $system['icon_logo'] = '/'.$request['image']->store('assets/uploads/','public');
                break;
            case 'text':
                $system['text_logo'] = '/'.$request['image']->store('assets/uploads/','public');
                break;
        }
        $system->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Upload Success. File processing started.'
        ]);
    }
}
