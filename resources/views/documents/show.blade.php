@extends('layouts.cici')

@section('title', $doc['title'])

@section('content')
<div class="page-hero">
    <div class="container">
        <span class="page-hero__kicker">{{ $doc['type'] }}</span>
        <h1>{{ $doc['title'] }}</h1>
        <p>{{ $doc['subtitle'] }}</p>
    </div>
</div>

<section class="section">
    <div class="container">

        <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:2rem;">
            <a href="{{ route('home') }}#documentos" class="btn btn--outline btn--sm">← Volver a documentos</a>
        </div>

        <div class="grid grid--3">
            <article class="card" style="display:flex; flex-direction:column; justify-content:space-between; border:1px solid var(--borde);">
                <div>
                    <img src="{{ asset('images/piezas/banner-general.png') }}"
                         alt="Banner general oficial CICI 2027"
                         style="border-radius:12px; margin-bottom:1rem; width:100%; height:auto;">
                    <h3>Banner general</h3>
                    <p class="card__meta">Pieza gráfica general del IV Congreso Internacional de Ciencia e Ingeniería – CICI 2027.</p>
                </div>
                <div style="margin-top:1.25rem;">
                    <a href="{{ asset('images/piezas/banner-general.png') }}" download class="btn btn--primary btn--sm">
                        Descargar banner general
                    </a>
                </div>
            </article>

            <article class="card" style="display:flex; flex-direction:column; justify-content:space-between; border:1px solid var(--borde);">
                <div>
                    <img src="{{ asset('images/piezas/banner-ejes-tematicos.png') }}"
                         alt="Banner de ejes temáticos CICI 2027"
                         style="border-radius:12px; margin-bottom:1rem; width:100%; height:auto;">
                    <h3>Banner de ejes temáticos</h3>
                    <p class="card__meta">Pieza gráfica con los ejes temáticos oficiales del CICI 2027.</p>
                </div>
                <div style="margin-top:1.25rem;">
                    <a href="{{ asset('images/piezas/banner-ejes-tematicos.png') }}" download class="btn btn--primary btn--sm">
                        Descargar banner de ejes
                    </a>
                </div>
            </article>

            <article class="card" style="display:flex; flex-direction:column; justify-content:space-between; background:var(--noche); color:#fff;">
                <div>
                    <img src="{{ asset('images/logo-blanco.png') }}"
                         alt="Logo oficial CICI 2027"
                         style="max-height:96px; margin:1.5rem auto 1.5rem;">
                    <h3 style="color:#fff;">Identidad visual CICI 2027</h3>
                    <p class="card__meta" style="color:rgba(255,255,255,.8);">Logotipo oficial del IV Congreso Internacional de Ciencia e Ingeniería.</p>
                </div>
                <div style="margin-top:1.25rem; display:flex; gap:.6rem; flex-wrap:wrap;">
                    <a href="{{ asset('images/logo-blanco.png') }}" download class="btn btn--light btn--sm">
                        Logo blanco
                    </a>
                    <a href="{{ asset('images/cici-sigla.png') }}" download class="btn btn--ghost btn--sm">
                        Sigla CICI
                    </a>
                </div>
            </article>
        </div>

    </div>
</section>
@endsection
