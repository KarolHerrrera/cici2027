<?php

namespace App\Http\Controllers;

use App\Models\CongressDocument;
use App\Models\CongressSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function show(string $slug): View|RedirectResponse
    {
        // Si existe el documento administrable en BD con archivo o URL activa, redirigir a su descarga
        if ($slug !== 'material-difusion' && Schema::hasTable('congress_documents')) {
            $docModel = CongressDocument::where('slug', $slug)->first();
            if ($docModel && $docModel->isAvailable() && $docModel->downloadUrl()) {
                return redirect()->away($docModel->downloadUrl());
            }
        }

        if ($slug === 'formato-resumen') {
            $url = CongressSetting::getValue('abstract_template_url', config('cici.abstract_template_url'));
            if ($url) {
                return redirect()->away($url);
            }
        }

        if ($slug === 'plantilla-poster') {
            $url = CongressSetting::getValue('poster_template_url', config('cici.poster_template_url'));
            if ($url) {
                return redirect()->away($url);
            }
        }

        if ($slug === 'convocatoria') {
            $url = CongressSetting::getValue('terms_url', config('cici.terms_url'));
            if ($url) {
                return redirect()->away($url);
            }
        }

        abort_unless($slug === 'material-difusion', 404);

        return view('documents.show', [
            'doc' => [
                'title'    => 'Material de Difusión Oficial · CICI 2027',
                'type'     => 'Recursos Oficiales',
                'subtitle' => 'Descarga las piezas gráficas oficiales del CICI 2027 para difusión institucional y redes sociales.',
                'slug'     => 'material-difusion',
            ],
        ]);
    }
}
