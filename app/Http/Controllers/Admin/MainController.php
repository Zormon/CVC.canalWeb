<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use Intervention\Image\Facades\Image;

class AdminController extends Controller {
    public function __construct() {}

    public function userlist(Request $request) {
        $users = DB::table('users')->whereRaw('id!=1')->orderBy('id','DESC')->get();

        $encoding_queue = DB::table('encode_queues')->count();

        return view('userlist', ['users' => $users]);
    }

    public function ffmpeg(Request $request) {
        $salida = shell_exec(env('PHP_BINARY').' '.env('ENCODING_SCRIPT').' > /dev/null 2>&1 &');

        return redirect()->back()->with("success",__("Successfully!"));
    }
    
    public function EncodeQueue(Request $request) {
        $salida = shell_exec(env('PHP_BINARY').' '.env('ENCODING_SCRIPT').' > /dev/null 2>&1 &');

        return redirect()->back()->with("success",__("Successfully!"));
    }

    public function searchusers(Request $request) {
        $data = $request->json()->all();

        $users = DB::table('users')->where("users.name", "like", '%'.$data[0]['query'].'%')->orderBy('users.id','DESC')->get();

        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function userswithplaylist() {
        $users = DB::table('users')->whereRaw('id!=1')->orderBy('id','DESC')->get();

        return view('userslistplaylists', ['users' => $users]);
    }

    public function userplaylists($id) {
        $counter = null;
        $playlists = DB::table('playlists')->where('userId', [$id])->orderBy('id','DESC')->get();

        foreach ($playlists as $key => $value) {
            $active = DB::table('uploads')->where('playlistId',$value->id)->where('active',1)->count();
            $noactive = DB::table('uploads')->where('playlistId',$value->id)->where('active',0)->count();

            $counter[$value->id]['active'] = $active;
            $counter[$value->id]['noactive'] = $noactive;
        }

        $user = DB::table('users')->where('id', [$id])->first();

        return view('lista.admin.index', ['id' => $id, 'playlists' => $playlists, 'counter' => $counter]);

    }

    public function userentrylist($id) {
        $counter = null;
        $playlists = DB::table('playlists')->where('userId', [$id])->orderBy('id','DESC')->get();

        foreach ($playlists as $key => $value) {
            $active = DB::table('uploads')->where('playlistId',$value->id)->where('active',1)->count();
            $noactive = DB::table('uploads')->where('playlistId',$value->id)->where('active',0)->count();

            $counter[$value->id]['active'] = $active;
            $counter[$value->id]['noactive'] = $noactive;
        }

        $user = DB::table('users')->where('id', [$id])->first();

        return view('lista.index', ['playlists' => $playlists, 'counter' => $counter]);
    }

    public function edituser(Request $request, $id) {
        $request->user()->authorizeRoles(['admin']);

        $userinfo = DB::table('users')->where('id', $id)->first();
        $userinfo->schedule = json_decode($userinfo->schedule);

        return view('edituser', ['user' => $userinfo]);
    }

    public function profilestore(Request $request) {
        $request->user()->authorizeRoles(['admin']);
        $data = $request->all();

        if(!empty($data['password']) AND ($data['password'] == $data['password_confirmation'])) {
            $password = bcrypt($request->get('password'));
            DB::table('users')->where('id',$data['id'])->update(['password' => $password]);
        }

        DB::table('users')->where('id',$data['id'])->update(
            [
                'name' => $data['name'],
                'username' => $data['username'],
                'schedule' => json_encode($data['schedule']),
                'address' => $data['address'],
                'notes' => $data['notes']
            ]
        );

        unset($data['_token']);
        unset($data['name']);

        $usrId = $data['id'];
        unset($data['id']);
        unset($data['username']);
        unset($data['password']);
        unset($data['password_confirmation']);

        return redirect()->back()->with("success",__("Successfully saved!"));
    }
}
