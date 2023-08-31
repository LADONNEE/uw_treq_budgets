<?php

/**
 * Pulls budget records from UW Enterprise Data Warehouse
 */

namespace App\Edw;

use App\Services\BienniumsService;

class BudgetDataSource
{
    /* @var $edw EdwConnection */
    protected $edw;
    protected $scope;
    protected $uworg_hierarchy;
    protected $uworg_top_costcenter_hierarchy;

    public function __construct(EdwConnection $edw)
    {
        $this->edw = $edw;
        $this->scope = config('budgets.scope');
        $this->uworg_hierarchy = config('app.uworg_hierarchy'); 
        $this->uworg_top_costcenter_hierarchy = config('app.uworg_top_costcenter_hierarchy'); 

        //uworg_top_costcenter_hierarchy
    }

    /**
     * Returns an array of rows from the EDW which contain budget data
     * @return array
     */
    public function getCollegeBudgets()
    {
        $sql = sqlInclude(__DIR__ . '/Queries/sql/budgets.sql', [
            '__BIENNIUMS__' => $this->bienniums(app('bienniums')),
            '__COLLEGE_ORGS__' => $this->getScope('college-codes'),
            '__ORG_CODES__' => $this->getScope('org-codes'),
            '__BUDGETS__' => $this->getScope('budgets'),
        ]);
        return $this->edw->fetchAssoc($sql);
    }

    public function getWorktags($tagType)
    {
        $queryFile = sprintf('%s/Queries/sql/worktags-%s.sql', __DIR__, strtolower($tagType));

        $sql = sqlInclude($queryFile, [
            '__UWORG_HIERARCHY_LIKE__' => $this->uworg_hierarchy,
        ]);
        return $this->edw->fetchAssoc($sql);
    }

    public function getWorktagsByCostCenter()
    {
        $sql = sqlInclude(__DIR__ . '/Queries/sql/worktags-by-cost-center.sql', [
            '__TOP_CCH_WID__' => $this->uworg_top_costcenter_hierarchy,
        ]);

        return $this->edw->fetchAssoc($sql);
    }

    public function getWorktagHierarchy($tagType)
    {
        $queryFile = sprintf('%s/Queries/sql/hierarchy-%s.sql', __DIR__, strtolower($tagType));

        $sql = sqlInclude($queryFile, [
            '__UWORG_HIERARCHY_LIKE__' => $this->uworg_hierarchy,
        ]);
        return $this->edw->fetchAssoc($sql);
    }

    /**
     * Extract the code values from application configuration
     * Config has entity number/codes as array index, human readable names as array values
     * @param $key
     * @return string
     */
    private function getScope($key)
    {
        return implode(',', array_keys($this->scope[$key]));
    }

    /**
     * List of BienniumYear values that can be used in a WHERE clause
     * Returned list includes bienniums that appear in existing data plus the
     * user configured current biennium
     * @return string
     */
    private function bienniums(BienniumsService $bienniumsService): string
    {
        // Bienniums that appear in actual data
        $bienniums = $bienniumsService->budgetScope();

        // User configured current biennium
        $current = $bienniumsService->current();

        if (!in_array($current, $bienniums)) {
            $bienniums[] = $current;
        }

        return $this->bienniumsToDbList($bienniums);
    }

    /**
     * Convert array of Biennium values to comma separated string of quoted values
     * @param $bienniums
     * @return string
     */
    private function bienniumsToDbList($bienniums): string
    {
        $out = [];
        foreach ($bienniums as $biennium) {
            $biennium = str_replace("'", '', $biennium);
            $out[] = "'$biennium'";
        }
        return implode(',', $out);
    }
}
