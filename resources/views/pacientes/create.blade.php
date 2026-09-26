<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Paciente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('pacientes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rut" class="block text-sm font-medium text-gray-700">RUT</label>
                        <input type="text" name="rut" id="rut" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('rut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <a href="{{ route('pacientes.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 mt-2">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-molaris-mint hover:bg-emerald-600 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out">
    Guardar Paciente
</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>