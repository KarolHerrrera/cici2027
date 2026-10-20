<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\ThematicAxis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubmissionStatusController extends Controller
{
    /** Formulario público para postular y enviar un resumen al congreso. */
    public function create(): View
    {
        $axes = ThematicAxis::with('topics')->orderBy('sort_order')->get();

        return view('submissions.create', compact('axes'));
    }

    /** Guarda la postulación enviada por un ponente desde el portal público. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'document_type'      => ['nullable', 'string', Rule::in(['CC', 'CE', 'TI', 'Pasaporte'])],
            'document'           => ['required', 'string', 'max:30'],
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:40'],
            'institution'        => ['required', 'string', 'max:255'],
            'city'               => ['nullable', 'string', 'max:100'],
            'country'            => ['nullable', 'string', 'max:100'],
            'coauthors'          => ['nullable', 'string', 'max:1000'],
            'title'              => ['required', 'string', 'max:255'],
            'thematic_axis_id'   => ['required', 'exists:thematic_axes,id'],
            'axis_topic_id'      => ['nullable', 'exists:axis_topics,id'],
            'requested_modality' => ['required', Rule::in([Submission::MODALITY_ORAL, Submission::MODALITY_POSTER])],
            'abstract_text'      => ['nullable', 'string', 'max:6000', 'required_without_all:file,file_url'],
            'keywords'           => ['nullable', 'string', 'max:500'],
            'file'               => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'file_url'           => ['nullable', 'url', 'max:500'],
        ], [
            'required'                               => 'El campo :attribute es obligatorio.',
            'email'                                  => 'Ingresa un correo electrónico válido.',
            'url'                                    => 'Ingresa un enlace válido (con https://).',
            'abstract_text.required_without_all'     => 'Debes escribir el contenido del resumen o adjuntar el archivo en el formato oficial.',
            'file.mimes'                             => 'El archivo adjunto debe estar en formato PDF, DOC o DOCX.',
            'file.max'                               => 'El archivo no debe superar los 10 MB.',
        ], [
            'document_type'      => 'tipo de documento',
            'document'           => 'número de cédula o documento',
            'name'               => 'nombre completo',
            'email'              => 'correo electrónico',
            'phone'              => 'teléfono',
            'institution'        => 'institución o universidad',
            'city'               => 'ciudad o municipio',
            'country'            => 'país',
            'coauthors'          => 'coautores',
            'title'              => 'título de la ponencia',
            'thematic_axis_id'   => 'eje temático',
            'axis_topic_id'      => 'línea temática',
            'requested_modality' => 'modalidad solicitada',
            'abstract_text'      => 'texto del resumen',
            'keywords'           => 'palabras clave',
            'file'               => 'archivo del resumen',
            'file_url'           => 'enlace al documento',
        ]);

        unset($data['file']);
        $data['document_type'] = $data['document_type'] ?? 'CC';
        $data['document']      = Submission::normalizeDocument($data['document']);
        $data['country']       = $data['country'] ?: 'Colombia';

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $data['file_path']          = $uploaded->store('submissions', 'public');
            $data['file_original_name'] = $uploaded->getClientOriginalName();
        }

        $submission = Submission::create($data);

        return redirect()
            ->route('submissions.status', ['documento' => $submission->document, 'radicado' => $submission->tracking_code])
            ->with('submitted_code', $submission->tracking_code);
    }

    /** Vista pública de consulta por cédula o código de radicado. */
    public function form(Request $request): View
    {
        $queryDoc = trim((string) $request->query('documento', ''));
        $submissions = null;

        if ($queryDoc !== '') {
            $submissions = $this->findSubmissions($queryDoc);
        }

        return view('submissions.status', [
            'submissions' => $submissions,
            'searchedDoc' => $queryDoc,
        ]);
    }

    /** Consulta por número de cédula (o código de radicado CICI-2027-XXXX). */
    public function lookup(Request $request): View
    {
        $data = $request->validate([
            'document' => ['required', 'string', 'max:40'],
            'email'    => ['nullable', 'email', 'max:255'],
        ], [
            'document.required' => 'Ingresa tu número de cédula o código de radicado.',
            'email.email'       => 'Ingresa un correo electrónico válido.',
        ]);

        $submissions = $this->findSubmissions($data['document'], $data['email'] ?? null);

        return view('submissions.status', [
            'submissions' => $submissions,
            'searchedDoc' => $data['document'],
        ]);
    }

    private function findSubmissions(string $input, ?string $email = null)
    {
        $raw = trim($input);
        $normalized = Submission::normalizeDocument($raw);

        $query = Submission::with(['axis', 'topic'])
            ->where(function ($q) use ($raw, $normalized) {
                $q->where('document', $normalized)
                  ->orWhereRaw('UPPER(tracking_code) = ?', [mb_strtoupper($raw)])
                  ->orWhereRaw('REPLACE(UPPER(tracking_code), "-", "") = ?', [$normalized]);
            });

        if ($email !== null && trim($email) !== '') {
            $query->whereRaw('LOWER(email) = ?', [mb_strtolower(trim($email))]);
        }

        return $query->latest()->get();
    }
}
