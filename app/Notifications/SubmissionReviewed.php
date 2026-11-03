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

            if ($s->isPoster() && config('cici.poster_template_url')) {
                $mail->line('Tu propuesta fue aprobada en modalidad **Póster**. Puedes descargar la plantilla oficial desde la consulta de estado ('.config('cici.poster_template_url').').');
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
            ->line('Recuerda que puedes consultar el estado ingresando tu número de cédula ('.$s->document.') o código de radicado ('.$s->tracking_code.').');
    }
}
