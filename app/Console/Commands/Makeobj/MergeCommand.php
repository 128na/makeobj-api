<?php

namespace App\Console\Commands\Makeobj;

use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Console\Command;

class MergeCommand extends Command
{
    protected $signature = 'makeobj:merge
        {pakFileLibrary : 結合後のpakファイルパス}
        {pakFiles : 結合するpakファイル一覧}';

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
