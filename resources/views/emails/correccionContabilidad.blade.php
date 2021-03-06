<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Correción de Contabilidad</title>
</head>
<body>

    <h3>Estimado(a) {{$mail->nombre}}</h3>

    <p>Por este medio se le informa, que la Liquidación No. <b>{{$liquidacion->ID}}</b>, ha sido rechazada por Contabilidad, por el siguiente motivo: {{$liquidacion->SUPERVISOR_CONTABILIDAD}}. </p>

    <a href="{{ $mail->ruta }}">Ir a la Liquidación</a>

    <p>Facturas a corregir:</p>
    <ul>
        @foreach($facturas as $factura)        
            <li>Factura #: {{$factura->NUMERO}} ::: Motivo: {{$factura->COMENTARIO_CONTABILIDAD}}</li>
        @endforeach
    </ul>

    Saludos,

</body>
</html>

