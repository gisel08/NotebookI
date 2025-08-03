<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $task;
    public $notificationType;

    public function __construct(Task $task, string $notificationType)
    {
        $this->task = $task;
        $this->notificationType = $notificationType;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->notificationType) {
            'created' => 'Nueva Tarea: ' . $this->task->title,
            'updated' => 'Tarea Actualizada: ' . $this->task->title,
            'completed' => 'Tarea Completada: ' . $this->task->title,
            'delayed' => 'Tarea Atrasada: ' . $this->task->title,
            default => 'Notificación de Tarea: ' . $this->task->title,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tasks.notification',
            with: [
                'task' => $this->task,
                'notificationType' => $this->notificationType,
                'creatorEmail' => $this->task->user->email,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}