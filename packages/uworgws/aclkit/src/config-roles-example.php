<?php
/**
 * Sample configuration for role definitions
 * Each index is a role defined in the system. The array value lists the other roles inherited by this role.
 */
return [
    // Roles based on specific actions
    'view-catalog' => [],
    'edit-catalog' => ['view-catalog'],
    'view-sales' => [],
    'ok-returns' => [],
    'enter-sales' => [],
    'delete-sales' => [],
    'view-reports' => [],
    'make-payments' => [],
    'manage-users' => [],

    // Roles based on domain responsibilities
    'guest' => ['view-catalog'],
    'user' => ['guest'],
    'sales' => ['user', 'edit-catalog', 'enter-sales'],
    'sales-manager' => ['sales', 'delete-sales'],
    'service' => ['user', 'view-sales', 'ok-returns'],
    'fiscal' => ['sales', 'view-sales', 'make-payments'],
    'audit' => ['fiscal'],
    'admin' => ['audit', 'manage-users'],
    'super' => ['*'],
];
