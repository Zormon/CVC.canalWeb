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
use Intervention\Image\Facades\Image;

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

    public function upload(Request $request) {
        $user = Auth::user();
        $user->authorizeRoles(['user','admin']);

        if (!$user->hasRole('admin')) {
            $pl = Playlist::where('id', $request->plId)->where('userId',$user->id)->first();
            if (empty($pl))         { return response(null, Response::HTTP_FORBIDDEN); }
        }

        $files = $request->file('media');
        if (!is_array($files)) { $files = [$files]; }

        $num = 0;
        foreach($files as $file) {
            $num++;
            $mime = explode('/', $file->getClientMimeType())[0];
            $name = uniqid();

            $title = $request->title!=''? $request->title.'_'.$num : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            switch( $mime ) {
                case 'video':
                    $newFilename = $name . '.' . $file->getClientOriginalExtension();

                    if ($user->hasRole('admin') && !!$request->noCod) { // Si es admin, no codifica
                        $duration = 10; // TODO: Extraer duracion

                        $file->move(PATH_MEDIA, $newFilename);
                        $upload = new Upload();
                        $upload->active = 1;
                        $upload->duration = $duration;
                        $upload->filename = $newFilename;
                        $upload->title = $title;
                        $upload->original_name = basename($file->getClientOriginalName());

                        shell_exec(PATH_BIN . 'ffmpeg ' .
                            ' -i ' . PATH_MEDIA . $upload->filename .
                            ' -ss 00:00:2.435 -vframes 1 -filter:v scale="250:-1" ' .
                            PATH_THUMBS . pathinfo($upload->filename, PATHINFO_FILENAME) . '.webp'
                        );
                    } else {
                        $file->move(PATH_QUEUE, $newFilename);
                        $upload = new EncodeQueue();

                        $upload->filename = $newFilename;
                        $data['title'] = $title;
                        $data['original_name'] = basename($file->getClientOriginalName());
                        $data['transition'] = $request->transition??'none';
                        $data['dateFrom'] = $request->dateFrom??null;
                        $data['dateTo'] = $request->dateTo??null;
                        $data['timeFrom'] = $request->timeFrom??null;
                        $data['timeTo'] = $request->timeTo??null;
                        $data['volume'] = $request->volume??0;

                        $upload->data = json_encode($data);
                    }

                    
                    $upload->userId = $user->id;
                    $upload->playlistId = $request->plId;
                    
                    $upload->save();
                break;


                case 'image':
                    $targetSize = Playlist::where('id', $request->plId)->first();
                    $targetW = $targetSize->screenW;
                    $targetH = $targetSize->screenH;

                    $saveName = $name . '.webp';
                    $resizeName = $name . '.webp';

                    Image::make($file)
                        ->resize(250, null, function ($constraints) {
                            $constraints->aspectRatio();
                        })->save(PATH_THUMBS . $resizeName, 60);

                    $image = Image::make($file);
                    $image->resize($targetW, $targetH);
                    $image->save(PATH_MEDIA . $saveName, 80);

                    $upload = new Upload();
                    $upload->filename = $saveName;
                    $upload->title = $title;
                    $upload->userId = $user->id;
                    $upload->playlistId = $request->plId;
                    $upload->transition = $request->transition??'none';
                    $upload->dateFrom = $request->dateFrom??null;
                    $upload->dateTo = $request->dateTo??null;
                    $upload->timeFrom = $request->timeFrom??null;
                    $upload->timeTo = $request->timeTo??null;

                    $upload->active = 1;
                    $upload->duration = 10;
                    $upload->original_name = basename($file->getClientOriginalName());
                    $upload->save();
                break;

            }
        }

        return response(null, Response::HTTP_OK);
    }

    public function RunEncodingQueue(Request $request) {
        // Prevent multiple instances of this script from running
        $lock_file = fopen('/tmp/encoding_queue', 'c');
        $got_lock = flock($lock_file, LOCK_EX | LOCK_NB, $wouldblock);
        if ($lock_file === false || (!$got_lock && !$wouldblock))   { throw new Exception('ERROR: Unable to create or lock lock file.'); }
        else if (!$got_lock && $wouldblock)                         { return response(null, Response::HTTP_SERVICE_UNAVAILABLE);}
        ftruncate($lock_file, 0); fwrite($lock_file, getmypid() . "\n");
        // End of lock


        $queue = EncodeQueue::where('encoding', 0)->get();

        $output["Encoded"] = [];
        foreach ($queue as $q) {
            $basename = pathinfo($q->filename, PATHINFO_FILENAME);
            $queuedFile = PATH_QUEUE . $q->filename;

            $data = json_decode($q->data);

            if ( !file_exists( PATH_MEDIA.'.mp4' ) ) {
                $pl = Playlist::where('id', $q->playlistId)->first();
                $duration = shell_exec(PATH_BIN . 'ffprobe -show_streams ' . $queuedFile . ' 2> /dev/null | grep duration= | cut -d"=" -f2');
                $duration = ceil((float)$duration);

                // Begin encoding ====================================
                EncodeQueue::where('id', $q->id)->update(['encoding' => 1]);

                exec(PATH_BIN . 'ffmpeg ' .
                    ' -i ' . $queuedFile .
                    ' -vf scale=' . $pl->screenW . ':' . $pl->screenH . ',setsar=1,setdar=w/h' .
                    ' -c:v libx264 -crf 23' .
                    ' -pix_fmt yuv420p  ' .
                    PATH_MEDIA . $basename . '.mp4'
                );

                exec(PATH_BIN . 'ffmpeg ' .
                    ' -i ' . $queuedFile .
                    ' -ss 00:00:2.435 -vframes 1 -vf "scale=250:-1,crop=\'min(200,iw)\':\'min(150,ih)\'" ' .
                    PATH_THUMBS . $basename . '.webp'
                );
                
                unlink( $queuedFile );

                EncodeQueue::where('id', $q->id)->delete();
                // End encoding ======================================

                $upload = new Upload();
                $upload->filename = $basename . '.mp4';
                $upload->userId = $q->userId;
                $upload->playlistId = $q->playlistId;

                $upload->title = $data->title;
                $upload->original_name = $data->original_name;
                $upload->transition = $data->transition;
                $upload->dateFrom = $data->dateFrom;
                $upload->dateTo = $data->dateTo;
                $upload->timeFrom = $data->timeFrom;
                $upload->timeTo = $data->timeTo;
                $upload->volume = $data->volume;
                $upload->duration = $duration;
                $upload->active = 1;
                $upload->save();

            }
            array_push($output["Encoded"], ["file" => $q->filename, "data" => $data]);
        }

        ftruncate($lock_file, 0); flock($lock_file, LOCK_UN);
        return response()->json($output);
    }
}
