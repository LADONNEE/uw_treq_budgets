<?php
namespace Uwcoenvws\Importkit;

use Uwcoenvws\Importkit\Extractors\FixedWidthExtractor;
use Uwcoenvws\Importkit\Mappers\IndexSearchMapper;

class ConfiguredImportSource extends ImportSource
{

    public function __construct($reader, array $config)
    {
        parent::__construct($reader);
        $this->configure($config);
    }

    public function configure(array $config)
    {
        $cExtractor = [];
        $cMapper = [];
        $cParsers = [];

        foreach ($config as $name => $field) {
            if (isset($field['extract'])) {
                $cExtractor[] = $field['extract'];
                $cMapper[$name] = $field['extract'];
            } elseif (isset($field['index'])) {
                $cMapper[$name] = $field['index'];
            }
            if (isset($field['parser'])) {
                $cParsers[$name] = $field['parser'];
            }
        }

        if (count($cExtractor) > 0) {
            $this->addHelper(new FixedWidthExtractor($cExtractor));
        }

        if (count($cMapper) > 0) {
            $this->addHelper(new IndexSearchMapper($cMapper));
        }

        if (count($cParsers) > 0) {
            $fac = new ParserFactory();
            $lib = new ParserLibrary();
            foreach ($cParsers as $name => $spec) {
                $parser = $fac->make($spec);
                $lib->add($name, $parser);
            }
            $this->addHelper($lib);
        }
    }

}
