<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Boxes de Atención') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alertas de estado -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <p class="font-bold">Error en la operación:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario de Registro de Nuevo Box -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Registrar Nuevo Box</h3>

                <form action="{{ route('boxes.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Nombre del Box -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre o Identificador del Box</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Ej: Box 3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <!-- Estado Inicial -->
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado Inicial</label>
                            <select name="estado" id="estado" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="Disponible">Disponible</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow transition">
                            + Crear Box
                        </button>
                    </div>
                </form>
            </div>

            <!-- Listado y Control de Estado de Boxes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Estado de los Boxes de Atención</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($boxes as $box)
                        <div class="border rounded-lg p-5 shadow-sm bg-gray-50 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-xl font-bold text-gray-800">{{ $box->nombre }}</h4>
                                    
                                    <!-- Badge de Estado -->
                                    @if($box->estado == 'Disponible')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Disponible
                                        </span>
                                    @elseif($box->estado == 'Mantenimiento')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Mantenimiento
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">Última actualización: {{ $box->updated_at ? $box->updated_at->diffForHumans() : 'Sin registro' }}</p>
                            </div>

                            <!-- Formulario de cambio de estado rápido -->
                            <form action="{{ route('boxes.updateEstado', $box->id) }}" method="POST" class="pt-2 border-t border-gray-200">
                                @csrf
                                @method('PATCH')
                                <label for="estado_{{ $box->id }}" class="block text-xs font-medium text-gray-600 mb-1">Cambiar Estado:</label>
                                <div class="flex space-x-2">
                                    <select name="estado" id="estado_{{ $box->id }}" class="text-xs border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="Disponible" {{ $box->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                        <option value="Mantenimiento" {{ $box->estado == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                        <option value="Inactivo" {{ $box->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-xs px-3 py-1 rounded shadow transition">
                                        Actualizar
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-gray-500">
                            No hay boxes configurados actualmente.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>