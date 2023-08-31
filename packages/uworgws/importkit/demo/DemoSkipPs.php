<?php

class DemoSkipPs implements \Uwcoenvws\Importkit\Contracts\SkipRuleContract
{

    public function shouldSkip($record)
    {
        return strpos($record, 'p') === 0;
    }

}
