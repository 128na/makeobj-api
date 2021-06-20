<?php

namespace App\Console\Commands\Makeobj;

use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Console\Command;

class VersionCommand extends Command
{
    protected $signature = 'makeobj:version';

    protected $description = 'makeobjのバージョン情報を表示';

    private MakeobjServiceInterface $makeobjServiceInterface;

    public function __construct(MakeobjServiceInterface $makeobjServiceInterface)
    {
        parent::__construct();
        $this->makeobjServiceInterface = $makeobjServiceInterface;
    }

    public function handle()
    {
        $res = $this->makeobjServiceInterface->version();
        $this->info($res);

        return 0;
    }
}
