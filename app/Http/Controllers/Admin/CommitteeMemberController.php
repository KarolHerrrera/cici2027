<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThematicAxis;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Gestión de cuentas del comité científico (líder y evaluadores). */
class CommitteeMemberController extends Controller
{
    public function index(): View
    {
        return view('admin.committee.index', [
            'members' => User::with('axis')
                ->where(fn ($q) => $q->where('is_committee', true)->orWhere('is_committee_leader', true))
                ->orderByDesc('is_committee_leader')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.committee.create', [
            'axes' => ThematicAxis::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'            => ['required', 'confirmed', Password::min(8)],
            'specialty'           => ['nullable', 'string', 'max:255'],
            'thematic_axis_id'    => ['nullable', 'exists:thematic_axes,id'],
            'is_committee_leader' => ['nullable', 'boolean'],
        ], [], [
            'name'             => 'nombre',
            'email'            => 'correo',
            'password'         => 'contraseña',
            'specialty'        => 'especialidad o programa',
            'thematic_axis_id' => 'eje temático',
        ]);

        User::create([
            'name'                => $data['name'],
            'email'               => $data['email'],
            'password'            => $data['password'],
            'specialty'           => $data['specialty'] ?? null,
            'thematic_axis_id'    => $data['thematic_axis_id'] ?? null,
            'is_committee'        => true,
            'is_committee_leader' => (bool) ($data['is_committee_leader'] ?? false),
        ]);

        return redirect()->route('admin.committee.index')->with('status', 'Miembro del comité creado.');
    }

    public function edit(User $member): View
    {
        abort_unless($member->is_committee || $member->is_committee_leader, 404);

        return view('admin.committee.edit', [
            'member' => $member,
            'axes'   => ThematicAxis::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        abort_unless($member->is_committee || $member->is_committee_leader, 404);

        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($member->id)],
            'password'            => ['nullable', 'confirmed', Password::min(8)],
            'specialty'           => ['nullable', 'string', 'max:255'],
            'thematic_axis_id'    => ['nullable', 'exists:thematic_axes,id'],
            'is_committee_leader' => ['nullable', 'boolean'],
        ], [], [
            'name'             => 'nombre',
            'email'            => 'correo',
            'password'         => 'contraseña',
            'specialty'        => 'especialidad o programa',
            'thematic_axis_id' => 'eje temático',
        ]);

        $payload = [
            'name'                => $data['name'],
            'email'               => $data['email'],
            'specialty'           => $data['specialty'] ?? null,
            'thematic_axis_id'    => $data['thematic_axis_id'] ?? null,
            'is_committee'        => true,
            'is_committee_leader' => (bool) ($data['is_committee_leader'] ?? false),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $member->update($payload);

        return redirect()->route('admin.committee.index')->with('status', 'Miembro del comité actualizado.');
    }

    public function destroy(User $member): RedirectResponse
    {
        abort_unless(($member->is_committee || $member->is_committee_leader) && ! $member->is_admin, 404);

        $member->delete();

        return redirect()->route('admin.committee.index')->with('status', 'Miembro del comité eliminado.');
    }
}
