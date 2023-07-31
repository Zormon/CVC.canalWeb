<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\DeployController;

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
Route::get('/', [HomeController::class, 'root']);

// Deploy
Route::get('/deploy/{id}', [DeployController::class, 'json'])->name('json');


// Home
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/issues', [HomeController::class, 'issues'])->name('issues');


// Playlist
Route::get('/playlists/{uId?}', [PlaylistController::class, 'userPlaylists'])->name('playlists.user');
Route::get('/playlist/{plId}', [PlaylistController::class, 'showList'])->name('playlist.single');
Route::post('/playlist', [PlaylistController::class, 'new'])->name('playlist');
Route::put('/playlist/{plId}', [PlaylistController::class, 'update']);
Route::patch('/playlist/{plId}', [PlaylistController::class, 'updatePositions']);
Route::delete('/playlist/{plId}', [PlaylistController::class, 'delete']); //TODO: Implementar


// Media
Route::patch('/media/{id}',[MediaController::class, 'update'])->name('media.single');
Route::post('/media',[MediaController::class, 'new'])->name('media');
Route::delete('/media/{id}',[MediaController::class, 'delete']);
// Procesar colas
Route::get('/EncodeQueue', [MediaController::class, 'RunEncodingQueue'])->name('EncodeQueue');


// Users
Route::get('/users', [UserController::class, 'showList'])->name('users');
Route::post('/users', [UserController::class, 'new']);

Route::get('/user/{uId}', [UserController::class, 'showEdit'])->name('profile');
Route::patch('/user/{uId}', [UserController::class, 'update'])->name('profile.update');
Route::delete('/user/{uId}', [UserController::class, 'delete'])->name('profile.delete'); //TODO: Implementar


// Test
Route::get('/dev/phpinfo', [HomeController::class, 'phpinfo'])->name('dev.phpinfo');

