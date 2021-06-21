<?php

namespace Tests\Feature\Api\v1\MakeobjController;

use Tests\TestCase;

class VersionTest extends TestCase
{
    public function test()
    {
        $url = route('api.v1.version');
        $response = $this->getJson($url);

        $response->assertStatus(200);
        $this->assertStringContainsString('Makeobj version', $response->json('version'));
    }
}
