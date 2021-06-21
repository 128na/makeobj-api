<?php

namespace Tests\Feature\Api\v1\MakeobjController;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PakTest extends TestCase
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
        $url = route('api.v1.pak');
        $data = [
            'filename' => 'testing',
            'dat' => file_get_contents(__DIR__.'/example/example.dat'),
            'size' => 64,
            'images' => [$this->getFile('1xL.png', 'image/png')],
        ];
        $response = $this->postJson($url, $data);

        $response->assertStatus(200);
        $this->assertStringContainsString('testing.pak', $response->json('pakfile'));
    }
}
