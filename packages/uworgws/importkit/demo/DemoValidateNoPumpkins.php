<?php

class DemoValidateNoPumpkins implements \Uwcoenvws\Importkit\Contracts\ValidatorContract
{

    public function valid($record)
    {
        if ($record === 'pumpkin') {
            return false;
        }
        return true;
    }

}
