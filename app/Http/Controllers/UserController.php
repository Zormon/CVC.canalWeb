<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller  {
    
    public function showList() {
        $user = Auth::user();
        $user->authorizeRoles(['admin']);

        $users = User::get();
        return view('users', ["users" => $users]);
    }

    public function edit(string $uId) {
        $user = Auth::user();
        $user->authorizeRoles(['user','admin']);

        if (!$user->hasRole('admin') && $uId != $user->id) {
            return response(null, Response::HTTP_FORBIDDEN);
        }

        $userData = User::where('id', $uId)->first();
        $userData->power = json_decode($userData->power);

        return view('user', ["userData" => $userData]);
    }

    public function update(Request $request, string $uId) {
        $user = Auth::user();
        $user->authorizeRoles(['user','admin']);

        $data = $request->json()->all();

        // Si no es admin, comprobar si intenta editar otro usuario o quiere cambiar parametros que no puede
        if (!$user->hasRole('admin') ) {
            if (
                $uId != $user->id
                || array_key_exists('username', $data)
                || array_key_exists('notes', $data)
            ) { 
                return response(null, Response::HTTP_FORBIDDEN); 
            }
        } else { // Si es admin, comprobar si el nombre de usuario ya existe
            $username = User::where('username', $data['username'])->first();
            if ($username && $username->id != $uId) { return response(null, Response::HTTP_CONFLICT); }
        }

        if (!empty($data['password']))                  { $data['password'] = bcrypt($data['password']); } // Hash password
        else                                            { unset($data['password']); } // Remove password from data
        foreach ($data as $k => $v) { if (empty($v))    { $data[$k]=null; } } // Remove empty values

        for ($i=0; $i<=6; $i++) {
            $power[$i]['ON'] = $data['power.'.$i.'.ON']??null;
            unset($data['power.'.$i.'.ON']);    

            $power[$i]['OFF'] = $data['power.'.$i.'.OFF']??null;
            unset($data['power.'.$i.'.OFF']);
        }
        $data['power'] = json_encode($power);

        User::where('id', $uId)->update($data);

        return response(null, Response::HTTP_OK);
    }

}