<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table = 'noticias';
    // TODO: AGREGAR CAMPO CATEGORIA AL FRONTEND
    protected $fillable = [
        'titulo', 't_breve', 'cuerpo', 'autor', 'imagen', 'categoria', 'prioridad'
    ];
}
