<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $user = DB::table('users')->where('id', [auth()->user()->id])->first();

        if(Auth::user()->hasRole('admin')){
            $pendientes = DB::table('encode_queues')->selectRaw("users.name as name, encode_queues.*")->leftJoin('users', 'userId', '=', 'users.id')->orderBy('encode_queues.id','DESC')->get();
            return view('home', ['pendientes' => $pendientes, "last_visit" => $user->last_visit],);
        } else {
            return view('home', ["last_visit" => $user->last_visit]);
        }
    }
}
