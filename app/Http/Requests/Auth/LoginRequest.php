<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Autoprotección para despliegues en la nube (Render): si la BD recién migrada aún no tiene las cuentas oficiales, sembrarlas automáticamente.
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users') && \App\Models\User::where('email', 'admin@cici.test')->doesntExist()) {
                (new \Database\Seeders\DatabaseSeeder())->run();
            }
        } catch (\Throwable $e) {
            // Si algún seeder secundario falla, garantizar igualmente las cuentas de acceso principales:
            try {
                \App\Models\User::updateOrCreate(
                    ['email' => 'admin@cici.test'],
                    [
                        'name'     => 'Administrador CICI 2027',
                        'password' => \Illuminate\Support\Facades\Hash::make('Cici2027*'),
                        'is_admin' => true,
                    ]
                );
                \App\Models\User::updateOrCreate(
                    ['email' => 'comite@cici.test'],
                    [
                        'name'                => 'Prof. Fabián · Líder Comité Científico',
                        'password'            => \Illuminate\Support\Facades\Hash::make('Comite2027*'),
                        'is_committee'        => true,
                        'is_committee_leader' => true,
                        'specialty'           => 'Ingeniería Civil',
                    ]
                );
            } catch (\Throwable) {
            }
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
