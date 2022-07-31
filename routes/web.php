<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/todo/', 'App\Http\Controllers\TodoListController@index')->name('todo.index');
Route::post('/todo/', 'App\Http\Controllers\TodoListController@store')->name('todo.store');
Route::delete('/todo/{code}/', 'App\Http\Controllers\TodoListController@destroy')->name('todo.destroy');
Route::patch('/todo/{code}/', 'App\Http\Controllers\TodoListController@update')->name('todo.update');
