<?php

namespace Tests\Feature\Api\v1\MakeobjController;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ExtractTest extends TestCase
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
        $url = route('api.v1.extract');
        $data = ['file' => $this->getFile('example.all.pak')];
        $response = $this->postJson($url, $data);

        $response->assertStatus(200);
        $this->assertStringContainsString('building.example1.pak', $response->json('pakfiles.0'));
        $this->assertStringContainsString('building.example2.pak', $response->json('pakfiles.1'));
    }
}
