<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Mesa de Ayuda y Mensajes de Contacto · CICI 2027
                </h2>
                <p class="text-sm text-gray-500">
                    RF-13 · Consultas recibidas desde el formulario público de contacto del congreso.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:underline">← Volver al Panel</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('admin.messages.index') }}" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-slate-600">
                    <div class="text-xs uppercase font-semibold text-gray-500">Total recibidos</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $counts['total'] }}</div>
                </a>
                <a href="{{ route('admin.messages.index', ['status' => 'nuevo']) }}" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-amber-500">
                    <div class="text-xs uppercase font-semibold text-amber-700">Nuevos sin atender</div>
                    <div class="text-2xl font-bold text-amber-600 mt-1">{{ $counts['nuevo'] }}</div>
                </a>
                <a href="{{ route('admin.messages.index', ['status' => 'en_proceso']) }}" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="text-xs uppercase font-semibold text-blue-700">En gestión</div>
                    <div class="text-2xl font-bold text-blue-600 mt-1">{{ $counts['en_proceso'] }}</div>
                </a>
                <a href="{{ route('admin.messages.index', ['status' => 'respondido']) }}" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-emerald-600">
                    <div class="text-xs uppercase font-semibold text-emerald-700">Atendidos</div>
                    <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $counts['respondido'] }}</div>
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @forelse ($messages as $msg)
                    <div class="border border-gray-200 rounded-lg p-5 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $msg->status === 'nuevo' ? 'bg-amber-100 text-amber-800' : ($msg->status === 'respondido' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $msg->statusLabel() }}
                                </span>
                                <span class="font-semibold text-gray-900 ms-2">{{ $msg->subject }}</span>
                                <span class="text-xs text-gray-500 ms-2">({{ strtoupper($msg->category) }})</span>
                            </div>
                            <span class="text-xs text-gray-400">{{ $msg->created_at?->format('Y-m-d H:i') }}</span>
                        </div>

                        <div class="text-xs text-gray-600">
                            <strong>Remitente:</strong> {{ $msg->name }} ·
                            <a href="mailto:{{ $msg->email }}" class="text-indigo-600 underline">{{ $msg->email }}</a>
                            @if ($msg->phone) · Tel: {{ $msg->phone }} @endif
                            @if ($msg->institution) · Institución: {{ $msg->institution }} @endif
                        </div>

                        <p class="text-sm text-gray-800 bg-gray-50 p-3 rounded border border-gray-100 whitespace-pre-line">{{ $msg->message }}</p>

                        <form method="POST" action="{{ route('admin.messages.update', $msg) }}" class="flex flex-wrap items-center gap-3 pt-2">
                            @csrf
                            @method('PUT')
                            <select name="status" class="rounded border-gray-300 text-xs">
                                <option value="nuevo" @selected($msg->status === 'nuevo')>Nuevo</option>
                                <option value="en_proceso" @selected($msg->status === 'en_proceso')>En gestión</option>
                                <option value="respondido" @selected($msg->status === 'respondido')>Atendido</option>
                            </select>
                            <input type="text" name="admin_notes" value="{{ $msg->admin_notes }}" placeholder="Nota interna de seguimiento..." class="flex-1 rounded border-gray-300 text-xs">
                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white rounded text-xs font-semibold hover:bg-indigo-700">
                                Guardar seguimiento
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">No hay mensajes de contacto registrados en este filtro.</p>
                @endforelse

                <div>
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
