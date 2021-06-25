<?php

namespace Tests\Feature\Console\Makeobj;

use Illuminate\Filesystem\FilesystemAdapter;
use Mockery\MockInterface;
use Tests\TestCase;

class DeleteOldTest extends TestCase
{
    public function test()
    {
        $this->mock(FilesystemAdapter::class, function (MockInterface $m) {
            $m->shouldReceive('directories')->times(5)->andReturn(['foo']);
            $m->shouldReceive('lastModified')->times(5)->andReturn(now()->modify('-8 days')->timestamp);
            $m->shouldReceive('deleteDirectory')->times(5)->andReturn(true);
        });

        $this->artisan('makeobj:delete_old')
            ->assertExitCode(0);
    }
}
