{{-- Pie de página institucional. Se reutiliza con <x-footer /> --}}
<footer class="footer">
    <div class="container footer__grid">
        <div class="footer__brand">
            <img src="{{ asset('images/logo-blanco.png') }}" alt="IV Congreso Internacional de Ciencia e Ingeniería, CICI 2027" width="1049" height="316">
            <p>Facultad de Ingeniería<br>Universidad Mariana</p>
        </div>

        <div>
            <h3 class="footer__title">Congreso y Ponencias</h3>
            <ul class="footer__list">
                <li>14, 15 y 16 de abril de 2027</li>
                <li><a href="{{ route('submissions.public.create') }}">Postular y enviar resumen</a></li>
                <li><a href="{{ route('submissions.status') }}">Consultar estado con cédula</a></li>
                <li><a href="{{ route('home') }}#inscripciones">Inscripciones (Universidad Mariana)</a></li>
                <li><a href="{{ route('home') }}#documentos">Formatos y plantillas oficiales</a></li>
            </ul>
        </div>

        <div>
            <h3 class="footer__title">Contacto</h3>
            <ul class="footer__list">
                <li><a href="mailto:congresoingenieria@umariana.edu.co">congresoingenieria@umariana.edu.co</a></li>
                <li><a href="tel:+573148521240">314 852 1240</a></li>
                <li><a href="https://wa.me/573148521240" target="_blank" rel="noopener">WhatsApp</a></li>
            </ul>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <span>© {{ date('Y') }} Universidad Mariana · CICI 2027</span>
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
