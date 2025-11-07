<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Caprichos Pet Grooming</title>
        <link rel="icon" type="image/png" href="{{ asset('img/Isotipo_Caprichos_Rojo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="w-full h-auto" style="background-image: url('{{ asset('img/PET.png') }}'); background-size: cover; background-position: center;">
            {{ $slot }}
        </div>
        @livewireScripts
    </body>
</html>
