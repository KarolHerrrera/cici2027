@php
    $anioMeta   = \App\Models\CongressSetting::getValue('event_year', '2027');
    $nombreMeta = \App\Models\CongressSetting::getValue('event_name', 'IV Congreso Internacional de Ciencia e Ingeniería');
    $fechasMeta = \App\Models\CongressSetting::getValue('event_dates_label', '14, 15 y 16 de abril de 2027');
    $siglaMeta  = \App\Models\CongressSetting::getValue('event_logo_sigla', '/images/cici-sigla.png') ?: '/images/cici-sigla.png';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', "CICI {$anioMeta} · {$nombreMeta}")</title>
    <meta name="description" content="@yield('description', "{$nombreMeta}. {$fechasMeta}. Facultad de Ingeniería, Universidad Mariana.")">
    <meta name="theme-color" content="#114e91">

    <link rel="icon" type="image/png" href="{{ str_starts_with($siglaMeta, 'http') ? $siglaMeta : asset(ltrim($siglaMeta, '/')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cici.css') }}">
</head>
<body>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>

    <x-navbar />

    <main id="contenido">
        @yield('content')
    </main>

    <x-footer />

    <script src="{{ asset('js/cici.js') }}" defer></script>
</body>
</html>
