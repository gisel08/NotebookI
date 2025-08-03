<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\TaskNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage; // Añadido para manejo de archivos si lo usas en el futuro

class TaskController extends Controller
{
    /**
     * Muestra un listado de las tareas para el usuario autenticado.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->latest()->get();

        $tasks->each(function ($task) {
            $this->updateTaskPriorityAndStatus($task);
        });

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Muestra el formulario para crear una nueva tarea.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Almacena una nueva tarea creada en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_at' => 'required|date|after_or_equal:now',
            'notified_emails' => 'nullable|string',
        ]);

        $task = Auth::user()->tasks()->create($validatedData);

        $this->updateTaskPriorityAndStatus($task);

        if (!empty($task->notified_emails)) {
            $emails = explode(',', $task->notified_emails);
            foreach ($emails as $email) {
                if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) { // Validación adicional
                     Mail::to(trim($email))->send(new TaskNotificationMail($task, 'created'));
                }
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Muestra la tarea especificada.
     */
    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        $this->updateTaskPriorityAndStatus($task);

        return view('tasks.show', compact('task'));
    }

    /**
     * Muestra el formulario para editar la tarea especificada.
     */
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Actualiza la tarea especificada en la base de datos.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_at' => 'required|date',
            'notified_emails' => 'nullable|string',
            'status' => 'required|in:pending,completed,delayed',
        ]);

        $oldStatus = $task->status;
        $oldPriority = $task->priority;

        $task->update($validatedData);

        $this->updateTaskPriorityAndStatus($task);

        if (!empty($task->notified_emails)) {
            $emails = explode(',', $task->notified_emails);
            $type = 'updated';
            if ($task->status === 'completed' && $oldStatus !== 'completed') {
                $type = 'completed';
            } elseif ($task->status === 'delayed' && $oldStatus !== 'delayed') {
                $type = 'delayed';
            }
            foreach ($emails as $email) {
                if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) { // Validación adicional
                    Mail::to(trim($email))->send(new TaskNotificationMail($task, $type));
                }
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Elimina la tarea especificada de la base de datos.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada exitosamente.');
    }

    /**
     * Método auxiliar para actualizar la prioridad y el estado de la tarea.
     */
    private function updateTaskPriorityAndStatus(Task $task)
    {
        $now = Carbon::now();
        $dueAt = Carbon::parse($task->due_at);

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
        $task->save();
    }
}