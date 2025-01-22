<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testSomething(): void
    {
        $params = true;
        // $params = false;
        $this->assertTrue($params);
    }
}