<?php
namespace App;

class BudgetHelper
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

    public static function composeAST(array $data)
    {
        $collection = collect($data);
        $byDays = $collection
            ->chunkWhile(fn ($value) => !empty($value))
            ->map(fn ($item) => self::clearArray($item->toArray()))
            ->map(
                function ($item) {
                    $oneDay = collect($item);
                    $date = $oneDay->first();
                    $tail = $oneDay->slice(1);

                    $byCategories = $tail->map(
                        function ($line) {
                            $items = collect(explode(' ', $line));
                            $category = $items->first();
                            $numbers = $items->slice(1);
    
                            return [
                                'category' => $category,
                                'sum' => $numbers->sum()
                            ];
                        }
                    );

                    return [
                        'day' => $date,
                        'data' => $byCategories->all()
                    ];
                }
            );
        
        return $byDays->all();
    }

    public static function createText(array $ast)
    {
        $collection = collect($ast);
        $result = $collection
            ->map(
                function ($item) {
                    $data = collect($item["data"])
                        ->map(fn ($item) => "{$item['category']} {$item['sum']}")
                        ->implode("\n");

                    return "{$item['day']}\n{$data}";
                }
            );
        
        
        return $result->implode("\n\n");
    }
}