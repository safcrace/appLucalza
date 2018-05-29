<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Envío a Supervisión</title>
</head>
<body>

    <h3>Estimado(a) {{$mail->nombre}}</h3>

    <p>Por este medio se le informa, que la Liquidación No. <b>{{$liquidacion->ID}}</b>, le ha sido envíada para su respectiva revisión. </p>

    <a href="{{ $mail->ruta }}">Ir a la Liquidación</a>    

    Saludos,

</body>
</html>

