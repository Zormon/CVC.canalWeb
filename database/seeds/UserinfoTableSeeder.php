<?php

use Illuminate\Database\Seeder;
use App\Userinfo;


class UserinfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new Userinfo();
        $user->userId = 2;
        $user->address = 'Calle León y Castillo, 17';

        $user->schedule = json_encode(['M'=>['AM'=>'8:00-13:00','PM'=>'16:00-20:00'], 'T'=>['AM'=>'8:00-13:00','PM'=>'16:00-20:00'], 'W'=>['AM'=>'8:00-13:00','PM'=>'16:00-20:00'], 'TH'=>['AM'=>'8:00-13:00','PM'=>'16:00-20:00'], 'F'=>['AM'=>'8:00-13:00','PM'=>'16:00-20:00'], 'S'=>['AM'=>'8:30-13:30'] ]);
        $user->save();






        $user = new Userinfo();
        $user->userId = 3;
        $user->address = 'C/ Zaragoza , 29';

        $user->schedule = json_encode(['M'=>['AM'=>'8:30','PM'=>'22:00'], 'T'=>['AM'=>'8:30','PM'=>'22:00'], 'W'=>['AM'=>'8:30','PM'=>'22:00'], 'TH'=>['AM'=>'8:30','PM'=>'22:00'], 'F'=>['AM'=>'8:30','PM'=>'22:00'], 'S'=>['AM'=>'9:30','PM'=>'22:00'] ]);
        $user->primary_text_color = '#FFFFFF';
        $user->secondary_text_color = '#222222';
        $user->save();



    }
}
