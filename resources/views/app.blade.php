<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title","StockNp")</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>
<body class="">
    <div id="app" data-component="{{ $component }}" data-props="{{ json_encode($props) }}"></div>
</body>
<script src="{{ mix('/js/app.js') }}"></script>
</html>