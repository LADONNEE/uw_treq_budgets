<?php
namespace AppTest;

include_once __DIR__ . '/UserProviderStub.php';
include_once __DIR__ . '/UserStub.php';

use Utilws\Aclkit\Exceptions\RoleNotDefined;
use Utilws\Aclkit\RoleCheck;
use PHPUnit\Framework\TestCase;

class RoleCheckTest extends TestCase
{
    public function test_role_check_class_exists()
    {
        $check = new RoleCheck([]);
        $this->assertInstanceOf(RoleCheck::class, $check);
    }

    public function test_denies_missing_role()
    {
        $user = new UserStub(['sales']);
        $check = new RoleCheck($this->getExampleConfig());
        $this->assertFalse($check->hasRole('audit', $user));
        $this->assertFalse($check->hasRole('manage-users', $user));
    }

    public function test_confirms_direct_role()
    {
        $user = new UserStub(['sales']);
        $check = new RoleCheck($this->getExampleConfig());
        $this->assertTrue($check->hasRole('sales', $user));
    }

    public function test_confirms_direct_additional_role()
    {
        $user = new UserStub(['sales', 'ok-returns']);
        $check = new RoleCheck($this->getExampleConfig());
        $this->assertTrue($check->hasRole('ok-returns', $user));
    }

    public function test_confirms_inherited_role()
    {
        $user = new UserStub(['audit']);
        $check = new RoleCheck($this->getExampleConfig());
        $this->assertTrue($check->hasRole('view-sales', $user));
    }

    public function test_confirms_inherited_additional_role()
    {
        $user = new UserStub(['sales', 'make-payments']);
        $check = new RoleCheck($this->getExampleConfig());
        $this->assertTrue($check->hasRole('view-finance', $user));
    }

    public function test_handles_circular_config()
    {
        $user = new UserStub(['moe']);
        $check = new RoleCheck($this->getCircularConfig());
        $this->assertTrue($check->hasRole('larry', $user));
    }

    public function test_throws_error_on_unknown_role()
    {
        $this->expectException(RoleNotDefined::class);
        $user = new UserStub(['audit']);
        $check = new RoleCheck($this->getExampleConfig());
        $check->hasRole('foo', $user);
    }

    public function test_gets_user_from_user_provider()
    {
        $provider = new UserProviderStub( new UserStub(['sales']));
        $check = new RoleCheck($this->getExampleConfig(), $provider);
        $this->assertTrue($check->hasRole('sales'));
    }

    public function test_falls_back_to_null_user()
    {
        $check = new RoleCheck($this->getExampleConfig());
        $this->assertFalse($check->hasRole('sales'));
    }

    public function getExampleConfig()
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

    public function getCircularConfig()
    {
        return [
            'larry' => ['moe'],
            'curly' => ['larry'],
            'moe' => ['curly'],
        ];
    }

}
