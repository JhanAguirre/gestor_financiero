<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; padding: 6px 0;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0; line-height: 1.25;">
                    Panel Financiero
                </h2>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">
                    Hola, <strong style="color: #334155;">{{ Auth::user()->name }}</strong> · Vista general de tu dinero
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('transacciones.create') }}"
                   style="background-color: #059669; color: #ffffff; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; box-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                    + Nueva transacción
                </a>
                <a href="{{ route('cuentas.create') }}"
                   style="background-color: #0f172a; color: #ffffff; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; box-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                    + Nueva cuenta
                </a>
            </div>
        </div>
    </x-slot>

    <div style="background-color: #f1f5f9; padding: 28px 16px; min-height: calc(100vh - 130px);">
        <div style="max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

            {{-- Fila 1: 3 KPIs con buen respiro interior --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <span style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">
                            Saldo Consolidado
                        </span>
                        <span style="background-color: rgba(16, 185, 129, 0.2); color: #6ee7b7; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600;">
                            {{ $cuentas->count() }} {{ $cuentas->count() === 1 ? 'cuenta' : 'cuentas' }}
                        </span>
                    </div>
                    <div style="margin-top: 14px; display: flex; align-items: baseline; gap: 8px;">
                        <span style="font-size: 28px; font-weight: 800; letter-spacing: -0.02em; color: #ffffff;">
                            $ {{ number_format($saldoTotal, 2, ',', '.') }}
                        </span>
                        <span style="color: #94a3b8; font-size: 12px; font-weight: 600;">COP</span>
                    </div>
                    <p style="color: #cbd5e1; font-size: 12px; margin: 10px 0 0 0;">
                        Disponible total entre tus cuentas activas
                    </p>
                </div>

                <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; border-top: 4px solid #10b981; padding: 22px 24px; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <span style="color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">
                            Ingresos Totales
                        </span>
                        <span style="background-color: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600;">
                            Entradas
                        </span>
                    </div>
                    <div style="margin-top: 14px;">
                        <span style="color: #059669; font-size: 28px; font-weight: 800; letter-spacing: -0.02em;">
                            + $ {{ number_format($totalIngresos, 2, ',', '.') }}
                        </span>
                    </div>
                    <p style="color: #64748b; font-size: 12px; margin: 10px 0 0 0;">
                        Suma de ingresos registrados
                    </p>
                </div>

                <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; border-top: 4px solid #f43f5e; padding: 22px 24px; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <span style="color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">
                            Gastos Totales
                        </span>
                        <span style="background-color: #fff1f2; color: #e11d48; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600;">
                            Salidas
                        </span>
                    </div>
                    <div style="margin-top: 14px;">
                        <span style="color: #e11d48; font-size: 28px; font-weight: 800; letter-spacing: -0.02em;">
                            - $ {{ number_format($totalGastos, 2, ',', '.') }}
                        </span>
                    </div>
                    <p style="color: #64748b; font-size: 12px; margin: 10px 0 0 0;">
                        Distribuido en {{ $totalCategorias }} categorías
                    </p>
                </div>
            </div>

            {{-- Fila 2: Gráficas con buen padding interno --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px;">
                <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px 24px; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px;">
                        <div>
                            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Movimientos Recientes</h3>
                            <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0;">Ingresos en verde (+) y gastos en rojo (-) en COP</p>
                        </div>
                        <a href="{{ route('transacciones.index') }}"
                           style="font-size: 12px; font-weight: 600; color: #059669; text-decoration: none; white-space: nowrap;">
                            Ver todo →
                        </a>
                    </div>
                    <div style="height: 260px; position: relative;">
                        <canvas id="graficaMovimientos"></canvas>
                    </div>
                </div>

                <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px 24px; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);">
                    <div style="margin-bottom: 16px;">
                        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Volumen por Categoría</h3>
                        <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0;">Distribución de montos por categoría</p>
                    </div>
                    <div style="height: 260px; position: relative;">
                        <canvas id="graficaCategorias"></canvas>
                    </div>
                </div>
            </div>

            {{-- Fila 3: Mis Cuentas con márgenes internos cómodos --}}
            <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px 24px; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px;">
                    <div>
                        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Mis Cuentas</h3>
                        <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0;">Saldo actual y participación sobre el total</p>
                    </div>
                    <a href="{{ route('cuentas.index') }}"
                       style="font-size: 12px; font-weight: 600; color: #334155; text-decoration: none; white-space: nowrap;">
                        Administrar cuentas →
                    </a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                    @forelse ($cuentas as $cuenta)
                        @php
                            $porcentaje = $saldoTotal > 0 ? min(100, round(((float) $cuenta->saldo / $saldoTotal) * 100)) : 0;
                        @endphp
                        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;">
                                <div>
                                    <p style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">{{ $cuenta->nombre }}</p>
                                    <p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">{{ $cuenta->descripcion ?? 'Sin descripción' }}</p>
                                </div>
                                <span style="background-color: #e2e8f0; color: #334155; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                                    {{ $cuenta->moneda }}
                                </span>
                            </div>

                            <div style="margin-top: 16px;">
                                <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-size: 16px; font-weight: 800; color: #0f172a;">
                                        $ {{ number_format((float) $cuenta->saldo, 2, ',', '.') }}
                                    </span>
                                    <span style="font-size: 12px; font-weight: 600; color: #64748b;">{{ $porcentaje }}%</span>
                                </div>
                                <div style="background-color: #e2e8f0; height: 7px; border-radius: 999px; overflow: hidden;">
                                    <div style="width: {{ $porcentaje }}%; height: 100%; background-color: #10b981; border-radius: 999px;"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="font-size: 13px; color: #64748b; margin: 0;">No hay cuentas registradas todavía.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxMov = document.getElementById('graficaMovimientos');
            if (ctxMov) {
                new Chart(ctxMov, {
                    type: 'bar',
                    data: {
                        labels: @json($labelsMovimientos),
                        datasets: [{
                            label: 'Impacto en saldo (COP)',
                            data: @json($valoresMovimientos),
                            backgroundColor: @json($coloresMovimientos),
                            borderRadius: 8,
                            maxThickness: 44
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                grid: { color: '#f1f5f9' },
                                ticks: {
                                    color: '#64748b',
                                    font: { size: 11 }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#475569',
                                    font: { size: 11 }
                                }
                            }
                        }
                    }
                });
            }

            const ctxCat = document.getElementById('graficaCategorias');
            if (ctxCat) {
                new Chart(ctxCat, {
                    type: 'doughnut',
                    data: {
                        labels: @json($labelsCategorias),
                        datasets: [{
                            data: @json($valoresCategorias),
                            backgroundColor: [
                                '#10b981',
                                '#f43f5e',
                                '#0ea5e9',
                                '#f59e0b',
                                '#6366f1',
                                '#14b8a6',
                                '#ec4899'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    padding: 12,
                                    font: { size: 11 },
                                    color: '#334155'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>