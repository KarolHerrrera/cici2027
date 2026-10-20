@extends('layouts.cici')

@section('content')

@php
    // colores del logo para los tres pilares
    $tonos = ['azul', 'verde', 'naranja'];
@endphp

{{-- ================= INICIO ================= --}}
<section class="hero" id="inicio">
    <div class="container hero__inner">
        <span class="hero__badge">IV edición</span>

        <img class="hero__logo" src="{{ asset('images/logo-blanco.png') }}"
             alt="IV Congreso Internacional de Ciencia e Ingeniería, CICI 2027" width="1049" height="316">

        <p class="hero__dates">{{ $evento['fechas'] }}</p>
        <p class="hero__lead">{{ $evento['lema'] }}</p>

        <div class="hero__actions" style="max-width: 680px;">
            <a href="{{ $enlaces['inscripciones'] }}" class="btn btn--accent" target="_blank" rel="noopener">Inscribirme (U. Mariana)</a>
            <a href="{{ $enlaces['resumenes'] }}" class="btn btn--light">Postular resumen</a>
            <a href="{{ route('submissions.status') }}" class="btn btn--ghost">Consultar con mi cédula</a>
        </div>
    </div>
</section>

{{-- Franja de datos clave que se superpone al inicio --}}
<div class="container facts">
    <div class="facts__card">
        <div class="facts__count">
            <span class="facts__label">Faltan para el congreso</span>
            <div class="countdown" id="countdown" data-fecha="{{ $evento['inicio'] }}" role="timer" aria-label="Tiempo restante para el congreso">
                <div><strong data-unit="dias">--</strong><span>días</span></div>
                <div><strong data-unit="horas">--</strong><span>horas</span></div>
                <div><strong data-unit="minutos">--</strong><span>min</span></div>
                <div><strong data-unit="segundos">--</strong><span>seg</span></div>
            </div>
        </div>
        <div class="facts__item">
            <span class="facts__label">Modalidad</span>
            <strong>{{ $evento['modalidad'] }}</strong>
        </div>
        <div class="facts__item">
            <span class="facts__label">Cierre de resúmenes</span>
            <strong>{{ $evento['cierre_resumenes'] }}</strong>
        </div>
        <div class="facts__item">
            <span class="facts__label">Organiza</span>
            <strong>Facultad de Ingeniería, Universidad Mariana</strong>
        </div>
    </div>
</div>

{{-- ================= CONGRESO ================= --}}
<section class="section" id="congreso">
    <div class="container split">
        <div>
            <x-section-title title="Ciencia e ingeniería al servicio del territorio"
                subtitle="La Facultad de Ingeniería de la Universidad Mariana, con sus programas de pregrado y posgrado, organiza este espacio para compartir investigación, innovación y soluciones para la región." />

            <ul class="tags" aria-label="Dirigido a">
                <li>Estudiantes</li><li>Docentes</li><li>Investigadores</li><li>Profesionales</li><li>Comunidad académica</li>
            </ul>
        </div>

        <ul class="pillars">
            @foreach ($pilares as $i => $pilar)
                <li class="pillar pillar--{{ $tonos[$i] }}">
                    <h3>{{ $pilar['titulo'] }}</h3>
                    <p>{{ $pilar['lema'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ================= FECHAS ================= --}}
<section class="section section--alt" id="fechas">
    <div class="container">
        <x-section-title title="Fechas importantes"
            subtitle="Marca estas fechas para no perder ninguna etapa del proceso." />

        <ol class="steps">
            @foreach ($fechas as $item)
                <li class="step {{ $item['destacada'] ? 'is-featured' : '' }}">
                    <span class="step__dot"></span>
                    <span class="step__date">{{ $item['fecha'] }}</span>
                    <span class="step__title">{{ $item['titulo'] }}</span>
                </li>
            @endforeach
        </ol>
        <p class="note">La fecha para la entrega de artículos completos se anunciará más adelante.</p>
    </div>
</section>

{{-- ================= EJES TEMÁTICOS ================= --}}
<section class="section" id="ejes">
    <div class="container">
        <x-section-title title="Ejes temáticos"
            subtitle="Seis áreas para presentar y discutir trabajos de investigación e innovación." />

        <ol class="axes">
            @foreach ($ejes as $i => $eje)
                <li class="axes__row {{ $eje['transversal'] ? 'is-transversal' : '' }}">
                    <span class="axes__n">{{ $eje['transversal'] ? '+' : $i + 1 }}</span>
                    <h3>
                        {{ $eje['titulo'] }}
                        @if ($eje['transversal'])<span class="pill">Transversal</span>@endif
                    </h3>
                    <span class="axes__lines">{{ $eje['lineas'] }} {{ $eje['lineas'] === 1 ? 'línea temática' : 'líneas temáticas' }}</span>
                    @if (!empty($eje['temas']))
                        <div class="axes__topics">
                            @foreach ($eje['temas'] as $tema)
                                <span class="axes__topic-tag">{{ $tema }}</span>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ================= PONENTES ================= --}}
<section class="section section--alt" id="ponentes">
    <div class="container">
        <x-section-title title="Ponentes magistrales invitados"
            subtitle="Investigadores y profesionales de Colombia, México, Estados Unidos y Ecuador." />

        <div class="grid grid--3">
            @foreach ($ponentes as $p)
                <article class="card speaker">
                    @if (!empty($p['foto']))
                        <img class="speaker__photo" src="{{ asset('storage/'.$p['foto']) }}" alt="Foto de {{ $p['nombre'] }}">
                    @else
                        <div class="speaker__avatar" aria-hidden="true">
                            {{ collect(explode(' ', preg_replace('/^(Dr\.|Dra\.|Ing\.|Mg\.|Ph\.D\.)\s+/', '', $p['nombre'])))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}
                        </div>
                    @endif
                    <span class="speaker__country">{{ $p['pais'] }}</span>
                    <h3>{{ $p['nombre'] }}</h3>
                    <p class="speaker__area">{{ $p['area'] }}</p>
                    @if (!empty($p['cargo']))
                        <p class="card__meta">{{ $p['cargo'] }}</p>
                    @endif
                    <p class="card__meta">{{ $p['institucion'] }}@if (!empty($p['ciudad']))<br>{{ $p['ciudad'] }}@endif</p>
                    @if (!empty($p['charla']))
                        <p class="card__meta" style="margin-top:.6rem; font-style:italic; color:var(--tinta);">«{{ $p['charla'] }}»</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= PROGRAMA ================= --}}
<section class="section section--dark" id="programa">
    <div class="container">
        <x-section-title title="Programa"
            subtitle="El programa detallado está en proceso de definición. Lo publicaremos aquí cuando esté listo." />

        <div class="grid grid--3">
            @foreach ($jornadas as $j)
                <article class="day">
                    <span class="day__name">{{ $j['dia'] }}</span>
                    <span class="day__num">{{ $j['numero'] }}</span>
                    <span class="day__month">{{ $j['mes'] }} de 2027</span>
                    <p>Programación por confirmar</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= INSCRIPCIONES ================= --}}
<section class="section" id="inscripciones">
    <div class="container">
        <x-section-title title="Inscripciones"
            subtitle="La inscripción oficial al congreso se realiza con la Universidad Mariana. El pago se realiza por proyecto (valores en pesos colombianos)." />

        <div class="grid grid--4 prices">
            @foreach ($tarifas as $t)
                <article class="card price {{ $t['destacada'] ? 'is-featured' : '' }}">
                    @if ($t['destacada'])<span class="price__flag">Más elegida</span>@endif
                    <h3>{{ $t['nombre'] }}</h3>
                    <p class="card__meta">{{ $t['detalle'] }}</p>
                    <p class="price__value">${{ number_format($t['valor'], 0, ',', '.') }}</p>
                </article>
            @endforeach
        </div>

        <div class="actions" style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center;">
            <a href="{{ $enlaces['inscripciones'] }}" class="btn btn--primary" target="_blank" rel="noopener">Inscribirse en Universidad Mariana</a>
            <a href="{{ $enlaces['resumenes'] }}" class="btn btn--outline">Postularme como ponente (Enviar resumen)</a>
        </div>
    </div>
</section>

{{-- ================= CONVOCATORIA ================= --}}
<section class="cta" id="convocatoria">
    <div class="container cta__inner">
        <div>
            <h2>Postulación de ponencias y pósteres</h2>
            <p>Envía tu resumen para evaluación del Comité Científico. Si ya lo enviaste, consulta con tu número de cédula si fue aprobado en modalidad oral o póster y descarga la plantilla correspondiente.</p>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:.85rem; flex-shrink:0;">
            <a href="{{ $enlaces['resumenes'] }}" class="btn btn--light">Enviar resumen</a>
            <a href="{{ route('submissions.status') }}" class="btn btn--ghost">Consultar con mi cédula</a>
        </div>
    </div>
</section>

{{-- ================= DOCUMENTOS ================= --}}
<section class="section" id="documentos">
    <div class="container container--narrow">
        <x-section-title title="Documentos y plantillas"
            subtitle="Formatos oficiales de resumen, plantilla de póster y términos de referencia del congreso." />

        <ul class="docs">
            @foreach ($documentos as $d)
                <li class="docs__item">
                    <div>
                        <span class="docs__type">{{ $d['tipo'] }}</span>
                        <span class="docs__title">{{ $d['titulo'] }}</span>
                    </div>
                    @if ($d['url'])
                        <a href="{{ $d['url'] }}" class="btn btn--outline btn--sm">Ver / Descargar</a>
                    @else
                        <span class="badge">Próximamente</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ================= CONTACTO ================= --}}
<section class="section section--alt" id="contacto">
    <div class="container">
        <x-section-title title="Contacto"
            subtitle="Resolvemos tus dudas sobre participación, inscripción y ponencias." />

        <div class="grid grid--3 contact">
            <a class="card contact__item" href="mailto:{{ $contacto['correo'] }}">
                <span class="card__meta">Correo</span>
                <span class="contact__value">{{ $contacto['correo'] }}</span>
            </a>
            <a class="card contact__item" href="tel:+57{{ preg_replace('/\D/', '', $contacto['telefono']) }}">
                <span class="card__meta">Teléfono</span>
                <span class="contact__value">{{ $contacto['telefono'] }}</span>
            </a>
            <a class="card contact__item" href="{{ $enlaces['whatsapp'] }}" target="_blank" rel="noopener">
                <span class="card__meta">WhatsApp</span>
                <span class="contact__value">Escríbenos</span>
            </a>
        </div>
    </div>
</section>

@endsection
