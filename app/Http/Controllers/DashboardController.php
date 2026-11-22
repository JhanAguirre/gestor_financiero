<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cuenta;
use App\Models\Transaccion;

class DashboardController extends Controller
{
    public function index()
    {
        $saldoTotal = (float) Cuenta::sum('saldo');
        $totalIngresos = (float) Transaccion::where('tipo', 'ingreso')->sum('monto');
        $totalGastos = (float) Transaccion::where('tipo', 'gasto')->sum('monto');

        $cuentas = Cuenta::orderBy('saldo', 'desc')->get();
        $totalCategorias = Categoria::count();

        // Últimos 6 movimientos en orden cronológico para graficarlos
        $movimientosGrafica = Transaccion::with(['cuenta', 'categoria'])
            ->orderBy('id', 'desc')
            ->take(6)
            ->get()
            ->reverse()
            ->values();

        $labelsMovimientos = $movimientosGrafica->map(function ($m) {
            $cat = $m->categoria->nombre ?? 'Movimiento';
            $cta = $m->cuenta->nombre ?? '';
            return "{$cat} ({$cta})";
        })->toArray();

        $valoresMovimientos = $movimientosGrafica->map(function ($m) {
            return $m->tipo === 'gasto' ? -1 * (float) $m->monto : (float) $m->monto;
        })->toArray();

        $coloresMovimientos = $movimientosGrafica->map(function ($m) {
            return $m->tipo === 'ingreso' ? '#10b981' : '#f43f5e';
        })->toArray();

        // Montos agrupados por categoría para gráfica de dona
        $porCategoria = Transaccion::with('categoria')
            ->get()
            ->groupBy(fn ($t) => $t->categoria->nombre ?? 'Sin categoría')
            ->map(fn ($grupo) => round($grupo->sum(fn ($x) => (float) $x->monto), 2));

        $labelsCategorias = $porCategoria->keys()->toArray();
        $valoresCategorias = $porCategoria->values()->toArray();

        return view('dashboard', compact(
            'saldoTotal',
            'totalIngresos',
            'totalGastos',
            'cuentas',
            'totalCategorias',
            'labelsMovimientos',
            'valoresMovimientos',
            'coloresMovimientos',
            'labelsCategorias',
            'valoresCategorias'
        ));
    }
}