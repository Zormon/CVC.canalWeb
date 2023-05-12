<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use Intervention\Image\Facades\Image;


class UserController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->logos_path = public_path('/storage/logos');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['user','admin']);
        $user = DB::table('users')->where('id', [auth()->user()->id])->first();

        if(Auth::user()->hasRole('admin')){
            return view('editadmin');
        } else {
            $userinfo = DB::table('users')->where('id', [auth()->user()->id])->first();
            if(isset($userinfo->schedule)){ $userinfo->schedule = json_decode($userinfo->schedule);}

            return view('useredituser', ['user' => $userinfo]);
        }
    }

    public function store(Request $request) {
        $request->user()->authorizeRoles(['user','admin']);
        $data = $request->all();

        if(!DB::table('users')->where('id',[auth()->user()->id])->count()){
            DB::table('users')->insert(['id'=> auth()->user()->id]);
        }

        if(empty($data['max_lists'])){
            $data['max_lists'] = 0;
        }

        if(!empty($data['password']) AND ($data['password'] == $data['password_confirmation'])){
            $password = bcrypt($request->get('password'));
            DB::table('users')->where('id',[auth()->user()->id])->update(['password' => $password]);
        }

        $data['schedule'] = json_encode($data['schedule']);
        DB::table('users')->where('id',[auth()->user()->id])->update(['schedule' => $data['schedule'], 'max_lists' => $data['max_lists']]);

        unset($data['_token']);     unset($data['name']);
        unset($data['max_lists']);  unset($data['id']);
        unset($data['password']);   unset($data['password_confirmation']);

        if ( !is_dir($this->logos_path) ) { mkdir($this->logos_path, 0777); }

        DB::table('users')->where('id',[auth()->user()->id])->update($data);

        return redirect()->back()->with("success",__("Successfully saved!"));
    }
}
