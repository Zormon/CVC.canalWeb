<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // La creación de datos de roles debe ejecutarse primero
        $this->call(RoleTableSeeder::class);
        // Los usuarios necesitarán los roles previamente generados
        $this->call(UserTableSeeder::class);
        // La creación de datos de playlist
        $this->call(PlaylistTableSeeder::class);
        // La creación de datos de playlist
        $this->call(UploadTableSeeder::class);
        // La creación de datos de usuario
        $this->call(UserinfoTableSeeder::class);


    }
}
