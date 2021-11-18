<?php
namespace App;

class Utils
{
    private $_data;

    public function __construct(array $data)
    {
        $this->_data = collect($data);
    }

    public function replaceCommasToChar(string $character = '.')
    {
        $result = $this->_data->map(fn ($item) => str_replace(',', $character, $item));

        return new Utils($result->all());
    }

    public function trimSpaces()
    {
        $result = $this->_data->map(fn ($item) => trim($item));

        return new Utils($result->all());
    }

    public function deleteEmptyItems()
    {
        $result = $this->_data->filter(fn ($item) => !empty($item));

        return new Utils($result->all());
    }

    public function toArray()
    {
        return $this->_data->all();
    }
}