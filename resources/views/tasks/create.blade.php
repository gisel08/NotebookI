<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Tarea') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-label for="title" value="{{ __('Título') }}" />
                        <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                        <x-input-error for="title" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-label for="description" value="{{ __('Descripción') }}" />
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-label for="due_at" value="{{ __('Fecha y Hora Límite') }}" />
                        <x-input id="due_at" class="block mt-1 w-full" type="datetime-local" name="due_at" :value="old('due_at')" required />
                        <x-input-error for="due_at" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-label for="notified_emails" value="{{ __('Correos para Notificar (separados por coma)') }}" />
                        <x-input id="notified_emails" class="block mt-1 w-full" type="text" name="notified_emails" :value="old('notified_emails')" placeholder="ejemplo@correo.com, otro@correo.com" />
                        <x-input-error for="notified_emails" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ms-4">
                            {{ __('Guardar Tarea') }}
                        </x-button>
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-4">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>