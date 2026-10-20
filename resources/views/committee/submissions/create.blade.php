<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar resumen manualmente</h2>
            <a href="{{ route('committee.submissions.index') }}" class="text-sm text-indigo-600 hover:underline">← Volver a la lista</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="mb-6 text-sm text-gray-600">
                    Los postulantes pueden enviar su resumen directamente desde <a href="{{ route('submissions.public.create') }}" class="text-indigo-600 underline" target="_blank">/enviar-resumen</a>.
                    También puedes usar este formulario si recibiste un resumen por correo institucional y deseas cargarlo al sistema.
                </p>

                <form method="POST" action="{{ route('committee.submissions.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="document_type" value="Tipo de documento" />
                            <select id="document_type" name="document_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="CC">Cédula de ciudadanía (CC)</option>
                                <option value="CE">Cédula de extranjería (CE)</option>
                                <option value="TI">Tarjeta de identidad (TI)</option>
                                <option value="Pasaporte">Pasaporte / DNI</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="document" value="Número de cédula / documento *" />
                            <x-text-input id="document" name="document" type="text" class="mt-1 block w-full" :value="old('document')" required />
                            <x-input-error :messages="$errors->get('document')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="name" value="Nombre completo del autor principal *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Correo electrónico *" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Teléfono / WhatsApp" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="institution" value="Institución / Universidad" />
                            <x-text-input id="institution" name="institution" type="text" class="mt-1 block w-full" :value="old('institution')" />
                            <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="city" value="Ciudad / Municipio" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city')" />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="country" value="País" />
                            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', 'Colombia')" />
                            <x-input-error :messages="$errors->get('country')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="coauthors" value="Coautores (opcional)" />
                            <x-text-input id="coauthors" name="coauthors" type="text" class="mt-1 block w-full" :value="old('coauthors')" />
                            <x-input-error :messages="$errors->get('coauthors')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="title" value="Título del resumen / ponencia *" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="thematic_axis_id" value="Eje temático" />
                            <select id="thematic_axis_id" name="thematic_axis_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Sin eje</option>
                                @foreach ($axes as $axis)
                                    <option value="{{ $axis->id }}" @selected(old('thematic_axis_id') == $axis->id)>{{ $axis->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('thematic_axis_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="requested_modality" value="Modalidad solicitada por el autor" />
                            <select id="requested_modality" name="requested_modality"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="oral" @selected(old('requested_modality', 'oral') === 'oral')>Ponencia oral</option>
                                <option value="poster" @selected(old('requested_modality') === 'poster')>Póster</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="abstract_text" value="Texto del resumen" />
                            <textarea id="abstract_text" name="abstract_text" rows="5"
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('abstract_text') }}</textarea>
                            <x-input-error :messages="$errors->get('abstract_text')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="keywords" value="Palabras clave" />
                            <x-text-input id="keywords" name="keywords" type="text" class="mt-1 block w-full" :value="old('keywords')" />
                        </div>

                        <div>
                            <x-input-label for="file" value="Archivo adjunto (PDF, DOC, DOCX)" />
                            <input id="file" name="file" type="file" accept=".pdf,.doc,.docx" class="mt-2 block w-full text-sm text-gray-700">
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="file_url" value="O enlace externo al resumen (opcional)" />
                            <x-text-input id="file_url" name="file_url" type="url" class="mt-1 block w-full" :value="old('file_url')" />
                            <x-input-error :messages="$errors->get('file_url')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <x-primary-button>Guardar resumen</x-primary-button>
                        <a href="{{ route('committee.submissions.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
