<?php

return [
    /*
    | Enlaces oficiales del congreso (tomados de https://cici.umariana.edu.co/).
    | Se pueden cambiar en el .env sin tocar el código. Dejar una variable vacía
    | (por ejemplo CICI_ABSTRACT_FORM_URL=) desactiva el enlace externo.
    */

    // Formulario externo donde los postulantes envían su resumen.
    // Si está vacío, los botones "Enviar resumen" usan el formulario integrado (/enviar-resumen).
    'abstract_form_url' => env('CICI_ABSTRACT_FORM_URL', 'https://forms.gle/GoMs6wT4ceXXD4GV9'),

    // Formato oficial de resumen (documento de Google).
    'abstract_template_url' => env('CICI_ABSTRACT_TEMPLATE_URL', 'https://docs.google.com/document/d/1aC80AhrR2_4r7MohHf30_srPf1kNfsPF/edit?usp=drive_link'),

    // Plantilla de póster: el sitio oficial la marca como "próximamente".
    // Cuando esté lista, poner la URL en CICI_POSTER_TEMPLATE_URL.
    'poster_template_url' => env('CICI_POSTER_TEMPLATE_URL'),

    // Convocatoria / Términos de Referencia: "próximamente" en el sitio oficial.
    'terms_url' => env('CICI_TERMS_URL'),

    // Inscripción institucional (Universidad Mariana).
    'registration_url' => env('CICI_REGISTRATION_URL', 'https://fenix.umariana.edu.co/sgacampus/services/inscripciones/home'),

    // Datos de contacto oficiales.
    'contact_email' => env('CICI_CONTACT_EMAIL', 'congresoingenieria@umariana.edu.co'),
    'contact_phone' => env('CICI_CONTACT_PHONE', '318 6063123'),

    // WhatsApp: el sitio oficial no publica uno. Si el comité define un número,
    // poner el enlace completo (ej. https://wa.me/57XXXXXXXXXX) y se mostrará en la página.
    'whatsapp_url' => env('CICI_WHATSAPP_URL'),

    // Disco de almacenamiento para archivos y fotos subidas por el Administrador ('public' local o 's3' para Bucket en la nube / Firebase / R2).
    'uploads_disk' => env('CICI_UPLOADS_DISK', 'public'),
];
