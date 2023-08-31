<?php
namespace Uwcoenvws\Importkit\Contracts;

interface ValidatorContract
{
    /**
     * Return true if $record contains valid field values to be imported
     * @param mixed $record
     * @return boolean
     */
    public function valid($record);
}
