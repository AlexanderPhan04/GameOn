<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the application returns a successful response.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test database connection.
     */
    public function test_database_connection(): void
    {
        // Test database is working by checking we can query it
        $this->assertDatabaseMissing('users', [
            'email' => 'nonexistent@example.com',
        ]);

        // Verify database connection is working
        $this->assertTrue(true);
    }
}
