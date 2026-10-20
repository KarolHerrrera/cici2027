{{-- Menú principal. Se reutiliza en todas las páginas con <x-navbar /> --}}
@php
    $links = [
        'congreso'      => 'Congreso',
        'fechas'        => 'Fechas',
        'ejes'          => 'Ejes',
        'ponentes'      => 'Ponentes',
        'programa'      => 'Programa',
        'inscripciones' => 'Inscripciones',
        'documentos'    => 'Documentos',
        'contacto'      => 'Contacto',
    ];
@endphp

<header class="navbar" id="navbar">
    <div class="container navbar__inner">
        <a href="{{ route('home') }}#inicio" class="navbar__brand" aria-label="CICI 2027, ir al inicio">
            <img src="{{ asset('images/cici-sigla.png') }}" alt="CICI" width="624" height="323">
            <span class="navbar__year">2027</span>
        </a>

        <button class="navbar__toggle" id="navToggle" type="button"
                aria-label="Abrir menú" aria-expanded="false" aria-controls="navMenu">
            <span></span><span></span><span></span>
        </button>

        <nav class="navbar__menu" id="navMenu" aria-label="Principal">
            <ul>
                @foreach ($links as $ancla => $texto)
                    <li><a href="{{ route('home') }}#{{ $ancla }}">{{ $texto }}</a></li>
                @endforeach
                <li><a href="{{ route('submissions.status') }}">Estado resumen</a></li>
            </ul>
            <a href="{{ route('submissions.public.create') }}" class="btn btn--primary btn--sm">Enviar resumen</a>
        </nav>
    </div>
</header>
