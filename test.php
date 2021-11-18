<?php
use Tightenco\Collect\Support\Collection;

function clearArray(array $data, bool $deleteEmptyItems = true): array
{
    // without commas
    $result = array_map(fn ($item) => str_replace(',', '.', $item), $data);
    
    // without spaces
    $result = array_map(fn ($item) => trim($item), $result);

    if ($deleteEmptyItems) {
        $result = array_values(array_filter($result, fn ($item) => !empty($item)));
    }

    return $result;
}

function prepareData(array $data) 
{
    $collection = collect($data);
    // $byDaysRaw = explode('_', implode("\n", $data));
    
    print_r($collection);
    
    /* $byDaysRaw = explode('_', implode("\n", $data));

    $byDaysGrouped = array_map(
        function ($str) {
            $arr = clearArray(explode("\n", $str));
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

    return implode("\n\n", $byDaysGrouped); */
}


$data = <<<TEXT
11.11
Продукты 10 157 13
Хозтовары 145
_
12.11
Работа 50 48
TEXT;

$strings = explode("\n", trim($data, $characters = " \n\r\t\v\0"));
$result = clearArray($strings, false);

prepareData($result);