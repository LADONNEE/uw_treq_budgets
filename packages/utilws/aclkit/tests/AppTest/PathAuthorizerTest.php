<?php
namespace AppTest;

use Utilws\Aclkit\PathAuthorizer;
use PHPUnit\Framework\TestCase;

class PathAuthorizerTest extends TestCase
{

    public function test_returns_null_for_match_when_no_rule_matches()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig());
        $this->assertNull( $pathAuthorizer->matchedRule('/bogus/url/goes/here'));
    }

    public function test_empty_rule_matches_any_path()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfigWithEmpty());
        $this->assertSame('', $pathAuthorizer->matchedRule('/bogus/url/goes/here'));
    }

    public function test_matches_path_to_rule_index()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig());
        $this->assertSame('catalog', $pathAuthorizer->matchedRule('/catalog/item/123'));
    }

    public function test_matches_most_specific_rule()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig());
        $this->assertSame('sales/entry/rma', $pathAuthorizer->matchedRule('/sales/entry/rma/begin'));
    }

    public function test_match_ignores_protocol_and_host_portions_of_url()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig(), ['foo']);
        $this->assertSame('fiscal/payments', $pathAuthorizer->matchedRule('https://www.fake.com/fiscal/payments/2017'));
        $this->assertSame('settings/users', $pathAuthorizer->matchedRule('http://localhost/foo/settings/users/jsmith'));
    }

    public function test_match_ignores_base_paths()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig(), ['foo','my-app']);
        $this->assertSame('fiscal/payments', $pathAuthorizer->matchedRule('https://www.fake.com/my-app/fiscal/payments/2017'));
    }

    public function test_returns_null_for_require_role_when_no_rule_matches()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig());
        $this->assertNull( $pathAuthorizer->requiredRole('/bogus/url/goes/here'));
    }

    public function test_provides_required_role()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig());
        $this->assertSame('view-catalog', $pathAuthorizer->requiredRole('/catalog/item/123'));
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig());
        $this->assertSame('make-payments', $pathAuthorizer->requiredRole('http://www.fake.com/fiscal/payments/2017'));
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfig(), ['foo']);
        $this->assertSame('manage-users', $pathAuthorizer->requiredRole('http://localhost/foo/settings/users/jsmith'));
    }

    public function test_default_role_can_be_set_using_empty_rule()
    {
        $pathAuthorizer = new PathAuthorizer($this->getExampleConfigWithEmpty());
        $this->assertSame('guest', $pathAuthorizer->requiredRole('/bogus/url/goes/here'));
    }

    public function getExampleConfig()
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

    public function getExampleConfigWithEmpty()
    {
        $out = $this->getExampleConfig();
        $out[''] = 'guest';
        return $out;
    }

}