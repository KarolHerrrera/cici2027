<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpeakerRequest;
use App\Models\Speaker;
use App\Models\ThematicAxis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SpeakerController extends Controller
{
    public function index(): View
    {
        $speakers = Speaker::with('axis')->orderBy('sort_order')->orderBy('name')->paginate(10);

        return view('admin.speakers.index', compact('speakers'));
    }

    public function create(): View
    {
        return view('admin.speakers.create', [
            'speaker' => new Speaker(),
            'axes'    => ThematicAxis::orderBy('sort_order')->get(),
        ]);
    }

    public function store(SpeakerRequest $request): RedirectResponse
    {
        $data = $this->datos($request);

        Speaker::create($data);

        return redirect()->route('admin.speakers.index')->with('status', 'Ponente creado.');
    }

    public function edit(Speaker $speaker): View
    {
        return view('admin.speakers.edit', [
            'speaker' => $speaker,
            'axes'    => ThematicAxis::orderBy('sort_order')->get(),
        ]);
    }

    public function update(SpeakerRequest $request, Speaker $speaker): RedirectResponse
    {
        $data = $this->datos($request);

        // Si subieron una foto nueva, se borra la anterior.
        if (isset($data['photo']) && $speaker->photo) {
            Storage::disk('public')->delete($speaker->photo);
        }

        $speaker->update($data);

        return redirect()->route('admin.speakers.index')->with('status', 'Ponente actualizado.');
    }

    public function destroy(Speaker $speaker): RedirectResponse
    {
        if ($speaker->photo) {
            Storage::disk('public')->delete($speaker->photo);
        }

        $speaker->delete();

        return redirect()->route('admin.speakers.index')->with('status', 'Ponente eliminado.');
    }

    /** Datos validados + guardado de la foto (si viene una). */
    private function datos(SpeakerRequest $request): array
    {
        $data = $request->validated();

        unset($data['photo']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('speakers', 'public');
        }

        return $data;
    }
}