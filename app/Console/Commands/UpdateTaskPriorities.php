<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;
use App\Mail\TaskNotificationMail;
use Illuminate\Support\Facades\Mail;

class UpdateTaskPriorities extends Command
{
    protected $signature = 'tasks:update-priorities';
    protected $description = 'Actualiza las prioridades de las tareas según la fecha de vencimiento y el estado.';

    public function handle()
    {
        $this->info('Iniciando la actualización de prioridades de tareas...');

        $tasks = Task::all();
        $now = Carbon::now();

        foreach ($tasks as $task) {
            $dueAt = Carbon::parse($task->due_at);

            $oldPriority = $task->priority;
            $oldStatus = $task->status;

            if ($task->status === 'completed') {
                $task->priority = 'completed';
            } elseif ($dueAt->isPast()) {
                $task->priority = 'delayed';
                if ($task->status !== 'completed') {
                    $task->status = 'delayed';
                }
            } elseif ($dueAt->diffInHours($now) <= 24) {
                $task->priority = 'urgent';
                if ($task->status !== 'completed' && $task->status !== 'delayed') {
                    $task->status = 'pending';
                }
            } elseif ($dueAt->diffInHours($now) <= 72) {
                $task->priority = 'important';
                if ($task->status !== 'completed' && $task->status !== 'delayed') {
                    $task->status = 'pending';
                }
            } else {
                $task->priority = 'normal';
                if ($task->status !== 'completed' && $task->status !== 'delayed') {
                    $task->status = 'pending';
                }
            }

            if ($task->isDirty('priority') || $task->isDirty('status')) {
                $task->save();
                $this->info("Task '{$task->title}' updated: Old Priority: {$oldPriority} -> New Priority: {$task->priority}, Old Status: {$oldStatus} -> New Status: {$task->status}");

                if (!empty($task->notified_emails)) {
                    $emails = explode(',', $task->notified_emails);
                    $notificationType = 'updated';
                    if ($task->priority === 'delayed' && $oldPriority !== 'delayed') {
                        $notificationType = 'delayed';
                    } elseif ($task->status === 'completed' && $oldStatus !== 'completed') {
                        $notificationType = 'completed';
                    }

                    foreach ($emails as $email) {
                        if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                            Mail::to(trim($email))->send(new TaskNotificationMail($task, $notificationType));
                        }
                    }
                }
            }
        }

        $this->info('Actualización de prioridades de tareas completada.');
    }
}