<?php

return [
    /*
    | Enlaces y documentos del congreso.
    | Si se definen variables en .env se usan esas URLs; de lo contrario,
    | se usan las rutas internas de envío y plantillas oficiales del sistema.
    */

    // Si hay un formulario externo opcional, se puede configurar aquí;
    // por defecto el portal usa el formulario integrado (/enviar-resumen).
    'abstract_form_url' => env('CICI_ABSTRACT_FORM_URL'),

    // Plantilla de póster (se descarga cuando un resumen es aprobado como póster).
    'poster_template_url' => env('CICI_POSTER_TEMPLATE_URL'),

    // Plantilla de ponencia oral.
    'oral_template_url' => env('CICI_ORAL_TEMPLATE_URL'),

    // Formato de resumen para postulantes.
    'abstract_template_url' => env('CICI_ABSTRACT_TEMPLATE_URL'),
];
