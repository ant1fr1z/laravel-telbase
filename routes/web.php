<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/save', [
    'uses' => 'NumbersController@index',
    'as'=> 'index'
]);

//страница создания объекта
Route::get('/{number_id}/create', [
    'uses' => 'ObjectsController@create',
    'as'=> 'objects.create'
]);
//запрос на сохранение объекта
Route::post('/{number_id}/create', [
    'uses' => 'ObjectsController@store',
    'as'=> 'objects.store'
]);
//страница редактирования объекта
Route::get('/{object_id}/edit', [
    'uses' => 'ObjectsController@edit',
    'as'=> 'objects.edit'
]);
//страница редактирования объекта
Route::post('/{object_id}/edit', [
    'uses' => 'ObjectsController@update',
    'as'=> 'objects.update'
]);
//добавление номера к объекту
Route::post('/{object_id}/addnumber', [
    'uses' => 'ObjectsController@addnumber',
    'as'=> 'objects.addnumber'
]);
//удаление номера от объекта
Route::get('/{object_id}/{number_id}/delnumber', [
    'uses' => 'ObjectsController@delnumber',
    'as'=> 'objects.delnumber'
]);

