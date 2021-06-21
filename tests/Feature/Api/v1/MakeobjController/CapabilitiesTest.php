<?php

namespace Tests\Feature\Api\v1\MakeobjController;

use Tests\TestCase;

class CapabilitiesTest extends TestCase
{
    public function test()
    {
        $url = route('api.v1.capabilities');
        $response = $this->getJson($url);

        $response->assertStatus(200);
        $capabilities = $response->json('capabilities');
        $this->assertEquals('bridge', $capabilities[0]);
        $this->assertEquals('way-object', $capabilities[count($capabilities) - 1]);
    }
}
