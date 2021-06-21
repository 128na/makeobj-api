<?php

namespace Tests\Feature\Api\v1\MakeobjController;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ListTest extends TestCase
{
    private function getFile(string $filename, string $mime = 'application/octet-stream'): UploadedFile
    {
        return new UploadedFile(
            __DIR__.'/example/'.$filename,
            $filename,
            $mime,
            UPLOAD_ERR_OK,
            true
        );
    }

    public function test()
    {
        $url = route('api.v1.list');
        $data = ['file' => $this->getFile('example.all.pak')];
        $response = $this->postJson($url, $data);

        $response->assertStatus(200);
        $this->assertStringContainsString('example1', $response->json('list.0.name'));
        $this->assertStringContainsString('example2', $response->json('list.1.name'));
    }
}
