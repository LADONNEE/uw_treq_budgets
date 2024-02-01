<?php
namespace Utilws\Importkit;

use Utilws\Importkit\Contracts\ExtractorContract;
use Utilws\Importkit\Contracts\MapperContract;
use Utilws\Importkit\Contracts\ReaderContract;
use Utilws\Importkit\Contracts\SkipRuleContract;
use Utilws\Importkit\Contracts\ValidatorContract;

class ImportSource implements \Iterator
{
    /**
     * Interface to the inbound data source that can be accessed using a foreach loop.
     * @var ReaderContract
     */
    private $reader;

    /**
     * Optional skip rule. This component detects records that should be skipped such as headers or blank items.
     * @var SkipRuleContract|null
     */
    private $skipRule;

    /**
     * Optional extract strategy. Splits individual records provided by Reader into array of field values.
     * @var ExtractorContract|null
     */
    private $extracter;

    /**
     * Optional mapping strategy. Extracts fields from a records and returns array of fields with
     * keys for the destination system.
     * @var MapperContract|null
     */
    private $mapper;

    /**
     * Optional collection of parsing strategies. These will be applied to the mapped array values.
     * @var ParserLibrary
     */
    private $parsers;

    /**
     * Optional validation strategy. Given opportunity to evaluate records before they are returned.
     * @var ValidatorContract
     */
    private $validator;

    /**
     * Iterator tracking
     */
    private $_current;
    private $_key;
    private $_valid;

    public function __construct($reader, ...$helpers)
    {
        $this->reader = $reader;
        foreach ($helpers as $helper) {
            $this->addHelper($helper);
        }
    }

    public function addHelper($helper)
    {
        if ($helper instanceof SkipRuleContract) {
            $this->skipRule = $helper;
            return;
        }
        if ($helper instanceof ExtractorContract) {
            $this->extracter = $helper;
            return;
        }
        if ($helper instanceof MapperContract) {
            $this->mapper = $helper;
            return;
        }
        if ($helper instanceof ParserLibrary) {
            $this->parsers = $helper;
            return;
        }
        if ($helper instanceof ValidatorContract) {
            $this->validator = $helper;
            return;
        }
    }

    protected function readRecord()
    {
        do {
            $record = $this->reader->read();
        } while ($record !== false && $this->shouldSkip($record));

        if ($record === false) {
            return false;
        }

        if ($this->extracter) {
            $record = $this->extracter->extract($record);
        }
        if ($this->mapper) {
            $record = $this->mapper->map($record);
        }
        if ($this->parsers) {
            $record = $this->parsers->parseRow($record);
        }
        if ($this->validator) {
            if (!$this->validator->valid($record)) {
                // current record is not valid, return the next
                return $this->readRecord();
            }
        }

        return $record;
    }

    protected function shouldSkip($record)
    {
        if ($this->skipRule) {
            return $this->skipRule->shouldSkip($record);
        }
        return false;
    }

    /* Iterator implementation */

    public function current()
    {
        return $this->_current;
    }

    public function next()
    {
        $this->_key += 1;
        $this->_current = $this->readRecord();
        $this->_valid = $this->_current !== false;
    }

    public function key()
    {
        return $this->_key;
    }

    public function valid()
    {
        return $this->_valid;
    }

    public function rewind()
    {
        $this->reader->reset();
        $this->_key = 0;
        $this->_current = $this->readRecord();
        $this->_valid = $this->_current !== false;
    }

}
