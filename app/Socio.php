<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $table = 'socios';
    protected $fillable = [
        'nombre', 'descripcion', 'imagen', 'enlace'
    ];
}
