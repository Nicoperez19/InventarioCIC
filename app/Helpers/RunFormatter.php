<?php
namespace App\Helpers;
class RunFormatter
{
    public static function format(string $input): string
    {
        $input = preg_replace('/[^0-9kK]/', '', $input);
        $input = strtoupper($input);
        
        if (strlen($input) <= 7) {
            return $input;
        }
        
        if (strlen($input) == 8) {
            return substr($input, 0, 7) . '-' . substr($input, 7, 1);
        }
        
        if (strlen($input) == 9) {
            return substr($input, 0, 8) . '-' . substr($input, 8, 1);
        }
        
        return substr($input, 0, 8) . '-' . substr($input, 8, 1);
    }
    
}

