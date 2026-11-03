{{-- Pie de página institucional. Se reutiliza con <x-footer /> --}}
@php
    $resumenUrl  = \App\Models\CongressSetting::getValue('abstract_form_url', config('cici.abstract_form_url')) ?: route('submissions.public.create');
    $resumenExt  = (bool) \App\Models\CongressSetting::getValue('abstract_form_url', config('cici.abstract_form_url'));
    $correo      = \App\Models\CongressSetting::getValue('contact_email', config('cici.contact_email', 'congresoingenieria@umariana.edu.co'));
    $telefono    = \App\Models\CongressSetting::getValue('contact_phone', config('cici.contact_phone', '318 6063123'));
    $fechasTexto = \App\Models\CongressSetting::getValue('event_dates_label', '14, 15 y 16 de abril de 2027');
    $modalidad   = \App\Models\CongressSetting::getValue('event_modality', 'Modalidad mixta');
    $anioEvento  = \App\Models\CongressSetting::getValue('event_year', '2027');
    $whatsapp    = config('cici.whatsapp_url');
@endphp

<footer class="footer">
    <div class="container footer__grid">
        <div class="footer__brand">
            <img src="{{ asset('images/logo-blanco.png') }}" alt="Congreso Internacional de Ciencia e Ingeniería, CICI {{ $anioEvento }}" width="1049" height="316">
            <p>Facultad de Ingeniería · Universidad Mariana<br>{{ $fechasTexto }} · {{ $modalidad }}</p>
        </div>

        <div>
            <h3 class="footer__title">Congreso y Ponencias</h3>
            <ul class="footer__list">
                <li>
                    <a href="{{ $resumenUrl }}" @if ($resumenExt) target="_blank" rel="noopener" @endif>
                        Enviar resumen
                    </a>
                </li>
                <li><a href="{{ route('submissions.status') }}">Consultar estado con cédula</a></li>
                <li><a href="{{ route('home') }}#inscripciones">Inscripciones</a></li>
                <li><a href="{{ route('home') }}#documentos">Documentos oficiales</a></li>
                <li><a href="{{ route('home') }}#noticias">Noticias y novedades</a></li>
            </ul>
        </div>

        <div>
            <h3 class="footer__title">Información oficial</h3>
            <ul class="footer__list">
                <li><a href="https://cici.umariana.edu.co" target="_blank" rel="noopener">cici.umariana.edu.co</a></li>
                <li><a href="mailto:{{ $correo }}">{{ $correo }}</a></li>
                <li><a href="tel:+57{{ preg_replace('/\D/', '', $telefono) }}">Teléfono: {{ $telefono }}</a></li>
                @if ($whatsapp)
                    <li><a href="{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp</a></li>
                @endif
            </ul>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <span>© {{ date('Y') }} Facultad de Ingeniería · Universidad Mariana · CICI {{ $anioEvento }}</span>
            <div style="display:flex; gap:1.25rem; flex-wrap:wrap;">
                <a href="{{ route('submissions.status') }}">Consultar estado de resumen</a>
                @auth
                    <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('committee.submissions.index') }}">
                        Ir al panel ({{ Auth::user()->roleLabel() }})
                    </a>
                @else
                    <a href="{{ route('login') }}">Acceso Comité Científico / Administración</a>
                @endauth
            </div>
        </div>
    </div>
</footer>
