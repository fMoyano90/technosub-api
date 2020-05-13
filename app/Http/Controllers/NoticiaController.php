<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Noticia;

class NoticiaController extends Controller
{
    // OBTENER TODAS LAS NOTICIAS
    public function index()
    {
        $noticias = Noticia::all();

        return response()->json([
            'codigo' => 200,
            'estado' => 'success',
            'noticias' => $noticias
        ], 200);
    }

    // OBTENER NOTICIA POR ID
    public function show($id)
    {
        $noticia = Noticia::find($id);

        if (is_object($noticia)) {
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'noticia' => $noticia
            ];
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'La noticia no existe'
            ];
        }

        return response()->json($data, $data['codigo']);
    }

    // CREAR NUEVA NOTICIA 
    public function store(Request $request)
    {
        // Recoger datos por post 
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar los datos 
            $validate = \Validator::make($params_array, [
                'titulo'      => 'required',
                't_breve'     => 'required',
                'cuerpo'      => 'required',
                'categoria'   => 'required',
                'prioridad'   => 'required',
            ]);

            if ($validate->fails()) {
                $data = [
                    'codigo' => 400,
                    'estado' => 'error',
                    'mensaje' => 'No se ha guardado la noticia, faltan datos'
                ];
            } else {

                // Guardar la noticia
                $noticia = new Noticia();
                $noticia->titulo    = $params->titulo;
                $noticia->t_breve   = $params->t_breve;
                $noticia->cuerpo    = $params->cuerpo;
                $noticia->autor     = $params->autor;
                $noticia->categoria = $params->categoria;
                $noticia->imagen    = $params->imagen;
                $noticia->prioridad = $params->prioridad;

                $noticia->save();

                $data = [
                    'codigo' => 200,
                    'estado' => 'success',
                    'noticia' => $noticia
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

    // ACTUALIZAR NOTICIA POR SU ID
    public function update($id, Request $request)
    {
        // Recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar datos
            $validate = \Validator::make($params_array, [
                'titulo'      => 'required',
                't_breve'     => 'required',
                'cuerpo' => 'required',
                'categoria'   => 'required',
                'prioridad'   => 'required',
            ]);


            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['codigo']);
            }

            // Eliminar lo que no queremos actualizar 

            unset($params_array['id']);
            unset($params_array['created_at']);

            // Buscar el registro 
            $noticia =  Noticia::where('id', $id)
                ->first();

            if (!empty($noticia) && is_object($noticia)) {
                // Actualizar el registro     
                $noticia->update($params_array);

                // Devolver un resultado
                $data = [
                    'codigo'  => 200,
                    'estado'   => 'success',
                    'noticia' => $noticia,
                    'cambios'  => $params_array
                ];
            }
        }

        return response()->json($data, $data['codigo']);
    }

    // ELIMINAR NOTICIA POR SU ID
    public function destroy($id)
    {
        // Conseguir la noticia
        $noticia =  Noticia::where('id', $id)->first();

        if (!empty($noticia)) {
            // Borrar noticia
            $noticia->delete();

            // Devolver algo 
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'noticia' => $noticia
            ];
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'La noticia no existe'
            ];
        }
        return response()->json($data, $data['codigo']);
    }

    // OBTENER NOTICIA POR CATEGORIA
    public function getNoticiaPorCategoria($categoria)
    {
        $noticias = Noticia::where('categoria', $categoria)->get();

        return response()->json([
            'codigo' => 200,
            'estado' => 'success',
            'noticias' => $noticias
        ], 200);
    }

    // OBTENER NOTICIAS PRINCIPALES
    public function getNoticiaPrincipal()
    {
        $noticias = Noticia::where('prioridad', 'PRINCIPAL')->take(2)->get();

        return response()->json([
            'codigo' => 200,
            'estado' => 'success',
            'noticias' => $noticias
        ], 200);
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

            \Storage::disk('noticias')->put($image_name, \File::get($imagen));

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
        $isset =  \Storage::disk('noticias')->exists($filename);

        if ($isset) {
            // Conseguir la imagen 
            $file = \Storage::disk('noticias')->get($filename);
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
