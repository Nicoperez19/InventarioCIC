<?php

namespace App\Services;

use App\Models\Producto;
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
            $barcode = $this->generate8DigitBarcodeByUnit($unidadId);
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

    private function generate8DigitBarcodeByUnit(string $unidadId = null): string
    {
        $prefix = $this->getUnitPrefix($unidadId);
        $sequenceNumber = $this->getNextSequenceForUnit($unidadId);
        $code = $prefix . str_pad($sequenceNumber, 6, '0', STR_PAD_LEFT);
        $checksum = $this->calculate8DigitChecksum($code);
        
        return $code . $checksum;
    }

    private function getNextSequenceForUnit(string $unidadId = null): int
    {
        $lastBarcode = Producto::where('id_unidad', $unidadId)
            ->whereNotNull('codigo_barra')
            ->orderBy('codigo_barra', 'desc')
            ->first();

        if (!$lastBarcode || !$lastBarcode->codigo_barra) {
            return 1;
        }

        $lastCode = $lastBarcode->codigo_barra;
        $prefix = $this->getUnitPrefix($unidadId);
        
        if (str_starts_with($lastCode, $prefix)) {
            $sequence = (int) substr($lastCode, 1, 6);
            return $sequence + 1;
        }

        return 1;
    }

    private function calculate8DigitChecksum(string $code): int
    {
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $digit = (int) $code[$i];
            $sum += ($i % 2 === 0) ? $digit * 3 : $digit;
        }
        
        return (10 - ($sum % 10)) % 10;
    }

    private function barcodeExists(string $barcode): bool
    {
        return Producto::where('codigo_barra', $barcode)->exists();
    }

    public function generateBarcodeImage(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "barcode_{$barcode}.png";
        }

        $imageData = $this->generatorPNG->getBarcode($barcode, BarcodeGeneratorPNG::TYPE_CODE_128, 2, 50, [255, 255, 255], [0, 0, 0]);
        $path = "codigos_productos/{$filename}";
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    public function generateSmallBarcode(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "barcode_small_{$barcode}.png";
        }

        $imageData = $this->generatorPNG->getBarcode($barcode, BarcodeGeneratorPNG::TYPE_CODE_128, 1, 30, [255, 255, 255], [0, 0, 0]);
        $path = "codigos_productos/{$filename}";
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    public function generateBarcodeSVG(string $barcode, string $filename = null): string
    {
        if (!$filename) {
            $filename = "barcode_{$barcode}.svg";
        }

        $svgData = $this->generatorSVG->getBarcode($barcode, BarcodeGeneratorSVG::TYPE_CODE_128, 2, 50);
        $path = "codigos_productos/{$filename}";
        Storage::disk('public')->put($path, $svgData);

        return $path;
    }

    public function validateBarcode(string $barcode): bool
    {
        if (!preg_match('/^\d{8}$/', $barcode)) {
            return false;
        }

        $code = substr($barcode, 0, 7);
        $checksum = (int) substr($barcode, 7, 1);
        $calculatedChecksum = $this->calculate8DigitChecksum($code);

        return $checksum === $calculatedChecksum;
    }

    public function getBarcodeInfo(string $barcode): array
    {
        if (!$this->validateBarcode($barcode)) {
            return ['valid' => false, 'message' => 'Código de barras inválido'];
        }

        $prefix = substr($barcode, 0, 1);
        $sequence = substr($barcode, 1, 6);
        
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
            'checksum' => substr($barcode, 7, 1)
        ];
    }
}