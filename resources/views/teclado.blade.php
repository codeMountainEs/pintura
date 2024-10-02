<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IrAcustica</title>



    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="antialiased font-sans">

    <livewire:accesos.create />

</body>
</html>
