<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return redirect('/');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    $app->get('/api', [
        'uses' => 'ApiController@index'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Website Routes
    |--------------------------------------------------------------------------
    */
    $app->group(['namespace' => 'App\Http\Controllers'], function ($app) {

        $app->get('/api/website/count', [
            'uses' => 'WebsiteController@count'
        ]);
        $app->get('/api/website', [
            'as' => 'website',
            'uses' => 'WebsiteController@index'
        ]);
        $app->get('/api/website/{id:\d+}', [
            'uses' => 'WebsiteController@show'
        ]);
        $app->post('/api/website', [
            'uses' => 'WebsiteController@create'
        ]);
        $app->put('/api/website/{id:\d+}', [
            'uses' => 'WebsiteController@update'
        ]);
        $app->delete('/api/website/{id:\d+}', [
            'uses' => 'WebsiteController@destroy'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | FTP Routes
    |--------------------------------------------------------------------------
    */
    $app->group(['namespace' => 'App\Http\Controllers'], function ($app) {

        $app->get('/api/ftp/{website_id:\d+}', [
            'uses' => 'FTPController@index'
        ]);
        $app->get('/api/ftp/{website_id:\d+}/{id:\d+}', [
            'uses' => 'FTPController@show'
        ]);
        $app->post('/api/ftp/{website_id:\d+}', [
            'uses' => 'FTPController@create'
        ]);
        $app->put('/api/ftp/{website_id:\d+}/{id:\d+}', [
            'uses' => 'FTPController@update'
        ]);
        $app->delete('/api/ftp/{website_id:\d+}/{id:\d+}', [
            'uses' => 'FTPController@destroy'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Control Panel Routes
    |--------------------------------------------------------------------------
    */
    $app->group(['namespace' => 'App\Http\Controllers'], function ($app) {

        $app->get('/api/controlpanel/{website_id:\d+}', [
            'uses' => 'ControlPanelController@index'
        ]);
        $app->get('/api/controlpanel/{website_id:\d+}/{id:\d+}', [
            'uses' => 'ControlPanelController@show'
        ]);
        $app->post('/api/controlpanel/{website_id:\d+}', [
            'uses' => 'ControlPanelController@create'
        ]);
        $app->put('/api/controlpanel/{website_id:\d+}/{id:\d+}', [
            'uses' => 'ControlPanelController@update'
        ]);
        $app->delete('/api/controlpanel/{website_id:\d+}/{id:\d+}', [
            'uses' => 'ControlPanelController@destroy'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Database Routes
    |--------------------------------------------------------------------------
    */
    $app->group(['namespace' => 'App\Http\Controllers'], function ($app) {

        $app->get('/api/database/{website_id:\d+}', [
            'uses' => 'DatabaseController@index'
        ]);
        $app->get('/api/database/{website_id:\d+}/{id:\d+}', [
            'uses' => 'DatabaseController@show'
        ]);
        $app->post('/api/database/{website_id:\d+}', [
            'uses' => 'DatabaseController@create'
        ]);
        $app->put('/api/database/{website_id:\d+}/{id:\d+}', [
            'uses' => 'DatabaseController@update'
        ]);
        $app->delete('/api/database/{website_id:\d+}/{id:\d+}', [
            'uses' => 'DatabaseController@destroy'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    $app->group(['namespace' => 'App\Http\Controllers'], function ($app) {

        $app->get('/api/admin/{website_id:\d+}', [
            'uses' => 'AdminController@index'
        ]);
        $app->get('/api/admin/{website_id:\d+}/{id:\d+}', [
            'uses' => 'AdminController@show'
        ]);
        $app->post('/api/admin/{website_id:\d+}', [
            'uses' => 'AdminController@create'
        ]);
        $app->put('/api/admin/{website_id:\d+}/{id:\d+}', [
            'uses' => 'AdminController@update'
        ]);
        $app->delete('/api/admin/{website_id:\d+}/{id:\d+}', [
            'uses' => 'AdminController@destroy'
        ]);
    });
});
