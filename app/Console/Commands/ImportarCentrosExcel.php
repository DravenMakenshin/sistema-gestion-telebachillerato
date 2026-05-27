<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class ImportarCentrosExcel extends Command
{
    protected $signature = 'importar:centros {archivo}';
    protected $description = 'Importa centros desde archivo Excel';

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
            $this->info("📋 Encabezados encontrados: " . implode(' | ', array_slice($encabezado, 0, 6)));
            
            $importados = 0;
            $errores = 0;
            $duplicados = 0;
            
            foreach ($datos as $indice => $fila) {
                try {
                    // Verificar que la fila tiene datos mínimos
                    if (empty($fila[1]) && empty($fila[2])) {
                        continue;
                    }
                    
                    // Estructura del Excel CentrosTBC.xlsx:
                    // $fila[0] = Clave (no se usa)
                    // $fila[1] = Telebachillerato (nombre)
                    // $fila[2] = Clave del Centro de Trabajo
                    // $fila[3] = Municipio
                    // $fila[4] = Encargado
                    // $fila[5] = Correo del encargado
                    
                    $clave_ct = $fila[2] ?? null;
                    $nombre = $fila[1] ?? 'Sin nombre';
                    
                    // Verificar si ya existe para evitar duplicados
                    $existente = DB::table('centros')->where('clave_ct', $clave_ct)->first();
                    
                    if ($existente) {
                        $duplicados++;
                        $this->warn("⚠️ Duplicado: {$nombre} (clave: {$clave_ct}) - omitido");
                        continue;
                    }
                    
                    DB::table('centros')->insert([
                        'clave_ct' => $clave_ct,
                        'nombre' => $nombre,
                        'municipio' => $fila[3] ?? null,
                        'encargado' => $fila[4] ?? null,
                        'correo_encargado' => $fila[5] ?? null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $importados++;
                    $this->info("✅ [$importados] Importado: {$nombre}");
                    
                } catch (\Exception $e) {
                    $errores++;
                    $this->error("❌ Error en fila " . ($indice + 2) . ": " . $e->getMessage());
                }
            }
            
            $this->info("\n📊 === RESUMEN DE IMPORTACIÓN ===");
            $this->info("✅ Importados: $importados");
            $this->info("⚠️ Duplicados omitidos: $duplicados");
            $this->info("❌ Errores: $errores");
            $this->info("📁 Total filas procesadas: " . ($importados + $duplicados + $errores));
            
        } catch (\Exception $e) {
            $this->error("❌ Error al leer el archivo: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}