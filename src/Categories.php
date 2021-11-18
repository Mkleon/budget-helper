<?php
namespace App;

class Categories
{
    private static $_data = [
        [
            'category' => 'Одежда и обувь',
            'words' => ['Одежда', 'Ботинки', 'Куртка', 'Кофта']
        ],
        [
            'category' => 'Продукты',
            'words' => ['Продукты', 'Торт']
        ]
    ];

    public static function findKeysByValue(string $value)
    {
        $result = collect(self::$_data)
            ->filter(fn ($item) => collect($item['words'])->contains($value))
            ->map(fn ($item) => $item['category']);

        return $result->all();

    }
}
