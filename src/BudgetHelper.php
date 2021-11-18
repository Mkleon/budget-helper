<?php
namespace App;

//use Tightenco\Collect\Support\Collection;

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

    public static function composeText(array $data)
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
                                'sum' => array_sum($numbers->all())
                            ];
                        }
                    );

                    return [
                        'day' => $date,
                        'data' => $byCategories->all()
                    ];
                }
            );
        
        
        print_r($byDays->toArray());
        
        /* $byDaysRaw = explode('_', implode("\n", $data));

        $byDaysGrouped = array_map(
            function ($str) {
                $arr = self::clearArray(explode("\n", $str));
                $date = $arr[0];
                $tail = array_slice($arr, 1);

                $byCategories = array_map(
                    function ($item) {
                        $line = explode(' ', $item);
                        $category = $line[0];
                        $sum = array_sum(array_slice($line, 1));

                        return "{$category} {$sum}";
                    },
                    $tail
                );

                $body = implode("\n", $byCategories);
                return "{$date}\n{$body}";
            },
            $byDaysRaw
        );

        echo implode("\n\n", $byDaysGrouped); */
    }

}