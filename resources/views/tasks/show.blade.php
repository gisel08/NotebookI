<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Tarea: ') . $task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- ... (tu código existente para mostrar los detalles de la tarea) ... --}}

                <div class="mb-4">
                    <x-label for="title" value="{{ __('Título') }}" />
                    <p class="mt-1 text-gray-700">{{ $task->title }}</p>
                </div>

                <div class="mb-4">
                    <x-label for="description" value="{{ __('Descripción') }}" />
                    <p class="mt-1 text-gray-700">{{ $task->description ?? 'Sin descripción' }}</p>
                </div>

                <div class="mb-4">
                    <x-label for="due_at" value="{{ __('Fecha y Hora Límite') }}" />
                    <p class="mt-1 text-gray-700">{{ $task->due_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="mb-4">
                    <x-label for="notified_emails" value="{{ __('Correos para Notificar') }}" />
                    <p class="mt-1 text-gray-700">{{ $task->notified_emails ?? 'Ninguno' }}</p>
                </div>

                <div class="mb-4">
                    <x-label for="priority" value="{{ __('Prioridad') }}" />
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($task->priority == 'urgent') bg-red-100 text-red-800
                        @elseif($task->priority == 'important') bg-yellow-100 text-yellow-800
                        @elseif($task->priority == 'delayed') bg-gray-100 text-gray-800
                        @elseif($task->priority == 'completed') bg-green-100 text-green-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>

                <div class="mb-4">
                    <x-label for="status" value="{{ __('Estado') }}" />
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($task->status == 'completed') bg-green-100 text-green-800
                        @elseif($task->status == 'delayed') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ ucfirst($task->status) }}
                    </span>
                </div>

                <div class="mb-4">
                    <x-label value="{{ __('Creada el') }}" />
                    <p class="mt-1 text-gray-700">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="mb-4">
                    <x-label value="{{ __('Última Actualización') }}" />
                    <p class="mt-1 text-gray-700">{{ $task->updated_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition mr-3">
                        {{ __('Editar Tarea') }}
                    </a>
                    <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        {{ __('Volver a la lista') }}
                    </a>
                </div>

                {{-- Sección de Comentarios (AHORA CON LIVEWIRE) --}}
                <livewire:task-comments :task="$task" /> {{-- ¡Esta es la línea clave! --}}

            </div>
        </div>
    </div>
</x-app-layout>