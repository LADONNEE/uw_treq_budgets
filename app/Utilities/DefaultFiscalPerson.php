<?php
namespace App\Utilities;

use App\Models\Contact;

class DefaultFiscalPerson
{
    private static $cachedDefaultFiscalPerson = null;

    public static function defaultFiscalPerson(): Contact {
        if (self::$cachedDefaultFiscalPerson === null) {
            self::$cachedDefaultFiscalPerson = Contact::where('uwnetid', setting('fiscal-person-default'))->first();
        }

        return self::$cachedDefaultFiscalPerson;
    }
}
