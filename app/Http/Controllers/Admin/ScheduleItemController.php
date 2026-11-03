<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleItem;
use App\Models\ThematicAxis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleItemController extends Controller
{
    public function index(): View
    {
        $items = ScheduleItem::with('axis')
            ->orderBy('event_date')
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get();

        $axes = ThematicAxis::orderBy('sort_order')->get();

        return view('admin.schedule.index', compact('items', 'axes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'day_label'        => ['required', 'string', 'max:150'],
            'event_date'       => ['nullable', 'date'],
            'start_time'       => ['required', 'string', 'max:20'],
            'end_time'         => ['nullable', 'string', 'max:20'],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'speaker_name'     => ['nullable', 'string', 'max:180'],
            'location'         => ['nullable', 'string', 'max:180'],
            'activity_type'    => ['required', 'in:conferencia,ponencia,poster,panel,apertura,cultural'],
            'thematic_axis_id' => ['nullable', 'exists:thematic_axes,id'],
            'sort_order'       => ['nullable', 'integer', 'min:1'],
            'is_published'     => ['nullable', 'boolean'],
        ]);

        $data['sort_order']   = (int) ($data['sort_order'] ?? (ScheduleItem::max('sort_order') + 1));
        $data['is_published'] = (bool) ($data['is_published'] ?? true);

        ScheduleItem::create($data);

        return redirect()->route('admin.schedule.index')->with('status', 'Actividad agregada al programa académico.');
    }

    public function update(Request $request, ScheduleItem $item): RedirectResponse
    {
        $data = $request->validate([
            'day_label'        => ['required', 'string', 'max:150'],
            'event_date'       => ['nullable', 'date'],
            'start_time'       => ['required', 'string', 'max:20'],
            'end_time'         => ['nullable', 'string', 'max:20'],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'speaker_name'     => ['nullable', 'string', 'max:180'],
            'location'         => ['nullable', 'string', 'max:180'],
            'activity_type'    => ['required', 'in:conferencia,ponencia,poster,panel,apertura,cultural'],
            'thematic_axis_id' => ['nullable', 'exists:thematic_axes,id'],
            'sort_order'       => ['nullable', 'integer', 'min:1'],
            'is_published'     => ['nullable', 'boolean'],
        ]);

        $data['sort_order']   = (int) ($data['sort_order'] ?? $item->sort_order);
        $data['is_published'] = (bool) ($data['is_published'] ?? false);

        $item->update($data);

        return redirect()->route('admin.schedule.index')->with('status', 'Actividad del programa actualizada.');
    }

    public function destroy(ScheduleItem $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('admin.schedule.index')->with('status', 'Actividad eliminada del programa.');
    }
}
