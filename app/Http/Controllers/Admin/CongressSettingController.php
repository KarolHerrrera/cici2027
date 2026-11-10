<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CongressSetting;
use App\Models\RegistrationFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CongressSettingController extends Controller
{
    public function index(): View
    {
        $settings = CongressSetting::orderBy('group')->orderBy('id')->get();
        $fees     = RegistrationFee::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.settings.index', compact('settings', 'fees'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'settings'        => ['required', 'array'],
            'settings.*'      => ['nullable', 'string', 'max:1000'],
            'logo_white_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
            'logo_sigla_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
            'reset_logos'     => ['nullable', 'boolean'],
        ]);

        $labels = [
            'event_name'          => 'Nombre oficial del evento',
            'event_year'          => 'Año de la edición (ej. 2027 o 2028)',
            'event_edition_badge' => 'Insignia de edición en portada (ej. IV edición · 2027)',
            'event_dates_label'   => 'Fechas de realización',
            'event_start_iso'     => 'Fecha/hora de inicio para el reloj regresivo (formato ISO)',
            'event_modality'      => 'Modalidad del evento',
            'event_logo_white'    => 'Logo principal blanco (Banner y Pie de página)',
            'event_logo_sigla'    => 'Logo sigla barra superior e ícono',
        ];

        foreach ($data['settings'] as $key => $value) {
            $existing = CongressSetting::where('key', $key)->first();
            if ($existing) {
                $existing->update(['value' => $value !== null ? trim($value) : '']);
            } else {
                CongressSetting::create([
                    'key'   => $key,
                    'label' => $labels[$key] ?? $key,
                    'value' => $value !== null ? trim($value) : '',
                    'group' => str_starts_with($key, 'event_logo') ? 'identidad' : 'general',
                ]);
            }
        }

        // Subida directa de logos institucionales a public/images/custom/ (funciona sin depender de symlinks)
        $customDir = public_path('images/custom');
        if (! is_dir($customDir)) {
            @mkdir($customDir, 0775, true);
        }

        if ($request->boolean('reset_logos')) {
            CongressSetting::updateOrCreate(
                ['key' => 'event_logo_white'],
                ['label' => $labels['event_logo_white'], 'value' => '', 'group' => 'identidad']
            );
            CongressSetting::updateOrCreate(
                ['key' => 'event_logo_sigla'],
                ['label' => $labels['event_logo_sigla'], 'value' => '/images/cici-sigla.png', 'group' => 'identidad']
            );
        }

        if ($request->hasFile('logo_white_file')) {
            $file     = $request->file('logo_white_file');
            $filename = 'logo-hero-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($customDir, $filename);
            CongressSetting::updateOrCreate(
                ['key' => 'event_logo_white'],
                ['label' => $labels['event_logo_white'], 'value' => '/images/custom/' . $filename, 'group' => 'identidad']
            );
        }

        if ($request->hasFile('logo_sigla_file')) {
            $file     = $request->file('logo_sigla_file');
            $filename = 'logo-sigla-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($customDir, $filename);
            CongressSetting::updateOrCreate(
                ['key' => 'event_logo_sigla'],
                ['label' => $labels['event_logo_sigla'], 'value' => '/images/custom/' . $filename, 'group' => 'identidad']
            );
        }

        CongressSetting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('status', 'Configuración general, año de edición y logos institucionales actualizados correctamente.');
    }

    public function storeFee(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'profile'         => ['required', 'string', 'max:180'],
            'description'     => ['nullable', 'string', 'max:400'],
            'price_formatted' => ['required', 'string', 'max:40'],
            'price_amount'    => ['nullable', 'integer', 'min:0'],
            'includes'        => ['nullable', 'string', 'max:300'],
            'sort_order'      => ['nullable', 'integer', 'min:1'],
            'is_active'       => ['nullable', 'boolean'],
        ]);

        $data['price_amount'] = (int) ($data['price_amount'] ?? preg_replace('/\D/', '', $data['price_formatted']));
        $data['sort_order']   = (int) ($data['sort_order'] ?? (RegistrationFee::max('sort_order') + 1));
        $data['is_active']    = (bool) ($data['is_active'] ?? true);

        RegistrationFee::create($data);

        return redirect()->route('admin.settings.index')->with('status', 'Tarifa de inscripción agregada.');
    }

    public function updateFee(Request $request, RegistrationFee $fee): RedirectResponse
    {
        $data = $request->validate([
            'profile'         => ['required', 'string', 'max:180'],
            'description'     => ['nullable', 'string', 'max:400'],
            'price_formatted' => ['required', 'string', 'max:40'],
            'price_amount'    => ['nullable', 'integer', 'min:0'],
            'includes'        => ['nullable', 'string', 'max:300'],
            'sort_order'      => ['nullable', 'integer', 'min:1'],
            'is_active'       => ['nullable', 'boolean'],
        ]);

        $data['price_amount'] = (int) ($data['price_amount'] ?? preg_replace('/\D/', '', $data['price_formatted']));
        $data['sort_order']   = (int) ($data['sort_order'] ?? $fee->sort_order);
        $data['is_active']    = (bool) ($data['is_active'] ?? false);

        $fee->update($data);

        return redirect()->route('admin.settings.index')->with('status', 'Tarifa actualizada.');
    }

    public function destroyFee(RegistrationFee $fee): RedirectResponse
    {
        $fee->delete();

        return redirect()->route('admin.settings.index')->with('status', 'Tarifa eliminada.');
    }
}
