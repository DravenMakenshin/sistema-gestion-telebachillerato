<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class ImportarAlumnosExcel extends Command
{
    protected $signature = 'importar:alumnos {archivo}';
    protected $description = 'Importa alumnos desde archivo Excel';

    public function handle()
    {
        $archivo = $this->argument('archivo');
        
        if (!file_exists($archivo)) {
            $this->error("❌ Archivo no encontrado: $archivo");
            return 1;
        }

        $this->info("📂 Leyendo archivo: " . basename($archivo));
        
        try {
            $spreadsheet = IOFactory::load($archivo);
            $hoja = $spreadsheet->getActiveSheet();
            $datos = $hoja->toArray();
            
            // Eliminar encabezado (primera fila)
            $encabezado = array_shift($datos);
            $this->info("📋 Encabezados encontrados: " . implode(' | ', array_slice($encabezado, 0, 11)));
            
            $importados = 0;
            $errores = 0;
            $duplicados = 0;
            $sin_centro = 0;
            $fechas_invalidas = 0;
            $hombres = 0;
            $mujeres = 0;
            
            foreach ($datos as $indice => $fila) {
                try {
                    // Verificar que la fila tiene datos mínimos
                    if (empty($fila[0]) || empty($fila[3])) {
                        continue;
                    }
                    
                    // Estructura del Excel AlumnosTBC.xlsx:
                    // $fila[0] = Matrícula
                    // $fila[1] = Telebachillerato (nombre del centro)
                    // $fila[2] = Estatus Alumno
                    // $fila[3] = Nombre
                    // $fila[4] = Paterno
                    // $fila[5] = Materno
                    // $fila[6] = Genero (M = Mujer, H = Hombre)
                    // $fila[7] = Generación
                    // $fila[8] = Municipio Residencia
                    // $fila[9] = País Nacimiento
                    // $fila[10] = Fecha Nacimiento
                    
                    $matricula = trim($fila[0]);
                    $nombre_centro = trim($fila[1] ?? '');
                    $nombre = trim($fila[3] ?? '');
                    $apellido_paterno = trim($fila[4] ?? '');
                    $apellido_materno = trim($fila[5] ?? '');
                    
                    // Verificar si la matrícula ya existe
                    $existente = DB::table('alumnos')->where('matricula', $matricula)->first();
                    
                    if ($existente) {
                        $duplicados++;
                        $this->warn("⚠️ Duplicado: {$matricula} - {$nombre} {$apellido_paterno} (omitido)");
                        continue;
                    }
                    
                    // Buscar el centro por nombre
                    $centro = null;
                    if (!empty($nombre_centro)) {
                        $centro = DB::table('centros')->where('nombre', 'like', "%{$nombre_centro}%")->first();
                        if (!$centro) {
                            $sin_centro++;
                            $this->warn("⚠️ Centro no encontrado para: {$nombre_centro} - Alumno: {$nombre} {$apellido_paterno}");
                        }
                    }
                    
                    // ==============================================
                    // PROCESAR GÉNERO - CORREGIDO
                    // M en Excel = Mujer = F en BD
                    // H en Excel = Hombre = M en BD
                    // ==============================================
                    $genero = null;
                    $genero_texto = strtolower(trim($fila[6] ?? ''));
                    
                    if ($genero_texto == 'm') {
                        $genero = 'F';  // M en Excel = Mujer = F en BD
                        $mujeres++;
                        $this->info("   Género: M → F (Mujer)");
                    } elseif ($genero_texto == 'h') {
                        $genero = 'M';  // H en Excel = Hombre = M en BD
                        $hombres++;
                        $this->info("   Género: H → M (Hombre)");
                    } else {
                        $this->warn("   Género desconocido: '{$fila[6]}' para {$nombre} {$apellido_paterno}");
                    }
                    
                    // ==============================================
                    // PROCESAR FECHA DE NACIMIENTO
                    // ==============================================
                    $fechaNacimiento = null;
                    $fecha_original = $fila[10] ?? '';
                    
                    if (!empty($fecha_original)) {
                        try {
                            $fecha_procesada = false;
                            
                            if (is_numeric($fecha_original)) {
                                $unix = ($fecha_original - 25569) * 86400;
                                $fechaNacimiento = date('Y-m-d', $unix);
                                $fecha_procesada = true;
                            } else {
                                $fecha_limpia = trim($fecha_original);
                                $timestamp = strtotime($fecha_limpia);
                                if ($timestamp !== false && $timestamp > 0) {
                                    $fechaNacimiento = date('Y-m-d', $timestamp);
                                    $fecha_procesada = true;
                                }
                            }
                            
                            if ($fecha_procesada && $fechaNacimiento) {
                                $año = date('Y', strtotime($fechaNacimiento));
                                $año_actual = date('Y');
                                
                                if ($año < 1900 || $año > $año_actual) {
                                    $fechaNacimiento = null;
                                    $fechas_invalidas++;
                                }
                            } else {
                                $fechaNacimiento = null;
                                $fechas_invalidas++;
                            }
                        } catch (\Exception $e) {
                            $fechaNacimiento = null;
                            $fechas_invalidas++;
                        }
                    }
                    
                    // Insertar el alumno
                    DB::table('alumnos')->insert([
                        'matricula' => $matricula,
                        'id_centro' => $centro->id_centro ?? null,
                        'estatus' => trim($fila[2] ?? 'Activo'),
                        'nombre' => $nombre,
                        'apellido_paterno' => $apellido_paterno,
                        'apellido_materno' => $apellido_materno ?: null,
                        'genero' => $genero,
                        'generacion' => trim($fila[7] ?? null),
                        'municipio_residencia' => trim($fila[8] ?? null),
                        'pais_nacimiento' => trim($fila[9] ?? null),
                        'fecha_nacimiento' => $fechaNacimiento,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $importados++;
                    
                    if ($importados % 50 == 0) {
                        $this->info("📊 Progreso: {$importados} alumnos importados...");
                    }
                    
                } catch (\Exception $e) {
                    $errores++;
                    $this->error("❌ Error en fila " . ($indice + 2) . ": " . $e->getMessage());
                }
            }
            
            $this->info("\n📊 === RESUMEN DE IMPORTACIÓN ===");
            $this->info("✅ Alumnos importados: $importados");
            $this->info("👨 Hombres: $hombres");
            $this->info("👩 Mujeres: $mujeres");
            $this->info("⚠️ Duplicados omitidos: $duplicados");
            $this->info("⚠️ Alumnos sin centro asignado: $sin_centro");
            $this->info("⚠️ Fechas inválidas omitidas: $fechas_invalidas");
            $this->info("❌ Errores generales: $errores");
            
            // Mostrar estadísticas finales
            $total_centros = DB::table('centros')->count();
            $total_alumnos = DB::table('alumnos')->count();
            $total_hombres = DB::table('alumnos')->where('genero', 'M')->count();
            $total_mujeres = DB::table('alumnos')->where('genero', 'F')->count();
            
            $this->info("\n📈 ESTADÍSTICAS FINALES:");
            $this->info("🏫 Total centros en BD: $total_centros");
            $this->info("👨‍🎓 Total alumnos en BD: $total_alumnos");
            $this->info("👨 Hombres: $total_hombres");
            $this->info("👩 Mujeres: $total_mujeres");
            
        } catch (\Exception $e) {
            $this->error("❌ Error al leer el archivo: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}