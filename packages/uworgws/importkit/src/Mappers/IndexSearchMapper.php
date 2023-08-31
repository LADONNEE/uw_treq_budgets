<?php
namespace Uwcoenvws\Importkit\Mappers;

use Uwcoenvws\Importkit\Contracts\MapperContract;

class IndexSearchMapper implements MapperContract
{
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function map($row)
    {
        $out = [];
        foreach ($this->map as $name => $search) {
            $out[$name] = $this->findValue($row, $search);
        }
        return $out;
    }

    protected function findValue(array $row, $search)
    {
        $search = (is_array($search)) ? $search : [ $search ];
        foreach ($search as $index) {
            if (isset($row[$index]) || array_key_exists($index, $row)) {
                return $row[$index];
            }
        }
        return null;
    }

}
