// CICI 2027 · comportamiento mínimo del sitio público
document.addEventListener('DOMContentLoaded', function () {
    // ---------- Menú móvil ----------
    var toggle = document.getElementById('navToggle');
    var menu = document.getElementById('navMenu');

    function cerrarMenu() {
        if (!toggle || !menu) return;
        menu.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Abrir menú');
    }

    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            var abierto = menu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', abierto ? 'true' : 'false');
            toggle.setAttribute('aria-label', abierto ? 'Cerrar menú' : 'Abrir menú');
        });
        // al tocar un enlace, el menú se cierra
        menu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', cerrarMenu); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') cerrarMenu(); });
    }

    // ---------- Cuenta regresiva ----------
    var cont = document.getElementById('countdown');
    if (!cont) return;

    var meta = new Date(cont.dataset.fecha).getTime();
    var celdas = {
        dias: cont.querySelector('[data-unit="dias"]'),
        horas: cont.querySelector('[data-unit="horas"]'),
        minutos: cont.querySelector('[data-unit="minutos"]'),
        segundos: cont.querySelector('[data-unit="segundos"]')
    };
    var dos = function (n) { return String(n).padStart(2, '0'); };

    function actualizar() {
        var resto = Math.max(0, meta - Date.now());
        celdas.dias.textContent = Math.floor(resto / 86400000);
        celdas.horas.textContent = dos(Math.floor(resto / 3600000) % 24);
        celdas.minutos.textContent = dos(Math.floor(resto / 60000) % 60);
        celdas.segundos.textContent = dos(Math.floor(resto / 1000) % 60);
    }

    actualizar();
    setInterval(actualizar, 1000);
});
