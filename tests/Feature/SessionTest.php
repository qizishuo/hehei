<?php

namespace Tests\Feature;

use Tests\TestCase;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:rollback');
        $this->artisan('migrate');
    }

    /**
     * test session api.
     *
     * @return void
     */
    public function testSession()
    {
        $response = $this->withHeader(
            "Authorization", "Basic " . base64_encode("admin:admin")
        )->json("POST", "/api/admin/session");

        $response->assertStatus(200);
    }
}
