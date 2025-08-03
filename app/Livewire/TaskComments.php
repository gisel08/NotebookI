<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Comment;
use Livewire\WithFileUploads; // Para permitir subir archivos
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskComments extends Component
{
    use WithFileUploads; // Habilita la subida de archivos

    public Task $task; // Propiedad para recibir la tarea
    public $content = ''; // Para el contenido del nuevo comentario
    public $file; // Para el archivo adjunto
    public $fileName = ''; // Para el nombre original del archivo

    // Reglas de validación para el formulario de comentario
    protected $rules = [
        'content' => 'required_without_all:file|string|max:1000', // Requerido si no hay archivo
        'file' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx', // Opcional, máx 2MB, tipos permitidos
    ];

    // Mensajes personalizados de validación (opcional)
    protected $messages = [
        'content.required_without_all' => 'El comentario o un archivo son requeridos.',
        'file.max' => 'El archivo no debe exceder los 2MB.',
        'file.mimes' => 'Tipo de archivo no permitido. Solo JPG, PNG, PDF, DOC, DOCX, XLS, XLSX.',
    ];


    // Método de inicialización del componente
    public function mount(Task $task)
    {
        $this->task = $task;
    }

    // Método para añadir un nuevo comentario
    public function addComment()
    {
        $this->validate();

        $filePath = null;
        $fileName = null;

        if ($this->file) {
            // Guarda el archivo en el disco 'public'
            $filePath = $this->file->store('comments_files', 'public');
            $fileName = $this->file->getClientOriginalName();
        }

        $this->task->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        // Limpia los campos después de añadir el comentario
        $this->reset(['content', 'file', 'fileName']);

        // Emite un evento para que Livewire sepa que debe refrescar la lista
        $this->dispatch('commentAdded');
    }

    // Método para eliminar un comentario
    public function deleteComment(Comment $comment)
    {
        // Asegúrate de que el usuario autenticado sea el dueño del comentario
        if ($comment->user_id !== Auth::id()) {
            session()->flash('error', 'No tienes permiso para eliminar este comentario.');
            return;
        }

        // Si el comentario tiene un archivo, elimínalo del almacenamiento
        if ($comment->file_path) {
            Storage::disk('public')->delete($comment->file_path);
        }

        $comment->delete();
        $this->dispatch('commentDeleted'); // Emite un evento para refrescar
    }

    // Renderiza la vista del componente
    public function render()
    {
        // Carga los comentarios ordenados por el más reciente
        $comments = $this->task->comments()->latest()->get();
        return view('livewire.task-comments', compact('comments'));
    }
}