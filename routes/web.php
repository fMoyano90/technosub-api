<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;

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
Route::get('/api/producto/categoria/{categoria}', 'ProductoController@getProductoPorCategoria');
Route::post('/api/producto/upload', 'ProductoController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/producto/imagen/{filename}', 'ProductoController@getImage');

// RUTAS NOTICIAS
Route::resource('/api/noticia', 'NoticiaController');
Route::get('/api/noticia/categoria/{categoria}', 'NoticiaController@getNoticiaPorCategoria');
Route::get('/api/noticia/prioridad/principal', 'NoticiaController@getNoticiaPrincipal');
Route::post('/api/noticia/upload', 'NoticiaController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/noticia/imagen/{filename}', 'NoticiaController@getImage');

// RUTAS SOCIOS
Route::resource('/api/socio', 'SocioController');

// RUTA CORREO 
Route::post('/api/contacto', 'CorreoController@correoContacto');
