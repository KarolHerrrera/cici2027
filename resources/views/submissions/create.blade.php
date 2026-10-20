@extends('layouts.cici')

@section('title', 'Enviar resumen de ponencia o póster · CICI 2027')

@section('content')
<div class="page-hero">
    <div class="container">
        <span class="page-hero__kicker">Convocatoria de ponencias · CICI 2027</span>
        <h1>Postular resumen al congreso</h1>
        <p>Envía tu propuesta para evaluación del Comité Científico. Una vez revisada, podrás consultar con tu número de cédula si fue aprobada como ponencia oral o póster.</p>
    </div>
</div>

<section class="section section--alt">
    <div class="container container--narrow">

        <div class="card" style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; border-left: 6px solid var(--naranja);">
            <div>
                <h2 style="font-size: 1.15rem; margin-bottom: .25rem;">¿Aún no tienes el formato oficial de resumen?</h2>
                <p style="margin: 0; font-size: .95rem; color: var(--suave);">Descarga la guía elaborada por el Comité Científico o consulta si ya enviaste un resumen anteriormente.</p>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: .6rem;">
                <a href="{{ config('cici.abstract_template_url') ?: route('documents.show', 'formato-resumen') }}" class="btn btn--outline btn--sm">
                    Ver formato de resumen
                </a>
                <a href="{{ route('submissions.status') }}" class="btn btn--primary btn--sm">
                    Ya envié mi resumen (Consultar con cédula)
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="form-card" style="border-color: #f87171; background: #fef2f2; padding: 1.25rem 1.5rem;">
                <strong style="color: #991b1b; display: block; margin-bottom: .4rem;">Por favor corrige los siguientes campos antes de continuar:</strong>
                <ul style="list-style: disc; padding-left: 1.25rem; color: #b91c1c; font-size: .95rem;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('submissions.public.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- PASO 1: Autor principal --}}
            <div class="form-card">
                <h2 class="form-card__title">
                    <span class="form-card__step">1</span>
                    Datos del autor principal (postulante)
                </h2>

                <div class="form-grid form-grid--2">
                    <div class="field">
                        <label class="field__label" for="document_type">Tipo de documento <span class="field__req">*</span></label>
                        <select class="field__select" id="document_type" name="document_type">
                            @foreach (['CC' => 'Cédula de ciudadanía (CC)', 'CE' => 'Cédula de extranjería (CE)', 'TI' => 'Tarjeta de identidad (TI)', 'Pasaporte' => 'Pasaporte / DNI'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('document_type', 'CC') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="field__label" for="document">Número de cédula / documento <span class="field__req">*</span></label>
                        <input class="field__input" id="document" name="document" type="text" value="{{ old('document') }}"
                               placeholder="Ej. 1085123456" required>
                        <p class="field__hint">Con este número consultarás el estado de tu resumen sin necesidad de contraseña.</p>
                        @error('document') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field form-span-2">
                        <label class="field__label" for="name">Nombre completo del autor principal <span class="field__req">*</span></label>
                        <input class="field__input" id="name" name="name" type="text" value="{{ old('name') }}"
                               placeholder="Nombres y apellidos completos" required>
                        @error('name') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="email">Correo electrónico <span class="field__req">*</span></label>
                        <input class="field__input" id="email" name="email" type="email" value="{{ old('email') }}"
                               placeholder="nombre@universidad.edu.co" required>
                        <p class="field__hint">Allí enviaremos la notificación del Comité Científico.</p>
                        @error('email') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="phone">Teléfono / WhatsApp</label>
                        <input class="field__input" id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                               placeholder="Ej. 314 852 1240">
                        @error('phone') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field form-span-2">
                        <label class="field__label" for="institution">Universidad, institución o entidad <span class="field__req">*</span></label>
                        <input class="field__input" id="institution" name="institution" type="text" value="{{ old('institution') }}"
                               placeholder="Ej. Universidad Mariana" required>
                        @error('institution') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="city">Ciudad / Municipio</label>
                        <input class="field__input" id="city" name="city" type="text" value="{{ old('city') }}"
                               placeholder="Ej. Pasto">
                        @error('city') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="country">País</label>
                        <input class="field__input" id="country" name="country" type="text" value="{{ old('country', 'Colombia') }}">
                        @error('country') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field form-span-2">
                        <label class="field__label" for="coauthors">Coautores (opcional)</label>
                        <input class="field__input" id="coauthors" name="coauthors" type="text" value="{{ old('coauthors') }}"
                               placeholder="Nombres completos separados por coma (si aplica)">
                        @error('coauthors') <p class="field__error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- PASO 2: Eje y modalidad --}}
            <div class="form-card">
                <h2 class="form-card__title">
                    <span class="form-card__step">2</span>
                    Información de la ponencia y eje temático
                </h2>

                <div class="form-grid">
                    <div class="field">
                        <label class="field__label" for="title">Título del trabajo / resumen <span class="field__req">*</span></label>
                        <input class="field__input" id="title" name="title" type="text" value="{{ old('title') }}"
                               placeholder="Título claro y descriptivo de la investigación o proyecto" required>
                        @error('title') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-grid form-grid--2">
                        <div class="field">
                            <label class="field__label" for="thematic_axis_id">Eje temático <span class="field__req">*</span></label>
                            <select class="field__select" id="thematic_axis_id" name="thematic_axis_id" required>
                                <option value="">Selecciona un eje temático…</option>
                                @foreach ($axes as $axis)
                                    <option value="{{ $axis->id }}" @selected(old('thematic_axis_id') == $axis->id)>
                                        {{ $axis->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('thematic_axis_id') <p class="field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label class="field__label" for="axis_topic_id">Línea temática (opcional)</label>
                            <select class="field__select" id="axis_topic_id" name="axis_topic_id">
                                <option value="">Todas / General del eje</option>
                                @foreach ($axes as $axis)
                                    @foreach ($axis->topics as $topic)
                                        <option value="{{ $topic->id }}"
                                                data-axis="{{ $axis->id }}"
                                                @selected(old('axis_topic_id') == $topic->id)>
                                            {{ $topic->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('axis_topic_id') <p class="field__error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="field">
                        <span class="field__label">Modalidad a la que se postula <span class="field__req">*</span></span>
                        <p class="field__hint" style="margin-bottom: .4rem;">
                            El Comité Científico revisará tu resumen y definirá en el dictamen si se aprueba como <strong>Ponencia oral</strong> o como <strong>Póster</strong>.
                        </p>
                        <div class="modality-Selector">
                            <label class="modality-option">
                                <span class="modality-option__head">
                                    <input type="radio" name="requested_modality" value="oral" @checked(old('requested_modality', 'oral') === 'oral')>
                                    Ponencia oral
                                </span>
                                <span class="modality-option__desc">Presentación oral en sesión temática (15 min + preguntas).</span>
                            </label>

                            <label class="modality-option">
                                <span class="modality-option__head">
                                    <input type="radio" name="requested_modality" value="poster" @checked(old('requested_modality') === 'poster')>
                                    Modalidad Póster
                                </span>
                                <span class="modality-option__desc">Exposición visual en sesión de pósteres científicos del congreso.</span>
                            </label>
                        </div>
                        @error('requested_modality') <p class="field__error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- PASO 3: Contenido y archivo --}}
            <div class="form-card">
                <h2 class="form-card__title">
                    <span class="form-card__step">3</span>
                    Contenido y documento del resumen
                </h2>

                <div class="form-grid">
                    <div class="field">
                        <label class="field__label" for="abstract_text">Resumen del trabajo (introducción, metodología, resultados y conclusiones)</label>
                        <textarea class="field__textarea" id="abstract_text" name="abstract_text" rows="7"
                                  placeholder="Escribe o pega aquí el resumen de tu trabajo (recomendado entre 250 y 500 palabras)…">{{ old('abstract_text') }}</textarea>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <p class="field__hint">Escribe el texto aquí y/o adjunta el archivo en formato PDF o Word.</p>
                            <span id="wordCount" class="field__hint" style="font-weight:700;">0 palabras</span>
                        </div>
                        @error('abstract_text') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field">
                        <label class="field__label" for="keywords">Palabras clave</label>
                        <input class="field__input" id="keywords" name="keywords" type="text" value="{{ old('keywords') }}"
                               placeholder="3 a 5 palabras clave separadas por coma (ej. sostenibilidad, concreto reciclado, estructuras)">
                        @error('keywords') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-grid form-grid--2">
                        <div class="field">
                            <label class="field__label" for="file">Adjuntar archivo del resumen (PDF, DOC o DOCX, máx. 10 MB)</label>
                            <input class="field__input" id="file" name="file" type="file" accept=".pdf,.doc,.docx">
                            <p class="field__hint">Sube el archivo en el formato oficial de resumen.</p>
                            @error('file') <p class="field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label class="field__label" for="file_url">O enlace a Google Drive / OneDrive (opcional)</label>
                            <input class="field__input" id="file_url" name="file_url" type="url" value="{{ old('file_url') }}"
                                   placeholder="https://drive.google.com/...">
                            <p class="field__hint">Si prefieres compartir un enlace con permisos de lectura.</p>
                            @error('file_url') <p class="field__error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; flex-wrap: wrap; align-items: center; gap: 1rem;">
                    <button type="submit" class="btn btn--primary">Enviar resumen al Comité Científico</button>
                    <a href="{{ route('home') }}" class="btn btn--outline">Volver al inicio</a>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const axisSelect = document.getElementById('thematic_axis_id');
    const topicSelect = document.getElementById('axis_topic_id');
    const textarea = document.getElementById('abstract_text');
    const wordCount = document.getElementById('wordCount');

    if (axisSelect && topicSelect) {
        const options = Array.from(topicSelect.querySelectorAll('option[data-axis]'));
        function filterTopics() {
            const selectedAxis = axisSelect.value;
            options.forEach(opt => {
                const match = !selectedAxis || opt.getAttribute('data-axis') === selectedAxis;
                opt.hidden = !match;
                if (!match && opt.selected) {
                    topicSelect.value = '';
                }
            });
        }
        axisSelect.addEventListener('change', filterTopics);
        filterTopics();
    }

    if (textarea && wordCount) {
        function updateWords() {
            const text = textarea.value.trim();
            const count = text ? text.split(/\s+/).length : 0;
            wordCount.textContent = count + (count === 1 ? ' palabra' : ' palabras');
        }
        textarea.addEventListener('input', updateWords);
        updateWords();
    }
});
</script>
@endsection
