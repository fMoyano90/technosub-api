<p>Haz recibido una nueva solicitud de contacto desde el sitio web, en adelante los detalles:</p>

<ul style="list-style: none">
    <li><b>Nombre:</b> {{$msg['nombre']}} {{$msg['apellido']}}</li>
    <li><b>Télefono:</b> {{$msg['telefono']}}</li>
    <li><b>Email:</b> {{$msg['email']}}</li>
    <li><b>Mensaje:</b>
        <br>
        {{$msg['mensaje']}}
    </li>
</ul>