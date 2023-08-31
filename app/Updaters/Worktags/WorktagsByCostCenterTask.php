<?php

namespace App\Updaters\Worktags;

use App\Edw\BudgetDataSource;
use App\Models\Worktag;
use App\Models\WorktagHierarchy;
use App\Updaters\EdwParser;

/**
 * Import worktags that are configured as drivers referencing one of our Cost Center worktags
 */
class WorktagsByCostCenterTask
{
    protected $edw;
    protected $parser;

    /**
     * Map values from UWODS.sec.WorktagDefaultWorktag.WorktagType
     * to how we label those in the local system
     * @var string[]
     */
    private $worktagTypeAliases = [
        'Gift_Reference_ID' => Worktag::TYPE_GIFT,
        'Grant_ID' => Worktag::TYPE_GRANT,
        'Program_ID' => Worktag::TYPE_PROGRAM,
    ];

    public function __construct(BudgetDataSource $edw, EdwParser $parser)
    {
        $this->edw = $edw;
        $this->parser = $parser;
    }

    public function run()
    {
        $costCenterLookup = new CostCenterLookup();
        $results = $this->edw->getWorktagsByCostCenter();

        foreach ($results as $row) {
            $data = $this->parse($row);

            $worktag = Worktag::firstOrNew([
                'tag_type' => $data['tag_type'],
                'workday_code' => $data['workday_code'],
            ]);
            $worktag->name = $data['name'];
            $worktag->cc_worktag_id = $costCenterLookup->getId(
                $data['cc_workday_code'],
                $data['cc_name']
            );
            $worktag->save();
        }
    }

    private function parse(array $row): array
    {
        return [
            'tag_type' => $this->parseWorktagType($row['DriverWorktagType']),
            'workday_code' => $this->parser->string($row['DriverWorktagID']),
            'name' => $this->parser->stringFromEdwEncoding($row['DriverWorktagName']),
            'cc_workday_code' => $this->parser->string($row['CostCenterReferenceID']),
            'cc_name' => $this->parser->stringFromEdwEncoding($row['CostCenterName']),
        ];
    }

    private function parseWorktagType($value): string
    {
        $value = trim($value);
        return (isset($this->worktagTypeAliases[$value])) ? $this->worktagTypeAliases[$value] : $value;
    }
}
