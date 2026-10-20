@extends('layouts.cici')

@section('title', 'Estado de tu resumen · CICI 2027')

@section('content')
<div class="page-hero">
    <div class="container">
        <span class="page-hero__kicker">Consulta de postulantes · CICI 2027</span>
        <h1>Estado de tu resumen</h1>
        <p>Ingresa tu número de cédula (o el código de radicado) para consultar el dictamen del Comité Científico, ver la modalidad aprobada y descargar la plantilla correspondiente.</p>
    </div>
</div>

<section class="section section--alt">
    <div class="container container--narrow">

        @if (session('submitted_code'))
            <div class="alert-banner" role="status">
                <h2 style="font-size: 1.2rem; margin-bottom: .35rem; color: #065f46;">
                    ¡Resumen recibido con éxito! Código de radicado: <strong>{{ session('submitted_code') }}</strong>
                </h2>
                <p style="margin: 0; font-size: .96rem;">
                    Tu propuesta quedó registrada para revisión del Comité Científico. Puedes volver a esta página en cualquier momento e ingresar únicamente tu <strong>número de cédula</strong> para consultar si fue aprobada como ponencia oral o como póster.
                </p>
            </div>
        @endif

        <div class="form-card">
            <h2 class="form-card__title" style="margin-bottom: .75rem;">
                Consultar por número de cédula
            </h2>
            <p style="color: var(--suave); font-size: .95rem; margin-bottom: 1.25rem;">
                No necesitas usuario ni contraseña. Ingresa el número de documento con el que registraste tu resumen (o el código <code>CICI-2027-XXXX</code>).
            </p>

            <form method="POST" action="{{ route('submissions.lookup') }}">
                @csrf
                <div class="form-grid form-grid--2" style="align-items: end;">
                    <div class="field">
                        <label class="field__label" for="document">Número de cédula o código de radicado <span class="field__req">*</span></label>
                        <input class="field__input" type="text" id="document" name="document"
                               value="{{ old('document', $searchedDoc ?? '') }}"
                               placeholder="Ej. 1085123456 o CICI-2027-0001" required>
                        @error('document') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div style="display: flex; gap: .75rem; flex-wrap: wrap;">
                        <button type="submit" class="btn btn--primary" style="width: 100%; justify-content: center;">
                            Consultar estado
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if ($submissions !== null)
            @forelse ($submissions as $s)
                @php
                    $cardClass = match ($s->status) {
                        'approved' => 'status-card--approved',
                        'rejected' => 'status-card--rejected',
                        default    => 'status-card--pending',
                    };
                    $badgeClass = match ($s->status) {
                        'approved' => 'status-badge--approved',
                        'rejected' => 'status-badge--rejected',
                        default    => 'status-badge--pending',
                    };
                    $isReviewed = in_array($s->status, ['approved', 'rejected'], true);
                    $isUnderReview = $s->status === 'under_review' || $isReviewed;
                @endphp

                <article class="status-card {{ $cardClass }}">
                    <div class="status-card__top">
                        <span class="status-code">{{ $s->tracking_code }}</span>
                        <span class="status-badge {{ $badgeClass }}">
                            {{ $s->statusLabel() }}
                            @if ($s->isApproved() && $s->modalityLabel())
                                · Modalidad: {{ $s->modalityLabel() }}
                            @endif
                        </span>
                    </div>

                    <h2 style="font-size: 1.35rem; margin-bottom: .5rem;">{{ $s->title }}</h2>

                    {{-- Barra de progreso del trámite --}}
                    <div class="status-timeline" aria-label="Etapa del proceso de revisión">
                        <div class="status-step is-done">
                            <div class="status-step__bar"></div>
                            <span>1. Resumen recibido</span>
                            <small>{{ $s->created_at?->format('d/m/Y') }}</small>
                        </div>

                        <div class="status-step {{ $isUnderReview ? 'is-done' : '' }}">
                            <div class="status-step__bar"></div>
                            <span>2. Evaluación Comité Científico</span>
                            <small>{{ $isUnderReview ? 'Revisado' : 'En turno de evaluación' }}</small>
                        </div>

                        <div class="status-step {{ $s->isApproved() ? 'is-done is-approved' : ($s->status === 'rejected' ? 'is-done is-rejected' : '') }}">
                            <div class="status-step__bar"></div>
                            <span>3. Dictamen y modalidad</span>
                            <small>
                                @if ($s->isApproved())
                                    Aprobado ({{ $s->modalityLabel() }})
                                @elseif ($s->status === 'rejected')
                                    No aprobado
                                @else
                                    Pendiente de dictamen
                                @endif
                            </small>
                        </div>
                    </div>

                    <dl class="status-meta">
                        <div>
                            <dt>Autor principal</dt>
                            <dd>{{ $s->name }} ({{ $s->document_type }} {{ $s->document }})</dd>
                        </div>
                        <div>
                            <dt>Institución</dt>
                            <dd>{{ $s->institution ?: '—' }}@if ($s->city) · {{ $s->city }}@endif</dd>
                        </div>
                        <div>
                            <dt>Eje temático</dt>
                            <dd>{{ $s->axis?->name ?? 'Sin eje asignado' }}</dd>
                        </div>
                        <div>
                            <dt>Modalidad postulada vs. aprobada</dt>
                            <dd>
                                Postulada: {{ $s->requestedModalityLabel() }}
                                @if ($s->isApproved() && $s->modalityLabel())
                                    <br><span style="color: var(--verde-texto);">Aprobada por el Comité: {{ $s->modalityLabel() }}</span>
                                @endif
                            </dd>
                        </div>
                        @if ($s->coauthors)
                            <div>
                                <dt>Coautores</dt>
                                <dd>{{ $s->coauthors }}</dd>
                            </div>
                        @endif
                        @if ($s->score !== null)
                            <div>
                                <dt>Calificación del Comité</dt>
                                <dd>{{ $s->score }} / 100 puntos</dd>
                            </div>
                        @endif
                    </dl>

                    @if ($s->review_comment)
                        <div class="status-feedback">
                            <strong style="display:block; color:var(--tinta); margin-bottom:.3rem;">
                                Observaciones del Comité Científico:
                            </strong>
                            <p style="margin:0; white-space: pre-line;">{{ $s->review_comment }}</p>
                        </div>
                    @endif

                    {{-- Si fue aprobado como PÓSTER: descarga directa de la plantilla de póster --}}
                    @if ($s->isPoster())
                        <div class="status-box-action">
                            <div>
                                <strong style="display:block; color:#065f46; font-size:1.05rem;">
                                    ¡Tu resumen fue aprobado en modalidad PÓSTER!
                                </strong>
                                <span style="font-size:.92rem; color:var(--texto);">
                                    Descarga ahora la plantilla oficial de póster científico (90 × 120 cm) para preparar tu presentación y realiza tu inscripción con la Universidad Mariana.
                                </span>
                            </div>
                            <div style="display:flex; flex-wrap:wrap; gap:.6rem; flex-shrink:0;">
                                <a class="btn btn--accent btn--sm"
                                   href="{{ config('cici.poster_template_url') ?: route('documents.show', 'plantilla-poster') }}">
                                    Descargar plantilla de póster
                                </a>
                                <a class="btn btn--primary btn--sm"
                                   href="https://fenix.umariana.edu.co/sgacampus/services/inscripciones/home"
                                   target="_blank" rel="noopener">
                                    Ir a inscripción (U. Mariana)
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Si fue aprobado como PONENCIA ORAL: descarga de plantilla oral --}}
                    @if ($s->isOral())
                        <div class="status-box-action">
                            <div>
                                <strong style="display:block; color:#065f46; font-size:1.05rem;">
                                    ¡Tu resumen fue aprobado en modalidad PONENCIA ORAL!
                                </strong>
                                <span style="font-size:.92rem; color:var(--texto);">
                                    Descarga la guía y plantilla de diapositivas para ponencia oral y formaliza tu inscripción con la Universidad Mariana.
                                </span>
                            </div>
                            <div style="display:flex; flex-wrap:wrap; gap:.6rem; flex-shrink:0;">
                                <a class="btn btn--primary btn--sm"
                                   href="{{ config('cici.oral_template_url') ?: route('documents.show', 'plantilla-oral') }}">
                                    Descargar plantilla de ponencia oral
                                </a>
                                <a class="btn btn--outline btn--sm"
                                   href="https://fenix.umariana.edu.co/sgacampus/services/inscripciones/home"
                                   target="_blank" rel="noopener">
                                    Ir a inscripción (U. Mariana)
                                </a>
                            </div>
                        </div>
                    @endif
                </article>
            @empty
                <div class="form-card" style="text-align: center;">
                    <h2 style="font-size: 1.25rem; margin-bottom: .5rem;">No encontramos resúmenes registrados con «{{ $searchedDoc }}»</h2>
                    <p style="color: var(--suave); max-width: 520px; margin: 0 auto 1.25rem;">
                        Verifica que hayas escrito tu número de cédula sin errores, o postula tu resumen si aún no lo has enviado.
                    </p>
                    <a href="{{ route('submissions.public.create') }}" class="btn btn--primary btn--sm">
                        Postular y enviar mi resumen
                    </a>
                </div>
            @endforelse
        @endif

        <div class="card" style="margin-top: 1.5rem; background: var(--fondo); border: 1px dashed var(--borde);">
            <h3 style="font-size: 1rem; margin-bottom: .35rem;">Cédulas de demostración disponibles para probar</h3>
            <p style="font-size: .9rem; color: var(--suave); margin: 0;">
                Puedes probar la consulta instantánea con las siguientes cédulas de ejemplo cargadas en el sistema:
                <a href="{{ route('submissions.status', ['documento' => '1085200101']) }}"><strong>1085200101</strong> (Aprobado como Póster)</a> ·
                <a href="{{ route('submissions.status', ['documento' => '1085200202']) }}"><strong>1085200202</strong> (Aprobado como Ponencia Oral)</a> ·
                <a href="{{ route('submissions.status', ['documento' => '1085200303']) }}"><strong>1085200303</strong> (En evaluación)</a>
            </p>
        </div>
    </div>
</section>
@endsection
