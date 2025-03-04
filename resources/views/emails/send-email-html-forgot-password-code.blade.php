<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} - Recuperação de senha</title>
</head>

<body>

    <p>Prezado(a) {{ $content->user->name }}</p>

    <p>Para recuperar a sua senha do app {{ env('APP_NAME') }}, use o código de verificação abaixo:</p>

    <p>{{ $content->code }}</p>

    <p>Por questões de segurança esse código é válido somente até as {{ $content->formatted_time }} do dia {{ $content->formatted_date }}. Caso esse prazo esteja expirado, será necessário solicitar outro código.</p>

    <p>Atenciosamente,</p>

    <p>JEFWeb</p>

</body>

</html>
