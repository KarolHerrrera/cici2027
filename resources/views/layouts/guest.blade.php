<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Acceso Comité y Administración · CICI 2027</title>

        <link rel="icon" type="image/png" href="{{ asset('images/cici-sigla.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-900">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex flex-col items-center">
                    <img src="{{ asset('images/logo-blanco.png') }}" alt="CICI 2027" class="h-20 w-auto mb-2">
                </a>
                <p class="text-sm text-slate-300">Panel del Comité Científico y Administración</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>

            <div class="mt-6 flex gap-6 text-sm text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-white hover:underline">← Volver al sitio del congreso</a>
                <a href="{{ route('submissions.status') }}" class="hover:text-white hover:underline">Soy postulante (consultar con cédula)</a>
            </div>
        </div>
    </body>
</html>
