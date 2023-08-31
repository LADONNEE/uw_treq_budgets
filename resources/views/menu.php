<?php

return [
    'Worktags' => [
        ['Worktags', action('WorktagsController@index')],
        ['Cost centers Approvers', action('CostcentersController@index')],
        ['Missing Records', action('MissingController@index'), 'budget:fiscal'],
        ['About', action('AboutController@index')],
    ],
    'Budgets' => [
        ['Budgets', action('BudgetsController@index')],
        ['Missing Records', action('MissingController@index'), 'budget:fiscal'],
        ['Project Codes', action('ProjectCodeController@index'), 'budget:fiscal'],
        ['About Budgets', action('AboutController@index')],
    ],
    'Admin' => [
        ['Pending Email', route('pending-email'), 'budget:fiscal'],
        ['Settings', action('SettingsController@index'), 'budget:admin'],
        ['Scope', action('ScopeController@index')],
        ['Users', action('UsersController@index'), 'budget:admin'],
        //['Manage Faculty', action('FacultyController@index'), 'manage-faculty'],
        ['UWFT Worktags', route('worktags'), 'uwft'],
    ],
];
