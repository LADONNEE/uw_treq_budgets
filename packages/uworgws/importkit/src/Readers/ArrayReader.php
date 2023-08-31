<?php
namespace Uwcoenvws\Importkit\Readers;


use Uwcoenvws\Importkit\Contracts\ReaderContract;

class ArrayReader implements ReaderContract
{
    private $items;
    private $key = 0;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function read()
    {
        $key = $this->key;
        ++ $this->key;
        return $this->items[$key] ?? false;
    }

    public function reset()
    {
        $this->key = 0;
    }

}
