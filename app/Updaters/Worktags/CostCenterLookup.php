<?php

namespace App\Updaters\Worktags;

use App\Models\Worktag;

/**
 * Resolve database ids of Cost Center worktags from Workday worktag codes
 */
class CostCenterLookup
{
    private $cache;

    public function __construct()
    {
        $this->primeCache();
    }

    public function getId($worktag_code, $name)
    {
        $id = $this->getFromCache($worktag_code);

        return $id ?: $this->createAndCache($worktag_code, $name);
    }

    private function getFromCache($worktag_code)
    {
        return $this->cache[$worktag_code] ?? null;
    }

    private function createAndCache($worktag_code, $name)
    {
        $worktag = Worktag::query()
            ->firstOrNew([
                'tag_type' => Worktag::TYPE_COST_CENTER,
                'workday_code' => $worktag_code,
            ]);

        if (!$worktag->exists()) {
            $worktag->name = $name;
            $worktag->save();
        }

        $this->cache[$worktag_code] = $worktag->id;

        return $this->cache[$worktag_code];
    }

    private function primeCache()
    {
        $this->cache = Worktag::query()
            ->where('tag_type', Worktag::TYPE_COST_CENTER)
            ->orderBy('workday_code')
            ->pluck('id', 'workday_code')
            ->all();
    }
}
