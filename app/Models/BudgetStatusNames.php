<?php

namespace App\Models;

/**
 * Provide descriptions of UW Budget Status Codes.
 * Load descriptions from status_lookup table.
 */
class BudgetStatusNames
{
    private static $_instance;
    private $statuses = [];

    public function __construct($config = null)
    {
        if ($config) {
            $this->setStatuses($config);
        } else {
            $this->loadFromDatabase();
        }
    }

    public static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function full($code)
    {
        return self::instance()->getFull($code);
    }

    public static function short($code)
    {
        return self::instance()->getShort($code);
    }

    public function getFull($code)
    {
        if (isset($this->statuses[$code])) {
            return $this->statuses[$code][1];
        }
        return "unknown {$code}";
    }

    public function getShort($code)
    {
        if (isset($this->statuses[$code])) {
            return $this->statuses[$code][0];
        }
        return "unknown {$code}";
    }

    private function loadFromDatabase()
    {
        $this->setStatuses(StatusLookup::get());
    }

    private function setStatuses($config)
    {
        foreach ($config as $item) {
            $this->setStatusItem($item);
        }
    }

    private function setStatusItem($item)
    {
        if ($item instanceof StatusLookup || $item instanceof \stdClass) {
            $this->statuses[$item->uw_code] = [$item->short, $item->full];
        } else {
            $this->statuses[$item[0]] = [$item[1], $item[2]];
        }
    }
}
