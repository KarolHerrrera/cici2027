<x-guest-layout>
    <div class="mb-5">
        <h1 class="text-lg font-bold text-gray-900">Iniciar sesión</h1>
        <p class="text-sm text-gray-600">
            Acceso exclusivo para el <strong>Líder y miembros del Comité Científico</strong> y el <strong>Administrador</strong>.
            Si eres ponente postulante, <a href="{{ route('submissions.status') }}" class="text-indigo-600 font-semibold hover:underline">consulta tu estado con tu cédula aquí</a>.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Correo institucional" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Mantener sesión iniciada</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center py-2.5">
                Ingresar al panel
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
