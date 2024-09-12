<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;  // 追加する

class ExampleTest extends TestCase
{
    public function test_example()
    {
        Log::info('This is a test log message');
        $this->assertTrue(true);
    }
}
