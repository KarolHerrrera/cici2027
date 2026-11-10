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
        'noticias'      => 'Noticias',
        'contacto'      => 'Contacto',
    ];
    $resumenUrl = \App\Models\CongressSetting::getValue('abstract_form_url', config('cici.abstract_form_url')) ?: route('submissions.public.create');
    $resumenExt = (bool) \App\Models\CongressSetting::getValue('abstract_form_url', config('cici.abstract_form_url'));
    $anioEvento = \App\Models\CongressSetting::getValue('event_year', '2027');
    $logoSigla  = \App\Models\CongressSetting::getValue('event_logo_sigla', '/images/cici-sigla.png') ?: '/images/cici-sigla.png';
    $logoSiglaUrl = str_starts_with($logoSigla, 'http') ? $logoSigla : asset(ltrim($logoSigla, '/'));
@endphp

<header class="navbar" id="navbar">
    <div class="container navbar__inner">
        <a href="{{ route('home') }}#inicio" class="navbar__brand" aria-label="CICI {{ $anioEvento }}, ir al inicio">
            <img src="{{ $logoSiglaUrl }}" alt="CICI" width="624" height="323">
            <span class="navbar__year">{{ $anioEvento }}</span>
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
            <a href="{{ $resumenUrl }}"
               @if ($resumenExt) target="_blank" rel="noopener" @endif
               class="btn btn--primary btn--sm">Enviar resumen</a>
        </nav>
    </div>
</header>
