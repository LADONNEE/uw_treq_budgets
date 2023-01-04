<?php

return [
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
    ],
];
