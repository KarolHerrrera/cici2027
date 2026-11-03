<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:180'],
            'phone'       => ['nullable', 'string', 'max:40'],
            'institution' => ['nullable', 'string', 'max:180'],
            'category'    => ['required', 'in:resumenes,inscripciones,pagos,certificados,general'],
            'subject'     => ['required', 'string', 'max:200'],
            'message'     => ['required', 'string', 'min:10', 'max:2500'],
        ], [], [
            'name'        => 'nombre completo',
            'email'       => 'correo electrónico',
            'phone'       => 'teléfono',
            'institution' => 'institución',
            'category'    => 'categoría de consulta',
            'subject'     => 'asunto',
            'message'     => 'mensaje',
        ]);

        $data['status']     = 'nuevo';
        $data['ip_address'] = $request->ip();

        ContactMessage::create($data);

        return redirect(url('/#contacto'))
            ->with('contact_success', '¡Gracias por escribirnos! Hemos recibido tu consulta y el equipo del IV CICI 2027 te responderá al correo registrado.');
    }
}
