<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentroController extends Controller
{
    /**
     * Muestra el listado de centros con paginación y búsqueda
     */
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda', '');
        $porPagina = $request->get('por_pagina', 15);
        
        // Consulta base con SQL puro
        $query = DB::table('centros');
        
        // Aplicar búsqueda si existe
        if (!empty($busqueda)) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('clave_ct', 'like', "%{$busqueda}%")
                  ->orWhere('municipio', 'like', "%{$busqueda}%")
                  ->orWhere('encargado', 'like', "%{$busqueda}%");
            });
        }
        
        // Obtener resultados paginados
        $centros = $query->orderBy('nombre', 'asc')
                        ->paginate($porPagina);
        
        // Estadísticas adicionales
        $totalCentros = DB::table('centros')->count();
        $totalAlumnos = DB::table('alumnos')->count();
        
        // Mantener la búsqueda en la paginación
        $centros->appends(['busqueda' => $busqueda, 'por_pagina' => $porPagina]);
        
        return view('centros.index', compact('centros', 'busqueda', 'porPagina', 'totalCentros', 'totalAlumnos'));
    }
    
    /**
     * Muestra el detalle de un centro específico
     */
    public function show($id)
    {
        // Obtener el centro
        $centro = DB::table('centros')->where('id_centro', $id)->first();
        
        if (!$centro) {
            return redirect()->route('centros.index')
                ->with('error', 'Centro no encontrado');
        }
        
        // Obtener alumnos del centro
        $alumnos = DB::table('alumnos')
            ->where('id_centro', $id)
            ->orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombre', 'asc')
            ->get();
        
        // Estadísticas del centro
        $totalAlumnos = $alumnos->count();
        
        // Hombres y mujeres
        $hombres = DB::table('alumnos')
            ->where('id_centro', $id)
            ->where('genero', 'M')
            ->count();
        
        $mujeres = DB::table('alumnos')
            ->where('id_centro', $id)
            ->where('genero', 'F')
            ->count();
        
        // Alumnos activos
        $activos = DB::table('alumnos')
            ->where('id_centro', $id)
            ->where('estatus', 'Activo')
            ->count();
        
        // Generaciones
        $generaciones = DB::table('alumnos')
            ->where('id_centro', $id)
            ->select('generacion', DB::raw('count(*) as total'))
            ->groupBy('generacion')
            ->orderBy('generacion', 'desc')
            ->get();
        
        return view('centros.show', compact('centro', 'alumnos', 'totalAlumnos', 'hombres', 'mujeres', 'activos', 'generaciones'));
    }
    
    /**
     * API: Obtener centros para selects (JSON)
     */
    public function getCentrosJson()
    {
        $centros = DB::table('centros')->select('id_centro', 'nombre', 'clave_ct')->orderBy('nombre')->get();
        return response()->json($centros);
    }
}