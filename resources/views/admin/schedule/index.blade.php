<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Programa y Agenda Académica · CICI 2027
                </h2>
                <p class="text-sm text-gray-500">
                    RF-04 · Administra las conferencias magistrales, sesiones de ponencias orales, muestras de pósteres y actos protocolarios por día.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:underline">← Volver al Panel</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Formulario para agregar actividad --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Nueva Actividad en Agenda</h3>
                    <form method="POST" action="{{ route('admin.schedule.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Etiqueta de Jornada / Día *</label>
                            <input type="text" name="day_label" required value="Jornada 1 · Miércoles 14 de abril de 2027" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Fecha</label>
                                <input type="date" name="event_date" value="2027-04-14" class="mt-1 w-full rounded-md border-gray-300 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Inicio *</label>
                                <input type="text" name="start_time" required placeholder="08:30" class="mt-1 w-full rounded-md border-gray-300 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Fin</label>
                                <input type="text" name="end_time" placeholder="09:45" class="mt-1 w-full rounded-md border-gray-300 text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de actividad *</label>
                            <select name="activity_type" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                <option value="conferencia">Conferencia Magistral</option>
                                <option value="ponencia">Sesión de Ponencias Orales</option>
                                <option value="poster">Exhibición de Pósteres</option>
                                <option value="panel">Foro / Panel Académico</option>
                                <option value="apertura">Acto Protocolario / Registro</option>
                                <option value="cultural">Actividad Institucional / Clausura</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Título de la actividad *</label>
                            <input type="text" name="title" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Conferencista / Responsable</label>
                            <input type="text" name="speaker_name" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Salón / Lugar</label>
                            <input type="text" name="location" placeholder="Auditorio Madre Caridad Brader" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Eje temático asociado (opcional)</label>
                            <select name="thematic_axis_id" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                <option value="">— Transversal / Todos los ejes —</option>
                                @foreach ($axes as $axis)
                                    <option value="{{ $axis->id }}">{{ $axis->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción breve</label>
                            <textarea name="description" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Agregar al programa
                        </button>
                    </form>
                </div>

                {{-- Listado de actividades --}}
                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Actividades Programadas ({{ $items->count() }})</h3>
                    <div class="space-y-4">
                        @forelse ($items as $item)
                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <span class="px-2 py-0.5 font-semibold bg-slate-100 text-slate-800 rounded">{{ $item->day_label }}</span>
                                        <span class="px-2 py-0.5 font-mono font-bold bg-indigo-50 text-indigo-700 rounded">{{ $item->start_time }}{{ $item->end_time ? ' - ' . $item->end_time : '' }}</span>
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-800 rounded">{{ $item->activityTypeLabel() }}</span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mt-1.5">{{ $item->title }}</h4>
                                    @if ($item->speaker_name || $item->location)
                                        <p class="text-xs text-gray-600 mt-1">
                                            @if ($item->speaker_name) <strong>Ponente:</strong> {{ $item->speaker_name }} @endif
                                            @if ($item->location) · <strong>Lugar:</strong> {{ $item->location }} @endif
                                        </p>
                                    @endif
                                    @if ($item->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ $item->description }}</p>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('admin.schedule.destroy', $item) }}" onsubmit="return confirm('¿Eliminar esta actividad del programa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Eliminar</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay actividades registradas en el programa académico.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
