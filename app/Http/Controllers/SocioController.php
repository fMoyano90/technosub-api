<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Socio;

class SocioController extends Controller
{
    // OBTENER TODOS LOS SOCIOS
    public function index()
    {
        $socios = Socio::all();

        return response()->json([
            'codigo' => 200,
            'estado' => 'success',
            'socios' => $socios
        ], 200);
    }

    // OBTENER SOCIO POR ID
    public function show($id)
    {
        $socio = Socio::find($id);

        if (is_object($socio)) {
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'socio' => $socio
            ];
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'El socio no existe'
            ];
        }

        return response()->json($data, $data['codigo']);
    }

    // CREAR NUEVO SOCIO
    public function store(Request $request)
    {
        // Recoger datos por post 
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar los datos 
            $validate = \Validator::make($params_array, [
                'nombre' => 'required',
                'descripcion' => 'required',
            ]);

            if ($validate->fails()) {
                $data = [
                    'codigo' => 400,
                    'estado' => 'error',
                    'mensaje' => 'No se ha guardado el socio, faltan datos'
                ];
            } else {

                // Guardar la noticia
                $socio = new Socio();
                $socio->nombre      = $params->nombre;
                $socio->descripcion = $params->descripcion;
                $socio->enlace = $params->enlace;
                $socio->imagen      = $params->imagen;

                $socio->save();

                $data = [
                    'codigo' => 200,
                    'estado' => 'success',
                    'socio' => $socio
                ];
            }
        } else {
            $data = [
                'codigo'  => 404,
                'estado'  => 'error',
                'mensaje' => 'Envia los datos correctamente'
            ];
        }
        // Devolver respuesta 
        return response()->json($data, $data['codigo']);
    }

    // ACTUALIZAR SOCIO POR SU ID
    public function update($id, Request $request)
    {
        // Recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar datos
            $validate = \Validator::make($params_array, [
                'descripcion' => 'required',
            ]);

            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['codigo']);
            }

            // Eliminar lo que no queremos actualizar 

            unset($params_array['id']);
            unset($params_array['created_at']);

            // Buscar el registro 
            $socio =  Socio::where('id', $id)
                ->first();

            if (!empty($socio) && is_object($socio)) {
                // Actualizar el registro     
                $socio->update($params_array);

                // Devolver un resultado
                $data = [
                    'codigo'  => 200,
                    'estado'   => 'success',
                    'socio' => $socio,
                    'cambios'  => $params_array
                ];
            }
        }

        return response()->json($data, $data['codigo']);
    }

    // ELIMINAR SOCIO POR SU ID
    public function destroy($id)
    {
        // Conseguir el socio
        $socio =  Socio::where('id', $id)->first();

        if (!empty($socio)) {
            // Borrar socio
            $socio->delete();

            // Devolver algo 
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'socio' => $socio
            ];
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'No existe socio con ese id'
            ];
        }
        return response()->json($data, $data['codigo']);
    }

    // SUBIR IMAGEN
    public function upload(Request $request)
    {
        // Recoger la imagen de la petición
        $imagen = $request->file('file0');

        // Validar la imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'image|mimes:jpg,jpeg,png,gif',
        ]);

        // Guardar la imagen 
        if ($validate->fails()) {
            $data = [
                'codigo' => 400,
                'estado' => 'error',
                'mensaje' => 'Error al subir imagen'
            ];
        } else {
            $image_name = time() . $imagen->getClientOriginalName();

            \Storage::disk('socios')->put($image_name, \File::get($imagen));

            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'imagen' => $image_name,
            ];
        }
        // Devolver respuesta
        return response()->json($data, $data['codigo']);
    }

    // OBTENER IMAGEN DESDE STORAGE
    public function getImage($filename)
    {
        // Comprobar si existe el fichero
        $isset =  \Storage::disk('socios')->exists($filename);

        if ($isset) {
            // Conseguir la imagen 
            $file = \Storage::disk('socios')->get($filename);
            // Devolver la imgen 
            return new Response($file, 200);
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'La imagen no existe'
            ];
        }

        return response()->json($data, $data['codigo']);
    }
}
