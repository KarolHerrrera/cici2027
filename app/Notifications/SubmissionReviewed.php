<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionReviewed extends Notification
{
    public function __construct(public Submission $submission)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $s = $this->submission;

        $mail = (new MailMessage)
            ->subject('CICI 2027 · Dictamen del Comité Científico ('.$s->tracking_code.')')
            ->greeting('Cordial saludo, '.$s->name)
            ->line('El Comité Científico del IV Congreso Internacional de Ciencia e Ingeniería (CICI 2027) ha evaluado tu resumen:')
            ->line('**Radicado:** '.$s->tracking_code)
            ->line('**Título:** «'.$s->title.'»');

        if ($s->status === Submission::STATUS_APPROVED) {
            $mail->line('**Dictamen:** APROBADO en modalidad de **'.($s->modalityLabel() ?? 'participación').'**.');

            if ($s->isPoster()) {
                $posterUrl = config('cici.poster_template_url') ?: route('documents.show', 'plantilla-poster');
                $mail->line('Tu propuesta fue aprobada en modalidad **Póster**. Desde la consulta de estado podrás descargar la plantilla oficial de póster científico ('.$posterUrl.').');
            } elseif ($s->isOral()) {
                $oralUrl = config('cici.oral_template_url') ?: route('documents.show', 'plantilla-oral');
                $mail->line('Tu propuesta fue aprobada en modalidad **Ponencia oral**. Desde la consulta de estado podrás descargar la plantilla y guía de presentación ('.$oralUrl.').');
            }
        } else {
            $mail->line('**Dictamen:** NO APROBADO en esta convocatoria.');
        }

        if ($s->score !== null) {
            $mail->line('**Puntaje de evaluación:** '.$s->score.' / 100');
        }

        if ($s->review_comment) {
            $mail->line('**Observaciones del Comité Científico:** '.$s->review_comment);
        }

        return $mail
            ->action('Consultar estado con mi cédula', route('submissions.status', ['documento' => $s->document]))
            ->line('Recuerda que puedes consultar en cualquier momento el estado y descargar los formatos ingresando únicamente tu número de cédula ('.$s->document.') o código de radicado ('.$s->tracking_code.').');
    }
}
