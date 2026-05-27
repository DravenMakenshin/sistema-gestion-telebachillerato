<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio
     */
    public function index()
    {
        // Obtener estadísticas para el dashboard
        $totalCentros = DB::table('centros')->count();
        $totalAlumnos = DB::table('alumnos')->count();
        $totalCalificaciones = DB::table('calificaciones')->count();
        
        // Últimos centros agregados
        $ultimosCentros = DB::table('centros')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Últimos alumnos agregados
        $ultimosAlumnos = DB::table('alumnos')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('inicio', compact(
            'totalCentros', 
            'totalAlumnos', 
            'totalCalificaciones',
            'ultimosCentros',
            'ultimosAlumnos'
        ));
    }
}