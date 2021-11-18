<?php
namespace App;

use App\Utils;

class BudgetHelper
{
    public static function composeAST(array $data): array
    {
        $collection = collect($data);
        $byDays = $collection
            ->chunkWhile(fn ($value) => !empty($value))
            ->map(fn ($item) => Utils::clearArray($item->toArray()))
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

    public static function createText(array $ast): string
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