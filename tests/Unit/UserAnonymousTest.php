<?php

namespace Tests\Unit;

use App\Auth\UserAnonymous;
use PHPUnit\Framework\TestCase;

class UserAnonymousTest extends TestCase
{
    /** @test */
    public function it_gets_not_logged_in_lastname()
    {
        $user = new UserAnonymous();

        $this->assertEquals('Not Logged In', $user->lastname);
    }
}
