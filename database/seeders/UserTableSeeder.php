<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
class UserTableSeeder extends Seeder
{
    public function run()
    {
        $role_user = Role::where('name', 'user')->first();
        $role_admin = Role::where('name', 'admin')->first();


        $user = new User();
        $user->name = 'Admin';
        $user->email = 'desarrollo@comunicacionvisualcanarias.com';
        $user->password = bcrypt('1');
        $user->language = "es";
        $user->save();

        $user->roles()->attach($role_admin);


        $user = new User();
        $user->name = 'Julia Guerra Pulido';
        $user->email = 'usuario@comunicacionvisualcanarias.com';
        $user->password = bcrypt('123456789');
        $user->language = "es";
        $user->save();

        $user->roles()->attach($role_user);



        $user = new User();
        $user->name = 'Farmacia Ciudad Alta';
        $user->email = 'administracion@farmaciaciudadalta.com';
        $user->password = bcrypt('928253711');
        $user->language = "es";
        $user->save();

        $user->roles()->attach($role_user);



     }
}
