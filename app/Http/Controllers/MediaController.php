<?php

namespace App\Http\Controllers;

use App\Upload;
use App\Playlist;
use App\EncodeQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class MediaController extends Controller {

    public function delete (string $id) {
        $user = Auth::user();
        $user->authorizeRoles(['user', 'admin']);

        $media = Upload::where('id', $id)->first();
        if (empty($media))      { return response(null, Response::HTTP_NOT_FOUND); }

        if (!$user->hasRole('admin')) { // Si no es admin, comprobar que el usuario es el dueño de la lista
            $pl = Playlist::where('id', $media->playlistId)->where('userId',$user->id)->first();
            if (empty($pl))         { return response(null, Response::HTTP_FORBIDDEN); }
        }


        define('FILE', PATH_MEDIA . $media->filename);
        if (file_exists(FILE))  { unlink(FILE); }

        define('THUMB', PATH_THUMBS . pathinfo($media->filename, PATHINFO_FILENAME) . '.webp');
        if (file_exists(THUMB)) { unlink(THUMB); }

        Upload::where('id', $id)->delete();
        
        return response()->json(['filename' => $media->filename], Response::HTTP_OK);
    }

    public function update(Request $request, string $id) {
        $request->user()->authorizeRoles(['user','admin']);
        $data = $request->json()->all();

        Upload::where('id', $id)->update($data);

        return response(null, Response::HTTP_OK);
    }
}
