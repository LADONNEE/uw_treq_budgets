<?php

namespace App\Reports;

use App\Auth\User;

class HomeCollection
{
    private $user;
    public $period;
    public $year;

    private $reportClasses = [
        'tasks' => MyTasksReport::class,
        'myEffortReports' => MyEffortReportsReport::class,
        'myFaculty' => MyFacultyReport::class,
        'allReportAllocations' => AllReportAllocationsReport::class,
        'summerHiatus' => SummerHiatusReport::class,
        'revisits' => RevisitsReport::class,
    ];
    private $reportInstances = [];

    public function __construct(User $user, $period = null, $year = null)
    {
        $this->user = $user;
        $this->period = $period;
        $this->year = $year;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name)
    {
        if (!isset($this->reportInstances[$name])) {
            $classname = $this->reportClasses[$name];
            $this->reportInstances[$name] = new $classname($this->user->person_id, $this->period, $this->year);
        }
        return $this->reportInstances[$name];
    }

}
