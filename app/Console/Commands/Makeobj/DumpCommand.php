<?php

namespace App\Console\Commands\Makeobj;

use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Console\Command;

class DumpCommand extends Command
{
    protected $signature = 'makeobj:dump {filePath : ファイルパス}';

    protected $description = '指定pakファイルのダンプ情報を表示';

    private MakeobjServiceInterface $makeobjServiceInterface;

    public function __construct(MakeobjServiceInterface $makeobjServiceInterface)
    {
        parent::__construct();
        $this->makeobjServiceInterface = $makeobjServiceInterface;
    }

    public function handle()
    {
        $filePath = base_path($this->argument('filePath'));
        $res = $this->makeobjServiceInterface->dump($filePath);

        $this->info(json_encode($res, JSON_PRETTY_PRINT));

        return 0;
    }
}
