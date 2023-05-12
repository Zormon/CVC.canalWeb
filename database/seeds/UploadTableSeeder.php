<?php

use Illuminate\Database\Seeder;
use App\Upload;

class UploadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Upload::create( [
            'id'=>1,
            'filename'=>'14c0de81a5a1def4cced2ebdb31fc142e4dba8b5_enc.mp4',
            'resized_name'=>'14c0de81a5a1def4cced2ebdb31fc142e4dba8b5_thumb.png',
            'original_name'=>'NewHorarioJulia1080.mp4',
            'userId'=>2,
            'playlistId'=>8,
            'notes'=>'Horario',
            'position'=>10,
            'broadcast_from'=>'2019-05-20 12:29:29',
            'broadcast_to'=>'2025-05-29 00:00:00',
            'active'=>1,
            'duration'=>20,
            'mime'=>'video/mp4'
            ] );



            Upload::create( [
            'id'=>2,
            'filename'=>'16fcf9f9a42060cca8300ba6e6a6efe0098d10f2_enc.mp4',
            'resized_name'=>'16fcf9f9a42060cca8300ba6e6a6efe0098d10f2_thumb.png',
            'original_name'=>'(Intro1080p_V2.mp4',
            'userId'=>2,
            'playlistId'=>8,
            'notes'=>'Intro',
            'position'=>3,
            'broadcast_from'=>'2019-05-20 12:29:29',
            'broadcast_to'=>'2025-05-28 00:00:00',
            'active'=>1,
            'duration'=>10,
            'mime'=>'video/mp4'
            ] );



            Upload::create( [
            'id'=>3,
            'filename'=>'c2f97881a18390dee61f135d94d69470b05bd078_enc.mp4',
            'resized_name'=>'c2f97881a18390dee61f135d94d69470b05bd078_thumb.png',
            'original_name'=>'AmpollasGerminal1080_V2.mp4',
            'userId'=>2,
            'playlistId'=>8,
            'notes'=>'Germinal',
            'position'=>8,
            'broadcast_from'=>'2019-05-27 11:03:33',
            'broadcast_to'=>'2025-05-27 10:37:58',
            'active'=>1,
            'duration'=>15,
            'mime'=>'video/mp4'
            ] );



            Upload::create( [
            'id'=>4,
            'filename'=>'74c61fe580ef667ea6009683403ee00ce875237e_enc.mp4',
            'resized_name'=>'74c61fe580ef667ea6009683403ee00ce875237e_thumb.png',
            'original_name'=>'Bioderma1080.mp4',
            'userId'=>2,
            'playlistId'=>8,
            'notes'=>'Bioderma1080',
            'position'=>4,
            'broadcast_from'=>'2019-05-27 11:03:33',
            'broadcast_to'=>'2020-05-27 10:38:14',
            'active'=>1,
            'duration'=>15,
            'mime'=>'video/mp4'
            ] );



            Upload::create( [
            'id'=>5,
            'filename'=>'bf70fc45d015169234b1732c0cb1b386641b3d5a_enc.mp4',
            'resized_name'=>'bf70fc45d015169234b1732c0cb1b386641b3d5a_thumb.png',
            'original_name'=>'Sensilis1080p.mp4',
            'userId'=>2,
            'playlistId'=>8,
            'notes'=>'Sensilis1080p',
            'position'=>11,
            'broadcast_from'=>'2019-05-27 13:31:29',
            'broadcast_to'=>'2025-05-31 00:00:00',
            'active'=>1,
            'duration'=>20,
            'mime'=>'video/mp4'
            ] );


            Upload::create( [
                'id'=>6,
                'filename'=>'fc71a44a305783255cabdede4e47066fe03e3dd2_enc.mp4',
                'resized_name'=>'fc71a44a305783255cabdede4e47066fe03e3dd2_thumb.png',
                'original_name'=>'Chupetes1080p.mp4',
                'userId'=>2,
                'playlistId'=>8,
                'notes'=>'Chupetes1080p',
                'position'=>5,
                'broadcast_from'=>'2019-05-27 13:31:29',
                'broadcast_to'=>'2025-05-31 00:00:00',
                'active'=>1,
                'duration'=>20,
                'mime'=>'video/mp4'
                ] );


                Upload::create( [
                    'id'=>7,
                    'filename'=>'3331c8a3a20319305f54f45b004c7ffdf35d888d_enc.mp4',
                    'resized_name'=>'3331c8a3a20319305f54f45b004c7ffdf35d888d_thumb.png',
                    'original_name'=>'Lireac1080.mp4',
                    'userId'=>2,
                    'playlistId'=>8,
                    'notes'=>'Lireac1080',
                    'position'=>0,
                    'broadcast_from'=>'2019-05-27 13:31:29',
                    'broadcast_to'=>'2025-05-31 00:00:00',
                    'active'=>1,
                    'duration'=>167,
                    'mime'=>'video/mp4'
                    ] );


                    Upload::create( [
                        'id'=>8,
                        'filename'=>'0e1bd348ebb64126ca0ae7ef5af25ee7bb7ba626_enc.mp4',
                        'resized_name'=>'0e1bd348ebb64126ca0ae7ef5af25ee7bb7ba626_thumb.png',
                        'original_name'=>'VichyMineral.mp4',
                        'userId'=>2,
                        'playlistId'=>8,
                        'notes'=>'VichyMineral',
                        'position'=>0,
                        'broadcast_from'=>'2019-05-27 13:31:29',
                        'broadcast_to'=>'2025-05-31 00:00:00',
                        'active'=>1,
                        'duration'=>21,
                        'mime'=>'video/mp4'
                        ] );


                        Upload::create( [
                            'id'=>9,
                            'filename'=>'e64b448515cc48be50d2e3007e8a354cfea4d143_enc.mp4',
                            'resized_name'=>'e64b448515cc48be50d2e3007e8a354cfea4d143_thumb.png',
                            'original_name'=>'BiodermaPromocion1080p.mp4',
                            'userId'=>1,
                            'playlistId'=>1,
                            'notes'=>'BiodermaPromocion1080p',
                            'position'=>0,
                            'broadcast_from'=>'2019-05-27 13:31:29',
                            'broadcast_to'=>'2025-05-31 00:00:00',
                            'active'=>1,
                            'duration'=>20,
                            'mime'=>'video/mp4'
                            ] );






    }
}
