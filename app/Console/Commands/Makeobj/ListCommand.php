<?php

namespace App\Console\Commands\Makeobj;

use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Console\Command;

class ListCommand extends Command
{
    protected $signature = 'makeobj:list {filePath : ファイルパス}';

    protected $description = '指定pakファイル内のアドオン一覧を表示';

    private MakeobjServiceInterface $makeobjServiceInterface;

    public function __construct(MakeobjServiceInterface $makeobjServiceInterface)
    {
        parent::__construct();
        $this->makeobjServiceInterface = $makeobjServiceInterface;
    }

    public function handle()
    {
        $filePath = base_path($this->argument('filePath'));
        $res = $this->makeobjServiceInterface->list($filePath);

        $this->info(implode("\t", ['type', 'name', 'node', 'size']));
        foreach ($res as $item) {
            $this->info(implode("\t", $item));
        }

        return 0;
    }
}
