<?php
namespace AppTest;

include_once __DIR__ . '/UserProviderStub.php';
include_once __DIR__ . '/UserStub.php';

use Uworgws\Aclkit\Acl;
use PHPUnit\Framework\TestCase;

class AclTest extends TestCase
{
    /**
     * @var Acl
     */
    protected $acl;

    public function setup()
    {
        $provider = new UserProviderStub( new UserStub(['sales']));
        $this->acl = new Acl($this->getRoleConfig(), $this->getPathConfig(), $provider, ['foo']);
    }

    public function test_can_check_allowed_path()
    {
        $this->assertTrue($this->acl->allowedPath('/foo/catalog/edit/123'));
        $this->assertFalse($this->acl->allowedPath('http://localhost/foo/settings/users/jsmith'));
    }

    public function test_can_check_has_role()
    {
        $this->assertTrue($this->acl->hasRole('edit-catalog'));
        $this->assertFalse($this->acl->hasRole('admin'));
    }

    public function getRoleConfig()
    {
        return [
            // Roles based on specific actions
            'view-catalog' => [],
            'edit-catalog' => ['view-catalog'],
            'view-sales' => [],
            'ok-returns' => [],
            'enter-sales' => [],
            'delete-sales' => [],
            'view-reports' => [],
            'view-finance' => [],
            'make-payments' => ['view-finance'],
            'manage-users' => [],

            // Roles based on domain responsibilities
            'guest' => ['view-catalog'],
            'user' => ['guest'],
            'sales' => ['user', 'edit-catalog', 'enter-sales'],
            'sales-manager' => ['sales', 'delete-sales'],
            'service' => ['user', 'view-sales', 'ok-returns'],
            'fiscal' => ['user', 'view-sales', 'make-payments'],
            'audit' => ['fiscal'],
            'admin' => ['audit', 'manage-users'],
            'super' => ['*'],
        ];
    }

    public function getPathConfig()
    {
        return [
            'catalog' => 'view-catalog',
            'catalog/edit' => 'edit-catalog',
            'sales' => 'view-sales',
            'sales/entry/rma' => 'ok-returns',
            'sales/entry' => 'enter-sales',
            'sales/admin' => 'delete-sales',
            'reports' => 'view-reports',
            'fiscal' => 'fiscal',
            'fiscal/payments' => 'make-payments',
            'settings' => 'admin',
            'settings/users' => 'manage-users',
        ];
    }

}
