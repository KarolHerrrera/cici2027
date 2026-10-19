<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    <div class="md:col-span-2">
        <x-input-label for="name" value="Nombre completo" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $speaker->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="job_title" value="Cargo" />
        <x-text-input id="job_title" name="job_title" type="text" class="mt-1 block w-full" :value="old('job_title', $speaker->job_title)" />
        <x-input-error :messages="$errors->get('job_title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="specialty" value="Especialidad" />
        <x-text-input id="specialty" name="specialty" type="text" class="mt-1 block w-full" :value="old('specialty', $speaker->specialty)" />
        <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="institution" value="Institución" />
        <x-text-input id="institution" name="institution" type="text" class="mt-1 block w-full" :value="old('institution', $speaker->institution)" required />
        <x-input-error :messages="$errors->get('institution')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="city" value="Ciudad" />
        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $speaker->city)" />
        <x-input-error :messages="$errors->get('city')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="country" value="País" />
        <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $speaker->country)" required />
        <x-input-error :messages="$errors->get('country')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="thematic_axis_id" value="Eje temático" />
        <select id="thematic_axis_id" name="thematic_axis_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Sin eje asignado</option>
            @foreach ($axes as $axis)
                <option value="{{ $axis->id }}" @selected(old('thematic_axis_id', $speaker->thematic_axis_id) == $axis->id)>
                    {{ $axis->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('thematic_axis_id')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="talk_title" value="Título de la ponencia" />
        <x-text-input id="talk_title" name="talk_title" type="text" class="mt-1 block w-full" :value="old('talk_title', $speaker->talk_title)" />
        <x-input-error :messages="$errors->get('talk_title')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="bio" value="Biografía" />
        <textarea id="bio" name="bio" rows="5"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $speaker->bio) }}</textarea>
        <x-input-error :messages="$errors->get('bio')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sort_order" value="Orden en la lista (0 = primero)" />
        <x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full" :value="old('sort_order', $speaker->sort_order ?? 0)" />
        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="photo" value="Foto (JPG, PNG o WEBP, máximo 2 MB)" />
        @if ($speaker->photo)
            <img src="{{ asset('storage/'.$speaker->photo) }}" alt="Foto actual de {{ $speaker->name }}"
                 class="mt-2 h-20 w-20 rounded-full object-cover">
        @endif
        <input id="photo" name="photo" type="file" accept="image/*" class="mt-2 block w-full text-sm text-gray-700">
        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
    </div>
</div>

<div class="mt-8 flex items-center gap-4">
    <x-primary-button>Guardar</x-primary-button>
    <a href="{{ route('admin.speakers.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
</div>