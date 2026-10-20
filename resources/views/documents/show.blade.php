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
    <div class="container container--narrow">

        <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:2rem;">
            <a href="{{ route('home') }}#documentos" class="btn btn--outline btn--sm">← Volver a documentos</a>
            <div style="display:flex; flex-wrap:wrap; gap:.65rem;">
                <button type="button" onclick="window.print()" class="btn btn--primary btn--sm">
                    Imprimir / Guardar como PDF
                </button>
                <a href="{{ route('submissions.public.create') }}" class="btn btn--accent btn--sm">
                    Postular mi resumen
                </a>
            </div>
        </div>

        @if ($doc['slug'] === 'plantilla-poster')
            <div class="form-card">
                <h2 class="form-card__title">Especificaciones técnicas del Póster Científico · CICI 2027</h2>
                <p>Si el Comité Científico aprobó tu resumen en <strong>Modalidad Póster</strong>, debes elaborar tu póster siguiendo los lineamientos institucionales de la Facultad de Ingeniería de la Universidad Mariana:</p>

                <ul style="list-style:disc; padding-left:1.5rem; margin-bottom:1.5rem; line-height:1.8;">
                    <li><strong>Dimensiones oficiales:</strong> 90 cm de ancho × 120 cm de alto (orientación vertical / retrato).</li>
                    <li><strong>Encabezado institucional:</strong> Incluir el logo de CICI 2027 y de la Universidad Mariana en la franja superior.</li>
                    <li><strong>Tipografía recomendada:</strong> Familia <em>Lato</em> o <em>Arial</em>. Título mínimo 72 pt, subtítulos 42 pt, cuerpo de texto mínimo 28 pt (legible a 1,5 metros).</li>
                    <li><strong>Estructura obligatoria:</strong>
                        <ol style="list-style:decimal; padding-left:1.5rem; margin-top:.4rem;">
                            <li>Título del trabajo, autores, afiliación institucional, ciudad/municipio y correo de contacto.</li>
                            <li>Introducción y planteamiento del problema.</li>
                            <li>Objetivos.</li>
                            <li>Metodología y materiales (preferiblemente con diagramas o esquemas visuales).</li>
                            <li>Resultados y discusión (gráficas, tablas e ilustraciones con alta resolución ≥ 300 dpi).</li>
                            <li>Conclusiones principales y referencias bibliográficas clave (normas IEEE o APA 7.ª ed.).</li>
                        </ol>
                    </li>
                </ul>

                {{-- Maqueta visual de la plantilla de póster 90x120 --}}
                <div style="border:3px solid var(--azul); border-radius:16px; overflow:hidden; background:#fff; max-width:560px; margin:2rem auto; box-shadow:var(--sombra);">
                    <div style="background:linear-gradient(120deg, var(--noche), var(--azul-claro)); color:#fff; padding:1.25rem; text-align:center;">
                        <small style="opacity:.85; font-weight:700; letter-spacing:.04em;">IV CONGRESO INTERNACIONAL DE CIENCIA E INGENIERÍA · CICI 2027</small>
                        <h3 style="color:#fff; margin:.35rem 0; font-size:1.15rem;">TÍTULO DEL TRABAJO EN MAYÚSCULAS SOSTENIDAS (MÁX. 20 PALABRAS)</h3>
                        <small style="opacity:.9;">Autor Principal¹, Coautor Segundo², Coautor Tercero¹ · ¹Universidad Mariana, Pasto, Colombia</small>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:.85rem; padding:1.15rem; font-size:.86rem; background:var(--fondo-alt);">
                        <div style="background:#fff; padding:.85rem; border-radius:10px; border:1px solid var(--borde);">
                            <strong style="color:var(--azul); display:block;">1. INTRODUCCIÓN Y OBJETIVO</strong>
                            <span>Contexto del problema de ingeniería y propósito del estudio (máx. 150 palabras).</span>
                        </div>
                        <div style="background:#fff; padding:.85rem; border-radius:10px; border:1px solid var(--borde);">
                            <strong style="color:var(--azul); display:block;">2. METODOLOGÍA</strong>
                            <span>Diagrama de flujo experimental o numérico empleado en la investigación.</span>
                        </div>
                        <div style="grid-column:1 / -1; background:#fff; padding:.95rem; border-radius:10px; border:2px dashed var(--verde);">
                            <strong style="color:var(--verde-texto); display:block;">3. RESULTADOS Y ANÁLISIS (50% DEL ÁREA DEL PÓSTER)</strong>
                            <span>Gráficas comparativas, tablas resumidas, mapas o fotografías técnicas con pie de figura.</span>
                        </div>
                        <div style="background:#fff; padding:.85rem; border-radius:10px; border:1px solid var(--borde);">
                            <strong style="color:var(--naranja-texto); display:block;">4. CONCLUSIONES</strong>
                            <span>3 a 4 viñetas concisas con los hallazgos principales.</span>
                        </div>
                        <div style="background:#fff; padding:.85rem; border-radius:10px; border:1px solid var(--borde);">
                            <strong style="color:var(--tinta); display:block;">5. REFERENCIAS Y CONTACTO</strong>
                            <span>Citas principales (IEEE / APA) y código QR opcional al artículo o contacto.</span>
                        </div>
                    </div>
                    <div style="background:var(--noche); color:#fff; padding:.6rem 1rem; font-size:.78rem; display:flex; justify-content:space-between;">
                        <span>Facultad de Ingeniería · Universidad Mariana</span>
                        <span>Dimensiones reales: 90 cm × 120 cm</span>
                    </div>
                </div>
            </div>

        @elseif ($doc['slug'] === 'formato-resumen')
            <div class="form-card">
                <h2 class="form-card__title">Estructura del Resumen para Postulación · CICI 2027</h2>
                <p>El Comité Científico evalúa todos los resúmenes recibidos para determinar su aceptación y asignar la modalidad definitiva (<strong>Ponencia oral</strong> o <strong>Póster</strong>).</p>

                <ul style="list-style:disc; padding-left:1.5rem; margin-bottom:1.5rem; line-height:1.8;">
                    <li><strong>Extensión del resumen:</strong> Entre 250 y 500 palabras.</li>
                    <li><strong>Idiomas aceptados:</strong> Español, inglés o portugués.</li>
                    <li><strong>Contenido requerido:</strong>
                        <ol style="list-style:decimal; padding-left:1.5rem; margin-top:.4rem;">
                            <li><strong>Título del trabajo:</strong> Claro, conciso (máximo 20 palabras).</li>
                            <li><strong>Autores y afiliación:</strong> Nombre completo, documento de identidad, institución, ciudad/municipio, país y correo electrónico.</li>
                            <li><strong>Eje y línea temática:</strong> Seleccionar uno de los 6 ejes del congreso.</li>
                            <li><strong>Cuerpo del resumen:</strong> Introducción/contexto, objetivo, metodología, resultados principales y conclusiones.</li>
                            <li><strong>Palabras clave:</strong> De 3 a 5 términos separados por coma.</li>
                        </ol>
                    </li>
                </ul>

                <div style="margin-top:1.5rem; padding:1.25rem; border-radius:14px; background:var(--fondo-alt); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem;">
                    <div>
                        <strong style="color:var(--tinta);">¿Ya tienes listo tu resumen?</strong>
                        <p style="margin:0; font-size:.92rem; color:var(--suave);">Envíalo directamente desde el formulario oficial del portal.</p>
                    </div>
                    <a href="{{ route('submissions.public.create') }}" class="btn btn--primary btn--sm">Ir al formulario de envío</a>
                </div>
            </div>

        @elseif ($doc['slug'] === 'plantilla-oral')
            <div class="form-card">
                <h2 class="form-card__title">Lineamientos para Ponencia Oral · CICI 2027</h2>
                <p>Para los trabajos aprobados por el Comité Científico en <strong>Modalidad Ponencia Oral</strong>:</p>
                <ul style="list-style:disc; padding-left:1.5rem; line-height:1.8;">
                    <li><strong>Tiempo de exposición:</strong> 15 minutos de presentación + 5 minutos para preguntas del público y moderador.</li>
                    <li><strong>Relación de aspecto de diapositivas:</strong> Panorámica 16:9 (1920 × 1080 px).</li>
                    <li><strong>Cantidad sugerida de diapositivas:</strong> Entre 10 y 15 diapositivas (Portada con eje temático, Introducción, Metodología, Resultados, Conclusiones y Agradecimientos).</li>
                    <li><strong>Formatos de entrega en sala:</strong> PowerPoint (.pptx) y copia de respaldo en PDF.</li>
                </ul>
            </div>

        @elseif ($doc['slug'] === 'convocatoria')
            <div class="form-card">
                <h2 class="form-card__title">Términos de Referencia y Convocatoria · CICI 2027</h2>
                <p>La Facultad de Ingeniería de la Universidad Mariana invita a docentes, investigadores, estudiantes y profesionales nacionales e internacionales a participar en el <strong>IV Congreso Internacional de Ciencia e Ingeniería (CICI 2027)</strong>, a celebrarse los días <strong>14, 15 y 16 de abril de 2027</strong> en modalidad presencial y virtual.</p>

                <h3 style="margin-top:1.5rem; font-size:1.15rem;">Ejes temáticos oficiales</h3>
                <ol style="list-style:decimal; padding-left:1.5rem; line-height:1.75; margin-bottom:1.5rem;">
                    @foreach ($axes as $axis)
                        <li>
                            <strong>{{ $axis->name }}</strong>
                            @if ($axis->is_transversal) <span class="pill">Transversal</span> @endif
                        </li>
                    @endforeach
                </ol>

                <h3 style="margin-top:1.5rem; font-size:1.15rem;">Proceso de postulación y evaluación</h3>
                <ol style="list-style:decimal; padding-left:1.5rem; line-height:1.75;">
                    <li>El autor principal envía su resumen a través del portal en <a href="{{ route('submissions.public.create') }}">Enviar resumen</a>.</li>
                    <li>El <strong>Comité Científico</strong> evalúa la pertinencia, rigor metodológico y aporte del resumen.</li>
                    <li>El Comité Científico emite el dictamen: <strong>Aprobado como Ponencia Oral</strong>, <strong>Aprobado como Póster</strong> o <strong>No aprobado</strong>.</li>
                    <li>El autor consulta el resultado en <a href="{{ route('submissions.status') }}">Estado de resumen</a> ingresando su <strong>número de cédula</strong>, descarga la plantilla correspondiente y realiza la inscripción con la Universidad Mariana.</li>
                </ol>
            </div>

        @else
            <div class="form-card">
                <h2 class="form-card__title">Identidad Visual y Material de Difusión · CICI 2027</h2>
                <p>Recursos gráficos oficiales del IV Congreso Internacional de Ciencia e Ingeniería:</p>
                <div class="grid grid--3" style="margin-top:1.5rem;">
                    <div class="card" style="background:var(--noche); text-align:center;">
                        <img src="{{ asset('images/logo-blanco.png') }}" alt="Logo CICI 2027 blanco" style="max-height:90px; margin:0 auto 1rem;">
                        <a href="{{ asset('images/logo-blanco.png') }}" download class="btn btn--light btn--sm">Descargar logo blanco</a>
                    </div>
                    <div class="card" style="text-align:center; border:1px solid var(--borde);">
                        <img src="{{ asset('images/cici-sigla.png') }}" alt="Sigla CICI" style="max-height:90px; margin:0 auto 1rem;">
                        <a href="{{ asset('images/cici-sigla.png') }}" download class="btn btn--outline btn--sm">Descargar sigla CICI</a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>
@endsection
