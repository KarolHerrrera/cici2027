<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');

        $messages = ContactMessage::with('responder')
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'total'      => ContactMessage::count(),
            'nuevo'      => ContactMessage::where('status', 'nuevo')->count(),
            'en_proceso' => ContactMessage::where('status', 'en_proceso')->count(),
            'respondido' => ContactMessage::where('status', 'respondido')->count(),
        ];

        return view('admin.messages.index', compact('messages', 'counts', 'statusFilter'));
    }

    public function update(Request $request, ContactMessage $message): RedirectResponse
    {
        $data = $request->validate([
            'status'      => ['required', 'in:nuevo,en_proceso,respondido'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $message->update([
            'status'       => $data['status'],
            'admin_notes'  => $data['admin_notes'] ?? null,
            'responded_by' => Auth::id(),
            'responded_at' => $data['status'] === 'respondido' ? now() : $message->responded_at,
        ]);

        return redirect()->route('admin.messages.index')
            ->with('status', 'Estado del mensaje de contacto actualizado.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('status', 'Mensaje de contacto eliminado.');
    }
}
