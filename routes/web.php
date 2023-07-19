<?php
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
Route::get('/', 'HomeController@root');

// Deploy
Route::get('/deploy/{id}', 'DeployController@json')->name('json');


// Home
Route::get('/home', 'HomeController@home')->name('home');
Route::get('/issues', 'HomeController@issues')->name('issues');


// Playlist
Route::get('/playlists/{uId?}', 'PlaylistController@userPlaylists')->name('playlists.user');
Route::get('/playlist/{plId}', 'PlaylistController@showList')->name('playlist.single');
Route::post('/playlist', 'PlaylistController@new')->name('playlist');
Route::put('/playlist/{plId}', 'PlaylistController@edit');
Route::patch('/playlist/{plId}', 'PlaylistController@updatePositions');
Route::delete('/playlist/{plId}', 'PlaylistController@delete'); //TODO: Implementar


// Media
Route::patch('/media/{id}','MediaController@update')->name('media.single');
Route::delete('/media/{id}','MediaController@delete');


// Uploads
Route::post('/upload', 'UploadsController@store'); //TODO: Mover ruta a /media y el controlador a MediaController


// Users
Route::get('/users', 'UserController@showList')->name('users');
Route::get('/user/{uId}', 'UserController@edit')->name('profile');
Route::patch('/user/{uId}', 'UserController@update')->name('profile.update');
Route::delete('/user/{uId}', 'UserController@delete')->name('profile.delete'); //TODO: Implementar


// Procesar colas
Route::get('/EncodeQueue', 'UploadsController@RunEncodingQueue')->name('EncodeQueue');


// Test
Route::get('/dev/phpinfo', 'HomeController@phpinfo')->name('dev.phpinfo');

