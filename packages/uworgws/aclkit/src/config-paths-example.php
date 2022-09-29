<?php
/**
 * Sample configuration for path based authorizations
 * Each index is the beginning of an application path (e.g. URL) and the value is the role required
 * to access resources under that path
 */
return [
    'catalog' => 'view-catalog',
    'catalog/edit' => 'edit-catalog',
    'sales' => 'view-sales',
    'sales/rma' => 'ok-returns',
    'sales/entry' => 'enter-sales',
    'sales/admin' => 'delete-sales',
    'reports' => 'view-reports',
    'fiscal' => 'fiscal',
    'fiscal/payments' => 'make-payments',
    'settings' => 'admin',
    'settings/users' => 'manage-users',
];
