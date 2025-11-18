<?php

namespace App\Console\Commands;

use App\Models\Insumo;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckLowStockInsumos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insumos:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica insumos con stock bajo y crea notificaciones diarias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando insumos con stock bajo...');

        // Buscar insumos con stock <= 1
        $insumosBajos = Insumo::where('stock_actual', '<=', 1)
            ->orderBy('stock_actual', 'asc')
            ->get();

        if ($insumosBajos->isEmpty()) {
            $this->info('No hay insumos con stock bajo.');
            Log::info('Verificación de stock bajo: No se encontraron insumos con stock bajo.');
            return Command::SUCCESS;
        }

        $this->info("Se encontraron {$insumosBajos->count()} insumos con stock bajo.");

        // Obtener usuarios que deben recibir las notificaciones
        // Usuarios con permisos de insumos o administradores
        $usuarios = User::whereHas('roles', function ($query) {
            $query->where('name', 'Administrador');
        })->orWhereHas('permissions', function ($query) {
            $query->whereIn('name', ['insumos', 'mantenedor de tipos de insumo']);
        })->get();

        // Si no hay usuarios con esos permisos, usar todos los administradores
        if ($usuarios->isEmpty()) {
            $usuarios = User::whereHas('roles', function ($query) {
                $query->where('name', 'Administrador');
            })->get();
        }

        if ($usuarios->isEmpty()) {
            $this->warn('No se encontraron usuarios para enviar notificaciones.');
            Log::warning('Verificación de stock bajo: No se encontraron usuarios para enviar notificaciones.');
            return Command::SUCCESS;
        }

        // Agrupar insumos por estado
        $insumosAgotados = $insumosBajos->where('stock_actual', 0);
        $insumosCriticos = $insumosBajos->where('stock_actual', 1);

        // Crear notificaciones separadas para cada tipo
        $notificacionesCreadas = 0;
        foreach ($usuarios as $usuario) {
            // Notificación para insumos agotados
            if ($insumosAgotados->isNotEmpty()) {
                $mensajeAgotados = $this->generarMensajeNotificacion($insumosAgotados, collect([]));
                $notificacionAgotados = Notificacion::where('user_id', $usuario->run)
                    ->where('tipo', 'stock_agotado')
                    ->whereDate('created_at', today())
                    ->first();

                if (!$notificacionAgotados) {
                    Notificacion::create([
                        'tipo' => 'stock_agotado',
                        'titulo' => 'Insumos Agotados',
                        'mensaje' => $mensajeAgotados,
                        'user_id' => $usuario->run,
                        'leida' => false,
                    ]);
                    $notificacionesCreadas++;
                } else {
                    $notificacionAgotados->update([
                        'mensaje' => $mensajeAgotados,
                        'leida' => false,
                        'leida_at' => null,
                    ]);
                }
            }

            // Notificación para insumos críticos
            if ($insumosCriticos->isNotEmpty()) {
                $mensajeCriticos = $this->generarMensajeNotificacion(collect([]), $insumosCriticos);
                $notificacionCriticos = Notificacion::where('user_id', $usuario->run)
                    ->where('tipo', 'stock_critico')
                    ->whereDate('created_at', today())
                    ->first();

                if (!$notificacionCriticos) {
                    Notificacion::create([
                        'tipo' => 'stock_critico',
                        'titulo' => 'Insumos Críticos',
                        'mensaje' => $mensajeCriticos,
                        'user_id' => $usuario->run,
                        'leida' => false,
                    ]);
                    $notificacionesCreadas++;
                } else {
                    $notificacionCriticos->update([
                        'mensaje' => $mensajeCriticos,
                        'leida' => false,
                        'leida_at' => null,
                    ]);
                }
            }
        }

        $this->info("Se crearon/actualizaron {$notificacionesCreadas} notificaciones para {$usuarios->count()} usuarios.");
        Log::info('Verificación de stock bajo completada', [
            'insumos_bajos' => $insumosBajos->count(),
            'insumos_agotados' => $insumosAgotados->count(),
            'insumos_criticos' => $insumosCriticos->count(),
            'notificaciones_creadas' => $notificacionesCreadas,
            'usuarios_notificados' => $usuarios->count(),
        ]);

        return Command::SUCCESS;
    }

    /**
     * Genera el mensaje de notificación con los insumos con stock bajo
     */
    private function generarMensajeNotificacion($insumosAgotados, $insumosCriticos): string
    {
        $mensaje = "";

        if ($insumosAgotados->isNotEmpty()) {
            $totalAgotados = $insumosAgotados->count();
            $mensaje .= "Total: {$totalAgotados} insumos agotados\n\n";
            // Mostrar hasta 10 insumos agotados
            $mostrarAgotados = $insumosAgotados->take(10);
            foreach ($mostrarAgotados as $insumo) {
                $mensaje .= "• {$insumo->nombre_insumo}\n";
            }
            if ($totalAgotados > 10) {
                $mensaje .= "... y " . ($totalAgotados - 10) . " más\n";
            }
        }

        if ($insumosCriticos->isNotEmpty()) {
            $totalCriticos = $insumosCriticos->count();
            $mensaje .= "Total: {$totalCriticos} insumos críticos (Stock: 1)\n\n";
            // Mostrar hasta 10 insumos críticos
            $mostrarCriticos = $insumosCriticos->take(10);
            foreach ($mostrarCriticos as $insumo) {
                $mensaje .= "• {$insumo->nombre_insumo}\n";
            }
            if ($totalCriticos > 10) {
                $mensaje .= "... y " . ($totalCriticos - 10) . " más\n";
            }
        }

        $mensaje .= "\nPor favor, revisa el inventario y realiza las solicitudes necesarias.";

        return $mensaje;
    }
}

