 <!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} - Gerenciamento de tarefas</title>
</head>

<body>

    <p>Prezado(a) {{ $content->user->name }}</p>

    <p>Segue informações sobre uma tarefa que @if($content->action == 'relation') foi relacionada @else esta relacionada @endif a você</p>

    <p>Tarefa #{{ $content->task->id }} - {{ $content->task->title }}</p>

    <p>{{ $content->task->description }}</p>

    <p>A tarefa encontra-se no status de <strong>{{ $content->task->status }}</strong> com data de conclusão para {{ $content->task->duedate_at }}</p>

    <p>Atenciosamente,</p>

    <p>JEFWeb</p>

</body>

</html>
