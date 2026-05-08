<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Boleto;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==========================================
    // DASHBOARD
    // ==========================================
    public function dashboard()
    {
        $stats = DB::table('vista_dashboard_admin')->first();
        $ventasDiarias = DB::table('vista_ventas_diarias')
            ->orderBy('fecha', 'desc')
            ->limit(7)
            ->get()
            ->reverse()
            ->values();
        $ventasPeliculas = DB::table('vista_ventas_peliculas')->get();
        $ocupacion = DB::table('vista_ocupacion_funciones')
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'ventasDiarias', 'ventasPeliculas', 'ocupacion'));
    }

    // ==========================================
    // PELICULAS
    // ==========================================
    public function peliculas()
    {
        $peliculas = DB::table('peliculas')->orderBy('titulo')->get();
        return view('admin.peliculas.index', compact('peliculas'));
    }

    public function peliculasCreate()
    {
        return view('admin.peliculas.create');
    }

    public function peliculasStore(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'genero' => 'required|string|max:50',
            'duracion' => 'required|integer|min:1',
            'clasificacion' => 'required|string|max:10',
            'idioma' => 'required|string|max:30',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('peliculas', 'public');
        }

        DB::table('peliculas')->insert([
            'titulo' => $request->titulo,
            'genero' => $request->genero,
            'duracion' => $request->duracion,
            'clasificacion' => $request->clasificacion,
            'idioma' => $request->idioma,
            'imagen' => $imagenPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.peliculas')->with('success', 'Película creada exitosamente.');
    }

    public function peliculasEdit($id)
    {
        $pelicula = DB::table('peliculas')->where('id', $id)->first();
        if (!$pelicula) return redirect()->route('admin.peliculas')->with('error', 'Película no encontrada.');
        return view('admin.peliculas.edit', compact('pelicula'));
    }

    public function peliculasUpdate(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'genero' => 'required|string|max:50',
            'duracion' => 'required|integer|min:1',
            'clasificacion' => 'required|string|max:10',
            'idioma' => 'required|string|max:30',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'titulo' => $request->titulo,
            'genero' => $request->genero,
            'duracion' => $request->duracion,
            'clasificacion' => $request->clasificacion,
            'idioma' => $request->idioma,
            'updated_at' => now(),
        ];

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            $pelicula = DB::table('peliculas')->where('id', $id)->first();
            if ($pelicula->imagen && Storage::disk('public')->exists($pelicula->imagen)) {
                Storage::disk('public')->delete($pelicula->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('peliculas', 'public');
        }

        DB::table('peliculas')->where('id', $id)->update($data);

        return redirect()->route('admin.peliculas')->with('success', 'Película actualizada exitosamente.');
    }

    public function peliculasDestroy($id)
    {
        // Check if movie has related functions
        $tieneFunc = DB::table('funciones')->where('pelicula_id', $id)->exists();
        if ($tieneFunc) {
            return redirect()->route('admin.peliculas')->with('error', 'No se puede eliminar: esta película tiene funciones asociadas.');
        }

        DB::table('peliculas')->where('id', $id)->delete();
        return redirect()->route('admin.peliculas')->with('success', 'Película eliminada exitosamente.');
    }

    // ==========================================
    // SALAS
    // ==========================================
    public function salas()
    {
        $salas = DB::table('salas')->orderBy('nombre')->get();
        return view('admin.salas.index', compact('salas'));
    }

    public function salasCreate()
    {
        return view('admin.salas.create');
    }

    public function salasStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'tipo' => 'required|string|max:30',
            'precio' => 'required|numeric|min:0',
        ]);

        DB::table('salas')->insert([
            'nombre' => $request->nombre,
            'capacidad' => $request->capacidad,
            'tipo' => $request->tipo,
            'precio' => $request->precio,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.salas')->with('success', 'Sala creada exitosamente.');
    }

    public function salasEdit($id)
    {
        $sala = DB::table('salas')->where('id', $id)->first();
        if (!$sala) return redirect()->route('admin.salas')->with('error', 'Sala no encontrada.');
        return view('admin.salas.edit', compact('sala'));
    }

    public function salasUpdate(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'tipo' => 'required|string|max:30',
            'precio' => 'required|numeric|min:0',
        ]);

        DB::table('salas')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'capacidad' => $request->capacidad,
            'tipo' => $request->tipo,
            'precio' => $request->precio,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.salas')->with('success', 'Sala actualizada exitosamente.');
    }

    public function salasDestroy($id)
    {
        $tieneFunc = DB::table('funciones')->where('sala_id', $id)->exists();
        if ($tieneFunc) {
            return redirect()->route('admin.salas')->with('error', 'No se puede eliminar: esta sala tiene funciones asociadas.');
        }

        // Also delete related seats
        DB::table('asientos')->where('sala_id', $id)->delete();
        DB::table('salas')->where('id', $id)->delete();
        return redirect()->route('admin.salas')->with('success', 'Sala eliminada exitosamente.');
    }

    // ==========================================
    // FUNCIONES
    // ==========================================
    public function funciones()
    {
        $funciones = DB::table('vista_funciones_detalle')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();
        return view('admin.funciones.index', compact('funciones'));
    }

    public function funcionesCreate()
    {
        $peliculas = DB::table('peliculas')->orderBy('titulo')->get();
        $salas = DB::table('salas')->orderBy('nombre')->get();
        return view('admin.funciones.create', compact('peliculas', 'salas'));
    }

    public function funcionesStore(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|integer|exists:peliculas,id',
            'sala_id'     => 'required|integer|exists:salas,id',
            'fecha'       => 'required|date|after_or_equal:today',
            'hora'        => 'required',
        ]);

        // Verificar conflicto de horario
        $conflicto = $this->verificarConflictoHorario(
            $request->sala_id,
            $request->fecha,
            $request->hora,
            $request->pelicula_id
        );

        if ($conflicto) {
            $inicio = Carbon::parse($request->fecha . ' ' . $conflicto->hora)->format('H:i');
            $fin    = Carbon::parse($request->fecha . ' ' . $conflicto->hora)
                        ->addMinutes($conflicto->duracion + 30)->format('H:i');
            return back()->withInput()->withErrors([
                'hora' => "Conflicto: la sala ya tiene '" . $conflicto->titulo .
                          "' de {$inicio} a {$fin} (incluye 30 min de limpieza)."
            ]);
        }

        DB::table('funciones')->insert([
            'pelicula_id' => $request->pelicula_id,
            'sala_id'     => $request->sala_id,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.funciones')->with('success', 'Función creada exitosamente.');
    }

    public function funcionesEdit($id)
    {
        $funcion = DB::table('funciones')->where('id', $id)->first();
        if (!$funcion) return redirect()->route('admin.funciones')->with('error', 'Función no encontrada.');

        $peliculas = DB::table('peliculas')->orderBy('titulo')->get();
        $salas = DB::table('salas')->orderBy('nombre')->get();
        return view('admin.funciones.edit', compact('funcion', 'peliculas', 'salas'));
    }

    public function funcionesUpdate(Request $request, $id)
    {
        $request->validate([
            'pelicula_id' => 'required|integer|exists:peliculas,id',
            'sala_id'     => 'required|integer|exists:salas,id',
            'fecha'       => 'required|date',
            'hora'        => 'required',
        ]);

        // Verificar conflicto de horario (excluyendo esta función)
        $conflicto = $this->verificarConflictoHorario(
            $request->sala_id,
            $request->fecha,
            $request->hora,
            $request->pelicula_id,
            $id
        );

        if ($conflicto) {
            $inicio = Carbon::parse($request->fecha . ' ' . $conflicto->hora)->format('H:i');
            $fin    = Carbon::parse($request->fecha . ' ' . $conflicto->hora)
                        ->addMinutes($conflicto->duracion + 30)->format('H:i');
            return back()->withInput()->withErrors([
                'hora' => "Conflicto: la sala ya tiene '" . $conflicto->titulo .
                          "' de {$inicio} a {$fin} (incluye 30 min de limpieza)."
            ]);
        }

        DB::table('funciones')->where('id', $id)->update([
            'pelicula_id' => $request->pelicula_id,
            'sala_id'     => $request->sala_id,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.funciones')->with('success', 'Función actualizada exitosamente.');
    }

    public function funcionesDestroy($id)
    {
        $tieneBoletos = DB::table('boletos')->where('funcion_id', $id)->exists();
        if ($tieneBoletos) {
            return redirect()->route('admin.funciones')->with('error', 'No se puede eliminar: esta función tiene boletos vendidos.');
        }

        DB::table('funciones')->where('id', $id)->delete();
        return redirect()->route('admin.funciones')->with('success', 'Función eliminada exitosamente.');
    }

    // ==========================================
    // UTILIDADES PRIVADAS
    // ==========================================

    /**
     * Verifica si existe solapamiento de horario en una sala.
     * Considera la duración de la película + 30 min de limpieza/preparación.
     *
     * @param int         $salaId      ID de la sala
     * @param string      $fecha       Fecha de la función (Y-m-d)
     * @param string      $hora        Hora de inicio (H:i o H:i:s)
     * @param int         $peliculaId  ID de la nueva película
     * @param int|null    $excluirId   ID de función a excluir (en edición)
     * @return object|null  La función conflictiva o null si no hay conflicto
     */
    private function verificarConflictoHorario(
        int $salaId,
        string $fecha,
        string $hora,
        int $peliculaId,
        ?int $excluirId = null
    ): ?object {
        $nuevaPelicula  = DB::table('peliculas')->where('id', $peliculaId)->first();
        $nuevaInicio    = Carbon::parse("{$fecha} {$hora}");
        $nuevaFin       = $nuevaInicio->copy()->addMinutes($nuevaPelicula->duracion + 30);

        $funcionesEnSala = DB::table('funciones')
            ->join('peliculas', 'funciones.pelicula_id', '=', 'peliculas.id')
            ->where('funciones.sala_id', $salaId)
            ->where('funciones.fecha', $fecha)
            ->when($excluirId, fn($q) => $q->where('funciones.id', '!=', $excluirId))
            ->select('funciones.hora', 'peliculas.duracion', 'peliculas.titulo')
            ->get();

        foreach ($funcionesEnSala as $f) {
            $existInicio = Carbon::parse("{$fecha} {$f->hora}");
            $existFin    = $existInicio->copy()->addMinutes($f->duracion + 30);

            // Solapamiento: la nueva empieza antes de que termine la existente
            // Y la nueva termina después de que empiece la existente
            if ($nuevaInicio->lt($existFin) && $nuevaFin->gt($existInicio)) {
                return $f;
            }
        }

        return null;
    }

    // ==========================================
    // BOLETOS (Read-only)
    // ==========================================
    public function boletos()
    {
        $boletos = DB::table('vista_boletos_detalle')
            ->orderBy('fecha_compra', 'desc')
            ->get();
        return view('admin.boletos.index', compact('boletos'));
    }

    // ==========================================
    // USUARIOS (Read-only)
    // ==========================================
    public function usuarios()
    {
        $usuarios = DB::table('users')
            ->select('id', 'name', 'email', 'telefono', 'rol', 'created_at')
            ->orderBy('name')
            ->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }
}
