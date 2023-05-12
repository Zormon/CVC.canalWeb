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


class AdminListaController extends Controller {
    private $photos_path;

    public function __construct() {
        $this->middleware('auth');
        $this->photos_path = public_path('/storage');
    }

    public function listVideos($userid, $id, Request $request) {
        $request->user()->authorizeRoles(['admin']);

        $videos = DB::table('uploads')->where('playlistId', $id)->orderBy('position','ASC')->get();
        $pendientes = DB::table('encode_queues')->where('playlistId', $id)->orderBy('id','ASC')->get();

        $lista = DB::table('playlists')->where('id', $id)->first();

        return view('lista.admin.videos', ['id' => $userid, 'userid' => $userid, 'videos' => $videos, 'pendientes' => $pendientes, 'lista' => $lista]);
    }

    public function editVideos($userid,$id, Request $request) {
        $request->user()->authorizeRoles(['admin']);
        $video = DB::table('uploads')->where('id', $id)->first();

        return view('lista.admin.editvideos', ['video' => $video]);
    }

    public function updateVideo($userid, Request $request) {
        $request->user()->authorizeRoles(['admin']);

        $data = $request->json()->all();
        
        foreach ($data as $value) {
            if ( isset($value['broadcast_from']) && $value['broadcast_from'] == '' )  { $value['broadcast_from'] = null; }
            if ( isset($value['broadcast_to']) && $value['broadcast_to'] == '' )  { $value['broadcast_to'] = null; }
            if ( isset($value['time_from']) && $value['time_from'] == '' )  { $value['time_from'] = null; }
            if ( isset($value['time_to']) && $value['time_to'] == '' )      { $value['time_to'] = null; }

            DB::table('uploads')->where('id', $value['id'])->update($value);
        }

        header('Content-Type: application/json');
        echo '{"message": "success"}';
    }

    public function updateLista($userid, Request $request) {
        $request->user()->authorizeRoles(['admin']);

        $data = $request->json()->all();

        foreach ($data as $value) {
            DB::table('playlists')->where('id', $value['id'])->update($value);
        }

        header('Content-Type: application/json');
        echo '{"message": "success"}';
    }

    public function cancelarCodificacion($userid, Request $request) {
        $request->user()->authorizeRoles(['admin']);

        $data = $request->json()->all();

        foreach ($data as $value) {
            if(Auth::user()->hasRole('admin')){
                $video = DB::table('encode_queues')->where('id', $value['id'])->where('encoding', 0)->first();
            } else {
                $video = DB::table('encode_queues')->where('id', $value['id'])->where('encoding', 0)->first();
            }

            if ($video->id == $value['id']) {
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

    public function deleteVideo($userid, Request $request) {
        $request->user()->authorizeRoles(['admin']);

        $data = $request->json()->all();

        foreach ($data as $value) {
            $video = DB::table('uploads')->where('id', $value['id'])->first();

            if ($video->id == $value['id']) {
                $filename = $request->id;
                $uploaded_image = Upload::where('id', $value['id'])->first();

                if (empty($uploaded_image)) {
                    return Response::json(['message' => 'Sorry file does not exist'], 400);
                }

                $file_path = $this->photos_path . '/' . $uploaded_image->filename;
                $resized_file = $this->photos_path . '/thumbs/' . $uploaded_image->resized_name;

                if (file_exists($file_path)) { unlink($file_path); }
                if (file_exists($resized_file)) { unlink($resized_file); }
                if (!empty($uploaded_image)) { $uploaded_image->delete(); }

                return Response::json(['message' => 'File successfully delete'], 200);
            }
        }
    }

    public function deleteLista($userid, Request $request) {
        $request->user()->authorizeRoles(['admin']);

        $data = $request->json()->all();

        $value=$data[0];
        $videos = DB::table('uploads')->where('playlistId', $value['id'])->get();

        foreach($videos as $video) {
            $uploaded_image = Upload::where('id', $video->id)->first();

            if (empty($uploaded_image)) {
                return Response::json(['message' => 'Sorry file does not exist'], 400);
            }

            $file_path = $this->photos_path . '/' . $uploaded_image->filename;
            $resized_file = $this->photos_path . '/thumbs/' . $uploaded_image->resized_name;

            if (file_exists($file_path))        { unlink($file_path); }
            if (file_exists($resized_file))     { unlink($resized_file); }
            if (!empty($uploaded_image))        { $uploaded_image->delete(); }
        }

        DB::table('playlists')->where('id', $value['id'])->delete();

        header('Content-Type: application/json');
        echo '{"message": "success"}';
    }

    public function newLista($userid, Request $request)     {
        $request->user()->authorizeRoles(['admin']);
        $playlists = DB::table('playlists')->get();
        $user = DB::table('users')->where('id', [$userid])->first();
        $max_listas = $user->max_lists - count($playlists);

        if($max_listas > 0){
            DB::table('playlists')->insert(['userId' => $userid, 'name' => __('New Untitled playlist')]);
            header('Content-Type: application/json');
            echo '{"message": "success"}';
        } else {
            header('Content-Type: application/json');
            echo '{"message": "error"}';
        }
    }

    public function listVideosJson() {
        $request->user()->authorizeRoles(['admin']);

        $pendientes = DB::table('encode_queues')->orderBy('id','ASC')->get();
        header('Content-Type: application/json');

        echo json_encode($pendientes);
        die();
    }
}
