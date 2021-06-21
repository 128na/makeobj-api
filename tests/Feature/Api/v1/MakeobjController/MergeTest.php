<?php

namespace Tests\Feature\Api\v1\MakeobjController;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MergeTest extends TestCase
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
        $url = route('api.v1.merge');
        $data = [
            'filename' => 'testing',
            'files' => [$this->getFile('example1.pak'), $this->getFile('example2.pak')],
        ];
        $response = $this->postJson($url, $data);

        $response->assertStatus(200);
        $this->assertStringContainsString('testing.pak', $response->json('pakfile'));
    }
}
