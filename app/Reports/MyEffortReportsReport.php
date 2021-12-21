<?php

namespace App\Reports;

use App\Models\Contact;
use App\Models\EffortReport;
use Carbon\Carbon;

class MyEffortReportsReport
{
    public $title = 'Base Report';
    public $view = 'orders._status-table';
    public $viewCsv = 'orders.csv';

    public $count = 0;
    public $orders;
    public $period;
    public $year;

    protected $person_id;

    private $dontIncludeStages = [
        EffortReport::STAGE_CANCELED,
        EffortReport::STAGE_SUPERSEDED,
    ];

    public function __construct($person_id, $period = null, $year = null)
    {
        $this->person_id = $person_id;
        $this->period = $period;
        $this->year = $year;
        $this->orders = $this->load();
        $this->count = count($this->orders);
    }

    public function load()
    {
        return EffortReport::where(function ($query) {
                $query->where('creator_contact_id', Contact::personToContact($this->person_id))
                    ->where('created_at', '>', Carbon::now()->subDays(90))
                    ->whereNotIn('stage', $this->dontIncludeStages);
            })->orderBy('created_at', 'DESC')
            ->get();
    }
}
