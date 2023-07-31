<?php

namespace App\Http\Controllers;

use App\Models\EncodeQueue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function root(Request $request) {
        $user = Auth::user();

        if ($user) { 
            if ($user->isAdmin())           { return redirect()->route('users'); }
            else                            { return redirect()->route('home');}
        }
        else                                { return redirect()->route('login');}
    }

    public function home(Request $request) {
        $user = User::where('id', [auth()->user()->id])->first();

        if(Auth::user()->isAdmin()){
            $queue = EncodeQueue::selectRaw("users.name as username, queue.*")->leftJoin('users', 'userId', '=', 'users.id')->orderBy('queue.id','DESC')->get();
            return view('home', ['queue' => $queue, "last_visit" => $user->last_visit],);
        } else {
            return view('home', ["last_visit" => $user->last_visit]);
        }
    }

    public function phpinfo() {
        Auth::user()->authorizeAdmin();
        phpinfo();
        die;
    }

    public function issues(Request $request) {
        return view('issues');
    }
}
