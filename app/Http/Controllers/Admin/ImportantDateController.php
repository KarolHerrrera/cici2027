<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantDate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImportantDateController extends Controller
{
    public function index(): View
    {
        $dates = ImportantDate::orderBy('date')->get();

        return view('admin.dates.index', compact('dates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:date'],
            'is_featured' => ['nullable', 'boolean'],
        ], [], [
            'title'    => 'título',
            'date'     => 'fecha inicial',
            'end_date' => 'fecha final',
        ]);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);

        ImportantDate::create($data);

        return redirect()->route('admin.dates.index')->with('status', 'Fecha agregada al cronograma.');
    }

    public function update(Request $request, ImportantDate $date): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:date'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);

        $date->update($data);

        return redirect()->route('admin.dates.index')->with('status', 'Fecha actualizada.');
    }

    public function destroy(ImportantDate $date): RedirectResponse
    {
        $date->delete();

        return redirect()->route('admin.dates.index')->with('status', 'Fecha eliminada.');
    }
}
