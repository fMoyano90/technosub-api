<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;

Route::group([
    'middleware' => ['cors'],
], function ($router) {

    Route::get('/', function () {
        return view('welcome');
    });

    // RUTAS USUARIOS
    Route::post('api/register', 'UsuarioController@register');
    Route::post('api/login', 'UsuarioController@login');
    Route::get('api/usuario', 'UsuarioController@index');
    Route::put('api/usuario/update/{id}', 'UsuarioController@update');
    Route::delete('api/usuario/delete/{id}', 'UsuarioController@destroy');

    // RUTAS PRODUCTOS
    Route::resource('/api/producto', 'ProductoController');
    Route::post('/api/producto/upload', 'ProductoController@upload');
    Route::get('/api/producto/imagen/{filename}', 'ProductoController@getImage');
    Route::get('/api/producto/productos/oferta', 'ProductoController@getProductosOferta');

    // RUTA CORREO 
    Route::post('/api/contacto', 'CorreoController@correoContacto');
    Route::post('/api/comprobantes/upload', 'CorreoController@upload');
    Route::get('/api/comprobantes/file/{filename}', 'CorreoController@getFile');
});
