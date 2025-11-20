<?php
namespace App\Services;
use App\Models\Insumo;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;
class BarcodeService
{
    private BarcodeGeneratorPNG $generatorPNG;
    private BarcodeGeneratorSVG $generatorSVG;
    public function __construct()
    {
        $this->generatorPNG = new BarcodeGeneratorPNG();
        $this->generatorSVG = new BarcodeGeneratorSVG();
    }
    public function generateUniqueBarcode(string $unidadId = null): string
    {
        do {
            $barcode = $this->generateEAN13BarcodeByUnit($unidadId);
        } while ($this->barcodeExists($barcode));
        return $barcode;
    }
    private function getUnitPrefix(string $unidadId): string
    {
        $unitPrefixes = [
            'U001' => '1',
            'U002' => '2',
            'U003' => '3',
            'U004' => '4',
            'U005' => '5',
            'U006' => '6',
            'U007' => '7',
        ];
        return $unitPrefixes[$unidadId] ?? '1';
    }
    private function generateEAN13BarcodeByUnit(string $unidadId = null): string
    {
        $prefix = $this->getUnitPrefix($unidadId);
        $sequenceNumber = $this->getNextSequenceForUnit($unidadId);
        // EAN-13: prefijo (1 dígito) + código (11 dígitos) = 12 dígitos, luego checksum
        $code = $prefix . str_pad($sequenceNumber, 11, '0', STR_PAD_LEFT);
        $checksum = $this->calculateEAN13Checksum($code);
        return $code . $checksum;
    }
    private function getNextSequenceForUnit(string $unidadId = null): int
    {
        $lastBarcode = Insumo::where('id_unidad', $unidadId)
            ->whereNotNull('codigo_barra')
            ->orderBy('codigo_barra', 'desc')
            ->first();
        if (!$lastBarcode || !$lastBarcode->codigo_barra) {
            return 1;
        }
        $lastCode = $lastBarcode->codigo_barra;
        $prefix = $this->getUnitPrefix($unidadId);
        if (strlen($lastCode) === 13 && str_starts_with($lastCode, $prefix)) {
            $sequence = (int) substr($lastCode, 1, 11);
            return $sequence + 1;
        }
        return 1;
    }
    /**
     * Calcula el checksum para códigos EAN-13
     * Algoritmo: Sumar dígitos en posiciones impares (1,3,5...) * 1 + 
     *            Sumar dígitos en posiciones pares (2,4,6...) * 3
     *            El checksum es: (10 - (suma % 10)) % 10
     */
    private function calculateEAN13Checksum(string $code): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $code[$i];
            // Posiciones impares (0-indexed) se multiplican por 1
            // Posiciones pares (0-indexed) se multiplican por 3
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        return (10 - ($sum % 10)) % 10;
    }
    private function barcodeExists(string $barcode): bool
    {
        return Insumo::where('codigo_barra', $barcode)->exists() || 
               User::where('codigo_barra', $barcode)->exists();
    }
    public function generateBarcodeImage(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "barcode_{$barcode}.png";
        }
        // EAN-13 muestra los números automáticamente debajo
        // Aumentamos la altura a 80 para que los números sean visibles
        $imageData = $this->generatorPNG->getBarcode($barcode, BarcodeGeneratorPNG::TYPE_EAN_13, 2, 80, [255, 255, 255], [0, 0, 0]);
        $path = "codigos_insumos/{$filename}";
        Storage::disk('public')->put($path, $imageData);
        return $path;
    }

    public function generateBarcodeSVG(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "barcode_{$barcode}.svg";
        }
        // EAN-13 muestra los números automáticamente debajo
        // Aumentamos la altura a 80 para que los números sean visibles
        $svgData = $this->generatorSVG->getBarcode($barcode, BarcodeGeneratorSVG::TYPE_EAN_13, 2, 80);
        $path = "codigos_insumos/{$filename}";
        Storage::disk('public')->put($path, $svgData);
        return $path;
    }
    public function validateBarcode(string $barcode): bool
    {
        if (!preg_match('/^\d{13}$/', $barcode)) {
            return false;
        }
        $code = substr($barcode, 0, 12);
        $checksum = (int) substr($barcode, 12, 1);
        $calculatedChecksum = $this->calculateEAN13Checksum($code);
        return $checksum === $calculatedChecksum;
    }
    public function getBarcodeInfo(string $barcode): array
    {
        if (!$this->validateBarcode($barcode)) {
            return ['valid' => false, 'message' => 'Código de barras inválido'];
        }
        $prefix = substr($barcode, 0, 1);
        $sequence = substr($barcode, 1, 11);
        $prefixToUnit = [
            '1' => ['id' => 'U001', 'name' => 'Caja'],
            '2' => ['id' => 'U002', 'name' => 'Unidad'],
            '3' => ['id' => 'U003', 'name' => 'Bolsa'],
            '4' => ['id' => 'U004', 'name' => 'Display'],
            '5' => ['id' => 'U005', 'name' => 'Bidon'],
            '6' => ['id' => 'U006', 'name' => 'Botella'],
            '7' => ['id' => 'U007', 'name' => 'Kilogramo'],
        ];
        $unitInfo = $prefixToUnit[$prefix] ?? ['id' => 'Unknown', 'name' => 'Unidad desconocida'];
        return [
            'valid' => true,
            'barcode' => $barcode,
            'prefix' => $prefix,
            'sequence' => (int) $sequence,
            'unit' => $unitInfo,
            'checksum' => substr($barcode, 12, 1)
        ];
    }

    public function getBarcodeUrl(string $barcode): string
    {
        $filename = "barcode_{$barcode}.png";
        $path = "codigos_insumos/{$filename}";
        
        // Si la imagen no existe, generarla
        if (!Storage::disk('public')->exists($path)) {
            $this->generateBarcodeImage($barcode, $filename);
        }
        
        return asset('storage/' . $path);
    }

    public function deleteBarcodeImage(string $barcode): void
    {
        $filename = "barcode_{$barcode}.png";
        $path = "codigos_insumos/{$filename}";
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Genera un código de barras único para un usuario usando su RUN directamente
     */
    public function generateUserBarcode(string $run): string
    {
        // Limpiar el RUN (remover puntos y guiones)
        $runClean = str_replace(['.', '-'], '', $run);
        
        // Convertir 'k' o 'K' a '0' si es el dígito verificador
        $runClean = str_replace(['k', 'K'], '0', $runClean);
        
        // EAN-13 requiere 12 dígitos antes del checksum
        // Tomar el RUN completo y rellenar con ceros al inicio para llegar a 12 dígitos
        $runNumeric = str_pad($runClean, 12, '0', STR_PAD_LEFT);
        
        // Si el RUN tiene más de 12 dígitos, tomar solo los últimos 12
        if (strlen($runNumeric) > 12) {
            $runNumeric = substr($runNumeric, -12);
        }
        
        // Calcular checksum EAN-13
        $checksum = $this->calculateEAN13Checksum($runNumeric);
        
        // El código de barras es el RUN (12 dígitos) + checksum (1 dígito) = 13 dígitos
        $barcode = $runNumeric . $checksum;
        
        return $barcode;
    }


    /**
     * Verifica si un código de barras existe para usuarios
     */
    private function userBarcodeExists(string $barcode): bool
    {
        return User::where('codigo_barra', $barcode)->exists();
    }

    /**
     * Genera imagen de código de barras para usuario
     */
    public function generateUserBarcodeImage(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "user_barcode_{$barcode}.png";
        }
        
        // Asegurar que el directorio existe
        $directory = "codigos_usuarios";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // EAN-13 muestra los números automáticamente debajo
        // Parámetros: código, tipo, ancho_línea, altura, color_fondo, color_línea
        // Aumentamos la altura a 80 para que los números sean visibles
        $imageData = $this->generatorPNG->getBarcode($barcode, BarcodeGeneratorPNG::TYPE_EAN_13, 2, 80, [255, 255, 255], [0, 0, 0]);
        $path = "{$directory}/{$filename}";
        
        // Eliminar si ya existe antes de crear uno nuevo
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        Storage::disk('public')->put($path, $imageData);
        return $path;
    }

    /**
     * Genera SVG de código de barras para usuario
     */
    public function generateUserBarcodeSVG(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "user_barcode_{$barcode}.svg";
        }
        
        // Asegurar que el directorio existe
        $directory = "codigos_usuarios";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // EAN-13 muestra los números automáticamente debajo
        // Aumentamos la altura a 80 para que los números sean visibles
        $svgData = $this->generatorSVG->getBarcode($barcode, BarcodeGeneratorSVG::TYPE_EAN_13, 2, 80);
        $path = "{$directory}/{$filename}";
        
        // Eliminar si ya existe antes de crear uno nuevo
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        Storage::disk('public')->put($path, $svgData);
        return $path;
    }

    /**
     * Obtiene la URL del código de barras de un usuario
     */
    public function getUserBarcodeUrl(string $barcode): string
    {
        $filename = "user_barcode_{$barcode}.png";
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
     * Elimina todas las imágenes del código de barras de un usuario
     */
    public function deleteUserBarcodeImage(string $barcode): void
    {
        $filename = "user_barcode_{$barcode}.png";
        $path = "codigos_usuarios/{$filename}";
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // También eliminar SVG si existe
        $svgFilename = "user_barcode_{$barcode}.svg";
        $svgPath = "codigos_usuarios/{$svgFilename}";
        if (Storage::disk('public')->exists($svgPath)) {
            Storage::disk('public')->delete($svgPath);
        }
    }

    /**
     * Elimina todas las imágenes de códigos de barras de un usuario (por RUN)
     * Útil cuando se regenera el código de barras
     */
    public function deleteAllUserBarcodeImages(User $user): void
    {
        $directory = "codigos_usuarios";
        
        // Buscar y eliminar todas las imágenes relacionadas con este usuario
        $files = Storage::disk('public')->files($directory);
        
        foreach ($files as $file) {
            // Si el archivo contiene el código de barras del usuario, eliminarlo
            if ($user->codigo_barra && str_contains($file, $user->codigo_barra)) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
