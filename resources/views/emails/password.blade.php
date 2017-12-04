<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecimieto de Contraseña</title>
</head>
<body>
    <h1>Restablecimiento de Contraseña</h1>

    <p>
        Hola!  Hemos recibido una solicitud para restablecer la contraseña de su cuenta.
        Ahora puede iniciar una sesión haciendo clic en este enlace o copiándolo y pegándolo en su navegador:
        {{ url('password/reset/' . $token) }}.
    </p>
    <p>Este enlace le llevará a una página donde podrá restablecer su contraseña. 
        El enlace caduca al cabo de un día y no pasa nada si no se usa. 
    </p>
</body>
</html>

