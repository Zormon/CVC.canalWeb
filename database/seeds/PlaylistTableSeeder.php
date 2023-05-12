<?php

use Illuminate\Database\Seeder;
use App\Playlist;


class PlaylistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


            Playlist::create( [
            'id'=>1,
            'userId'=>1,
            'name'=>'Canal General'
            ] );

            Playlist::create( [
                'id'=>2,
                'userId'=>1,
                'name'=>'Header general'
                ] );

            Playlist::create( [
                'id'=>3,
                'userId'=>1,
                'name'=>'Footer general'
                ] );


            Playlist::create( [
                'id'=>4,
                'userId'=>1,
                'name'=>'Otros general'
                ] );



            Playlist::create( [
            'id'=>5,
            'userId'=>2,
            'name'=>'Totem'
            ] );

            Playlist::create( [
                'id'=>6,
                'userId'=>2,
                'name'=>'Canal Vertical'
                ] );

            Playlist::create( [
                    'id'=>7,
                    'userId'=>2,
                    'name'=>'Canal Corporativo'
                    ] );


            Playlist::create( [
            'id'=>8,
            'userId'=>2,
            'name'=>'Turnomatic'
            ] );



            Playlist::create( [
                'id'=>9,
                'userId'=>3,
                'name'=>'Corporativo'
                ] );

            Playlist::create( [
                'id'=>10,
                'userId'=>3,
                'name'=>'Turnomatic'
                ] );

            Playlist::create( [
                'id'=>11,
                'userId'=>3,
                'name'=>'Totem'
                ] );


    }
}
