<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;

class CorreoController extends Controller

{
    public function correoContacto(Request $request)
    {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!$params_array) {
            $data = [
                'codigo' => 400,
                'estado' => 'error',
                'mensaje' => 'Error al enviar correo'
            ];
        } else {

            // Enviar correo a usuario
            $subject = "Hemos recibido tu compra, gracias por preferirnos.";
            $for = $params_array['email'];
            Mail::send('contactoUsuario', ['msg' => $params_array], function ($msj) use ($subject, $for) {
                $msj->from("kriquelme@terramedical.cl", "Katherine Riquelme");
                $msj->subject($subject);
                $msj->to($for);
            });

            // Enviar correo a empresa
            $subject = "Tienes una nueva solicitud de compra desde el sitio web";
            $for = 'kriquelme@terramedical.cl';
            $cc = 'fbarrios@terramedical.cl';
            Mail::send('contactoEmpresa', ['msg' => $params_array], function ($msj) use ($subject, $for, $cc) {
                $msj->from("ventas@terramedical.cl", "Sitio Web");
                $msj->subject($subject);
                $msj->to($for);
                $msj->cc($cc);
            });

            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'correo' => $params_array
            ];
        }

        // Devolver respuesta
        return response()->json($data, $data['codigo']);
    }


    // SUBIR ARCHIVO
    public function upload(Request $request)
    {
        // Recoger el archivo de la petición
        $file = $request->file('file0');

        // Validar el archivo
        $validate = \Validator::make($request->all(), [
            'file0' => 'required',
        ]);

        // Guardar la archivo 
        if ($validate->fails()) {
            $data = [
                'codigo' => 400,
                'estado' => 'error',
                'mensaje' => 'Error al subir archivo'
            ];
        } else {
            $file_name = time() . $file->getClientOriginalName();

            \Storage::disk('comprobantes')->put($file_name, \File::get($file));

            $data = [
                'codigo' => 200,
                'estado' => 'success',
                'imagen' => $file_name,
            ];
        }
        // Devolver respuesta
        return response()->json($data, $data['codigo']);
    }

    // OBTENER COMPROBANTE DESDE STORAGE
    public function getFile($filename)
    {
        // Comprobar si existe el fichero
        $isset =  \Storage::disk('comprobantes')->exists($filename);

        if ($isset) {
            // Conseguir el archivo
            $file = \Storage::disk('comprobantes')->download($filename);
            // Devolver el archivo
            return $file;
        } else {
            $data = [
                'codigo' => 404,
                'estado' => 'error',
                'mensaje' => 'El archivo no existe'
            ];
        }

        return response()->json($data, $data['codigo']);
    }
}
