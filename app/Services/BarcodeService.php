<?php
namespace App\Services;
use App\Models\Insumo;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeService
{
    /**
     * Genera un código QR único para un insumo
     * El código será generado automáticamente por el sistema
     */
    public function generateUniqueBarcode(string $unidadId = null): string
    {
        do {
            // Generar código único basado en timestamp y unidad
            $timestamp = time();
            $random = rand(1000, 9999);
            $prefix = $this->getUnitPrefix($unidadId);
            $barcode = "INS-{$prefix}-{$timestamp}-{$random}";
        } while ($this->barcodeExists($barcode));
        
        return $barcode;
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

    private function barcodeExists(string $barcode): bool
    {
        return Insumo::where('codigo_barra', $barcode)->exists() || 
               User::where('codigo_barra', $barcode)->exists();
    }

    /**
     * Genera imagen de código QR para insumo
     */
    public function generateBarcodeImage(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "qr_{$barcode}.png";
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
            ->generate($barcode);
        
        Storage::disk('public')->put($path, $qrCode);
        return $path;
    }

    /**
     * Genera SVG de código QR para insumo
     */
    public function generateBarcodeSVG(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "qr_{$barcode}.svg";
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
            ->generate($barcode);
        
        Storage::disk('public')->put($path, $qrCode);
        return $path;
    }

    /**
     * Valida el formato del código QR (ahora acepta cualquier formato)
     */
    public function validateBarcode(string $barcode): bool
    {
        // Los códigos QR pueden tener cualquier formato, solo verificamos que no esté vacío
        return !empty($barcode) && strlen($barcode) > 0;
    }

    /**
     * Obtiene información del código QR
     */
    public function getBarcodeInfo(string $barcode): array
    {
        if (!$this->validateBarcode($barcode)) {
            return ['valid' => false, 'message' => 'Código QR inválido'];
        }
        
        // Extraer información del código si tiene formato INS-PREFIX-...
        $parts = explode('-', $barcode);
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
            'barcode' => $barcode,
            'unit' => $unitInfo,
            'type' => 'QR'
        ];
    }

    /**
     * Obtiene la URL del código QR de un insumo
     */
    public function getBarcodeUrl(string $barcode): string
    {
        $filename = "qr_{$barcode}.png";
        $path = "codigos_insumos/{$filename}";
        
        // Si la imagen no existe, generarla
        if (!Storage::disk('public')->exists($path)) {
            $this->generateBarcodeImage($barcode, $filename);
        }
        
        return asset('storage/' . $path);
    }

    /**
     * Elimina la imagen del código QR
     */
    public function deleteBarcodeImage(string $barcode): void
    {
        $filename = "qr_{$barcode}.png";
        $path = "codigos_insumos/{$filename}";
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // También eliminar SVG si existe
        $svgFilename = "qr_{$barcode}.svg";
        $svgPath = "codigos_insumos/{$svgFilename}";
        if (Storage::disk('public')->exists($svgPath)) {
            Storage::disk('public')->delete($svgPath);
        }
    }

    /**
     * Genera un código QR para un usuario usando su RUN
     * El código QR simplemente contiene el RUN formateado
     */
    public function generateUserBarcode(string $run): string
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
     * Verifica si un código QR existe para usuarios
     */
    private function userBarcodeExists(string $barcode): bool
    {
        return User::where('codigo_barra', $barcode)->exists();
    }

    /**
     * Genera imagen de código QR para usuario
     */
    public function generateUserBarcodeImage(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "user_qr_{$barcode}.png";
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
            ->generate($barcode);
        
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
    public function generateUserBarcodeSVG(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "user_qr_{$barcode}.svg";
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
            ->generate($barcode);
        
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
    public function getUserBarcodeUrl(string $barcode): string
    {
        $filename = "user_qr_{$barcode}.png";
        $path = "codigos_usuarios/{$filename}";
        
        // Asegurar que el directorio existe
        $directory = "codigos_usuarios";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Si la imagen no existe, generarla
        if (!Storage::disk('public')->exists($path)) {
            $this->generateUserBarcodeImage($barcode, $filename);
        }
        
        // Usar Storage::url() para obtener la URL correcta con timestamp para evitar caché
        $url = Storage::disk('public')->url($path);
        return $url . '?t=' . time();
    }

    /**
     * Elimina todas las imágenes del código QR de un usuario
     */
    public function deleteUserBarcodeImage(string $barcode): void
    {
        $filename = "user_qr_{$barcode}.png";
        $path = "codigos_usuarios/{$filename}";
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // También eliminar SVG si existe
        $svgFilename = "user_qr_{$barcode}.svg";
        $svgPath = "codigos_usuarios/{$svgFilename}";
        if (Storage::disk('public')->exists($svgPath)) {
            Storage::disk('public')->delete($svgPath);
        }
    }

    /**
     * Elimina todas las imágenes de códigos QR de un usuario (por código)
     * Útil cuando se regenera el código QR
     */
    public function deleteAllUserBarcodeImages(User $user): void
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
