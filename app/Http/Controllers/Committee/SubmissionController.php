<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\ThematicAxis;
use App\Models\User;
use App\Notifications\SubmissionReviewed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $status   = $request->query('status');
        $axisId   = $request->query('axis_id');
        $modality = $request->query('modality');
        $search   = trim((string) $request->query('q', ''));

        $validStatuses = [
            Submission::STATUS_PENDING,
            Submission::STATUS_UNDER_REVIEW,
            Submission::STATUS_APPROVED,
            Submission::STATUS_REJECTED,
        ];

        $submissions = Submission::with(['axis', 'topic', 'reviewer', 'assignee'])
            ->when(in_array($status, $validStatuses, true), fn ($q) => $q->where('status', $status))
            ->when($axisId, fn ($q) => $q->where('thematic_axis_id', $axisId))
            ->when(in_array($modality, [Submission::MODALITY_ORAL, Submission::MODALITY_POSTER], true),
                fn ($q) => $q->where('modality', $modality))
            ->when($search !== '', function ($q) use ($search) {
                $norm = Submission::normalizeDocument($search);
                $q->where(function ($sub) use ($search, $norm) {
                    $sub->where('document', 'like', "%{$norm}%")
                        ->orWhere('tracking_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('institution', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total'           => Submission::count(),
            'pending'         => Submission::whereIn('status', [Submission::STATUS_PENDING, Submission::STATUS_UNDER_REVIEW])->count(),
            'approved_oral'   => Submission::where('status', Submission::STATUS_APPROVED)->where('modality', Submission::MODALITY_ORAL)->count(),
            'approved_poster' => Submission::where('status', Submission::STATUS_APPROVED)->where('modality', Submission::MODALITY_POSTER)->count(),
            'rejected'        => Submission::where('status', Submission::STATUS_REJECTED)->count(),
        ];

        $axes = ThematicAxis::orderBy('sort_order')->get();

        return view('committee.submissions.index', compact(
            'submissions',
            'status',
            'axisId',
            'modality',
            'search',
            'stats',
            'axes'
        ));
    }

    public function export(): StreamedResponse
    {
        $rows = Submission::with(['axis', 'topic', 'reviewer'])->latest()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 para que Excel en Windows abra tildes y ñ correctamente.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'Radicado',
                'Tipo Doc.',
                'Documento',
                'Autor Principal',
                'Correo',
                'Teléfono',
                'Institución',
                'Ciudad',
                'País',
                'Coautores',
                'Título',
                'Eje Temático',
                'Línea Temática',
                'Modalidad Solicitada',
                'Estado',
                'Modalidad Aprobada',
                'Puntaje',
                'Evaluador',
                'Fecha Revisión',
            ], ';');

            foreach ($rows as $s) {
                fputcsv($out, [
                    $s->tracking_code,
                    $s->document_type,
                    $s->document,
                    $s->name,
                    $s->email,
                    $s->phone,
                    $s->institution,
                    $s->city,
                    $s->country,
                    $s->coauthors,
                    $s->title,
                    $s->axis?->name,
                    $s->topic?->name,
                    $s->requestedModalityLabel(),
                    $s->statusShortLabel(),
                    $s->modalityLabel() ?? '—',
                    $s->score ?? '—',
                    $s->reviewer?->name ?? '—',
                    $s->reviewed_at?->format('Y-m-d H:i') ?? '—',
                ], ';');
            }

            fclose($out);
        }, 'resumenes-cici2027-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create(): View
    {
        return view('committee.submissions.create', [
            'axes' => ThematicAxis::with('topics')->orderBy('sort_order')->get(),
        ]);
    }

    /** Registro de un resumen desde el panel del comité o administración. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'document_type'      => ['nullable', 'string', Rule::in(['CC', 'CE', 'TI', 'Pasaporte'])],
            'document'           => ['required', 'string', 'max:30'],
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:40'],
            'institution'        => ['nullable', 'string', 'max:255'],
            'city'               => ['nullable', 'string', 'max:100'],
            'country'            => ['nullable', 'string', 'max:100'],
            'coauthors'          => ['nullable', 'string', 'max:1000'],
            'title'              => ['required', 'string', 'max:255'],
            'thematic_axis_id'   => ['nullable', 'exists:thematic_axes,id'],
            'axis_topic_id'      => ['nullable', 'exists:axis_topics,id'],
            'requested_modality' => ['nullable', Rule::in([Submission::MODALITY_ORAL, Submission::MODALITY_POSTER])],
            'abstract_text'      => ['nullable', 'string', 'max:6000'],
            'keywords'           => ['nullable', 'string', 'max:500'],
            'file'               => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'file_url'           => ['nullable', 'url', 'max:500'],
        ], [], [
            'document'         => 'cédula',
            'name'             => 'nombre',
            'email'            => 'correo',
            'institution'      => 'institución',
            'title'            => 'título',
            'thematic_axis_id' => 'eje temático',
            'file'             => 'archivo adjunto',
            'file_url'         => 'enlace al resumen',
        ]);

        unset($data['file']);
        $data['document_type']      = $data['document_type'] ?? 'CC';
        $data['document']           = Submission::normalizeDocument($data['document']);
        $data['country']            = $data['country'] ?? 'Colombia';
        $data['requested_modality'] = $data['requested_modality'] ?? Submission::MODALITY_ORAL;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $data['file_path']          = $uploaded->store('submissions', 'public');
            $data['file_original_name'] = $uploaded->getClientOriginalName();
        }

        $submission = Submission::create($data);

        return redirect()
            ->route('committee.submissions.show', $submission)
            ->with('status', 'Resumen registrado con código '.$submission->tracking_code.'.');
    }

    public function show(Submission $submission): View
    {
        $submission->load(['axis', 'topic', 'reviewer', 'assignee']);

        $reviewers = User::where('is_committee', true)
            ->orWhere('is_committee_leader', true)
            ->orWhere('is_admin', true)
            ->orderBy('name')
            ->get();

        return view('committee.submissions.show', compact('submission', 'reviewers'));
    }

    /** Dictamen del comité: aprobar (oral o póster), rechazar o poner en evaluación, y notificar al ponente. */
    public function review(Request $request, Submission $submission): RedirectResponse
    {
        $data = $request->validate([
            'decision'       => ['required', Rule::in([
                Submission::STATUS_UNDER_REVIEW,
                Submission::STATUS_APPROVED,
                Submission::STATUS_REJECTED,
            ])],
            'modality'       => [
                'required_if:decision,'.Submission::STATUS_APPROVED,
                'nullable',
                Rule::in([Submission::MODALITY_ORAL, Submission::MODALITY_POSTER]),
            ],
            'score'          => ['nullable', 'integer', 'min:0', 'max:100'],
            'assigned_to'    => ['nullable', 'exists:users,id'],
            'review_comment' => ['nullable', 'string', 'max:3000'],
            'internal_notes' => ['nullable', 'string', 'max:3000'],
        ], [
            'modality.required_if' => 'Elige la modalidad aprobada (Ponencia oral o Póster) para aprobar el resumen.',
        ]);

        $approved = $data['decision'] === Submission::STATUS_APPROVED;
        $isFinalDecision = in_array($data['decision'], [Submission::STATUS_APPROVED, Submission::STATUS_REJECTED], true);

        $submission->update([
            'status'         => $data['decision'],
            'modality'       => $approved ? $data['modality'] : null,
            'score'          => $data['score'] ?? $submission->score,
            'assigned_to'    => $data['assigned_to'] ?? $submission->assigned_to,
            'review_comment' => $data['review_comment'] ?? null,
            'internal_notes' => $data['internal_notes'] ?? $submission->internal_notes,
            'reviewed_by'    => $request->user()->id,
            'reviewed_at'    => now(),
        ]);

        if (! $isFinalDecision) {
            return redirect()
                ->route('committee.submissions.show', $submission)
                ->with('status', 'El resumen quedó marcado como «En evaluación por el comité».');
        }

        $message = 'Dictamen guardado correctamente.';

        try {
            Notification::route('mail', $submission->email)->notify(new SubmissionReviewed($submission));
            $submission->update(['notified_at' => now()]);
            $message .= ' Se envió la notificación al correo '.$submission->email.' y se actualizó la consulta pública por cédula.';
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar el correo de la revisión: '.$e->getMessage(), ['submission' => $submission->id]);
            $message .= ' El resultado ya está visible en la consulta por cédula (el correo quedó pendiente de envío).';
        }

        return redirect()->route('committee.submissions.show', $submission)->with('status', $message);
    }

    public function destroy(Submission $submission): RedirectResponse
    {
        if ($submission->file_path) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return redirect()->route('committee.submissions.index')->with('status', 'Resumen eliminado.');
    }
}
