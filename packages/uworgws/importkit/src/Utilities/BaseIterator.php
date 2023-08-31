<?php
namespace Uwcoenvws\Importkit\Utilities;

class BaseIterator implements \Iterator
{
    protected $_current;
    protected $_key;
    protected $_valid;
    protected $_values;

    public function __construct(array $values = [])
    {
        $this->_values = $values;
    }

    public function getNextRecord()
    {
        return $this->_values[$this->_key] ?? false;
    }

    public function resetStream()
    {
        //
    }

    public function current()
    {
        return $this->_current;
    }

    public function next()
    {
        $this->_key += 1;
        $this->_current = $this->getNextRecord();
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
        $this->resetStream();
        $this->_key = 0;
        $this->_current = $this->getNextRecord();
        $this->_valid = $this->_current !== false;
    }

}
