<?php

namespace Tests\Unit\App\Models;

use App\Models\Consent;
use Tests\TestCase;

class ConsentTest extends TestCase
{
    public function testIsConfirmed()
    {
        $consent1 = new Consent();
        $consent2 = new Consent(['confirmed' => false]);
        $consent3 = new Consent(['confirmed' => true]);

        $this->assertFalse($consent1->isConfirmed());
        $this->assertFalse($consent2->isConfirmed());
        $this->assertTrue($consent3->isConfirmed());
    }
}
