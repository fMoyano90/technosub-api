<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Usuario;

class JwtAuth
{

    public $key;

    public function __construct()
    {
        $this->key = 'esto_es_una_clave_super_secreta-007';
    }

    public function signup($email, $password, $getToken =  null)
    {

        // Buscar si existe usuario con credenciales 
        $usuario = Usuario::where([
            'email'    => $email,
            'password' => $password
        ])->first();

        // Comprobar si son correctas 
        $signup = false;

        if (is_object($usuario)) {
            $signup = true;
        }

        // Generar el token con los datos del usuario identificado
        if ($signup) {
            $token = array(
                'id'      => $usuario->id,
                'email'   => $usuario->email,
                'nombre'  => $usuario->nombre,
                'role'    => $usuario->role,
                'iat'     => time(),
                'exp'     => time() + (7 * 24 * 60 * 60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            // Devolver los datos decodificados o el token, en funcion de un parametro

            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = [
                'status' => 'error',
                'message' =>  'Login incorrecto'
            ];
        }

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}
