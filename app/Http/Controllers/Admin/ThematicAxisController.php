<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AxisTopic;
use App\Models\ThematicAxis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThematicAxisController extends Controller
{
    public function index(): View
    {
        $axes = ThematicAxis::with('topics')->orderBy('sort_order')->get();

        return view('admin.axes.index', compact('axes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:1000'],
            'is_transversal' => ['nullable', 'boolean'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_transversal'] = (bool) ($data['is_transversal'] ?? false);
        $data['sort_order']     = $data['sort_order'] ?? (ThematicAxis::max('sort_order') + 1);

        ThematicAxis::create($data);

        return redirect()->route('admin.axes.index')->with('status', 'Eje temático creado.');
    }

    public function update(Request $request, ThematicAxis $axis): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:1000'],
            'is_transversal' => ['nullable', 'boolean'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_transversal'] = (bool) ($data['is_transversal'] ?? false);
        $data['sort_order']     = $data['sort_order'] ?? $axis->sort_order;

        $axis->update($data);

        return redirect()->route('admin.axes.index')->with('status', 'Eje temático actualizado.');
    }

    public function destroy(ThematicAxis $axis): RedirectResponse
    {
        $axis->delete();

        return redirect()->route('admin.axes.index')->with('status', 'Eje temático eliminado.');
    }

    public function storeTopic(Request $request, ThematicAxis $axis): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $axis->topics()->create([
            'name'       => $data['name'],
            'sort_order' => (int) $axis->topics()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.axes.index')->with('status', 'Línea temática agregada.');
    }

    public function destroyTopic(AxisTopic $topic): RedirectResponse
    {
        $topic->delete();

        return redirect()->route('admin.axes.index')->with('status', 'Línea temática eliminada.');
    }
}
