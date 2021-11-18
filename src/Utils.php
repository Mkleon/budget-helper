<?php
namespace App;

class Utils
{
    public static function clearArray(array $data, bool $deleteEmptyItems = true): array
    {
        // delete commas
        $result = array_map(fn ($item) => str_replace(',', '.', $item), $data);
        
        // trim spaces
        $result = array_map(fn ($item) => trim($item), $result);

        if ($deleteEmptyItems) {
            $result = array_values(array_filter($result, fn ($item) => !empty($item)));
        }

        return $result;
    }
}