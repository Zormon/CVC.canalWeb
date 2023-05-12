<?php

namespace App\Http\Controllers;

use App\Upload;
use App\EncodeQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class AdminUploadImagesController extends Controller {
    private $photos_path;
    private $mimeVideo;

    public function __construct() {
        $this->photos_path = public_path('/storage');
    }


    /**
     * Saving images uploaded through XHR Request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($userid, Request $request)
    {

        $photos = $request->file('file');


        if (!is_array($photos)) {
            $photos = [$photos];
        }

        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }

        for ($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];

            $pos = strpos($photo->getClientMimeType(), 'image/');

            if ($pos === false) {
                //GESTIONA LOS VIDEOS
                $pos = strpos($photo->getClientMimeType(), 'video/');

                if ($pos !== false) {

                $name = sha1(date('YmdHis') . str_random(30));
                $save_name = $name . '.' . $photo->getClientOriginalExtension();
                $resize_name = $name . str_random(2) . '.' . $photo->getClientOriginalExtension();
/*
                Image::make($photo)
                    ->resize(250, null, function ($constraints) {
                        $constraints->aspectRatio();
                    })
                    ->save($this->photos_path . '/thumbs/' . $resize_name);
*/
                $photo->move($this->photos_path, $save_name);

                $upload = new EncodeQueue();
                $upload->filename = $save_name;

                $real_path = realpath($this->photos_path . '/' . $save_name);

                $upload->userId = $userid;
                $upload->playlistId = $request->playlistId;
                $upload->notes = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $upload->position = 0;
                $upload->active = 0;

                $upload->resized_name = $resize_name;
                $upload->original_name = basename($photo->getClientOriginalName());
                $upload->save();

//////////////////////////////////////////////////////////////////////////////
                }

            }else{
                //GESTIONA LAS IMAGENES
                $name = sha1(date('YmdHis') . str_random(30));
                //$save_name = $name . '.' . $photo->getClientOriginalExtension();
                //$resize_name = $name . str_random(2) . '.' . $photo->getClientOriginalExtension();
                $save_name = $name . '.jpg';
                $resize_name = $name . str_random(2) . '.jpg';

                Image::make($photo)
                    ->resize(250, null, function ($constraints) {
                        $constraints->aspectRatio();
                    })->save($this->photos_path . '/thumbs/' . $resize_name, 60);



                Image::make($photo)->resize(1280, null, function ($constraints){
                    $constraints->aspectRatio();
                })->save($this->photos_path . '/' . $save_name, 90);

                $upload = new Upload();
                $upload->filename = $save_name;

                $real_path = realpath($this->photos_path . '/' . $save_name);

                $upload->userId = $userid;
                $upload->playlistId = $request->playlistId;
                $upload->notes = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $upload->position = 0;
                $upload->active = 1; //ZOR: Activo por defecto
                $upload->duration = 10;
                $upload->mime = trim(shell_exec("file --mime-type -b ".$real_path));
                $upload->broadcast_to = date('Y-m-d H:i:s',time() + (365 * 24 * 60 * 60));


                $upload->resized_name = $resize_name;
                $upload->original_name = basename($photo->getClientOriginalName());
                $upload->save();
            }
        }

        return Response::json([
            'message' => 'Image saved Successfully'
        ], 200);
    }
}
