<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Todo list</title>
</head>
<body class="antialiased">
<div id="app">
    <todo-lists/>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>