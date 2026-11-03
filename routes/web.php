<?php

use App\Http\Controllers\Admin\CommitteeMemberController;
use App\Http\Controllers\Admin\CongressDocumentController;
use App\Http\Controllers\Admin\CongressSettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ImportantDateController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\PortalContentController;
use App\Http\Controllers\Admin\ScheduleItemController;
use App\Http\Controllers\Admin\SpeakerController;
use App\Http\Controllers\Admin\ThematicAxisController;
use App\Http\Controllers\Committee\SubmissionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Documentos y plantillas oficiales del congreso (póster, oral, formato de resumen, convocatoria).
Route::get('/documentos/{slug}', [DocumentController::class, 'show'])->name('documents.show');

// Formulario público de contacto y mesa de ayuda (RF-13).
Route::post('/contacto', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

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

    // Cronograma de fechas importantes (RF-05).
    Route::get('fechas', [ImportantDateController::class, 'index'])->name('dates.index');
    Route::post('fechas', [ImportantDateController::class, 'store'])->name('dates.store');
    Route::put('fechas/{date}', [ImportantDateController::class, 'update'])->name('dates.update');
    Route::delete('fechas/{date}', [ImportantDateController::class, 'destroy'])->name('dates.destroy');

    // Ejes temáticos y líneas de investigación (RF-02).
    Route::get('ejes', [ThematicAxisController::class, 'index'])->name('axes.index');
    Route::post('ejes', [ThematicAxisController::class, 'store'])->name('axes.store');
    Route::put('ejes/{axis}', [ThematicAxisController::class, 'update'])->name('axes.update');
    Route::delete('ejes/{axis}', [ThematicAxisController::class, 'destroy'])->name('axes.destroy');
    Route::post('ejes/{axis}/lineas', [ThematicAxisController::class, 'storeTopic'])->name('axes.topics.store');
    Route::delete('lineas/{topic}', [ThematicAxisController::class, 'destroyTopic'])->name('axes.topics.destroy');

    // Configuración general, enlaces institucionales y tarifas (RF-01 y RF-06).
    Route::get('configuracion', [CongressSettingController::class, 'index'])->name('settings.index');
    Route::put('configuracion', [CongressSettingController::class, 'updateSettings'])->name('settings.update');
    Route::post('tarifas', [CongressSettingController::class, 'storeFee'])->name('fees.store');
    Route::put('tarifas/{fee}', [CongressSettingController::class, 'updateFee'])->name('fees.update');
    Route::delete('tarifas/{fee}', [CongressSettingController::class, 'destroyFee'])->name('fees.destroy');

    // Programa y agenda académica (RF-04).
    Route::get('programa', [ScheduleItemController::class, 'index'])->name('schedule.index');
    Route::post('programa', [ScheduleItemController::class, 'store'])->name('schedule.store');
    Route::put('programa/{item}', [ScheduleItemController::class, 'update'])->name('schedule.update');
    Route::delete('programa/{item}', [ScheduleItemController::class, 'destroy'])->name('schedule.destroy');

    // Documentos descargables y plantillas oficiales (RF-07).
    Route::get('documentos', [CongressDocumentController::class, 'index'])->name('documents.index');
    Route::post('documentos', [CongressDocumentController::class, 'store'])->name('documents.store');
    Route::put('documentos/{document}', [CongressDocumentController::class, 'update'])->name('documents.update');
    Route::delete('documentos/{document}', [CongressDocumentController::class, 'destroy'])->name('documents.destroy');

    // Noticias, novedades y avisos (RF-08).
    Route::get('noticias', [NewsPostController::class, 'index'])->name('news.index');
    Route::post('noticias', [NewsPostController::class, 'store'])->name('news.store');
    Route::put('noticias/{news}', [NewsPostController::class, 'update'])->name('news.update');
    Route::delete('noticias/{news}', [NewsPostController::class, 'destroy'])->name('news.destroy');

    // Comité organizador público, Galería histórica, Aliados y Preguntas Frecuentes (RF-09 a RF-12).
    Route::get('contenido-portal', [PortalContentController::class, 'index'])->name('portal.index');
    Route::post('comite-organizador', [PortalContentController::class, 'storeOrganizer'])->name('portal.organizers.store');
    Route::delete('comite-organizador/{organizer}', [PortalContentController::class, 'destroyOrganizer'])->name('portal.organizers.destroy');
    Route::post('galeria', [PortalContentController::class, 'storeGallery'])->name('portal.gallery.store');
    Route::delete('galeria/{gallery}', [PortalContentController::class, 'destroyGallery'])->name('portal.gallery.destroy');
    Route::post('aliados', [PortalContentController::class, 'storeSponsor'])->name('portal.sponsors.store');
    Route::delete('aliados/{sponsor}', [PortalContentController::class, 'destroySponsor'])->name('portal.sponsors.destroy');
    Route::post('faqs', [PortalContentController::class, 'storeFaq'])->name('portal.faqs.store');
    Route::put('faqs/{faq}', [PortalContentController::class, 'updateFaq'])->name('portal.faqs.update');
    Route::delete('faqs/{faq}', [PortalContentController::class, 'destroyFaq'])->name('portal.faqs.destroy');

    // Mensajes de contacto y mesa de ayuda (RF-13).
    Route::get('mensajes', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::put('mensajes/{message}', [ContactMessageController::class, 'update'])->name('messages.update');
    Route::delete('mensajes/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
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
