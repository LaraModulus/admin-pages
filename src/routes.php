<?php
use Illuminate\Support\Facades\Route;
Route::group([
    'prefix'     => 'admin/pages',
    'middleware' => ['admin', 'auth.admin'],
    'namespace'  => 'LaraMod\Admin\Pages\Controllers',
], function () {
    Route::get('/', ['as' => 'admin.pages', 'uses' => 'PagesController@index']);
    Route::get('/form', ['as' => 'admin.pages.form', 'uses' => 'PagesController@getForm']);
    Route::post('/form', ['as' => 'admin.pages.form', 'uses' => 'PagesController@postForm']);

    Route::get('/delete', ['as' => 'admin.pages.delete', 'uses' => 'PagesController@delete']);
    Route::get('/datatable', ['as' => 'admin.pages.datatable', 'uses' => 'PagesController@dataTable']);
});