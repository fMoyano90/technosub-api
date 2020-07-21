<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nueva venta</title>
    <style>
        .ticket{
            background-color: lightblue;
            padding: 20px;
            color: #e000080;
            width: 280px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .fs18{
            font-size: 18px;
        }
        table {
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid black;
}
    </style>
</head>
<body>
<p>Se ha generado una solicitud de compra, en adelante los detalles:</p>
<p class="ticket"><b>Transacción:</b><br> {{$msg['transaccion']}}</p>
<p><b>1. Datos del cliente:</b></p>
<ul>
    <li>Nombre: {{$msg['nombre']}}</li>
    <li>Apellido: {{$msg['apellido']}}</li>
    <li>Email: {{$msg['email']}}</li>
    <li>Telefono: {{$msg['telefono']}}</li>
</ul>
<p><b>2. Productos</b></p>

  @foreach ($msg['carrito'] as $item)
    <p>Cantidad: {{$item['cantidad']}}</p>
    <p><b>Datos del producto</b></p>
    @foreach ($item['producto'] as $producto)
    <p>{{$producto}}</p>
    @endforeach
  <hr>
  @endforeach
</table>
<h2>Total a pagar: <b>{{$msg['total']}}</b></h2>


<a href="{{url('https://terramedical.cl/api/comprobantes/file/'.$msg['comprobante'])}}" download>
    DESCARGAR COMPROBANTE DE PAGO
</a>
</body>

</html>