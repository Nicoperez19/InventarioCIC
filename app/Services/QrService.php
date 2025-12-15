<?php
namespace App\Services;
use App\Models\Insumo;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrService
{
    /**
     * Genera un código QR único para un insumo
     * El código será generado automáticamente por el sistema
     */
    public function generateUniqueQr(string $unidadId = null): string
    {
        do {
            // Generar código único basado en timestamp y unidad
            $timestamp = time();
            $random = rand(1000, 9999);
            $prefix = $this->getUnitPrefix($unidadId);
            $qr = "INS-{$prefix}-{$timestamp}-{$random}";
        } while ($this->qrExists($qr));
        
        return $qr;
    }

    private function getUnitPrefix(string $unidadId): string
    {
        $unitPrefixes = [
            'U001' => 'CJA',
            'U002' => 'UNI',
            'U003' => 'BOL',
            'U004' => 'DIS',
            'U005' => 'BID',
            'U006' => 'BOT',
            'U007' => 'KIL',
        ];
        return $unitPrefixes[$unidadId] ?? 'GEN';
    }

    private function qrExists(string $qr): bool
    {
        return Insumo::where('codigo_barra', $qr)->exists() || 
               User::where('codigo_barra', $qr)->exists();
    }

    /**
     * Genera imagen de código QR para insumo
     */
    public function generateQrImage(string $qr, string $filename = null): string
    {
        if (!$filename) {
            $filename = "qr_{$qr}.png";
        }
        
        $path = "codigos_insumos/{$filename}";
        
        // Asegurar que el directorio existe
        $directory = "codigos_insumos";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Generar código QR
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qr);
        
        Storage::disk('public')->put($path, $qrCode);
        return $path;
    }

    /**
     * Genera SVG de código QR para insumo
     */
    public function generateQrSVG(string $qr, string $filename = null): string
    {
        if (!$filename) {
            $filename = "qr_{$qr}.svg";
        }
        
        $path = "codigos_insumos/{$filename}";
        
        // Asegurar que el directorio existe
        $directory = "codigos_insumos";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Generar código QR en formato SVG
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($qr);
        
        Storage::disk('public')->put($path, $qrCode);
        return $path;
    }

    /**
     * Valida el formato del código QR (ahora acepta cualquier formato)
     */
    public function validateQr(string $qr): bool
    {
        // Los códigos QR pueden tener cualquier formato, solo verificamos que no esté vacío
        return !empty($qr) && strlen($qr) > 0;
    }

    /**
     * Obtiene información del código QR
     */
    public function getQrInfo(string $qr): array
    {
        if (!$this->validateQr($qr)) {
            return ['valid' => false, 'message' => 'Código QR inválido'];
        }
        
        // Extraer información del código si tiene formato INS-PREFIX-...
        $parts = explode('-', $qr);
        $unitInfo = ['id' => 'Unknown', 'name' => 'Unidad desconocida'];
        
        if (count($parts) >= 2 && $parts[0] === 'INS') {
            $prefix = $parts[1];
            $prefixToUnit = [
                'CJA' => ['id' => 'U001', 'name' => 'Caja'],
                'UNI' => ['id' => 'U002', 'name' => 'Unidad'],
                'BOL' => ['id' => 'U003', 'name' => 'Bolsa'],
                'DIS' => ['id' => 'U004', 'name' => 'Display'],
                'BID' => ['id' => 'U005', 'name' => 'Bidon'],
                'BOT' => ['id' => 'U006', 'name' => 'Botella'],
                'KIL' => ['id' => 'U007', 'name' => 'Kilogramo'],
            ];
            $unitInfo = $prefixToUnit[$prefix] ?? $unitInfo;
        }
        
        return [
            'valid' => true,
            'qr' => $qr,
            'unit' => $unitInfo,
            'type' => 'QR'
        ];
    }

    /**
     * Obtiene la URL del código QR de un insumo
     */
    public function getQrUrl(string $qr): string
    {
        $filename = "qr_{$qr}.png";
        $path = "codigos_insumos/{$filename}";
        
        // Si la imagen no existe, generarla
        if (!Storage::disk('public')->exists($path)) {
            $this->generateQrImage($qr, $filename);
        }
        
        return asset('storage/' . $path);
    }

    /**
     * Elimina la imagen del código QR
     */
    public function deleteQrImage(string $qr): void
    {
        $filename = "qr_{$qr}.png";
        $path = "codigos_insumos/{$filename}";
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // También eliminar SVG si existe
        $svgFilename = "qr_{$qr}.svg";
        $svgPath = "codigos_insumos/{$svgFilename}";
        if (Storage::disk('public')->exists($svgPath)) {
            Storage::disk('public')->delete($svgPath);
        }
    }

    /**
     * Genera un código QR para un usuario usando su RUN
     * El código QR simplemente contiene el RUN formateado
     */
    public function generateUserQr(string $run): string
    {
        // Limpiar el RUN (remover puntos y guiones) y normalizar
        $runClean = str_replace(['.', '-'], '', $run);
        
        // Convertir 'k' o 'K' a '0' si es el dígito verificador
        $runClean = str_replace(['k', 'K'], '0', $runClean);
        
        // El código QR es simplemente el RUN limpio
        // Esto permite que al escanear el QR se obtenga directamente el RUN
        return $runClean;
    }

    /**
     * Genera imagen de código QR para usuario
     */
    public function generateUserQrImage(string $qr, string $filename = null): string
    {
        if (!$filename) {
            $filename = "user_qr_{$qr}.png";
        }
        
        // Asegurar que el directorio existe
        $directory = "codigos_usuarios";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Generar código QR
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qr);
        
        $path = "{$directory}/{$filename}";
        
        // Eliminar si ya existe antes de crear uno nuevo
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        Storage::disk('public')->put($path, $qrCode);
        return $path;
    }

    /**
     * Genera SVG de código QR para usuario
     */
    public function generateUserQrSVG(string $qr, string $filename = null): string
    {
        if (!$filename) {
            $filename = "user_qr_{$qr}.svg";
        }
        
        // Asegurar que el directorio existe
        $directory = "codigos_usuarios";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Generar código QR en formato SVG
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($qr);
        
        $path = "{$directory}/{$filename}";
        
        // Eliminar si ya existe antes de crear uno nuevo
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        Storage::disk('public')->put($path, $qrCode);
        return $path;
    }

    /**
     * Obtiene la URL del código QR de un usuario
     */
    public function getUserQrUrl(string $qr): string
    {
        $filename = "user_qr_{$qr}.png";
        $path = "codigos_usuarios/{$filename}";
        
        // Asegurar que el directorio existe
        $directory = "codigos_usuarios";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Si la imagen no existe, generarla
        if (!Storage::disk('public')->exists($path)) {
            $this->generateUserQrImage($qr, $filename);
        }
        
        // Usar Storage::url() para obtener la URL correcta con timestamp para evitar caché
        $url = Storage::disk('public')->url($path);
        return $url . '?t=' . time();
    }

    /**
     * Elimina todas las imágenes del código QR de un usuario
     */
    public function deleteUserQrImage(string $qr): void
    {
        $filename = "user_qr_{$qr}.png";
        $path = "codigos_usuarios/{$filename}";
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // También eliminar SVG si existe
        $svgFilename = "user_qr_{$qr}.svg";
        $svgPath = "codigos_usuarios/{$svgFilename}";
        if (Storage::disk('public')->exists($svgPath)) {
            Storage::disk('public')->delete($svgPath);
        }
    }

    /**
     * Elimina todas las imágenes de códigos QR de un usuario (por código)
     * Útil cuando se regenera el código QR
     */
    public function deleteAllUserQrImages(User $user): void
    {
        if (!$user->codigo_barra) {
            return;
        }
        
        $directory = "codigos_usuarios";
        
        // Buscar archivos específicos del usuario en lugar de iterar todos
        $filename = "user_qr_{$user->codigo_barra}.png";
        $svgFilename = "user_qr_{$user->codigo_barra}.svg";
        
        $pngPath = "{$directory}/{$filename}";
        $svgPath = "{$directory}/{$svgFilename}";
        
        if (Storage::disk('public')->exists($pngPath)) {
            Storage::disk('public')->delete($pngPath);
        }
        
        if (Storage::disk('public')->exists($svgPath)) {
            Storage::disk('public')->delete($svgPath);
        }
    }
}

