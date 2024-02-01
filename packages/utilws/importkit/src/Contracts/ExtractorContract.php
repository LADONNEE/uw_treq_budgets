<?php
namespace Utilws\Importkit\Contracts;

interface ExtractorContract
{
    /**
     * Accept a single record provided by a Reader and return that record
     * split into individual fields as an array
     * @param mixed $record
     * @return array
     */
    public function extract($record);
}
