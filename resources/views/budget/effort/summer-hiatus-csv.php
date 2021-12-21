<?php

use \App\Reports\RowBuilders\SummerHiatusRowBuilder as Row;

$builder = new Row(function($row){
    return $row;
});

$filename = 'faculty_effort_'.$period.'_'.$year.'_'.date('Y-m-d').'.csv';

renderCsv($filename, $report->orders, $builder);
