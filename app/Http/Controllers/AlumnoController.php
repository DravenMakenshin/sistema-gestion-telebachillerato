<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    /**
     * Muestra el listado de alumnos con paginación y búsqueda
     */
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda', '');
        $porPagina = $request->get('por_pagina', 15);
        
        // Consulta base con SQL puro
        $query = DB::table('alumnos')
            ->leftJoin('centros', 'alumnos.id_centro', '=', 'centros.id_centro')
            ->select('alumnos.*', 'centros.nombre as centro_nombre', 'centros.clave_ct');
        
        // Aplicar búsqueda si existe
        if (!empty($busqueda)) {
            $query->where(function($q) use ($busqueda) {
                $q->where('alumnos.matricula', 'like', "%{$busqueda}%")
                  ->orWhere('alumnos.nombre', 'like', "%{$busqueda}%")
                  ->orWhere('alumnos.apellido_paterno', 'like', "%{$busqueda}%")
                  ->orWhere('alumnos.apellido_materno', 'like', "%{$busqueda}%")
                  ->orWhere('centros.nombre', 'like', "%{$busqueda}%");
            });
        }
        
        // Obtener resultados paginados
        $alumnos = $query->orderBy('alumnos.apellido_paterno', 'asc')
                        ->paginate($porPagina);
        
        // Mantener la búsqueda en la paginación
        $alumnos->appends(['busqueda' => $busqueda, 'por_pagina' => $porPagina]);
        
        // Estadísticas
        $totalAlumnos = DB::table('alumnos')->count();
        $totalHombres = DB::table('alumnos')->where('genero', 'M')->count();
        $totalMujeres = DB::table('alumnos')->where('genero', 'F')->count();
        $totalActivos = DB::table('alumnos')->where('estatus', 'Activo')->count();
        
        return view('alumnos.index', compact('alumnos', 'busqueda', 'porPagina', 
                    'totalAlumnos', 'totalHombres', 'totalMujeres', 'totalActivos'));
    }
    
    /**
     * Muestra el detalle de un alumno específico
     */
    public function show($id)
    {
        $alumno = DB::table('alumnos')
            ->leftJoin('centros', 'alumnos.id_centro', '=', 'centros.id_centro')
            ->select('alumnos.*', 'centros.nombre as centro_nombre', 
                    'centros.clave_ct', 'centros.municipio as centro_municipio')
            ->where('alumnos.id_alumno', $id)
            ->first();
        
        if (!$alumno) {
            return redirect()->route('alumnos.index')
                ->with('error', 'Alumno no encontrado');
        }
        
        // Obtener calificaciones del alumno
        $calificaciones = DB::table('calificaciones')
            ->join('materias', 'calificaciones.id_materia', '=', 'materias.id_materia')
            ->select('calificaciones.*', 'materias.nombre as materia_nombre', 'materias.creditos')
            ->where('calificaciones.id_alumno', $id)
            ->orderBy('calificaciones.periodo', 'desc')
            ->get();
        
        // Calcular promedio general
        $promedioGeneral = 0;
        if ($calificaciones->count() > 0) {
            $sumaPromedios = $calificaciones->sum('promedio');
            $promedioGeneral = round($sumaPromedios / $calificaciones->count(), 2);
        }
        
        return view('alumnos.show', compact('alumno', 'calificaciones', 'promedioGeneral'));
    }
    
    /**
     * Muestra el formulario para editar un alumno
     */
    public function edit($id)
    {
        $alumno = DB::table('alumnos')->where('id_alumno', $id)->first();
        $centros = DB::table('centros')->orderBy('nombre')->get();
        
        if (!$alumno) {
            return redirect()->route('alumnos.index')
                ->with('error', 'Alumno no encontrado');
        }
        
        return view('alumnos.edit', compact('alumno', 'centros'));
    }
    
    /**
     * Actualiza los datos de un alumno
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'genero' => 'nullable|in:M,F',
            'estatus' => 'required|string|in:Activo,Inactivo,Egresado',
            'id_centro' => 'nullable|exists:centros,id_centro'
        ]);
        
        DB::table('alumnos')
            ->where('id_alumno', $id)
            ->update([
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'genero' => $request->genero,
                'estatus' => $request->estatus,
                'id_centro' => $request->id_centro,
                'updated_at' => now()
            ]);
        
        return redirect()->route('alumnos.show', $id)
            ->with('success', 'Alumno actualizado correctamente');
    }
    
    /**
     * API: Obtener datos de alumnos para AJAX
     */
    public function getData(Request $request)
    {
        $alumnos = DB::table('alumnos')
            ->select('id_alumno', 'matricula', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->orderBy('apellido_paterno')
            ->get();
        
        return response()->json($alumnos);
    }
}