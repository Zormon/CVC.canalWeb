<?php
Route::get('/', function () { return view('welcome'); });
Auth::routes();


Route::get('/deploy/{id}', 'DeployController@json')->name('json');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/lista', 'UserListaController@index')->name('lista');
Route::get('/lista/{id}', 'UserListaController@listVideos')->name('editentry');
Route::get('/lista/editvideo/{id}', 'UserListaController@editVideos')->name('editvideos');
Route::post('/lista/sort','UserListaController@updateVideo');
Route::post('/lista/media','UserListaController@updateVideo');
Route::post('/lista/media/del','UserListaController@deleteVideo');
Route::post('/lista/pendiente/del','UserListaController@cancelarCodificacion');
Route::post('/lista/edit','UserListaController@updateLista');
Route::post('/lista/images-save', 'UploadImagesController@store');

Route::get('/user/edit', 'UserController@index')->name('edituser');
Route::post('/user/edit/save', 'UserController@store')->name('saveuser');

Route::get('/issues', 'HomeController@issues')->name('issues');

Route::get('/users', 'AdminController@userlist')->name('userlist');
Route::get('/users/{id}/lista', 'AdminController@userentrylist')->name('userentrylist');
Route::get('/users/edit/{id}', 'AdminController@edituser')->name('edituser.profile');
Route::post('/users/edit/{id}/save', 'AdminController@profilestore')->name('saveuser.profile');
Route::post('/users/s','AdminController@searchusers');

Route::get('/usrlist', 'AdminController@userswithplaylist')->name('usersplaylist');
Route::get('/usrlist/{id}', 'AdminController@userplaylists')->name('userplaylists');
Route::get('/usrlist/{id}/lista/{pid}', 'AdminListaController@listVideos')->name('admin.editentry');
Route::get('/usrlist/{id}/lista/editvideo/{id2}', 'AdminListaController@editVideos')->name('admin.editvideos');
Route::post('/usrlist/{id}/lista/sort','AdminListaController@updateVideo');
Route::post('/usrlist/{id}/lista/media','AdminListaController@updateVideo');
Route::post('/usrlist/{id}/lista/media/del','AdminListaController@deleteVideo');
Route::post('/usrlist/{id}/lista/pendiente/del','AdminListaController@cancelarCodificacion');
Route::post('/usrlist/{id}/lista/edit','AdminListaController@updateLista');
Route::post('/usrlist/{id}/lista/del','AdminListaController@deleteLista');
Route::post('/usrlist/{id}/lista/new','AdminListaController@newLista');
Route::post('/usrlist/{id}/lista/images-save', 'AdminUploadImagesController@store')->name("adminstoreimg");

Route::get('/ffmpeg', 'AdminController@ffmpeg')->name('ffmpeg');
Route::get('/EncodeQueue', 'AdminController@EncodeQueue')->name('EncodeQueue');


