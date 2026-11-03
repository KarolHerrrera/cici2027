<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\OrganizerMember;
use App\Models\Sponsor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortalContentController extends Controller
{
    public function index(): View
    {
        $organizers = OrganizerMember::orderBy('sort_order')->orderBy('id')->get();
        $gallery    = GalleryItem::orderBy('sort_order')->orderBy('year')->get();
        $sponsors   = Sponsor::orderBy('sort_order')->orderBy('id')->get();
        $faqs       = Faq::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.portal.index', compact('organizers', 'gallery', 'sponsors', 'faqs'));
    }

    // --- Comité Organizador y Científico Público (RF-09) ---
    public function storeOrganizer(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:180'],
            'role'            => ['required', 'string', 'max:200'],
            'committee_type'  => ['required', 'in:directivo,cientifico,organizador,logistico'],
            'institution'     => ['required', 'string', 'max:180'],
            'program_or_unit' => ['nullable', 'string', 'max:180'],
            'email'           => ['nullable', 'email', 'max:180'],
            'sort_order'      => ['nullable', 'integer', 'min:1'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? (OrganizerMember::max('sort_order') + 1));
        $data['is_active']  = true;

        OrganizerMember::create($data);

        return redirect()->route('admin.portal.index')->with('status', 'Integrante o comité agregado.');
    }

    public function destroyOrganizer(OrganizerMember $organizer): RedirectResponse
    {
        $organizer->delete();

        return redirect()->route('admin.portal.index')->with('status', 'Registro de comité eliminado.');
    }

    // --- Galería e Histórico de Ediciones Anteriores (RF-10) ---
    public function storeGallery(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'edition'            => ['required', 'string', 'max:60'],
            'year'               => ['required', 'integer', 'min:2010', 'max:2035'],
            'title'              => ['required', 'string', 'max:200'],
            'description'        => ['nullable', 'string', 'max:800'],
            'participants_count' => ['nullable', 'string', 'max:50'],
            'papers_count'       => ['nullable', 'string', 'max:50'],
            'image'              => ['nullable', 'image', 'max:4096'],
            'sort_order'         => ['nullable', 'integer', 'min:1'],
        ]);

        $imagePath = 'images/umariana.jpg';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('gallery', 'public');
        }

        GalleryItem::create([
            'edition'            => $data['edition'],
            'year'               => $data['year'],
            'title'              => $data['title'],
            'description'        => $data['description'] ?? null,
            'participants_count' => $data['participants_count'] ?? null,
            'papers_count'       => $data['papers_count'] ?? null,
            'image_path'         => $imagePath,
            'sort_order'         => (int) ($data['sort_order'] ?? (GalleryItem::max('sort_order') + 1)),
            'is_published'       => true,
        ]);

        return redirect()->route('admin.portal.index')->with('status', 'Hito histórico / galería agregado.');
    }

    public function destroyGallery(GalleryItem $gallery): RedirectResponse
    {
        if ($gallery->image_path && ! str_starts_with($gallery->image_path, 'images/')) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.portal.index')->with('status', 'Elemento de galería eliminado.');
    }

    // --- Aliados, Patrocinadores y Entidades Participantes (RF-11) ---
    public function storeSponsor(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:180'],
            'category'    => ['required', 'in:organizador,aliado_academico,patrocinador,apoyo_institucional'],
            'country'     => ['nullable', 'string', 'max:80'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:255'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'sort_order'  => ['nullable', 'integer', 'min:1'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('sponsors', 'public');
        }

        Sponsor::create([
            'name'        => $data['name'],
            'category'    => $data['category'],
            'country'     => $data['country'] ?? null,
            'website_url' => $data['website_url'] ?? null,
            'description' => $data['description'] ?? null,
            'logo_path'   => $logoPath,
            'sort_order'  => (int) ($data['sort_order'] ?? (Sponsor::max('sort_order') + 1)),
            'is_active'   => true,
        ]);

        return redirect()->route('admin.portal.index')->with('status', 'Institución aliada o patrocinador registrado.');
    }

    public function destroySponsor(Sponsor $sponsor): RedirectResponse
    {
        if ($sponsor->logo_path && ! str_starts_with($sponsor->logo_path, 'images/')) {
            Storage::disk('public')->delete($sponsor->logo_path);
        }

        $sponsor->delete();

        return redirect()->route('admin.portal.index')->with('status', 'Aliado o patrocinador eliminado.');
    }

    // --- Preguntas Frecuentes FAQ (RF-12) ---
    public function storeFaq(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'question'   => ['required', 'string', 'max:300'],
            'answer'     => ['required', 'string', 'max:2000'],
            'category'   => ['required', 'in:resumenes,inscripciones,modalidades,certificados,general'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

        Faq::create([
            'question'     => $data['question'],
            'answer'       => $data['answer'],
            'category'     => $data['category'],
            'sort_order'   => (int) ($data['sort_order'] ?? (Faq::max('sort_order') + 1)),
            'is_published' => true,
        ]);

        return redirect()->route('admin.portal.index')->with('status', 'Pregunta frecuente agregada.');
    }

    public function updateFaq(Request $request, Faq $faq): RedirectResponse
    {
        $data = $request->validate([
            'question'     => ['required', 'string', 'max:300'],
            'answer'       => ['required', 'string', 'max:2000'],
            'category'     => ['required', 'in:resumenes,inscripciones,modalidades,certificados,general'],
            'sort_order'   => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $faq->update([
            'question'     => $data['question'],
            'answer'       => $data['answer'],
            'category'     => $data['category'],
            'sort_order'   => (int) ($data['sort_order'] ?? $faq->sort_order),
            'is_published' => (bool) ($data['is_published'] ?? false),
        ]);

        return redirect()->route('admin.portal.index')->with('status', 'Pregunta frecuente actualizada.');
    }

    public function destroyFaq(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.portal.index')->with('status', 'Pregunta frecuente eliminada.');
    }
}
