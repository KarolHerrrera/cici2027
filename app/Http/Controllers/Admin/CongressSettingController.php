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
            'settings'   => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($data['settings'] as $key => $value) {
            CongressSetting::where('key', $key)->update([
                'value' => $value !== null ? trim($value) : '',
            ]);
        }

        CongressSetting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('status', 'Configuración general y enlaces institucionales actualizados.');
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
