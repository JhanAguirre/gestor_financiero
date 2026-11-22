<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transacciones') }}
            </h2>
            <a href="{{ route('transacciones.create') }}"
               style="background-color: #1f2937; color: #ffffff;"
               class="px-4 py-2 text-sm font-medium rounded-md hover:opacity-90">
                Nueva transacción
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-600 uppercase text-xs">
                                <th class="py-3 px-4">Fecha</th>
                                <th class="py-3 px-4">Cuenta</th>
                                <th class="py-3 px-4">Categoría</th>
                                <th class="py-3 px-4">Tipo</th>
                                <th class="py-3 px-4">Monto</th>
                                <th class="py-3 px-4">Descripción</th>
                                <th class="py-3 px-4">Creado por</th>
                                <th class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($transacciones as $transaccion)
                                <tr>
                                    <td class="py-3 px-4 text-gray-700 whitespace-nowrap">
                                        {{ optional($transaccion->fecha)->format('Y-m-d') ?? $transaccion->fecha }}
                                    </td>
                                    <td class="py-3 px-4 font-medium text-gray-900">
                                        {{ $transaccion->cuenta->nombre ?? '—' }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-800">
                                        {{ $transaccion->categoria->nombre ?? '—' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $transaccion->tipo === 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($transaccion->tipo) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-semibold whitespace-nowrap {{ $transaccion->tipo === 'ingreso' ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $transaccion->tipo === 'ingreso' ? '+' : '-' }}
                                        {{ number_format((float) $transaccion->monto, 2, ',', '.') }}
                                        {{ $transaccion->cuenta->moneda ?? '' }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">{{ $transaccion->descripcion ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $transaccion->creado_por ?? '—' }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('transacciones.edit', ['transaccion' => $transaccion->id]) }}"
                                               style="background-color: #2563eb; color: #ffffff;"
                                               class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-90">
                                                Editar
                                            </a>

                                            <form action="{{ route('transacciones.destroy', ['transaccion' => $transaccion->id]) }}"
                                                  method="POST"
                                                  class="inline-block m-0"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta transacción?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        style="background-color: #dc2626; color: #ffffff;"
                                                        class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-90">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 px-4 text-center text-gray-500">
                                        No hay transacciones registradas todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>