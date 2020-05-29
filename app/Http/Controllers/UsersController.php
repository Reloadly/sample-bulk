<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OTIFSolutions\ACLMenu\Models\UserRole;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard.users.home', [
            'page' => [
                'type' => 'dashboard'
            ],
            'users' => User::all(),
            'userRoles' => UserRole::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'email' => 'required',
           'user_role_id' => 'required',
           'password' => 'required_with:confirm_password|same:confirm_password'
        ]);
        $user = User::find($request['id']);
        if ($user === null){
            $user = new User();
            $request->validate([
                'password' => 'required'
            ]);
        }
        $user['name'] = $request['name'];
        $user['email'] = $request['email'];
        $user['user_role_id'] = $request['user_role_id'];
        if (isset($request['password']))
            $user['password'] = Hash::make($request['password']);
        $user->save();
        return response()->json([
            'location' => '/users',
            'message' => 'User Saved. Redirecting Now'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('dashboard.users.modal',[
            'item' => User::find($id),
            'userRoles' => UserRole::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
    }
}
