<?php
/**
 * Configuration for Access Control List
 * Provides configuration for App\Auth\AclLaravel
 */

return [
    'act-on-behalf' => [],
    'budget-notes' => [],
    'cancel' => [],
    'create-requests' => [],
    'create-tasks' => [],
    'delete' => [],
    'delete-tasks' => [],
    'edit-notes' => [],
    'manage-faculty' => [],
    'manager' => [],
    'reassign-tasks' => [],
    'reconciler' => ['budget:viewer', 'budget-notes'],
    'settings' => [],
    'user-mgmt' => [],
    'who-am-i' => [],
    'workday' => [],

    'budget:user' => [],
    'budget:viewer' => ['budget:user', 'files:viewer'],
    'budget:fiscal' => ['budget:user', 'reconciler', 'create-tasks', 'delete-tasks', 'reassign-tasks', 'edit-notes', 'cancel'],
    'budget:admin' => ['budget:fiscal', 'settings', 'user-mgmt', 'delete', 'manage-faculty', 'manager'],
    'budget:super' => ['budget:admin', 'who-am-i', 'act-on-behalf'],
];
