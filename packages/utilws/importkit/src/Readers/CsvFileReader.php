<?php
namespace Utilws\Importkit\Readers;

class CsvFileReader extends FileReader
{

    public function read()
    {
        $this->lazyOpen();
        return fgetcsv($this->fh);
    }

}
