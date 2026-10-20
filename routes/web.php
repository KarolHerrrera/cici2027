<?php

use App\Http\Controllers\Admin\CommitteeMemberController;
use App\Http\Controllers\Admin\ImportantDateController;
use App\Http\Controllers\Admin\SpeakerController;
use App\Http\Controllers\Admin\ThematicAxisController;
use App\Http\Controllers\Committee\SubmissionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Documentos y plantillas oficiales del congreso (póster, oral, formato de resumen, convocatoria).
Route::get('/documentos/{slug}', [DocumentController::class, 'show'])->name('documents.show');

// Postulación pública de resúmenes.
Route::get('/enviar-resumen', [SubmissionStatusController::class, 'create'])->name('submissions.public.create');
Route::post('/enviar-resumen', [SubmissionStatusController::class, 'store'])
    ->middleware('throttle:15,1')
    ->name('submissions.public.store');

// Consulta pública del estado del resumen (con cédula o código de radicado).
Route::get('/estado-resumen', [SubmissionStatusController::class, 'form'])->name('submissions.status');
Route::post('/estado-resumen', [SubmissionStatusController::class, 'lookup'])
    ->middleware('throttle:20,1')
    ->name('submissions.lookup');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel de Administración general (solo administradores).
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::resource('speakers', SpeakerController::class)->except('show');

    // Cuentas del comité científico (líder y evaluadores).
    Route::get('comite', [CommitteeMemberController::class, 'index'])->name('committee.index');
    Route::get('comite/crear', [CommitteeMemberController::class, 'create'])->name('committee.create');
    Route::post('comite', [CommitteeMemberController::class, 'store'])->name('committee.store');
    Route::get('comite/{member}/editar', [CommitteeMemberController::class, 'edit'])->name('committee.edit');
    Route::put('comite/{member}', [CommitteeMemberController::class, 'update'])->name('committee.update');
    Route::delete('comite/{member}', [CommitteeMemberController::class, 'destroy'])->name('committee.destroy');

    // Cronograma de fechas importantes.
    Route::get('fechas', [ImportantDateController::class, 'index'])->name('dates.index');
    Route::post('fechas', [ImportantDateController::class, 'store'])->name('dates.store');
    Route::put('fechas/{date}', [ImportantDateController::class, 'update'])->name('dates.update');
    Route::delete('fechas/{date}', [ImportantDateController::class, 'destroy'])->name('dates.destroy');

    // Ejes temáticos y líneas de investigación.
    Route::get('ejes', [ThematicAxisController::class, 'index'])->name('axes.index');
    Route::post('ejes', [ThematicAxisController::class, 'store'])->name('axes.store');
    Route::put('ejes/{axis}', [ThematicAxisController::class, 'update'])->name('axes.update');
    Route::delete('ejes/{axis}', [ThematicAxisController::class, 'destroy'])->name('axes.destroy');
    Route::post('ejes/{axis}/lineas', [ThematicAxisController::class, 'storeTopic'])->name('axes.topics.store');
    Route::delete('lineas/{topic}', [ThematicAxisController::class, 'destroyTopic'])->name('axes.topics.destroy');
});

// Panel del Comité Científico (revisión, aprobación por modalidad y notificaciones).
Route::middleware(['auth', 'reviewer'])->prefix('comite')->name('committee.')->group(function () {
    Route::get('resumenes', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('resumenes-exportar', [SubmissionController::class, 'export'])->name('submissions.export');
    Route::get('resumenes/registrar', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('resumenes', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('resumenes/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::post('resumenes/{submission}/revision', [SubmissionController::class, 'review'])->name('submissions.review');
    Route::delete('resumenes/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
});

require __DIR__.'/auth.php';
