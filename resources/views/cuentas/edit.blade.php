<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cuenta') }}
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

                <form action="{{ route('cuentas.update', $cuenta) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre', $cuenta->nombre) }}"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <input type="text"
                               id="descripcion"
                               name="descripcion"
                               value="{{ old('descripcion', $cuenta->descripcion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="saldo" class="block text-sm font-medium text-gray-700">Saldo *</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               id="saldo"
                               name="saldo"
                               value="{{ old('saldo', $cuenta->saldo) }}"
                               required
                               onfocus="if (parseFloat(this.value) === 0) this.value = '';"
                               onblur="if (this.value.trim() === '') this.value = '0.00';"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="moneda" class="block text-sm font-medium text-gray-700">Moneda *</label>
                        <select id="moneda"
                                name="moneda"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @php
                                $monedas = [
                                    'COP' => 'COP - Peso colombiano',
                                    'USD' => 'USD - Dólar estadounidense',
                                    'EUR' => 'EUR - Euro',
                                    'MXN' => 'MXN - Peso mexicano',
                                    'ARS' => 'ARS - Peso argentino',
                                    'PEN' => 'PEN - Sol peruano',
                                    'CLP' => 'CLP - Peso chileno',
                                    'BRL' => 'BRL - Real brasileño',
                                    'GBP' => 'GBP - Libra esterlina',
                                ];
                                $monedaActual = old('moneda', $cuenta->moneda);
                            @endphp
                            @foreach ($monedas as $codigo => $etiqueta)
                                <option value="{{ $codigo }}" {{ $monedaActual === $codigo ? 'selected' : '' }}>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <a href="{{ route('cuentas.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 text-sm">
                            Cancelar
                        </a>
                        <button type="submit"
                                style="background-color: #1f2937; color: #ffffff;"
                                class="px-4 py-2 rounded-md hover:opacity-90 text-sm">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>