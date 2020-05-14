<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Usuario;
use App\Helpers\JwtAuth;

class UsuarioController extends Controller
{
    // OBTENER USUARIOS 
    public function index(Request $request)
    {
        $usuarios = Usuario::all();

        return response()->json([
            'codigo' => 200,
            'estado' => 'success',
            'usuarios' => $usuarios
        ], 200);
    }

    // REGISTRO DE USUARIOS
    public function register(Request $request)
    {
        // RECOGER LOS DATOS DEL USUARIO POR POST 
        $json = $request->input('json', null);
        $params = json_decode($json); // Objeto 
        $params_array = json_decode($json, true); // Array

        if (!empty($params_array)) {
            // Limpiar datos
            $params_array =  array_map('trim', $params_array);

            // Validar datos
            $validate = \Validator::make($params_array, [
                'nombre' => 'required',
                'role' => 'required',
                'email' => 'required|unique:usuarios',
                'password' => 'required'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'estado' => 'error',
                    'codigo' => 400,
                    'mensaje' => 'El usuario no se ha creado',
                    'errores' => $validate->errors()
                );
            } else {

                // Cifrar contraseña
                $pwd = hash('sha256', $params->password);

                // Crear el usuario
                $usuario = new Usuario();
                $usuario->nombre = $params_array['nombre'];
                $usuario->role = $params_array['role'];
                $usuario->email = $params_array['email'];
                $usuario->password = $pwd;

                // Guardar usuario
                $usuario->save();

                $data = array(
                    'estado' => 'success',
                    'codigo' => 200,
                    'mensaje' => 'El usuario se ha creado correctamente'
                );
            }
        } else {
            $data = array(
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'Los datos enviados no son correctos'
            );
        }

        return response()->json($data, $data['codigo']);
    }

    // LOGIN DE USUARIOS
    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth;

        // Recibir datos por POST 
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Validar los datos 
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            $signup = array(
                'estado' => 'error',
                'codigo' => 404,
                'mensaje' => 'El usuario no se ha logeado',
                'errores' => $validate->errors()
            );
        } else {

            // Cifrar contraseña
            $pwd = hash('sha256', $params->password);

            // Devolver token 
            $signup = $jwtAuth->signup($params->email, $pwd);

            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }

        return response()->json($signup, 200);
    }

    // ACTUALIZAR USUARIO 
    public function update($id, Request $request)
    {
        // Recoger los datos por post 
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validar los datos 
            $validate = \Validator::make($params_array, [
                'nombre' => 'required',
                'role' => 'required|alpha',
                'email' => 'required|email|unique:usuarios'
            ]);

            // Quitar los campos que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['updated_at']);

            // Actualizar usuario en bbdd
            $usuario_update = Usuario::where('id', $id)->update($params_array);

            // Devolver array con resultado 
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'usuario' => $usuario_update,
                'cambios' => $params_array
            ];
        } else {
            // Mensaje de error 
            $data = [
                'codigo' => 400,
                'estado' => 'error',
                'mensaje' => 'El usuario no usuario no está identificado.'
            ];
        }

        return response()->json($data, $data['codigo']);
    }

    // ELIMINAR USUARIO 
    public function destroy($id)
    {
        // Conseguir el usuario
        $usuario =  Usuario::where('id', $id)->first();

        if (!empty($usuario)) {
            // Borrar propiedad
            $usuario->delete();

            // Devolver algo 
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'usuario' => $usuario
            ];
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'El usuario no existe'
            ];
        }
        return response()->json($data, $data['codigo']);
    }
}
