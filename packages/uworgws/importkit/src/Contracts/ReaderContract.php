<?php
namespace Uwcoenvws\Importkit\Contracts;

interface ReaderContract
{
    /**
     * Return the next record from the source
     * If no more records are available return false
     * @return mixed
     */
    public function read();

    /**
     * Return the source to the first record
     */
    public function reset();
}
