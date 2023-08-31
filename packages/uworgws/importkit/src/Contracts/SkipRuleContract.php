<?php
namespace Uwcoenvws\Importkit\Contracts;

interface SkipRuleContract
{
    /**
     * Return true if $record should be skipped during import
     * $record will contain raw item returned by Reader
     * @param mixed $record
     * @return boolean
     */
    public function shouldSkip($record);

}
