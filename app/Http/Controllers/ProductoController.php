<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Producto;

class ProductoController extends Controller
{
    // OBTENER TODOS LOS PRODUCTOS
    public function index()
    {
        $productos = Producto::all();

        return response()->json([
            'codigo' => 200,
            'estado' => 'success',
            'productos' => $productos
        ], 200);
    }

    // OBTENER PRODUCTO POR ID
    public function show($id)
    {
        $producto = Producto::find($id);

        if (is_object($producto)) {
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'producto' => $producto
            ];
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'El producto no existe'
            ];
        }

        return response()->json($data, $data['codigo']);
    }

    // CREAR NUEVO PRODUCTO 
    public function store(Request $request)
    {
        // Recoger datos por post 
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar los datos 
            $validate = \Validator::make($params_array, [
                'nombre'      => 'required',
                'd_breve'     => 'required',
                'descripcion' => 'required',
                'imagen'   => 'required',
                'categoria'   => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'codigo' => 400,
                    'estado' => 'error',
                    'mensaje' => 'No se ha guardado el producto, faltan datos'
                ];
            } else {

                // Guardar la producto
                $producto = new Producto();
                $producto->codigo        = $params->codigo;
                $producto->nombre        = $params->nombre;
                $producto->d_breve       = $params->d_breve;
                $producto->descripcion   = $params->descripcion;
                $producto->imagen        = $params->imagen;
                $producto->categoria     = $params->categoria;

                $producto->save();

                $data = [
                    'codigo' => 200,
                    'estado' => 'success',
                    'producto' => $producto
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

    // ACTUALIZAR PRODUCTO POR SU ID
    public function update($id, Request $request)
    {
        // Recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar datos
            $validate = \Validator::make($params_array, [
                'nombre'      => 'required',
                'd_breve'     => 'required',
                'descripcion' => 'required',
                'imagen'   => 'required',
                'categoria'   => 'required'
            ]);


            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['codigo']);
            }

            // Eliminar lo que no queremos actualizar 

            unset($params_array['id']);
            unset($params_array['created_at']);

            // Buscar el registro 
            $producto =  Producto::where('id', $id)
                ->first();

            if (!empty($producto) && is_object($producto)) {
                // Actualizar el registro     
                $producto->update($params_array);

                // Devolver un resultado
                $data = [
                    'codigo'  => 200,
                    'estado'   => 'success',
                    'producto' => $producto,
                    'cambios'  => $params_array
                ];
            }
        }

        return response()->json($data, $data['codigo']);
    }

    // ELIMINAR PRODUCTO POR SU ID
    public function destroy($id)
    {
        // Conseguir la producto
        $producto =  Producto::where('id', $id)->first();

        if (!empty($producto)) {
            // Borrar producto
            $producto->delete();

            // Devolver algo 
            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'producto' => $producto
            ];
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'El producto no existe'
            ];
        }
        return response()->json($data, $data['codigo']);
    }

    // OBTENER PRODUCTO POR CATEGORIA
    public function getProductoPorCategoria($categoria)
    {
        $productos = Producto::where('categoria', $categoria)->get();

        return response()->json([
            'codigo' => 200,
            'estado' => 'success',
            'productos' => $productos
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

            \Storage::disk('productos')->put($image_name, \File::get($imagen));

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
        $isset =  \Storage::disk('productos')->exists($filename);

        if ($isset) {
            // Conseguir la imagen 
            $file = \Storage::disk('productos')->get($filename);
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
