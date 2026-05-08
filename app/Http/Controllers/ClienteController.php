<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Boleto;
use App\Mail\BoletoConfirmacion;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClienteController extends Controller
{
    // PASO 1: Cartelera
    public function cartelera()
    {
        $peliculas = DB::table('vista_cartelera')->get();
        return view('cliente.cartelera', compact('peliculas'));
    }

    // PASO 2: Funciones de una pelicula
    public function funciones($id)
    {
        $pelicula = DB::table('peliculas')->where('id', $id)->first();

        if (!$pelicula) {
            return redirect()->route('cartelera')->with('error', 'Película no encontrada.');
        }

        $funciones = DB::table('vista_funciones_detalle')
            ->where('pelicula_id', $id)
            ->whereBetween('fecha', [
                now()->toDateString(),
                now()->addDay()->toDateString()
            ])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('cliente.funciones', compact('pelicula', 'funciones'));
    }

    // PASO 3: Seleccionar cantidad de boletos
    public function seleccionarCantidad($id)
    {
        $funcion = DB::table('vista_funciones_detalle')
            ->where('funcion_id', $id)
            ->first();

        if (!$funcion) {
            return redirect()->route('cartelera')->with('error', 'Función no encontrada.');
        }

        $disponibles = DB::table('vista_asientos_disponibilidad')
            ->where('funcion_id', $id)
            ->where('estado', 'disponible')
            ->count();

        return view('cliente.cantidad', compact('funcion', 'disponibles'));
    }

    // PASO 4: Seleccionar asientos
    public function seleccionarAsientos($id, $cantidad)
    {
        $funcion = DB::table('vista_funciones_detalle')
            ->where('funcion_id', $id)
            ->first();

        if (!$funcion) {
            return redirect()->route('cartelera')->with('error', 'Función no encontrada.');
        }

        $asientos = DB::table('vista_asientos_disponibilidad')
            ->where('funcion_id', $id)
            ->orderBy('fila')
            ->orderBy('numero')
            ->get();

        $asientosPorFila = $asientos->groupBy('fila');

        return view('cliente.asientos', compact('funcion', 'asientosPorFila', 'cantidad'));
    }

    // PASO 5a: Preparar compra (público — guarda selección en sesión)
    public function prepararCompra(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|integer',
            'asientos'   => 'required|array|min:1',
        ]);

        // Guardar la selección en sesión para no perderla al redirigir al login
        $request->session()->put('compra_pendiente', [
            'funcion_id' => $request->funcion_id,
            'asientos'   => $request->asientos,
        ]);

        // Si no está autenticado, redirigir al login (después volverá al resumen)
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Inicia sesión para completar tu compra.');
        }

        return redirect()->route('boleto.resumen');
    }

    // PASO 5b: Resumen (requiere auth — lee datos de sesión)
    public function resumen(Request $request)
    {
        $compra = $request->session()->get('compra_pendiente');

        if (!$compra) {
            return redirect()->route('cartelera')->with('error', 'No hay una compra pendiente. Selecciona tus asientos nuevamente.');
        }

        $funcion = DB::table('vista_funciones_detalle')
            ->where('funcion_id', $compra['funcion_id'])
            ->first();

        $asientos = DB::table('vista_asientos_disponibilidad')
            ->whereIn('asiento_id', $compra['asientos'])
            ->where('funcion_id', $compra['funcion_id'])
            ->get();

        $total = $asientos->count() * $funcion->precio;

        return view('cliente.resumen', compact('funcion', 'asientos', 'total'));
    }

    // PASO 5b-extra: Guardar selección en sesión y redirigir a selección de pago
    public function prepararPago(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|integer',
            'asientos'   => 'required|array|min:1',
        ]);

        $request->session()->put('pago_pendiente', [
            'funcion_id' => $request->funcion_id,
            'asientos'   => $request->asientos,
        ]);

        return redirect()->route('boleto.pago');
    }

    // PASO 5c: Mostrar selección de método de pago
    public function mostrarPago(Request $request)
    {
        $pago = $request->session()->get('pago_pendiente');

        if (!$pago) {
            return redirect()->route('cartelera')->with('error', 'No hay una compra pendiente.');
        }

        $funcion = DB::table('vista_funciones_detalle')
            ->where('funcion_id', $pago['funcion_id'])
            ->first();

        $asientos = DB::table('vista_asientos_disponibilidad')
            ->whereIn('asiento_id', $pago['asientos'])
            ->where('funcion_id', $pago['funcion_id'])
            ->get();

        $total = $asientos->count() * $funcion->precio;

        return view('cliente.pago', compact('funcion', 'asientos', 'total', 'pago'));
    }

    // PASO 6: Confirmar compra
    public function confirmar(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|integer',
            'asientos'   => 'required|array|min:1',
        ]);

        $funcion = DB::table('funciones')
            ->join('salas', 'funciones.sala_id', '=', 'salas.id')
            ->where('funciones.id', $request->funcion_id)
            ->select('funciones.*', 'salas.precio')
            ->first();

        $boletosGenerados = [];
        $fechaCompra = now();

        foreach ($request->asientos as $asiento_id) {
            $yaVendido = Boleto::where('funcion_id', $request->funcion_id)
                ->where('asiento_id', $asiento_id)
                ->exists();

            if (!$yaVendido) {
                $boleto = Boleto::create([
                    'funcion_id'   => $request->funcion_id,
                    'asiento_id'   => $asiento_id,
                    'user_id'      => Auth::id(),
                    'precio'       => $funcion->precio,
                    'fecha_compra' => $fechaCompra,
                ]);
                $boletosGenerados[] = $boleto->id;
            }
        }

        if (!empty($boletosGenerados)) {
            $boletoData = DB::table('vista_boletos_detalle')
                ->whereIn('boleto_id', $boletosGenerados)
                ->get();

            if ($boletoData->isNotEmpty()) {
                $compraEmail = [
                    'pelicula'      => $boletoData->first()->pelicula,
                    'fecha_funcion' => $boletoData->first()->fecha_funcion,
                    'hora_funcion'  => $boletoData->first()->hora_funcion,
                    'sala'          => $boletoData->first()->sala,
                    'tipo_sala'     => $boletoData->first()->tipo_sala,
                    'cliente'       => $boletoData->first()->cliente,
                    'fecha_compra'  => $boletoData->first()->fecha_compra,
                    'asientos'      => $boletoData->pluck('asiento')->toArray(),
                    'total'         => $boletoData->sum('precio'),
                    'ids'           => $boletoData->pluck('boleto_id')->toArray(),
                ];

                try {
                    Mail::to(Auth::user()->email)->send(new BoletoConfirmacion($compraEmail));
                } catch (\Exception $e) {
                    \Log::warning('No se pudo enviar el email de confirmación: ' . $e->getMessage());
                }
            }
        }

        if (empty($boletosGenerados)) {
            return redirect()->route('cartelera')->with('error', 'Los asientos seleccionados ya no están disponibles.');
        }

        return redirect()->route('boleto.resultado', [
            'ids' => implode(',', $boletosGenerados)
        ]);
    }

    // Resultado
    public function resultado(Request $request)
    {
        $ids = array_filter(explode(',', $request->ids ?? ''));

        if (empty($ids)) {
            return redirect()->route('cartelera')->with('error', 'No se encontraron boletos.');
        }

        $boletos = DB::table('vista_boletos_detalle')
            ->whereIn('boleto_id', $ids)
            ->get();

        if ($boletos->isEmpty()) {
            return redirect()->route('cartelera')->with('error', 'No se encontraron los boletos solicitados.');
        }

        $compra = [
            'pelicula'      => $boletos->first()->pelicula,
            'fecha_funcion' => $boletos->first()->fecha_funcion,
            'hora_funcion'  => $boletos->first()->hora_funcion,
            'sala'          => $boletos->first()->sala,
            'tipo_sala'     => $boletos->first()->tipo_sala,
            'cliente'       => $boletos->first()->cliente,
            'fecha_compra'  => $boletos->first()->fecha_compra,
            'asientos'      => $boletos->pluck('asiento')->toArray(),
            'total'         => $boletos->sum('precio'),
            'ids'           => $boletos->pluck('boleto_id')->toArray(),
        ];

        $qrData = implode("\n", [
            'CINEAPP - Boleto de Entrada',
            'IDs: ' . implode(', ', $compra['ids']),
            'Pelicula: ' . $compra['pelicula'],
            'Fecha: ' . \Carbon\Carbon::parse($compra['fecha_funcion'])->format('d/m/Y'),
            'Hora: ' . \Carbon\Carbon::parse($compra['hora_funcion'])->format('H:i'),
            'Sala: ' . $compra['sala'] . ' (' . $compra['tipo_sala'] . ')',
            'Asientos: ' . implode(', ', $compra['asientos']),
        ]);

        $qrSvg = QrCode::size(160)->color(255, 99, 126)->generate($qrData);

        return view('cliente.resultado', compact('compra', 'qrSvg'));
    }

    // Mis boletos agrupados por compra
    public function misBoletos()
    {
        $boletos = DB::table('vista_boletos_detalle')
            ->where('user_id', Auth::id())
            ->orderBy('fecha_compra', 'desc')
            ->get();

        $compras = $boletos->groupBy('fecha_compra')->map(function($grupo) {
            return [
                'pelicula'      => $grupo->first()->pelicula,
                'fecha_funcion' => $grupo->first()->fecha_funcion,
                'hora_funcion'  => $grupo->first()->hora_funcion,
                'sala'          => $grupo->first()->sala,
                'tipo_sala'     => $grupo->first()->tipo_sala,
                'fecha_compra'  => $grupo->first()->fecha_compra,
                'asientos'      => $grupo->pluck('asiento')->toArray(),
                'total'         => $grupo->sum('precio'),
                'ids'           => $grupo->pluck('boleto_id')->toArray(),
            ];
        })->values();

        return view('cliente.mis_boletos', compact('compras'));
    }
}