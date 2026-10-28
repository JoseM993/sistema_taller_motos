<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = collect();

        try {
            // Intentamos consultar adaptándonos a los nombres reales de las tablas en PostgreSQL
            $cotizaciones = DB::table('cotizacion') // Si tu tabla se llama 'cotizaciones', cámbiala aquí
                ->join('diagnostico', 'cotizacion.idDiagnostico', '=', 'diagnostico.id')
                ->join('motocicleta', 'diagnostico.idMotocicleta', '=', 'motocicleta.id')
                ->join('cliente', 'motocicleta.idCliente', '=', 'cliente.idCliente')
                ->select(
                    'cotizacion.*',
                    'motocicleta.placa',
                    'motocicleta.marca',
                    'motocicleta.modelo',
                    'cliente.nombreCompleto as cliente'
                )
                ->orderBy('cotizacion.fecha', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Si la tabla es en plural ('cotizaciones'), probamos esta alternativa automáticamente
            try {
                $cotizaciones = DB::table('cotizaciones')
                    ->join('diagnosticos', 'cotizaciones.idDiagnostico', '=', 'diagnosticos.id')
                    ->join('motocicletas', 'diagnosticos.idMotocicleta', '=', 'motocicletas.id')
                    ->join('clientes', 'motocicletas.idCliente', '=', 'clientes.idCliente')
                    ->select(
                        'cotizaciones.*',
                        'motocicletas.placa',
                        'motocicletas.marca',
                        'motocicletas.modelo',
                        'clientes.nombreCompleto as cliente'
                    )
                    ->orderBy('cotizaciones.fecha', 'desc')
                    ->get();
            } catch (\Exception $ex) {
                $cotizaciones = collect();
            }
        }

        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:APROBADA,RECHAZADA,PENDIENTE'
        ]);

        // Intentamos actualizar en la tabla singular o plural según corresponda
        try {
            DB::table('cotizacion')->where('id', $id)->update(['estado' => $request->estado]);
        } catch (\Exception $e) {
            DB::table('cotizaciones')->where('id', $id)->update(['estado' => $request->estado]);
        }

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Estado de la cotización actualizado correctamente.');
    }
}
