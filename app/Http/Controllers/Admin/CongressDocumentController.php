<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CongressDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CongressDocumentController extends Controller
{
    public function index(): View
    {
        $documents = CongressDocument::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.documents.index', compact('documents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'slug'         => ['nullable', 'string', 'max:120'],
            'category'     => ['required', 'in:convocatoria,formato_resumen,plantilla_poster,guia_autores,memorias,material_difusion,otro'],
            'description'  => ['nullable', 'string', 'max:600'],
            'external_url' => ['nullable', 'string', 'max:500'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,png,jpg,jpeg', 'max:15360'],
            'version'      => ['nullable', 'string', 'max:30'],
            'status'       => ['required', 'in:disponible,proximamente,oculto'],
            'sort_order'   => ['nullable', 'integer', 'min:1'],
        ]);

        $slug = Str::slug($data['slug'] ?? $data['title']);
        if (CongressDocument::where('slug', $slug)->exists()) {
            $slug .= '-' . substr(uniqid(), -4);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('documents', 'public');
        }

        CongressDocument::create([
            'slug'         => $slug,
            'title'        => $data['title'],
            'category'     => $data['category'],
            'description'  => $data['description'] ?? null,
            'external_url' => $data['external_url'] ?? null,
            'file_path'    => $filePath,
            'version'      => $data['version'] ?? '2027.1',
            'status'       => $data['status'],
            'sort_order'   => (int) ($data['sort_order'] ?? (CongressDocument::max('sort_order') + 1)),
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Documento oficial registrado.');
    }

    public function update(Request $request, CongressDocument $document): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'category'     => ['required', 'in:convocatoria,formato_resumen,plantilla_poster,guia_autores,memorias,material_difusion,otro'],
            'description'  => ['nullable', 'string', 'max:600'],
            'external_url' => ['nullable', 'string', 'max:500'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,png,jpg,jpeg', 'max:15360'],
            'version'      => ['nullable', 'string', 'max:30'],
            'status'       => ['required', 'in:disponible,proximamente,oculto'],
            'sort_order'   => ['nullable', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->file_path = $request->file('file')->store('documents', 'public');
        }

        $document->update([
            'title'        => $data['title'],
            'category'     => $data['category'],
            'description'  => $data['description'] ?? null,
            'external_url' => $data['external_url'] ?? null,
            'file_path'    => $document->file_path,
            'version'      => $data['version'] ?? $document->version,
            'status'       => $data['status'],
            'sort_order'   => (int) ($data['sort_order'] ?? $document->sort_order),
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Documento oficial actualizado.');
    }

    public function destroy(CongressDocument $document): RedirectResponse
    {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')->with('status', 'Documento eliminado.');
    }
}
