<?php

return [
    'Budgets' => [
        ['Budgets', action('BudgetsController@index')],
        ['Missing Records', action('MissingController@index'), 'budget:fiscal'],
        ['About Budgets', action('AboutController@index')],
    ],
    'Effort' => [
        ['Faculty Effort', action('EffortController@index')],
    ],
    'Reports' => [
        ['Approved Allocations', action('ReportApprovedAllocationsController@index'), 'budget:fiscal'],
        ['Summer Hiatus', action('ReportSummerHiatusController@index'), 'budget:fiscal'],
    ],
    'Admin' => [
        ['Pending Email', route('pending-email'), 'budget:fiscal'],
        ['Settings', action('SettingsController@index'), 'budget:admin'],
        ['Scope', action('ScopeController@index')],
        ['Users', action('UsersController@index'), 'budget:admin'],
        ['Manage Faculty', action('FacultyController@index'), 'manage-faculty'],
    ],
];
