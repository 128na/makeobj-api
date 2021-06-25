<?php

namespace App\Console\Commands\Makeobj;

use App\Services\File\FileServiceInterface;
use Illuminate\Console\Command;

class DeleteOld extends Command
{
    protected $signature = 'makeobj:delete_old {days=7 : 日数}';

    protected $description = '指定日よりも過去のファイルを削除する';

    private FileServiceInterface $fileService;

    public function __construct(FileServiceInterface $fileService)
    {
        parent::__construct();
        $this->fileService = $fileService;
    }

    public function handle()
    {
        $directories = $this->getDirectories();
        $days = $this->argument('days');

        foreach ($directories as $dir) {
            $res = $this->fileService->deleteOldFiles($dir, $days);
            $this->info(sprintf('Deleted %d directories in the "%s" directory.', count($res), $dir));
        }

        return 0;
    }

    private function getDirectories(): array
    {
        return ['dump', 'extract', 'list', 'merge', 'pak'];
    }
}
