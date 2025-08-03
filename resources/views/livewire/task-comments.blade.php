<div class="mt-8 pt-8 border-t border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Comentarios') }}</h3>

    {{-- Formulario para añadir un nuevo comentario --}}
    <form wire:submit.prevent="addComment" class="mb-8">
        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700">Añadir Comentario:</label>
            <textarea wire:model.defer="content" id="content" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700">Adjuntar Archivo (Opcional):</label>
            <input type="file" wire:model.defer="file" id="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
            @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if ($file)
                <span class="text-xs text-gray-500">Archivo seleccionado: {{ $file->getClientOriginalName() }}</span>
            @endif
            <div wire:loading wire:target="file" class="text-sm text-gray-500 mt-1">Cargando archivo...</div>
        </div>

        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition" wire:loading.attr="disabled">
            Añadir Comentario
        </button>
        <div wire:loading wire:target="addComment" class="text-sm text-gray-500 mt-2">Guardando comentario...</div>
    </form>

    {{-- Lista de comentarios existentes --}}
    @if ($comments->isEmpty())
        <p class="text-gray-600">Aún no hay comentarios para esta tarea.</p>
    @else
        <div class="space-y-4">
            @foreach ($comments as $comment)
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $comment->user->name }}
                                <span class="text-xs text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                            </p>
                            <p class="text-gray-700 mt-1">{{ $comment->content }}</p>
                            @if ($comment->file_path)
                                <div class="mt-2 text-sm">
                                    <a href="{{ Storage::url($comment->file_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13.5"></path></svg>
                                        {{ $comment->file_name ?? 'Archivo Adjunto' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        @if ($comment->user_id === Auth::id())
                            <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="¿Estás seguro de que quieres eliminar este comentario?" class="text-red-500 hover:text-red-700 text-sm ml-4">
                                Eliminar
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>