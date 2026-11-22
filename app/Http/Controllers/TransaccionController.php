<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cuenta;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaccionController extends Controller
{
    public function index()
    {
        $transacciones = Transaccion::with(['cuenta', 'categoria'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('transacciones.index', compact('transacciones'));
    }

    public function create()
    {
        $cuentas = Cuenta::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('transacciones.create', compact('cuentas', 'categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cuenta_id' => ['required', 'exists:cuentas,id'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'tipo' => ['required', Rule::in(['ingreso', 'gasto'])],
            'monto' => ['required', 'numeric', 'gt:0'],
            'fecha' => ['required', 'date'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        $categoria = Categoria::findOrFail($validated['categoria_id']);
        if ($categoria->tipo !== $validated['tipo']) {
            return back()
                ->withInput()
                ->withErrors([
                    'categoria_id' => "La categoría seleccionada es de tipo '{$categoria->tipo}' y no coincide con '{$validated['tipo']}'.",
                ]);
        }

        try {
            DB::transaction(function () use ($validated) {
                $cuenta = Cuenta::where('id', $validated['cuenta_id'])->lockForUpdate()->firstOrFail();
                $monto = (float) $validated['monto'];
                $saldoActual = (float) $cuenta->saldo;

                if ($validated['tipo'] === 'gasto' && $monto > $saldoActual) {
                    throw new \RuntimeException('El gasto supera el saldo disponible en la cuenta seleccionada.');
                }

                $nuevoSaldo = $validated['tipo'] === 'ingreso'
                    ? $saldoActual + $monto
                    : $saldoActual - $monto;

                $cuenta->update(['saldo' => round($nuevoSaldo, 2)]);

                Transaccion::create([
                    'cuenta_id' => $cuenta->id,
                    'categoria_id' => $validated['categoria_id'],
                    'tipo' => $validated['tipo'],
                    'monto' => round($monto, 2),
                    'fecha' => $validated['fecha'],
                    'descripcion' => $validated['descripcion'] ?? null,
                    'creado_por' => auth()->user()->name ?? auth()->user()->email,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors(['monto' => $e->getMessage()]);
        }

        return redirect()
            ->route('transacciones.index')
            ->with('success', 'Transacción registrada y saldo actualizado correctamente.');
    }

    public function edit($id)
    {
        $transaccion = Transaccion::findOrFail($id);
        $cuentas = Cuenta::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('transacciones.edit', compact('transaccion', 'cuentas', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $transaccion = Transaccion::findOrFail($id);

        $validated = $request->validate([
            'cuenta_id' => ['required', 'exists:cuentas,id'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'tipo' => ['required', Rule::in(['ingreso', 'gasto'])],
            'monto' => ['required', 'numeric', 'gt:0'],
            'fecha' => ['required', 'date'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        $categoria = Categoria::findOrFail($validated['categoria_id']);
        if ($categoria->tipo !== $validated['tipo']) {
            return back()
                ->withInput()
                ->withErrors([
                    'categoria_id' => "La categoría seleccionada es de tipo '{$categoria->tipo}' y no coincide con '{$validated['tipo']}'.",
                ]);
        }

        try {
            DB::transaction(function () use ($validated, $transaccion) {
                $cuentaAnteriorId = (int) $transaccion->cuenta_id;
                $cuentaNuevaId = (int) $validated['cuenta_id'];

                $idsABloquear = collect([$cuentaAnteriorId, $cuentaNuevaId])->unique()->sort()->values();
                $cuentasBloqueadas = Cuenta::whereIn('id', $idsABloquear)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $cuentaAnterior = $cuentasBloqueadas[$cuentaAnteriorId];
                $cuentaNueva = $cuentasBloqueadas[$cuentaNuevaId];

                $montoAnterior = (float) $transaccion->monto;
                $saldoAnteriorRevertido = $transaccion->tipo === 'ingreso'
                    ? (float) $cuentaAnterior->saldo - $montoAnterior
                    : (float) $cuentaAnterior->saldo + $montoAnterior;

                $cuentaAnterior->update(['saldo' => round($saldoAnteriorRevertido, 2)]);

                $cuentaNueva->refresh();
                $montoNuevo = (float) $validated['monto'];
                $saldoActualNueva = (float) $cuentaNueva->saldo;

                if ($validated['tipo'] === 'gasto' && $montoNuevo > $saldoActualNueva) {
                    throw new \RuntimeException('El gasto supera el saldo disponible en la cuenta seleccionada.');
                }

                $nuevoSaldo = $validated['tipo'] === 'ingreso'
                    ? $saldoActualNueva + $montoNuevo
                    : $saldoActualNueva - $montoNuevo;

                $cuentaNueva->update(['saldo' => round($nuevoSaldo, 2)]);

                $transaccion->update([
                    'cuenta_id' => $cuentaNueva->id,
                    'categoria_id' => $validated['categoria_id'],
                    'tipo' => $validated['tipo'],
                    'monto' => round($montoNuevo, 2),
                    'fecha' => $validated['fecha'],
                    'descripcion' => $validated['descripcion'] ?? null,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors(['monto' => $e->getMessage()]);
        }

        return redirect()
            ->route('transacciones.index')
            ->with('success', 'Transacción actualizada y saldo recalculado correctamente.');
    }

    public function destroy($id)
    {
        $transaccion = Transaccion::findOrFail($id);

        DB::transaction(function () use ($transaccion) {
            $cuenta = Cuenta::where('id', $transaccion->cuenta_id)->lockForUpdate()->firstOrFail();
            $monto = (float) $transaccion->monto;

            $saldoRevertido = $transaccion->tipo === 'ingreso'
                ? (float) $cuenta->saldo - $monto
                : (float) $cuenta->saldo + $monto;

            $cuenta->update(['saldo' => round($saldoRevertido, 2)]);
            $transaccion->delete();
        });

        return redirect()
            ->route('transacciones.index')
            ->with('success', 'Transacción eliminada y saldo revertido correctamente.');
    }
}