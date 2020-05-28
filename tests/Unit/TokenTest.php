<?php

namespace Tests\Unit;

use App\Services\TokenService;
use Tests\TestCase;

class TokenTest extends TestCase
{
    /**
     * test token service.
     *
     * @return void
     */
    public function testToken()
    {
        $token_service = new TokenService("test", 100);
        $token = $token_service->write(123);
        $data = $token_service->get($token);
        $this->assertEquals($data, 123);
        $ttl = $token_service->ttl($token);
        $this->assertLessThanOrEqual($ttl, 100);
        $token_service->delete($token);
        $this->assertNull($token_service->get($token));
    }
}
