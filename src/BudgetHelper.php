<?php
namespace App;

use App\Utils;
use App\Categories;

class BudgetHelper
{
    private $_data = [];
    private $_ast = [];

    public function __construct(array $data)
    {
        $this->_data = $data;
        $this->_ast = $this->buildAST();
    }

    private function buildAST(): array
    {
        $collection = collect($this->_data);
        $byDays = $collection
            ->chunkWhile(fn ($value) => !empty($value))
            ->map(
                function ($item) {
                    $utils = new Utils($item->toArray());
                    return $utils
                        ->replaceCommasToChar()
                        ->trimSpaces()
                        ->deleteEmptyItems()
                        ->toArray();
                }
            )
            ->map(
                function ($item) {
                    $oneDay = collect($item);
                    $date = $oneDay->first();
                    $tail = $oneDay->slice(1);

                    $byCategories = $tail->map(
                        function ($line) {
                            $items = collect(explode(' ', $line));

                            $separatedItems = $items->reduce(
                                function ($acc, $value) {
                                    if (is_numeric($value)) {
                                        $acc['numbers'] = [...$acc['numbers'], $value];
                                    } else {
                                        $acc['words'] = [...$acc['words'], $value];
                                    }

                                    return $acc;
                                },
                                [
                                    'words' => [],
                                    'numbers' => []
                                ]
                            );

                            ['words' => $words, 'numbers' => $numbers] = $separatedItems;
                            $categoryName = collect($words)->implode(' ');
                            $sum = collect($numbers)->sum();

                            $refCategory = collect(Categories::findKeysByValue($categoryName))->first() ?? $categoryName;
    
                            return [
                                'category' => $refCategory,
                                'sum' => $sum
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

    public function createText(): string
    {
        $collection = collect($this->_ast);
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

    public function getAst(): array
    {
        return $this->_ast;
    }
}