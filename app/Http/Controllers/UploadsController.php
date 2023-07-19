<?php

namespace App\Http\Controllers;

use App\Upload;
use App\Playlist;
use App\EncodeQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class UploadsController extends Controller {
    private $filesPath;

    public function __construct() {
        $this->filesPath = storage_path('app/public/media/');
        $this->queuePath = storage_path('app/queue/');
    }

    public function store(Request $request) {
        $files = $request->file('file');
        if (!is_array($files)) { $files = [$files];}

        foreach($files as $file) {
            $mime = explode('/', $file->getClientMimeType())[0];
            $name = uniqid();

            switch( $mime ) {
                case 'video':
                    $newFilename = $name . '.' . $file->getClientOriginalExtension();
                    $file->move($this->queuePath, $newFilename);

                    $upload = new EncodeQueue();
                    $upload->filename = $newFilename;
                    $upload->title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $upload->userId = auth()->user()->id;
                    $upload->playlistId = $request->playlistId;
                    $upload->original_name = basename($file->getClientOriginalName());
                    $upload->save();

                    echo $this->queuePath . $newFilename;
                    die;
                break;

                case 'image':
                    $targetSize = Playlist::where('id', $request->playlistId)->first();
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
                    $upload->title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $upload->userId = auth()->user()->id;
                    $upload->playlistId = $request->playlistId;
                    $upload->position = 0;

                    $upload->active = 1;
                    $upload->duration = 10;
                    $upload->original_name = basename($file->getClientOriginalName());
                    $upload->save();
                break;

            }
        }

        return Response::json(['message' => 'Image saved Successfully'], 200);
    }

    public function RunEncodingQueue(Request $request) {
        $binPath = base_path('bin/');
        $queuePath = storage_path('app/queue/');
        $mediaPath = storage_path('app/public/media/');
        $thumbsPath = storage_path('app/public/thumbs/');
        
        $queue = EncodeQueue::where('encoding', 0)->get();

        $output["Encoded"] = [];
        foreach ($queue as $q) {
            $basename = pathinfo($q->filename, PATHINFO_FILENAME);
            $queuedFile = $queuePath . $q->filename;

            if ( !file_exists( $mediaPath.'.mp4' ) ) {
                $pl = Playlist::where('id', $q->playlistId)->first();
                $duration = shell_exec($binPath . 'ffprobe -show_streams ' . $queuedFile . ' 2> /dev/null | grep duration= | cut -d"=" -f2');
                $duration = ceil((float)$duration);

                // Begin encoding
                EncodeQueue::where('id', $q->id)->update(['encoding' => 1]);

                $encodeOutput = shell_exec($binPath . 'ffmpeg ' .
                    ' -i ' . $queuedFile .
                    ' -vf scale=' . $pl->screenW . ':' . $pl->screenH .
                    ' -vcodec libx264 -pix_fmt yuv420p -profile:v baseline -level 3 ' .
                    $mediaPath . $basename . '.mp4'
                );

                $thumbOutput = shell_exec($binPath . 'ffmpeg ' .
                    ' -i ' . $queuedFile .
                    ' -ss 00:00:2.435 -vframes 1 -filter:v scale="250:-1" ' .
                    $thumbsPath . $basename . '.webp'
                );
                
                unlink( $queuedFile );

                EncodeQueue::where('id', $q->id)->delete();
                // End encoding


                Upload::insert([
                    'filename' => $basename.'.mp4',
                    'title' => $q->title,
                    'original_name' => $q->original_name,
                    'userId' => $q->userId,
                    'playlistId' => $q->playlistId,
                    'dateTo' => date('Y-m-d H:i:s', strtotime('+1 year')),
                    'active' => 1,
                    'duration' => $duration
                ]);
            }
            array_push($output["Encoded"], ["encodeOutput" => $encodeOutput, "thumbOutput" => $thumbOutput]);
        }

        return response()->json($output);
    }
}
