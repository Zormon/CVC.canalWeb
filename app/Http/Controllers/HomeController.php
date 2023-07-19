<?php

namespace App\Http\Controllers;

use App\EncodeQueue;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function root(Request $request) {
        $user = Auth::user();

        if ($user) { 
            if ($user->hasRole('admin'))    { return redirect()->route('users'); }
            else                            { return redirect()->route('home');}
        }
        else                                { return redirect()->route('login');}
    }

    public function home(Request $request) {
        $user = User::where('id', [auth()->user()->id])->first();

        if(Auth::user()->hasRole('admin')){
            $pendientes = EncodeQueue::selectRaw("users.name as name, queue.*")->leftJoin('users', 'userId', '=', 'users.id')->orderBy('queue.id','DESC')->get();
            return view('home', ['pendientes' => $pendientes, "last_visit" => $user->last_visit],);
        } else {
            return view('home', ["last_visit" => $user->last_visit]);
        }
    }

    public function phpinfo() {
        Auth::user()->authorizeRoles(['admin']);
        phpinfo();
        die;
    }

    public function issues(Request $request) {
        return view('issues');
    }
}
