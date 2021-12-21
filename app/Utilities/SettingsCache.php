<?php
namespace App\Utilities;

use App\Models\Setting;

class SettingsCache
{
    protected static $instance;
    protected $cache;

    public function __construct($cache = null)
    {
        if (is_array($cache)) {
            $this->cache = $cache;
        } else {
            $this->loadFromStorage();
        }
    }

    public function get($name)
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }
        return null;
    }

    private function loadFromStorage()
    {
        $this->cache = Setting::orderBy('name')
            ->pluck('value', 'name')
            ->all();
    }

}
