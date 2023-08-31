<?php
namespace Uwcoenvws\Importkit\Readers;

use Uwcoenvws\Importkit\Contracts\ReaderContract;

class FileReader implements ReaderContract
{
    protected $filename;
    protected $fh;
    protected $skipTest;

    public function __construct($filename, $skipTest = null)
    {
        $this->filename = $filename;
        $this->skipTest = $skipTest;
    }

    public function read()
    {
        $this->lazyOpen();
        return fgets($this->fh);
    }

    public function reset()
    {
        $this->close();
    }

    protected function lazyOpen()
    {
        if (!is_resource($this->fh)) {
            $this->fh = fopen($this->filename, 'r');
        }
    }

    protected function close()
    {
        if (is_resource($this->fh)) {
            fclose($this->fh);
        }
    }

}
