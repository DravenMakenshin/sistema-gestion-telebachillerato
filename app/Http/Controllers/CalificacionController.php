<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalificacionController extends Controller
{
    /**
     * Muestra la vista principal de calificaciones
     */
    public function index()
    {
        // Obtener todos los alumnos para el select
        $alumnos = DB::table('alumnos')
            ->orderBy('apellido_paterno')
            ->orderBy('nombre')
            ->get(['id_alumno', 'matricula', 'nombre', 'apellido_paterno', 'apellido_materno']);
        
        // Obtener todas las materias para el select
        $materias = DB::table('materias')
            ->orderBy('codigo')
            ->get(['id_materia', 'codigo', 'nombre']);
        
        return view('calificaciones.index', compact('alumnos', 'materias'));
    }
    
    /**
     * API: Obtener materias por centro (para AJAX) - Método alternativo
     */
    public function getMateriasByCentro($id_centro)
    {
        $materias = DB::table('materias')
            ->where('id_centro', $id_centro)
            ->orderBy('nombre')
            ->get();
        
        return response()->json($materias);
    }
    
    /**
     * API: Obtener alumnos por centro (para AJAX) - Método alternativo
     */
    public function getAlumnosByCentro($id_centro)
    {
        $alumnos = DB::table('alumnos')
            ->where('id_centro', $id_centro)
            ->where('estatus', 'Activo')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get(['id_alumno', 'matricula', 'nombre', 'apellido_paterno', 'apellido_materno']);
        
        return response()->json($alumnos);
    }
    
    /**
     * API: Obtener calificación específica de un alumno por materia (sin periodo)
     */
    public function getCalificacion($id_alumno, $id_materia)
    {
        $calificacion = DB::table('calificaciones')
            ->where('id_alumno', $id_alumno)
            ->where('id_materia', $id_materia)
            ->first();
        
        return response()->json($calificacion);
    }
    
    /**
     * API: Guardar o actualizar calificación (AJAX) - Versión simplificada sin periodo
     */
    public function save(Request $request)
    {
        try {
            // Validar datos
            $request->validate([
                'id_alumno' => 'required|exists:alumnos,id_alumno',
                'id_materia' => 'required|exists:materias,id_materia',
                'parcial1' => 'nullable|numeric|min:0|max:10',
                'parcial2' => 'nullable|numeric|min:0|max:10',
                'parcial3' => 'nullable|numeric|min:0|max:10',
            ]);
            
            // Obtener valores
            $parcial1 = floatval($request->parcial1 ?? 0);
            $parcial2 = floatval($request->parcial2 ?? 0);
            $parcial3 = floatval($request->parcial3 ?? 0);
            
            // Calcular promedio
            $promedio = round(($parcial1 + $parcial2 + $parcial3) / 3, 2);
            
            // Determinar estado
            if ($promedio >= 6) {
                $estado = 'Aprobado';
            } elseif ($promedio > 0) {
                $estado = 'Reprobado';
            } else {
                $estado = 'Pendiente';
            }
            
            // Verificar si ya existe calificación para este alumno y materia
            $existente = DB::table('calificaciones')
                ->where('id_alumno', $request->id_alumno)
                ->where('id_materia', $request->id_materia)
                ->first();
            
            if ($existente) {
                // Actualizar calificación existente
                DB::table('calificaciones')
                    ->where('id_calificacion', $existente->id_calificacion)
                    ->update([
                        'parcial1' => $parcial1,
                        'parcial2' => $parcial2,
                        'parcial3' => $parcial3,
                        'promedio' => $promedio,
                        'estado' => $estado,
                        'updated_at' => now()
                    ]);
                $mensaje = 'Calificación actualizada correctamente';
            } else {
                // Insertar nueva calificación
                DB::table('calificaciones')->insert([
                    'id_alumno' => $request->id_alumno,
                    'id_materia' => $request->id_materia,
                    'parcial1' => $parcial1,
                    'parcial2' => $parcial2,
                    'parcial3' => $parcial3,
                    'promedio' => $promedio,
                    'estado' => $estado,
                    'periodo' => '2025-1', // Periodo fijo
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $mensaje = 'Calificación guardada correctamente';
            }
            
            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'promedio' => $promedio,
                'estado' => $estado,
                'calificacion' => [
                    'parcial1' => $parcial1,
                    'parcial2' => $parcial2,
                    'parcial3' => $parcial3,
                    'promedio' => $promedio,
                    'estado' => $estado
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API: Obtener listado de calificaciones (para listar)
     */
    public function list(Request $request)
    {
        try {
            $query = DB::table('calificaciones')
                ->join('alumnos', 'calificaciones.id_alumno', '=', 'alumnos.id_alumno')
                ->join('materias', 'calificaciones.id_materia', '=', 'materias.id_materia')
                ->select(
                    'calificaciones.*',
                    'alumnos.matricula',
                    'alumnos.nombre',
                    'alumnos.apellido_paterno',
                    'alumnos.apellido_materno',
                    'materias.codigo',
                    'materias.nombre as materia_nombre'
                )
                ->orderBy('calificaciones.created_at', 'desc');
            
            if ($request->has('id_alumno') && $request->id_alumno) {
                $query->where('calificaciones.id_alumno', $request->id_alumno);
            }
            
            if ($request->has('id_materia') && $request->id_materia) {
                $query->where('calificaciones.id_materia', $request->id_materia);
            }
            
            $calificaciones = $query->limit(500)->get();
            
            return response()->json($calificaciones);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}