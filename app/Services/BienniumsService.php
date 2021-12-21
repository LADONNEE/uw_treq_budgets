<?php

/**
 * Information about UW Biennium - two year time periods
 * Used in Budgets context
 */

namespace App\Services;

use App\Models\UwBudget;

class BienniumsService
{
    protected static $budgetScope = null;

    /**
     * Returns array of biennium (start years) included in Budget database
     * @return array
     */
    public function budgetScope()
    {
        if (self::$budgetScope === null) {
            self::$budgetScope = UwBudget::groupBy('BienniumYear')->pluck('BienniumYear')->values()->toArray();
        }
        return self::$budgetScope;
    }

    /**
     * Returns current biennium
     * @return string
     */
    public function current()
    {
        return setting('current-biennium');
    }

    /**
     * Returns value if it matches an included biennium
     * Returns the most recent biennium otherwise
     * @param string $value
     * @return string
     */
    public function valid($value)
    {
        $scopes = $this->budgetScope();
        if (in_array($value, $scopes)) {
            return $value;
        }
        return $this->current();
    }

}
