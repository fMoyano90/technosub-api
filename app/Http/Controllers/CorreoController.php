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
            $subject = "Hemos recibido tu solicitud";
            $for = $params_array['email'];
            Mail::send('contactoUsuario', ['msg' => $params_array], function ($msj) use ($subject, $for) {
                $msj->from("contacto@technosub.cl", "Daniel Piazza");
                $msj->subject($subject);
                $msj->to($for);
            });

            // Enviar correo a empresa
            $subject = "Te han contactado desde el sitio web";
            $for = 'contacto@technosub.cl';
            Mail::send('contactoEmpresa', ['msg' => $params_array], function ($msj) use ($subject, $for) {
                $msj->from("feedback@technosub.cl", "Sitio Web");
                $msj->subject($subject);
                $msj->to($for);
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
}
