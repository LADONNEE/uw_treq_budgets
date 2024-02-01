<?php
namespace Utilws\Importkit\Extractors;

use Utilws\Importkit\Contracts\ExtractorContract;

class FixedWidthExtractor implements ExtractorContract
{
    private $splits;

    public function __construct(array $fields)
    {
        $this->setFields($fields);
    }

    public function extract($record)
    {
        $len = strlen($record);
        $out = [];
        foreach ($this->splits as $name => $r) {
            if ($r['start'] < $len) {
                $out[$name] = substr($record, $r['start'], $r['length']);
            } else {
                $out[$name] = null;
            }
        }
        return $out;
    }

    protected function setFields(array $fields)
    {
        $this->splits = [];
        foreach ($fields as $range) {
            $matches = [];
            if (preg_match('/^([0-9]+)\-([0-9]+)$/', $range, $matches)) {
                $this->splits[$range] = [
                    'start' => $matches[1],
                    'length' => ($matches[2] - $matches[1]) + 1,
                ];
            } else {
                throw new \Exception("Bad field range '$range', use 0 indexed start hyphen end, ex: 10-25");
            }
        }
    }

}
