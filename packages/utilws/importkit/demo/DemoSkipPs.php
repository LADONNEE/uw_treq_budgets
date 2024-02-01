<?php

class DemoSkipPs implements \Utilws\Importkit\Contracts\SkipRuleContract
{

    public function shouldSkip($record)
    {
        return strpos($record, 'p') === 0;
    }

}
