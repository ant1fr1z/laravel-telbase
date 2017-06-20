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

//главная
Route::get('/', [
    'uses' => 'ObjectsController@index',
    'as'=> 'index'
]);

//отображение найденого объекта
Route::post('/', [
    'middleware' => 'telnumber',
    'uses' => 'ObjectsController@show',
    'as'=> 'objects.show'
]);

//temp
Route::get('/save', [
    'uses' => 'NumbersController@index',
    'as'=> 'save'
]);

//страница создания объекта
Route::get('/{number_id}/create', [
    'uses' => 'ObjectsController@create',
    'as'=> 'objects.create'
]);

//поиск по списку
Route::match(['get', 'post'],'/list', [
    'uses' => 'ObjectsController@searchlist',
    'as'=> 'objects.list'
]);

//поиск по обєкту
Route::match(['get', 'post'],'/searchobject', [
    'uses' => 'ObjectsController@searchobject',
    'as'=> 'objects.searchobject'
]);

//експорт в excel
Route::post('/getexcelfromlist', [
    'uses' => 'ObjectsController@getexcelfromlist',
    'as'=> 'objects.getexcelfromlist'
]);
//експорт в excel
Route::post('/getexcelfromobjects', [
    'uses' => 'ObjectsController@getexcelfromobjects',
    'as'=> 'objects.getexcelfromobjects'
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

//страница связей объекта
Route::get('/{object_id}/links', [
    'uses' => 'ObjectsController@links',
    'as'=> 'objects.links'
]);

//страница связей объекта
Route::post('/{object_id}/links', [
    'uses' => 'ObjectsController@linkModal',
    'as'=> 'objects.linkModal'
]);

//модальное окно из которого подтверждается связь объекта
Route::put('/{object_id}/links', [
    'uses' => 'ObjectsController@addLink',
    'as'=> 'objects.addLink'
]);

//удаление связи объекта
Route::get('/{link_id}/delete', [
    'uses' => 'ObjectsController@delLink',
    'as'=> 'objects.delLink'
]);
