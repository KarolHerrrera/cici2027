<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\ThematicAxis;
use App\Models\User;
use App\Notifications\SubmissionReviewed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubmissionReviewTest extends TestCase
{
    use RefreshDatabase;

    private function submission(array $attrs = []): Submission
    {
        return Submission::create($attrs + [
            'document'    => '1085123456',
            'name'        => 'Ana Postulante',
            'email'       => 'ana@example.com',
            'institution' => 'Universidad Mariana',
            'title'       => 'Concreto reciclado',
        ]);
    }

    public function test_applicant_can_submit_abstract_from_public_form(): void
    {
        Storage::fake('public');
        $axis = ThematicAxis::create(['name' => 'Infraestructura Civil', 'sort_order' => 1]);
        $file = UploadedFile::fake()->create('resumen-ana.pdf', 120, 'application/pdf');

        $response = $this->post('/enviar-resumen', [
            'document_type'      => 'CC',
            'document'           => '1.085.999.888',
            'name'               => 'Carlos Ponente',
            'email'              => 'carlos@umariana.edu.co',
            'phone'              => '3148521240',
            'institution'        => 'Universidad Mariana',
            'city'               => 'Pasto',
            'country'            => 'Colombia',
            'title'              => 'Análisis sísmico de puentes',
            'thematic_axis_id'   => $axis->id,
            'requested_modality' => 'oral',
            'abstract_text'      => 'Resumen detallado del trabajo de investigación sobre puentes.',
            'keywords'           => 'puentes, sismo, estructuras',
            'file'               => $file,
        ]);

        $submission = Submission::where('document', '1085999888')->firstOrFail();
        $this->assertNotEmpty($submission->tracking_code);
        $this->assertNotNull($submission->file_path);
        Storage::disk('public')->assertExists($submission->file_path);

        $response->assertRedirect(route('submissions.status', [
            'documento' => '1085999888',
            'radicado'  => $submission->tracking_code,
        ]));
    }

    public function test_guests_and_regular_users_cannot_enter_the_committee_panel(): void
    {
        $this->get('/comite/resumenes')->assertRedirect('/login');

        $this->actingAs(User::factory()->create())
            ->get('/comite/resumenes')
            ->assertForbidden();
    }

    public function test_committee_member_can_approve_as_poster_and_notify(): void
    {
        Notification::fake();
        $member = User::factory()->create(['is_committee' => true, 'is_committee_leader' => true]);
        $submission = $this->submission();

        $this->actingAs($member)
            ->post("/comite/resumenes/{$submission->id}/revision", [
                'decision'       => 'approved',
                'modality'       => 'poster',
                'score'          => 92,
                'review_comment' => 'Aprobado para sesión de pósteres.',
            ])
            ->assertRedirect();

        $submission->refresh();
        $this->assertSame('approved', $submission->status);
        $this->assertSame('poster', $submission->modality);
        $this->assertSame(92, $submission->score);
        $this->assertSame($member->id, $submission->reviewed_by);
        $this->assertNotNull($submission->notified_at);
        Notification::assertSentOnDemand(SubmissionReviewed::class);
    }

    public function test_approving_requires_a_modality(): void
    {
        $member = User::factory()->create(['is_committee' => true]);
        $submission = $this->submission();

        $this->actingAs($member)
            ->post("/comite/resumenes/{$submission->id}/revision", ['decision' => 'approved'])
            ->assertSessionHasErrors('modality');

        $this->assertSame('pending', $submission->fresh()->status);
    }

    public function test_rejecting_clears_modality(): void
    {
        Notification::fake();
        $member = User::factory()->create(['is_committee' => true]);
        $submission = $this->submission(['status' => 'approved', 'modality' => 'oral']);

        $this->actingAs($member)
            ->post("/comite/resumenes/{$submission->id}/revision", ['decision' => 'rejected'])
            ->assertRedirect();

        $this->assertSame('rejected', $submission->fresh()->status);
        $this->assertNull($submission->fresh()->modality);
    }

    public function test_public_status_works_with_cedula_alone_and_shows_poster_template_download(): void
    {
        $sub = $this->submission(['status' => 'approved', 'modality' => 'poster']);

        // Consulta únicamente con la cédula (con puntos o guiones), sin obligar a poner contraseña ni correo.
        $this->post('/estado-resumen', ['document' => '1.085.123-456'])
            ->assertOk()
            ->assertSee('Concreto reciclado')
            ->assertSee('Póster')
            ->assertSee('Descargar plantilla de póster');

        // La página de la plantilla de póster responde 200.
        $this->get('/documentos/plantilla-poster')->assertOk()->assertSee('90 cm');

        // Consulta también por código de radicado.
        $this->get('/estado-resumen?documento='.$sub->tracking_code)
            ->assertOk()
            ->assertSee('Concreto reciclado');
    }

    public function test_admin_can_create_committee_leader_and_manage_modules(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post('/admin/comite', [
            'name'                  => 'Líder Comité Civil',
            'email'                 => 'lider@example.com',
            'specialty'             => 'Ingeniería Civil',
            'is_committee_leader'   => '1',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/admin/comite');

        $member = User::where('email', 'lider@example.com')->firstOrFail();
        $this->assertTrue($member->is_committee);
        $this->assertTrue($member->is_committee_leader);
        $this->assertSame('Ingeniería Civil', $member->specialty);

        $this->actingAs($member)->get('/comite/resumenes')->assertOk();
        $this->actingAs($member)->get('/comite/resumenes-exportar')->assertOk();
        $this->actingAs($member)->get('/admin/comite')->assertForbidden();

        // El formulario de nuevo ponente existe y carga sin errores.
        $this->actingAs($admin)->get('/admin/speakers/create')->assertOk();
    }
}
