<?php

namespace App\Updaters\Translator;

use Uwcoenvws\Importkit\Contracts\SkipRuleContract;

class TranslatorSkipRule implements SkipRuleContract
{
    public function shouldSkip($record)
    {
        // check for a number in Column A[0] Mapping ID
        return ! isset($record[0]) || ! is_numeric($record[0]);
    }
}
