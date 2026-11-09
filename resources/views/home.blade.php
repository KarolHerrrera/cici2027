@extends('layouts.cici')

@section('content')

@php
    // colores del logo para los tres pilares
    $tonos = ['azul', 'verde', 'naranja'];
@endphp

{{-- ================= INICIO ================= --}}
<section class="hero" id="inicio">
    <div class="container hero__inner">
        <span class="hero__badge">{{ $evento['edicion'] ?? 'IV edición · 2027' }}</span>

        <img class="hero__logo" src="{{ asset('images/logo-blanco.png') }}"
             alt="{{ $evento['nombre'] }}, CICI {{ $evento['anio'] ?? '2027' }}" width="1049" height="316">

        <p class="hero__dates">{{ $evento['fechas'] }}</p>
        <p class="hero__lead">{{ $evento['lema'] }}</p>

        <div class="hero__actions" style="max-width: 680px;">
            <a href="{{ $enlaces['inscripciones'] }}" class="btn btn--accent" target="_blank" rel="noopener">Realizar inscripción</a>
            <a href="{{ $enlaces['resumenes'] }}"
               @if (!empty($enlaces['resumenes_externo'])) target="_blank" rel="noopener" @endif
               class="btn btn--light">Enviar resumen</a>
            <a href="{{ route('submissions.status') }}" class="btn btn--ghost">Consultar estado con cédula</a>
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
            <x-section-title title="La próxima edición ya está tomando forma"
                subtitle="La Facultad de Ingeniería de la Universidad Mariana, junto con sus programas de Ingeniería Ambiental, Civil, de Procesos, Mecatrónica y de Sistemas y los programas de posgrado en ingeniería, organiza el {{ $evento['nombre'] }} – CICI {{ $evento['anio'] ?? '2027' }}." />

            <p style="color: var(--texto); margin-bottom: 1.25rem;">
                El CICI {{ $evento['anio'] ?? '2027' }} está dirigido a estudiantes, docentes, investigadores, profesionales y comunidad académica interesada en las diferentes áreas de la ingeniería y disciplinas afines.
            </p>

            <ul class="tags" aria-label="Dirigido a">
                <li>Estudiantes</li><li>Docentes</li><li>Investigadores</li><li>Profesionales</li><li>Comunidad académica</li>
            </ul>
        </div>

        <ul class="pillars">
            @foreach ($pilares as $i => $pilar)
                <li class="pillar pillar--{{ $tonos[$i] }}">
                    <h3>{{ $pilar['titulo'] }}</h3>
                    <p><strong>{{ $pilar['lema'] }}</strong></p>
                    @if (!empty($pilar['descripcion']))
                        <p style="font-style: normal; font-size: .95rem; margin-top: .35rem;">{{ $pilar['descripcion'] }}</p>
                    @endif
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
        <p class="note">La fecha para la entrega de artículos completos será informada posteriormente.</p>
    </div>
</section>

{{-- ================= EJES TEMÁTICOS ================= --}}
<section class="section" id="ejes">
    <div class="container">
        <x-section-title title="Explora nuestros ejes temáticos"
            subtitle="Seis áreas temáticas definidas para la cuarta edición del Congreso." />

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
        <x-section-title title="Ponentes invitados"
            subtitle="Conoce a los profesionales e investigadores invitados al IV Congreso Internacional de Ciencia e Ingeniería – CICI 2027." />

        <div class="grid grid--3">
            @foreach ($ponentes as $idx => $p)
                <article class="card speaker" style="display:flex; flex-direction:column;">
                    @if (!empty($p['foto']))
                        <img class="speaker__photo" src="{{ $p['foto_url'] ?? asset('storage/'.$p['foto']) }}" alt="Foto de {{ $p['nombre'] }}">
                    @else
                        <div class="speaker__avatar" aria-hidden="true">
                            {{ collect(explode(' ', preg_replace('/^(Dr\.|Dra\.|Ing\.|Mg\.|Ph\.D\.)\s+/', '', $p['nombre'])))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}
                        </div>
                    @endif
                    <div>
                        <span class="speaker__country">{{ $p['pais'] }}</span>
                    </div>
                    <h3>{{ $p['nombre'] }}</h3>
                    <p class="speaker__area">{{ $p['area'] }}</p>
                    @if (!empty($p['cargo']))
                        <p class="card__meta">{{ $p['cargo'] }}</p>
                    @endif
                    <p class="card__meta" style="flex:1;">{{ $p['institucion'] }}@if (!empty($p['ciudad']))<br>{{ $p['ciudad'] }}@endif</p>

                    @if (!empty($p['bio']) || !empty($p['charla']))
                        <details style="margin-top: 1rem; text-align: left; border-top: 1px solid var(--borde); padding-top: .75rem;">
                            <summary style="cursor: pointer; font-weight: 700; font-size: .9rem; color: var(--azul-claro);">
                                Ver perfil completo →
                            </summary>
                            <div style="margin-top: .65rem; font-size: .9rem; color: var(--texto); line-height: 1.55;">
                                @if (!empty($p['bio']))
                                    <p style="margin-bottom: .5rem;">{{ $p['bio'] }}</p>
                                @endif
                                @if (!empty($p['charla']))
                                    <p style="margin: 0; color: var(--tinta);"><strong>Ponencia:</strong> «{{ $p['charla'] }}»</p>
                                @endif
                            </div>
                        </details>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= PROGRAMA ================= --}}
<section class="section section--dark" id="programa">
    <div class="container">
        <x-section-title title="Programa del Congreso"
            subtitle="Agenda académica por jornada, horario y espacio del CICI 2027." />

        <div class="grid grid--3">
            @foreach ($jornadas as $j)
                <article class="day">
                    <span class="day__name">{{ $j['dia'] }}</span>
                    <span class="day__num">{{ $j['numero'] }}</span>
                    <span class="day__month">{{ $j['mes'] }} de 2027</span>
                    <p>Conferencias magistrales, ponencias orales, pósteres y paneles académicos.</p>
                </article>
            @endforeach
        </div>

        @if (!empty($programaPorDia) && count($programaPorDia) > 0)
            <div style="margin-top: 2.25rem; display: grid; gap: 1.25rem;">
                @foreach ($programaPorDia as $dayLabel => $items)
                    <details class="card" style="background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.15); color: #fff;" @if ($loop->first) open @endif>
                        <summary style="cursor: pointer; font-weight: 700; font-size: 1.05rem; color: #fff; display: flex; justify-content: space-between; align-items: center;">
                            <span>{{ $dayLabel }}</span>
                            <span style="font-size: .8rem; opacity: .85;">{{ count($items) }} bloques programados ▾</span>
                        </summary>
                        <div style="margin-top: 1rem; display: grid; gap: .85rem;">
                            @foreach ($items as $item)
                                <div style="padding: .85rem 1rem; background: rgba(15, 23, 42, .55); border-radius: .6rem; border-left: 3px solid var(--dorado);">
                                    <div style="display: flex; flex-wrap: wrap; gap: .6rem; font-size: .8rem; color: #cbd5e1; margin-bottom: .25rem;">
                                        <strong style="color: var(--dorado);">{{ $item->start_time }}{{ $item->end_time ? ' – ' . $item->end_time : '' }}</strong>
                                        <span>· {{ $item->activityTypeLabel() }}</span>
                                        @if ($item->location)
                                            <span>· {{ $item->location }}</span>
                                        @endif
                                    </div>
                                    <div style="font-weight: 700; font-size: .96rem; color: #fff;">{{ $item->title }}</div>
                                    @if ($item->speaker_name)
                                        <div style="font-size: .86rem; color: #93c5fd; margin-top: .2rem;">{{ $item->speaker_name }}</div>
                                    @endif
                                    @if ($item->description)
                                        <div style="font-size: .84rem; color: #cbd5e1; margin-top: .2rem;">{{ $item->description }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </div>
        @endif

        <p class="note note--dark" style="margin-top: 1.5rem; font-size: .88rem; color: rgba(255,255,255,.78);">
            La programación está sujeta a ajustes por parte del comité organizador y académico del CICI 2027.
        </p>
    </div>
</section>

{{-- ================= INSCRIPCIONES ================= --}}
<section class="section" id="inscripciones">
    <div class="container">
        <x-section-title title="Participa en el CICI 2027"
            subtitle="Estos son los valores de inscripción establecidos para el IV Congreso Internacional de Ciencia e Ingeniería – CICI 2027." />

        <div class="grid grid--4 prices">
            @foreach ($tarifas as $t)
                <article class="card price {{ $t['destacada'] ? 'is-featured' : '' }}">
                    @if ($t['destacada'])<span class="price__flag">Ponencia nacional · póster</span>@endif
                    <h3>{{ $t['nombre'] }}</h3>
                    <p class="card__meta">{{ $t['detalle'] }}</p>
                    <p class="price__value">${{ number_format($t['valor'], 0, ',', '.') }} <small style="font-size:.5em; font-weight:700;">COP</small></p>
                </article>
            @endforeach
        </div>

        <p class="note" style="margin-top:1.75rem;">
            <strong>Importante:</strong> el pago de inscripción se realiza por proyecto. Cada proyecto podrá estar integrado por un máximo de 7 autores.
            Para conocer las condiciones, requisitos, modalidades de participación y demás información relacionada con la inscripción, consulte los Términos de Referencia del Congreso.
        </p>

        <div class="actions" style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center;">
            <a href="{{ $enlaces['inscripciones'] }}" class="btn btn--primary" target="_blank" rel="noopener">Realizar inscripción →</a>
            @if (!empty($enlaces['terminos']))
                <a href="{{ $enlaces['terminos'] }}" class="btn btn--outline" target="_blank" rel="noopener">Consultar términos de referencia (PDF) →</a>
            @endif
        </div>
    </div>
</section>

{{-- ================= CONVOCATORIA ================= --}}
<section class="cta" id="convocatoria">
    <div class="container cta__inner">
        <div>
            <h2>¿Tienes una investigación para compartir?</h2>
            <p>La convocatoria para la recepción de resúmenes inicia el 1 de octubre de 2026. Si ya enviaste tu resumen, consulta el dictamen del Comité Científico con tu número de cédula.</p>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:.85rem; flex-shrink:0;">
            <a href="{{ $enlaces['resumenes'] }}"
               @if (!empty($enlaces['resumenes_externo'])) target="_blank" rel="noopener" @endif
               class="btn btn--light">Enviar resumen</a>
            <a href="{{ route('submissions.status') }}" class="btn btn--ghost">Consultar con mi cédula</a>
        </div>
    </div>
</section>

{{-- ================= DOCUMENTOS ================= --}}
<section class="section" id="documentos">
    <div class="container container--narrow">
        <x-section-title title="Documentos del CICI 2027"
            subtitle="Aquí se publican los documentos oficiales del Congreso a medida que son habilitados por la organización." />

        <ul class="docs">
            @foreach ($documentos as $d)
                <li class="docs__item">
                    <div>
                        <span class="docs__type">{{ $d['tipo'] }}</span>
                        <span class="docs__title">{{ $d['titulo'] }}</span>
                    </div>
                    @if ($d['url'])
                        <a href="{{ $d['url'] }}"
                           @if (!empty($d['externo'])) target="_blank" rel="noopener" @endif
                           class="btn btn--outline btn--sm">Abrir</a>
                    @else
                        <span class="badge">Próximamente</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ================= NOTICIAS Y NOVEDADES ================= --}}
<section class="section section--alt" id="noticias">
    <div class="container">
        <x-section-title title="Noticias y novedades"
            subtitle="Actualidad y comunicados oficiales de la cuarta edición del Congreso." />

        <div class="grid grid--3">
            @foreach ($noticias as $n)
                <article class="card">
                    <span class="docs__type" style="margin-bottom:.35rem;">{{ $n['etiqueta'] }}</span>
                    <h3>{{ $n['titulo'] }}</h3>
                    <p class="card__meta">{{ $n['resumen'] }}</p>
                    @if (!empty($n['url']))
                        <div style="margin-top: .75rem;">
                            <a href="{{ $n['url'] }}" target="_blank" rel="noopener" style="font-size: .85rem; font-weight: 700; color: var(--azul-claro);">
                                Más información →
                            </a>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= COMITÉ ORGANIZADOR Y CIENTÍFICO (RF-09) ================= --}}
@if (!empty($comitePublico) && count($comitePublico) > 0)
<section class="section" id="comite">
    <div class="container">
        <x-section-title title="Comité Directivo, Científico y Organizador"
            subtitle="Dirección académica y coordinación por ejes temáticos de la Facultad de Ingeniería de la Universidad Mariana." />

        <div class="grid grid--3">
            @foreach ($comitePublico as $miembro)
                <article class="card">
                    <span class="docs__type" style="margin-bottom:.35rem;">{{ $miembro->committeeTypeLabel() }}</span>
                    <h3 style="font-size: 1.05rem;">{{ $miembro->name }}</h3>
                    <p class="card__meta" style="margin-top: .25rem; color: var(--tinta); font-weight: 600;">{{ $miembro->role }}</p>
                    <p class="card__meta" style="margin-top: .25rem;">
                        {{ $miembro->institution }}@if ($miembro->program_or_unit) · {{ $miembro->program_or_unit }}@endif
                    </p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= HISTÓRICO Y EDICIONES ANTERIORES (RF-10) ================= --}}
@if (!empty($galeria) && count($galeria) > 0)
<section class="section section--alt" id="trayectoria">
    <div class="container">
        <x-section-title title="Trayectoria del Congreso CICI"
            subtitle="Recorrido histórico de las ediciones anteriores organizadas por la Facultad de Ingeniería." />

        <div class="grid grid--3">
            @foreach ($galeria as $item)
                <article class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                        <span class="docs__type">{{ $item->edition }}</span>
                        <span class="badge">{{ $item->year }}</span>
                    </div>
                    <h3>{{ $item->title }}</h3>
                    <p class="card__meta" style="margin-top:.4rem;">{{ $item->description }}</p>
                    @if ($item->participants_count || $item->papers_count)
                        <div style="margin-top:.85rem; padding-top:.65rem; border-top:1px solid var(--borde); font-size:.82rem; font-weight:700; color:var(--azul);">
                            {{ $item->participants_count }} @if ($item->participants_count && $item->papers_count) · @endif {{ $item->papers_count }}
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= ALIADOS E INSTITUCIONES PARTICIPANTES (RF-11) ================= --}}
@if (!empty($aliados) && count($aliados) > 0)
<section class="section" id="aliados">
    <div class="container">
        <x-section-title title="Instituciones y Universidades Participantes"
            subtitle="Red académica nacional e internacional vinculada al IV Congreso Internacional de Ciencia e Ingeniería." />

        <div class="grid grid--3">
            @foreach ($aliados as $aliado)
                <article class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.35rem;">
                        <span class="docs__type">{{ $aliado->categoryLabel() }}</span>
                        @if ($aliado->country)
                            <span style="font-size:.78rem; font-weight:600; color:var(--texto-suave);">{{ $aliado->country }}</span>
                        @endif
                    </div>
                    <h3 style="font-size:1.02rem;">{{ $aliado->name }}</h3>
                    @if ($aliado->description)
                        <p class="card__meta" style="margin-top:.35rem;">{{ $aliado->description }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= PREGUNTAS FRECUENTES FAQ (RF-12) ================= --}}
@if (!empty($faqs) && count($faqs) > 0)
<section class="section section--alt" id="preguntas-frecuentes">
    <div class="container container--narrow">
        <x-section-title title="Preguntas Frecuentes"
            subtitle="Respuestas rápidas sobre postulación de resúmenes, consulta por cédula, modalidades e inscripción." />

        <div style="display: grid; gap: .85rem;">
            @foreach ($faqs as $faq)
                <details class="card" style="padding: 1.1rem 1.35rem;">
                    <summary style="cursor: pointer; font-weight: 700; color: var(--tinta); font-size: 1rem;">
                        {{ $faq->question }}
                    </summary>
                    <p style="margin: .75rem 0 0; color: var(--texto); font-size: .94rem; line-height: 1.6;">
                        {{ $faq->answer }}
                    </p>
                </details>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= CONTACTO (RF-13) ================= --}}
<section class="section" id="contacto">
    <div class="container">
        <x-section-title title="Conéctate con CICI 2027"
            subtitle="Canales oficiales de atención y mesa de ayuda para ponentes, estudiantes y asistentes." />

        <div class="grid grid--3 contact" style="margin-bottom: 2.5rem;">
            <a class="card contact__item" href="https://{{ $contacto['web'] }}" target="_blank" rel="noopener">
                <span class="card__meta">Sitio web</span>
                <span class="contact__value">{{ $contacto['web'] }}</span>
            </a>
            <a class="card contact__item" href="mailto:{{ $contacto['correo'] }}">
                <span class="card__meta">Correo del Congreso</span>
                <span class="contact__value">{{ $contacto['correo'] }}</span>
            </a>
            <a class="card contact__item" href="tel:+57{{ preg_replace('/\D/', '', $contacto['telefono']) }}">
                <span class="card__meta">Teléfono</span>
                <span class="contact__value">{{ $contacto['telefono'] }}</span>
            </a>
            @if (!empty($enlaces['whatsapp']))
                <a class="card contact__item" href="{{ $enlaces['whatsapp'] }}" target="_blank" rel="noopener">
                    <span class="card__meta">WhatsApp</span>
                    <span class="contact__value">Escríbenos</span>
                </a>
            @endif
        </div>

        {{-- Formulario público de consultas y contacto (RF-13) --}}
        <div class="card" style="max-width: 780px; margin: 0 auto; padding: 2rem;">
            <h3 style="margin-top: 0; margin-bottom: .35rem;">Envíanos tu consulta</h3>
            <p class="card__meta" style="margin-bottom: 1.25rem;">
                ¿Tienes dudas sobre el envío de tu resumen, modalidades o el pago en SGA Campus? Escríbenos y el Comité Organizador te responderá por correo electrónico.
            </p>

            @if (session('contact_success'))
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: .9rem 1.1rem; border-radius: .6rem; margin-bottom: 1.25rem; font-weight: 600;">
                    {{ session('contact_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" style="display: grid; gap: 1rem;">
                @csrf
                <div class="grid grid--2" style="gap: 1rem;">
                    <div>
                        <label style="display:block; font-size:.85rem; font-weight:700; margin-bottom:.3rem;">Nombre completo *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               style="width:100%; padding:.65rem .85rem; border:1px solid var(--borde); border-radius:.5rem; font-size:.95rem;">
                        @error('name') <small style="color:#dc2626;">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:.85rem; font-weight:700; margin-bottom:.3rem;">Correo electrónico *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               style="width:100%; padding:.65rem .85rem; border:1px solid var(--borde); border-radius:.5rem; font-size:.95rem;">
                        @error('email') <small style="color:#dc2626;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="grid grid--3" style="gap: 1rem;">
                    <div>
                        <label style="display:block; font-size:.85rem; font-weight:700; margin-bottom:.3rem;">Teléfono / Celular</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               style="width:100%; padding:.65rem .85rem; border:1px solid var(--borde); border-radius:.5rem; font-size:.95rem;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.85rem; font-weight:700; margin-bottom:.3rem;">Institución / Universidad</label>
                        <input type="text" name="institution" value="{{ old('institution') }}"
                               style="width:100%; padding:.65rem .85rem; border:1px solid var(--borde); border-radius:.5rem; font-size:.95rem;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.85rem; font-weight:700; margin-bottom:.3rem;">Tema de consulta *</label>
                        <select name="category" required
                                style="width:100%; padding:.65rem .85rem; border:1px solid var(--borde); border-radius:.5rem; font-size:.95rem; background:#fff;">
                            <option value="resumenes">Envío y evaluación de resúmenes</option>
                            <option value="inscripciones">Inscripción y tarifas</option>
                            <option value="pagos">Pagos en SGA Campus</option>
                            <option value="certificados">Certificados y memorias</option>
                            <option value="general">Información general</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:.85rem; font-weight:700; margin-bottom:.3rem;">Asunto *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                           style="width:100%; padding:.65rem .85rem; border:1px solid var(--borde); border-radius:.5rem; font-size:.95rem;">
                    @error('subject') <small style="color:#dc2626;">{{ $message }}</small> @enderror
                </div>

                <div>
                    <label style="display:block; font-size:.85rem; font-weight:700; margin-bottom:.3rem;">Mensaje o inquietud *</label>
                    <textarea name="message" rows="3" required minlength="10"
                              style="width:100%; padding:.65rem .85rem; border:1px solid var(--borde); border-radius:.5rem; font-size:.95rem;">{{ old('message') }}</textarea>
                    @error('message') <small style="color:#dc2626;">{{ $message }}</small> @enderror
                </div>

                <div>
                    <button type="submit" class="btn btn--primary">Enviar consulta al Comité</button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
