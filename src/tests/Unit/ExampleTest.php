<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        // $this->assertTrue(true);
        \Log::info('Starting test_example');

        $response = $this->get('/');

        \Log::info('Response status', ['status' => $response->status()]);

        $response->assertStatus(200);
    }
}
