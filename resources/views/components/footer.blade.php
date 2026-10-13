{{-- Pie de página institucional. Se reutiliza con <x-footer /> --}}
<footer class="footer">
    <div class="container footer__grid">
        <div class="footer__brand">
            <img src="{{ asset('images/logo-blanco.png') }}" alt="IV Congreso Internacional de Ciencia e Ingeniería, CICI 2027" width="1049" height="316">
            <p>Facultad de Ingeniería<br>Universidad Mariana</p>
        </div>

        <div>
            <h3 class="footer__title">Congreso</h3>
            <ul class="footer__list">
                <li>14, 15 y 16 de abril de 2027</li>
                <li>Presencial y virtual</li>
                <li><a href="{{ route('home') }}#inscripciones">Inscripciones</a></li>
                <li><a href="{{ route('home') }}#documentos">Documentos</a></li>
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
            <a href="{{ route('login') }}">Acceso administrador</a>
        </div>
    </div>
</footer>
