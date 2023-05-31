<?php

namespace App\Http\Controllers;

use App\Upload;
use App\EncodeQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;


class UserListaController extends Controller {
    private $photos_path;

    public function __construct() {
        $this->middleware('auth');
        $this->photos_path = public_path('/storage');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['user','admin']);

        $counter = null;
        $playlists = DB::table('playlists')->where('userId', [auth()->user()->id])->orderBy('id','DESC')->get();

        foreach ($playlists as $key => $value) {
            $active = DB::table('uploads')->where('playlistId',$value->id)->where('active',1)->count();
            $noactive = DB::table('uploads')->where('playlistId',$value->id)->where('active',0)->count();

            $counter[$value->id]['active'] = $active;
            $counter[$value->id]['noactive'] = $noactive;
        }

        $user = DB::table('users')->where('id', [auth()->user()->id])->first();
        $encoding_queue = DB::table('encode_queues')->where('userId', [auth()->user()->id])->count();

        return view('lista.index', ["encoding_queue" => $encoding_queue, 'playlists' => $playlists, 'counter' => $counter]);
    }

    public function listVideos($id, Request $request) {
        $request->user()->authorizeRoles(['user','admin']);

        $videos = DB::table('uploads')->where('playlistId', $id)->orderBy('position','ASC')->get();
        $pendientes = DB::table('encode_queues')->where('playlistId', $id)->orderBy('id','ASC')->get();

        $lista = DB::table('playlists')->where('id', $id)->first();

        $encoding_queue = DB::table('encode_queues')->where('userId', [auth()->user()->id])->count();

        return view('lista.videos', ["encoding_queue" => $encoding_queue, 'videos' => $videos, 'pendientes' => $pendientes, 'lista' => $lista]);
    }


    public function editVideos($id, Request $request) {
        $request->user()->authorizeRoles(['user','admin']);

        $video = DB::table('uploads')->where('id', $id)->where('userId', [auth()->user()->id])->first();

        $encoding_queue = DB::table('encode_queues')->where('userId', [auth()->user()->id])->count();
        return view('lista.editvideos', ["encoding_queue" => $encoding_queue]);
    }


    public function updateVideo(Request $request) {
        $request->user()->authorizeRoles(['user','admin']);
        $data = $request->json()->all();

        foreach ($data as $value) {
            DB::table('uploads')->where('id', $value['id'])->update($value);
        }

        header('Content-Type: application/json');
        echo '{"message": "success"}';
    }


    public function updateLista(Request $request) {
        $request->user()->authorizeRoles(['user','admin']);

        $data = $request->json()->all();

        foreach ($data as $value) {
            DB::table('playlists')->where('id', $value['id'])->update($value);
        }

        header('Content-Type: application/json');
        echo '{"message": "success"}';
    }

    public function cancelarCodificacion(Request $request) {
        $request->user()->authorizeRoles(['user','admin']);

        $data = $request->json()->all();

        foreach ($data as $value) {
            if (Auth::user()->hasRole('admin')){
                $video = DB::table('encode_queues')->where('id', $value['id'])->where('encoding', 0)->first();

            } else {
                $video = DB::table('encode_queues')->where('id', $value['id'])->where('encoding', 0)->first();
            }

            if($video->id == $value['id']){
                $filename = $request->id;
                if(Auth::user()->hasRole('admin')){
                    $uploaded_image = EncodeQueue::where('id', $value['id'])->where('encoding', 0)->first();
                } else {
                    $uploaded_image = EncodeQueue::where('id', $value['id'])->where('encoding', 0)->first();
                }

                if (empty($uploaded_image)) {
                    return Response::json(['message' => 'Sorry file does not exist'], 400);
                }

                $file_path = $this->photos_path . '/' . $uploaded_image->filename;

                if (file_exists($file_path)) { unlink($file_path); }

                if (!empty($uploaded_image)) { $uploaded_image->delete(); }

                return Response::json(['message' => 'File successfully delete'], 200);
            }
        }
    }

    public function deleteVideo(Request $request) {
        $request->user()->authorizeRoles(['user']);

        $data = $request->json()->all();

        foreach ($data as $value) {
            $video = DB::table('uploads')->where('id', $value['id'])->first();

            if($video->id == $value['id']) {
                $filename = $request->id;
                $uploaded_image = Upload::where('id', $value['id'])->first();

                if (empty($uploaded_image)) {
                    return Response::json(['message' => 'Sorry file does not exist'], 400);
                }

                $file_path = $this->photos_path . '/' . $uploaded_image->filename;
                $resized_file = $this->photos_path . '/thumbs/' . $uploaded_image->resized_name;

                if (file_exists($file_path))        { unlink($file_path); }
                if (file_exists($resized_file))     { unlink($resized_file); }
                if (!empty($uploaded_image))        { $uploaded_image->delete(); }

                return Response::json(['message' => 'File successfully delete'], 200);
            }
        }
    }

    public function listVideosJson() {
        $request->user()->authorizeRoles(['user']);

        $pendientes = DB::table('encode_queues')->orderBy('id','ASC')->get();
        header('Content-Type: application/json');

        echo json_encode($pendientes);
        die();
    }
}
