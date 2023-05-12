<?php

namespace App\Http\Controllers;

use App\Upload;
use App\EncodeQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class UploadImagesController extends Controller {
    private $filesPath;

    public function __construct() {
        $this->filesPath = public_path('/storage');
    }

    public function store(Request $request) {
        $files = $request->file('file');

        if (!is_array($files))             { $files = [$files];}
        if (!is_dir($this->filesPath))    { mkdir($this->filesPath, 0777); }

        foreach($files as $file) {
            $mime = explode('/', $file->getClientMimeType())[0];

            switch( $mime ) {
                case 'video': case 'image':
                    $name = sha1(date('YmdHis') . str_random(30));

                case 'video':
                    $save_name = $name . '.' . $file->getClientOriginalExtension();
                    $resize_name = $name . str_random(2) . '.' . $file->getClientOriginalExtension();
                    $file->move($this->filesPath, $save_name);

                    $upload = new EncodeQueue();
                    $upload->filename = $save_name;
                    $upload->userId = auth()->user()->id;
                    $upload->playlistId = $request->playlistId;
                    $upload->notes = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $upload->position = 0;

                    $upload->resized_name = $resize_name;
                    $upload->original_name = basename($file->getClientOriginalName());
                    $upload->save();
                break;

                case 'image':
                    $save_name = $name . '.jpg';
                    $resize_name = $name . str_random(2) . '.jpg';

                    Image::make($file)
                        ->resize(250, null, function ($constraints) {
                            $constraints->aspectRatio();
                        })->save($this->filesPath . '/thumbs/' . $resize_name, 60);

                    Image::make($file)->resize(1280, null, function ($constraints){
                        $constraints->aspectRatio();
                    })->save($this->filesPath . '/' . $save_name, 90);

                    $upload = new Upload();
                    $upload->filename = $save_name;

                    $real_path = realpath($this->filesPath . '/' . $save_name);

                    $upload->active = 1;
                    $upload->duration = 10;
                    $upload->resized_name = $resize_name;
                    $upload->original_name = basename($file->getClientOriginalName());
                    $upload->save();
                break;

            }
        }

        return Response::json(['message' => 'Image saved Successfully'], 200);
    }
}
