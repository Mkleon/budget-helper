<?php
namespace App\index;

require_once __DIR__ . '/vendor/autoload.php';


use App\BudgetHelper;

$data = <<<TEXT
11.11
Продукты 10 157 13
Хозтовары 145

12.11
Работа 50 48
TEXT;

$strings = explode("\n", trim($data, $characters = " \n\r\t\v\0"));
$result = BudgetHelper::clearArray($strings, false);
$ast = BudgetHelper::composeAST($result);

echo BudgetHelper::createText($ast);


/* include('vendor/autoload.php');
use Telegram\Bot\Api;

function handler($event, $context)
{
    $telegram = new Api();
    $request = json_decode($event['body'], true);

    $chat_id = $request["message"]["chat"]["id"];
    $text = $request["message"]["text"];

    $response = [
        'chat_id' => $chat_id,
    ];

    if ($text === "/start") {
        $response['text'] = "Добро пожаловать в чат, который помогает вести ваш домашний бюджет.";
    } else {

        $strings = explode("\n", trim($text, $characters = " \n\r\t\v\0"));
        $result = clearArray($strings, false);

        $response['text'] = prepareData($result);
    }

    $telegram->sendMessage($response);

    return [
        'statusCode' => 200,
        'body' => json_encode($response),
    ];
}

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
    $byDaysRaw = explode('_', implode("\n", $data));

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

    return implode("\n\n", $byDaysGrouped);
}*/