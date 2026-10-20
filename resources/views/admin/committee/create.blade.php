<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo miembro del Comité Científico</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.committee.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Nombre completo *" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Correo electrónico institucional *" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="specialty" value="Programa o especialidad (ej. Ingeniería Civil)" />
                            <x-text-input id="specialty" name="specialty" type="text" class="mt-1 block w-full" :value="old('specialty')" placeholder="Ej. Ingeniería Civil" />
                            <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="thematic_axis_id" value="Eje temático principal" />
                            <select id="thematic_axis_id" name="thematic_axis_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Todos los ejes</option>
                                @foreach ($axes as $axis)
                                    <option value="{{ $axis->id }}" @selected(old('thematic_axis_id') == $axis->id)>
                                        {{ $axis->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('thematic_axis_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="p-4 rounded-lg bg-amber-50 border border-amber-200">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_committee_leader" value="1"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                   @checked(old('is_committee_leader'))>
                            <span class="text-sm font-semibold text-amber-900">Designar como Líder del Comité Científico</span>
                        </label>
                        <p class="mt-1 text-xs text-amber-700">
                            El líder encabeza la evaluación de resúmenes y emite el dictamen final de modalidad (Ponencia oral o Póster).
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="password" value="Contraseña (mín. 8 caracteres) *" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" value="Confirmar contraseña *" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-4">
                        <x-primary-button>Crear miembro</x-primary-button>
                        <a href="{{ route('admin.committee.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
