<?php

namespace App\Console\Commands\Makeobj;

use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Console\Command;

class CapabilitiesCommand extends Command
{
    protected $signature = 'makeobj:capabilities';

    protected $description = '識別可能な形式一覧を表示';

    private MakeobjServiceInterface $makeobjServiceInterface;

    public function __construct(MakeobjServiceInterface $makeobjServiceInterface)
    {
        parent::__construct();
        $this->makeobjServiceInterface = $makeobjServiceInterface;
    }

    public function handle()
    {
        $res = $this->makeobjServiceInterface->capabilities();
        $this->info(implode("\n", $res));

        return 0;
    }
}
