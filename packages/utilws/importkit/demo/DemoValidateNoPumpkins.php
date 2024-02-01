<?php

class DemoValidateNoPumpkins implements \Utilws\Importkit\Contracts\ValidatorContract
{

    public function valid($record)
    {
        if ($record === 'pumpkin') {
            return false;
        }
        return true;
    }

}
