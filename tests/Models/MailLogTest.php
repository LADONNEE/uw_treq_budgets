<?php

namespace Tests\Models;

use App\Models\MailLog;
use Tests\TestCase;

class MailLogTest extends TestCase
{
    public function test_set_metadata_generates_json_string()
    {
        $it = new MailLog();

        $it->setMetadata([
            'foo' => 'hello'
        ]);

        $this->assertSame('{"foo":"hello"}', $it->metadata);
    }

    public function test_set_metadata_moves_fillable_field_values_to_field()
    {
        $it = new MailLog();

        $it->setMetadata([
            'effort_report_id' => 99,
            'email' => 'edmailbx@uw.edu',
            'foo' => 'hello'
        ]);

        $this->assertEquals(99, $it->effort_report_id);
        $this->assertSame('edmailbx@uw.edu', $it->email);
        $this->assertSame('{"foo":"hello"}', $it->metadata);
    }
}
