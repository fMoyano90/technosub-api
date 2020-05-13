<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'codigo', 'nombre', 'd_breve', 'descripcion', 'imagen', 'categoria'
    ];
}
