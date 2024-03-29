<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\Playlist;
use App\Models\EncodeQueue;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class PlaylistController extends Controller {

    public function userPlaylists(Request $request, string $uId=null) {
        $user = Auth::user();
        $user->authorizeLogin();

        if (!$user->isAdmin() && $uId != null) {
            abort(401);
        }

        $uId ??= $user->id;
        $userData = User::where('id', $uId)->first();
        if (empty($userData))   { abort(404); }
        $playlists = Playlist::where('userId', $uId)->orderBy('id','DESC')->get();

        foreach ($playlists as $k => $v) {
            $active = Upload::where('playlistId',$v->id)->where('active',1)->count();
            $noActive = Upload::where('playlistId',$v->id)->where('active',0)->count();

            $playlists[$k]->nActive = $active;
            $playlists[$k]->nNoActive = $noActive;
        }

        $user = User::where('id', [auth()->user()->id])->first();

        return view('playlists', ['playlists' => $playlists, 'uId' => $uId]);
    }

    public function showList(Request $request, string $plId) {
        $user = Auth::user();
        $user->authorizeLogin();

        $playlist = Playlist::where('id', $plId)->first();
        if (empty($playlist))   { abort(404); }

        $media = Upload::where('playlistId', $plId)->orderBy('position','ASC')->get();
        $queue = EncodeQueue::where('playlistId', $plId)->orderBy('id','ASC')->get();

        $today = date('Y-m-d');
        foreach ($media as $v) {
            $v->timedOut = !!$v->dateTo && $v->dateTo < $today;
        }

        return view('playlist', ["queue" => $queue, 'media' => $media, 'playlist' => $playlist, 'uId' => $user->id]);
    }


    // ======== < API ========

    public function update(Request $request, string $plId) {
        $user = Auth::user();
        $user->authorizeAdmin();

        $data = $request->json()->all();
        Playlist::where('id', $plId)->update($data);

        return response(null, Response::HTTP_OK);

    }

    public function updatePositions(Request $request, string $plId) {
        $user = Auth::user();
        $user->authorizeLogin();
        $data = $request->json()->all();

        foreach($data['positions'] as $k => $v) {
            Upload::where('id', $k)->update(['position' => $v]);
        }

        return response(null, Response::HTTP_OK);
    }

    public function new (Request $request) {
        $user = Auth::user();
        $user->authorizeAdmin();

        $data = $request->json()->all();
        Playlist::create($data);

        return response(null, Response::HTTP_OK);
    }

    public function delete (Request $request, string $id) {
        $user = Auth::user();
        $user->authorizeAdmin();

        $pl = Playlist::where('id', $id)->first();
        if (empty($pl))         { return response(null, Response::HTTP_NOT_FOUND); }

        $queue = EncodeQueue::where('playlistId', $id)->get();
        if ($queue->count() > 0)      { return response(null, Response::HTTP_CONFLICT); }

        // Borrar contenidos de la lista
        $media = Upload::where('playlistId', $id)->get();

        foreach ($media as $v) {
            $file = PATH_MEDIA . $v->filename;
            if (file_exists($file))  { unlink($file); }

            $thumb = PATH_THUMBS . pathinfo($v->filename, PATHINFO_FILENAME) . '.webp';
            if (file_exists($thumb)) { unlink($thumb); }
        }
        Upload::where('playlistId', $id)->delete();

        // Borrar cola de codificación

        foreach ($queue as $v) {
            $file = PATH_QUEUE . $v->filename;
            if (file_exists($file))  { unlink($file); }
        }
        EncodeQueue::where('playlistId', $id)->delete();

        // Borrar lista
        Playlist::where('id', $id)->delete();
        
        return response()->json(['playlist' => $pl->name, 'media' => $media], Response::HTTP_OK);
    }
    
    // ========  API > ========

}
