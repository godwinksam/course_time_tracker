<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course Hour Tracker</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        window.Laravel = {
            baseUrl: '{{ url("/") }}'
        }
    </script>
</head>
<body class="bg-gray-100 antialiased font-sans text-gray-900 leading-normal">
    <div id="app"></div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
