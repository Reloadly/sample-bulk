<?php

namespace App\Http\Controllers;

use App\System;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use OTIFSolutions\ACLMenu\Models\UserRole;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']], isset($request['remember-me']))) {
            $user = Auth::user();
            return response()->json([
                'location' => '/dashboard',
                'message' => 'Login Success. Redirecting Now'
            ]);
        }else return response()->json(['errors' => [
            'error' => 'Authentication Failed'
        ]],422);
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

}
