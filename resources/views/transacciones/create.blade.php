<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Transacción') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-md text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('transacciones.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo *</label>
                        <select id="tipo"
                                name="tipo"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona un tipo</option>
                            <option value="ingreso" {{ old('tipo') === 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                            <option value="gasto" {{ old('tipo') === 'gasto' ? 'selected' : '' }}>Gasto</option>
                        </select>
                    </div>

                    <div>
                        <label for="cuenta_id" class="block text-sm font-medium text-gray-700">Cuenta *</label>
                        <select id="cuenta_id"
                                name="cuenta_id"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona una cuenta</option>
                            @foreach ($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}" {{ (string) old('cuenta_id') === (string) $cuenta->id ? 'selected' : '' }}>
                                    {{ $cuenta->nombre }} (Saldo: {{ number_format((float) $cuenta->saldo, 2, ',', '.') }} {{ $cuenta->moneda }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría *</label>
                        <select id="categoria_id"
                                name="categoria_id"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ (string) old('categoria_id') === (string) $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }} ({{ ucfirst($categoria->tipo) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="monto" class="block text-sm font-medium text-gray-700">Monto *</label>
                        <input type="number"
                               step="0.01"
                               min="0.01"
                               id="monto"
                               name="monto"
                               value="{{ old('monto', '0.00') }}"
                               required
                               onfocus="if (parseFloat(this.value) === 0) this.value = '';"
                               onblur="if (this.value.trim() === '') this.value = '0.00';"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha *</label>
                        <input type="date"
                               id="fecha"
                               name="fecha"
                               value="{{ old('fecha', date('Y-m-d')) }}"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <input type="text"
                               id="descripcion"
                               name="descripcion"
                               value="{{ old('descripcion') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <a href="{{ route('transacciones.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 text-sm">
                            Cancelar
                        </a>
                        <button type="submit"
                                style="background-color: #1f2937; color: #ffffff;"
                                class="px-4 py-2 rounded-md hover:opacity-90 text-sm">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>